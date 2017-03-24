<?php

namespace P\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Bundle\AsseticBundle\Factory\Resource\ConfigurationResource;

class AssetCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $formulae = $this->getFonts();
        $formulae = array_merge($formulae, $this->getPng());
        $container->getDefinition('p_admin.assetic.config_resource')->replaceArgument(0, $formulae);
    }

    public function getFonts()
    {
        return array(
            'font_css' => array(
                array('@PAdminBundle/Resources/public/googlefonts/*'),
                array(),
                array('output' => 'googlefonts/*')
            ),
            'font_css' => array(
                array('@PAdminBundle/Resources/public/googlefonts/fonts.css'),
                array(),
                array('output' => 'googlefonts/fonts.css')
            ),
            'font1' => array(
                array('@PAdminBundle/Resources/public/googlefonts/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf'),
                array(),
                array('output' => 'googlefonts/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf')
            ),
            'font2' => array(
                array('@PAdminBundle/Resources/public/googlefonts/d-6IYplOFocCacKzxwXSOKCWcynf_cDxXwCLxiixG1c.ttf'),
                array(),
                array('output' => 'googlefonts/d-6IYplOFocCacKzxwXSOKCWcynf_cDxXwCLxiixG1c.ttf')
            ),
            'font3' => array(
                array('@PAdminBundle/Resources/public/googlefonts/DXI1ORHCpsQm3Vp6mXoaTYnF5uFdDttMLvmWuJdhhgs.ttf'),
                array(),
                array('output' => 'googlefonts/DXI1ORHCpsQm3Vp6mXoaTYnF5uFdDttMLvmWuJdhhgs.ttf'),
            ),
            'font4' => array(
                array('@PAdminBundle/Resources/public/googlefonts/Hgo13k-tfSpn0qi1SFdUfaCWcynf_cDxXwCLxiixG1c.ttf'),
                array(),
                array('output' => 'googlefonts/Hgo13k-tfSpn0qi1SFdUfaCWcynf_cDxXwCLxiixG1c.ttf'),
            ),
            'font5' => array(
                array('@PAdminBundle/Resources/public/googlefonts/k3k702ZOKiLJc3WVjuplzInF5uFdDttMLvmWuJdhhgs.ttf'),
                array(),
                array('output' => 'googlefonts/k3k702ZOKiLJc3WVjuplzInF5uFdDttMLvmWuJdhhgs.ttf'),
            ),
            'font6' => array(
                array('@PAdminBundle/Resources/public/googlefonts/MTP_ySUJH_bn48VBG8sNSonF5uFdDttMLvmWuJdhhgs.ttf'),
                array(),
                array('output' => 'googlefonts/MTP_ySUJH_bn48VBG8sNSonF5uFdDttMLvmWuJdhhgs.ttf'),
            ),
            'font7' => array(
                array('@PAdminBundle/Resources/public/googlefonts/RxZJdnzeo3R5zSexge8UUaCWcynf_cDxXwCLxiixG1c.ttf'),
                array(),
                array('output' => 'googlefonts/RxZJdnzeo3R5zSexge8UUaCWcynf_cDxXwCLxiixG1c.ttf')
            ),
            'font8' => array(
                array('@PAdminBundle/Resources/public/googlefonts/zN7GBFwfMP4uA6AR0HCoLQ.ttf'),
                array(),
                array('output' => 'googlefonts/zN7GBFwfMP4uA6AR0HCoLQ.ttf'),
            ),
            'font9' => array(
                array('@PAdminBundle/Resources/public/font-awesome/fonts/fontawesome-webfont.woff2'),
                array(),
                array('output' => 'Resources/public/font-awesome/fonts/fontawesome-webfont.woff2'),
            ),
            'font10' => array(
                array('@PAdminBundle/Resources/public/font-awesome/fonts/fontawesome-webfont.woff'),
                array(),
                array('output' => 'Resources/public/font-awesome/fonts/fontawesome-webfont.woff'),
            ),
            'font11' => array(
                array('@PAdminBundle/Resources/public/font-awesome/fonts/fontawesome-webfont.ttf'),
                array(),
                array('output' => 'Resources/public/font-awesome/fonts/fontawesome-webfont.ttf'),
            ),
            'font12' => array(
                array('@PAdminBundle/Resources/public/font-awesome/fonts/fontawesome-webfont.svg'),
                array(),
                array('output' => 'Resources/public/font-awesome/fonts/fontawesome-webfont.svg'),
            )
        );
    }

    public function getPng()
    {
        return array(
            'png1' => array(
                array('@PAdminBundle/Resources/public/css/patterns/header-profile.png'),
                array(),
                array('output' => 'Resources/public/css/patterns/header-profile.png'),
            ),
            'png2' => array(
                array('@PAdminBundle/Resources/public/css/plugins/iCheck/green@2x.png'),
                array(),
                array('output' => 'Resources/public/css/plugins/iCheck/green@2x.png'),
            )
        );
    }
}
