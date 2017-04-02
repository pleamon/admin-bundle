<?php

namespace P\AdminBundle\Entity;

/**
 * Crontab
 */
class Crontab
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $schedule;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var string
     */
    private $execute;

    /**
     * @var array
     */
    private $parameter;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $lastExecuteAt;

    public function __construct() {
        $this->createdAt = new \DateTime();
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
     * Set schedule
     *
     * @param string $schedule
     *
     * @return Crontab
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Crontab
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set execute
     *
     * @param string $execute
     *
     * @return Crontab
     */
    public function setExecute($execute)
    {
        $this->execute = $execute;

        return $this;
    }

    /**
     * Get execute
     *
     * @return string
     */
    public function getExecute()
    {
        return $this->execute;
    }

    /**
     * Set parameter
     *
     * @param array $parameter
     *
     * @return Crontab
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get parameter
     *
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Crontab
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Crontab
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Crontab
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set lastExecuteAt
     *
     * @param \DateTime $lastExecuteAt
     *
     * @return Crontab
     */
    public function setLastExecuteAt($lastExecuteAt)
    {
        $this->lastExecuteAt = $lastExecuteAt;

        return $this;
    }

    /**
     * Get lastExecuteAt
     *
     * @return \DateTime
     */
    public function getLastExecuteAt()
    {
        return $this->lastExecuteAt;
    }
}
