<?php

namespace P\AdminBundle\Component\Menu\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;


class MenuEvent extends Event
{
    private $menus;
    public function __construct($menus)
    {
        $this->menus = $menus;
    }

    public function getMenus()
    {
        return $this->menus;
    }

    public function setMenus($menus)
    {
        $this->menus = $menus;
    }
}
