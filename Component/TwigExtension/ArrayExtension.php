<?php

namespace P\AdminBundle\Component\TwigExtension;

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
        return 'p_array_extension';
    }
}

