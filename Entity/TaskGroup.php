<?php

namespace P\AdminBundle\Entity;

/**
 * TaskGroup
 */
class TaskGroup
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
     * @return TaskGroup
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
