<?php

namespace P\AdminBundle\Service;

use P\AdminBundle\Entity\Icon as IconEntity;

class Icon
{
    private $container = null;
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function render(IconEntity $icon = null, $class = "")
    {
        if($icon) {
            return sprintf('<i class="%s %s"></i>', $icon->getName(), $class);
        }
        return '';
    }
}
