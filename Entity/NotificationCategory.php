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
}

