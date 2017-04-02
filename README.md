## 添加composer源

```
# composer.json
"repositories": [
    { "packagist": false },
    { "type": "composer", "url": "https://packagist.phpcomposer.com" },
    { "type": "composer", "url": "https://satis.pleamon.com" }
]
```

## 安装bundle

```
composer require doctrine/doctrine-cache-bundle
composer require doctrine/doctrine-fixtures-bundle
composer require symfony/assetic-bundle

composer require friendsofsymfony/user-bundle ~2.0@dev
composer require friendsofsymfony/oauth-server-bundle

composer require p/user-bundle
composer require p/admin-bundle
// composer require p/home-bundle 前端bundle
```

## 加载bundle
```
new Symfony\Bundle\AsseticBundle\AsseticBundle(),

new FOS\UserBundle\FOSUserBundle(),
new FOS\OAuthServerBundle\FOSOAuthServerBundle(),

new P\UserBundle\PUserBundle(),
new P\AdminBundle\PAdminBundle(),

// new P\HomeBundle\PHomeBundle(), 需要时添加
```

## 配置

```
mkdir app/config/p app/config/fos
```

- config.yml

```
# app/config/p/admin.yml

parameters:
    locale: zh_CN

framework:
    translator:      { fallbacks: ["%locale%"] }

twig:
    form_themes:
        - 'bootstrap_3_layout.html.twig'

doctrine:
    orm:
        metadata_cache_driver:
            cache_provider: metadata_cache_driver
        query_cache_driver:
            cache_provider: query_cache_driver
        result_cache_driver:
            cache_provider: result_cache_driver

doctrine_cache:
    providers:
        metadata_cache_driver:
            type: file_system
        query_cache_driver:
            type: file_system
        result_cache_driver:
            type: file_system
```

- padmin.yml

```
# app/config/p/admin.yml

p_admin:
    search:
        route: search_route # type string default null
        text: 搜索框 # type string default ''
    base_template: "PAdminBundle:layout:standard_layout.html.twig" # type twig file default "PAdminBundle:layout:standard_layout.html.twig"
```

- puser.yml

```
p_user:
    profile_route: p_homepage # default fos_user_security_login
    template:
        login: 'PAdminBundle:Security:login.html.twig'

        register: 'PAdminBundle:Registration:register.html.twig'
        register_check_email: 'PAdminBundle:Registration:check_email.html.twig'
        register_confirmed: 'PAdminBundle:Registration:confirmed.html.twig'

        reset: 'PAdminBundle:Resetting:reset.html.twig'
        reset_request: 'PAdminBundle:Resetting:request.html.twig'
        reset_check_email: 'PAdminBundle:Resetting:check_email.html.twig'
    register_enabled: true # default false
```

- p/assetic.yml

```
# app/config/p/assetic.yml

assetic:
    debug:          '%kernel.debug%'
    use_controller: '%kernel.debug%'
    filters:
        cssrewrite: ~
```

- fos/user.yml

```
# app/config/p/fos/user.yml

fos_user:
    db_driver: orm
    firewall_name: main
    user_class: P\UserBundle\Entity\User
    group:
        group_class: P\UserBundle\Entity\Group
    registration:
        confirmation:
            enabled: true
            template: PAdminBundle:Registration:mail.email.twig
    from_email:
        address:    %mailer_user%
        sender_name: p admin Team
    service:
        mailer: fos_user.mailer.twig_swift
    resetting:
        email:
            template: PAdminBundle:Resetting:mail.email.twig
```

- fos/oauth.yml

```
# app/config/fos/oauth.yml

fos_oauth_server:
    db_driver: orm
    client_class:        P\OAuthBundle\Entity\OAuthClient
    access_token_class:  P\OAuthBundle\Entity\OAuthAccessToken
    refresh_token_class: P\OAuthBundle\Entity\OAuthRefreshToken
    auth_code_class:     P\OAuthBundle\Entity\OAuthAuthCode
```

- app/config/config.yml

```
imports:
    - { resource: fos/user.yml }
    - { resource: fos/oauth.yml }
    - { resource: p/user.yml }
    - { resource: p/admin.yml }
    #- { resource: p/home.yml }
    - { resource: p/assets.yml }

```

## 路由


```
# app/config/routing.yml


p_home:
    resource: "@PHomeBundle/Resources/config/routing.yml"
    prefix:   /

p_admin:
    resource: "@PAdminBundle/Resources/config/routing.yml"
    prefix:   /admin

p_user:
    resource: "@PUserBundle/Resources/config/routing.yml"
    prefix:     /admin

fos_user_security:
    prefix: /admin
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

fos_user_registration:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /admin/register

fos_user_reseting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /admin/resetting

fos_oauth_server_token:
    resource: "@FOSOAuthServerBundle/Resources/config/routing/token.xml"

fos_oauth_server_authorize:
    resource: "@FOSOAuthServerBundle/Resources/config/routing/authorize.xml"
```

## Firewall

```
# app/config/security.yml

security:
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username_email

    firewalls:
        oauth_token:
            pattern:            ^/oauth/v2/token
            security:           false

        api:
            pattern:            ^/api
            fos_oauth:          true
            stateless:          true
            anonymous:          true

        admin:
            pattern:             ^/admin
            form_login:
                provider:       fos_userbundle
                use_referer:    true
                use_forward:    true
                login_path:     /admin/login
                check_path:     /admin/login_check
                failure_path:   null
            logout:
                path:           /admin/logout
                target:         /admin
            remember_me:
                secret:          "%secret%"
                lifetime: 	    604800 # 1 week in seconds
                path:            /admin
            anonymous:          true
        home:
            pattern:             ^/
            anonymous:          true

    access_control:
        - { path: ^/api, roles: [ IS_AUTHENTICATED_FULLY ] }
        - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/login_check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/(_profile|_wdt), role: IS_AUTHENTICATED_ANONYMOUSLY }

        # dev env
        - { path: ^/(css|js), role: IS_AUTHENTICATED_ANONYMOUSLY }

        - { path: ^/admin/.*, roles: [ IS_AUTHENTICATED_FULLY ] }
```


