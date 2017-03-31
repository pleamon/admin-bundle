## 安装bundle

```
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
    db_driver: propel
    client_class:        P\OAuthBundle\Entity\OAuthClient
    access_token_class:  P\OAuthBundle\Entity\OAuthAccessToken
    refresh_token_class: P\OAuthBundle\Entity\OAuthRefreshToken
    auth_code_class:     P\OAuthBundle\Entity\OAuthAuthCode
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

## 初始化数据

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
