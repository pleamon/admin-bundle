<?php

namespace P\AdminBundle\Entity;

/**
 * NotificationCategory
 */
class NotificationCategory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nkey;

    /**
     * @var string
     */
    private $name;

    public function __tostring()
    {
        return $this->name;
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
     * Set nkey
     *
     * @param string $nkey
     *
     * @return NotificationCategory
     */
    public function setNkey($nkey)
    {
        $this->nkey = $nkey;

        return $this;
    }

    /**
     * Get nkey
     *
     * @return string
     */
    public function getNkey()
    {
        return $this->nkey;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return NotificationCategory
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $notifications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notification
     *
     * @param \P\AdminBundle\Entity\Notification $notification
     *
     * @return NotificationCategory
     */
    public function addNotification(\P\AdminBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \P\AdminBundle\Entity\Notification $notification
     */
    public function removeNotification(\P\AdminBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