## Usage

1. 修改配置文件

```
# app/config/parameters.yml

# This file is a "template" of what your parameters.yml file should look like
# Set parameters here that may be different on each deployment target of the app, e.g. development, staging, production.
# http://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration
parameters:
    database_host:     127.0.0.1
    database_port:     ~
    database_name:     symfony
    database_user:     root
    database_password: ~
    # You should uncomment this if you want use pdo_sqlite
    # database_path: "%kernel.root_dir%/data.db3"

    mailer_transport:  smtp
    mailer_host:       127.0.0.1
    mailer_user:       ~
    mailer_password:   ~

    # A secret key that's used to generate certain security-related tokens
    secret:            ThisTokenIsNotSoSecretChangeIt
```

2. 建立数据库

```
./bin/console doctrine:database:create
```

3. 更新数据库结构

```
./bin/console doctrine:schema:update --force
```

4. 加载初始化数据

 - 加载`icon`数据

 ```
 ./bin/console p:load:icon
 ```
 
 - 加载`地理信息`数据
 
 ```
 ./bin/console p:load:region
 ```
 
 - 加载`用户权限`数据
 
 ```
 ./bin/console p:load:user:role
 ```
 
 - 加载`用户组`数据
 
 ```
 ./bin/console p:load:user:group
 ```
 
 - 创建用户
 
 ```
 ./bin/consle p:user:create {username} {email} {password}
 ```
 
 - 分配用户组
 
 ```
 ./bin/console p:user:promote {username}
 ```
 
 - 测试发送邮件
 
 ```
 ./bin/console swiftmailer:email:send --from li@pleamon.com --to=sh-lbl@919yi.com --subject="hello" --body="this is test mail"
 ```

2. 导出静态文件

```
./bin/console assetic:dump
./bin/console assetic:install
```

3. 定义entities yml文件

```
# AppBundle/Resources/config/doctrine/PictureCategory.orm.yml

AppBundle\Entity\PictureCategory:
    type: entity
    table: picture_category
    id:
        id:
            type: integer
            generator:
                strategy: AUTO
    oneToMany:
        pictures:
            targetEntity: Picture
            mappedBy: category

    fields:
        name:
            type: string
            length: 255
```

```
# AppBundle/Resources/config/doctrine/PictureTag.orm.yml

AppBundle\Entity\PictureTag:
    type: entity
    table: picture_tag
    id:
        id:
            type: integer
            generator:
                strategy: AUTO
    manyToMany:
        pictures:
            targetEntity: Picture
            mappedBy: tags
    fields:
        name:
            type: string
            length: 255
```

```
# AppBundle/Resources/config/doctrine/Picture.orm.yml

AppBundle\Entity\Picture:
    type: entity
    table: admin_config
    id:
        id:
            type: integer
            generator:
                strategy: AUTO
    manyToOne:
        category:
            targetEntity: PictureCategory
            inversedBy: pictures
            joinColumn:
                name: category_id
                referencedColumnName: id
        file:
            targetEntity: P\AdminBundle\Entity\File
            joinColumn:
                name: file_id
                referencedColumnName: id
    manyToMany:
        tags:
            targetEntity: PictureTag
            inversedBy: pictures
            joinTable:
                name: picture_tags
                joinColumns:
                    picture_id:
                        referencedColumnName: id
                inverseJoinColumns:
                    tag_id:
                        referencedColumnName: id
    fields:
        name:
            type: string
            length: 255
        description:
            type: text
```

4. 生成entities php文件

```
./bin/console doctrine:generate:entities AppBundle
```

5. 更新数据结构到数据库

```
./bin/console doctrine:schema:update --force
```

6. 创建crud

```
# 生成过程中，`AppBundle/Resouces/config/routing.yml`文件不能被重复修改，每次生成后会在命令行中输出无法添加的routing配置，需要手动添加到`AppBundle/Resources/config/routing.yml`文件中
# 自动生成的view文件会放在`app/Resources/views`目录中，需要移动到`AppBundle/Resources/views`目录，并根据Entity的命名修改大小
./bin/console p:generate:crud
or
./bin/console p:generate:crud --with-write --format=yml --overwrite -n --entity AppBundle:PictureTag
./bin/console p:generate:crud --with-write --format=yml --overwrite -n --entity AppBundle:PictureCategory
./bin/console p:generate:crud --with-write --format=yml --overwrite -n --entity AppBundle:Picture
```

## OAuth

1. 生成client_id

```
./bin/console p:oauth:client:create

# 输出生成的client_id与secret_id
# client_id: 2_3kkl1m19ll8g4o0swogsswwoscw84cc0oss0cwgk4sckc48808
# secret: 9mmx0ub07lkwc00g84gks8gkw0c40gs08wsgogk8oc0ockwow
```

2. 创建controller，并将路由挂载到/api下，可根据需要自行修改

3. 测试oauth

```
# vendor/p/UserBundle/Tests/oauth_test.py
# 修改下面变量

# domain = "local.example.com"
# url = "/api/test"
# client_id = ""
# secret_id = ""
# username = ""
# password = ""

python vendor/p/UserBundle/Tests/oauth_test.py
```
