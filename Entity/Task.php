<?php

namespace P\AdminBundle\Entity;

/**
 * Task
 */
class Task
{
    /**
     * @var integer
     */
    private $id;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Task
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
}
