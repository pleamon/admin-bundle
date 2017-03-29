<?php

namespace P\AdminBundle\Entity;

/**
 * AdminMenu
 */
class AdminMenu
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $route;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \P\AdminBundle\Entity\AdminMenu
     */
    private $parent;

    /**
     * @var \P\AdminBundle\Entity\Icon
     */
    private $icon;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roles;

    public function __tostring()
    {
        return sprintf("%s [ %s ]", $this->text, $this->name);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AdminMenu
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return AdminMenu
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return AdminMenu
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return AdminMenu
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Add item
     *
     * @param \P\AdminBundle\Entity\AdminMenu $item
     *
     * @return AdminMenu
     */
    public function addItem(\P\AdminBundle\Entity\AdminMenu $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \P\AdminBundle\Entity\AdminMenu $item
     */
    public function removeItem(\P\AdminBundle\Entity\AdminMenu $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set parent
     *
     * @param \P\AdminBundle\Entity\AdminMenu $parent
     *
     * @return AdminMenu
     */
    public function setParent(\P\AdminBundle\Entity\AdminMenu $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \P\AdminBundle\Entity\AdminMenu
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set icon
     *
     * @param \P\AdminBundle\Entity\Icon $icon
     *
     * @return AdminMenu
     */
    public function setIcon(\P\AdminBundle\Entity\Icon $icon = null)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return \P\AdminBundle\Entity\Icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Add role
     *
     * @param \P\UserBundle\Entity\Role $role
     *
     * @return AdminMenu
     */
    public function addRole(\P\UserBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \P\UserBundle\Entity\Role $role
     */
    public function removeRole(\P\UserBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }
    /**
     * @var integer
     */
    private $sort;


    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return AdminMenu
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }
    /**
     * @var \P\AdminBundle\Entity\AdminMenu
     */
    private $mount;


    /**
     * Set mount
     *
     * @param \P\AdminBundle\Entity\AdminMenu $mount
     *
     * @return AdminMenu
     */
    public function setMount(\P\AdminBundle\Entity\AdminMenu $mount = null)
    {
        $this->mount = $mount;

        return $this;
    }

    /**
     * Get mount
     *
     * @return \P\AdminBundle\Entity\AdminMenu
     */
    public function getMount()
    {
        return $this->mount;
    }
    /**
     * @var boolean
     */
    private $show = true;


    /**
     * Set show
     *
     * @param boolean $show
     *
     * @return AdminMenu
     */
    public function setShow($show)
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Get show
     *
     * @return boolean
     */
    public function getShow()
    {
        return $this->show;
    }
    /**
     * @var boolean
     */
    private $enabled = true;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return AdminMenu
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mounts;


    /**
     * Add mount
     *
     * @param \P\AdminBundle\Entity\AdminMenu $mount
     *
     * @return AdminMenu
     */
    public function addMount(\P\AdminBundle\Entity\AdminMenu $mount)
    {
        $this->mounts[] = $mount;

        return $this;
    }

    /**
     * Remove mount
     *
     * @param \P\AdminBundle\Entity\AdminMenu $mount
     */
    public function removeMount(\P\AdminBundle\Entity\AdminMenu $mount)
    {
        $this->mounts->removeElement($mount);
    }

    /**
     * Get mounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMounts()
    {
        return $this->mounts;
    }
}
