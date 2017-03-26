<?php

namespace P\AdminBundle\TwigExtension;

class ArrayExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('arrayIntersect', 'array_intersect'),
        );
    }

    public function getName()
    {
        return 'padmin_extension';
    }
}

