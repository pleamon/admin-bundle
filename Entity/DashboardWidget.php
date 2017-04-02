<?php

namespace P\AdminBundle\Entity;

/**
 * DashboardWidget
 */
class DashboardWidget
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $widgetId;

    /**
     * @var string
     */
    private $widgetName;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var integer
     */
    private $sort = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set widgetId
     *
     * @param string $widgetId
     *
     * @return DashboardWidget
     */
    public function setWidgetId($widgetId)
    {
        $this->widgetId = $widgetId;

        return $this;
    }

    /**
     * Get widgetId
     *
     * @return string
     */
    public function getWidgetId()
    {
        return $this->widgetId;
    }

    /**
     * Set widgetName
     *
     * @param string $widgetName
     *
     * @return DashboardWidget
     */
    public function setWidgetName($widgetName)
    {
        $this->widgetName = $widgetName;

        return $this;
    }

    /**
     * Get widgetName
     *
     * @return string
     */
    public function getWidgetName()
    {
        return $this->widgetName;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return DashboardWidget
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
     * Set sort
     *
     * @param integer $sort
     *
     * @return DashboardWidget
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
     * Add user
     *
     * @param \P\UserBundle\Entity\User $user
     *
     * @return DashboardWidget
     */
    public function addUser(\P\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \P\UserBundle\Entity\User $user
     */
    public function removeUser(\P\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}

