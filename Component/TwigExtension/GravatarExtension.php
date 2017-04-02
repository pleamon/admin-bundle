<?php

namespace P\AdminBundle\Component\TwigExtension;

use P\AdminBundle\Component\Gravatar\Gravatar;

class GravatarExtension extends \Twig_Extension
{
    public function getFilters()
    {
        $gravatar = new Gravatar();
        return array(
            new \Twig_SimpleFilter('gravatar', array($gravatar, 'generateUrl')),
        );
    }

    public function getName()
    {
        return 'p_gravatar_extension';
    }
}

