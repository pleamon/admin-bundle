<?php

namespace P\AdminBundle\Component\TwigExtension;

class IconExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('icon', array($this, 'parse')),
        );
    }

    public function parse($name, $class = "")
    {
        return sprintf('<i class="%s %s"></i>', $name, $class);
    }

    public function getName()
    {
        return 'p_admin_icon_extension';
    }
}

