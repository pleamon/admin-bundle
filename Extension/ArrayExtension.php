<?php

namespace P\AdminBundle\Extension;

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

