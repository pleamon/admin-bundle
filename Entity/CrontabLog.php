<?php

namespace P\AdminBundle\Entity;

/**
 * CrontabLog
 */
class CrontabLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var string
     */
    private $result;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \P\AdminBundle\Entity\Crontab
     */
    private $crontab;


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
     * Set status
     *
     * @param integer $status
     *
     * @return CrontabLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set result
     *
     * @param string $result
     *
     * @return CrontabLog
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CrontabLog
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
     * Set crontab
     *
     * @param \P\AdminBundle\Entity\Crontab $crontab
     *
     * @return CrontabLog
     */
    public function setCrontab(\P\AdminBundle\Entity\Crontab $crontab = null)
    {
        $this->crontab = $crontab;

        return $this;
    }

    /**
     * Get crontab
     *
     * @return \P\AdminBundle\Entity\Crontab
     */
    public function getCrontab()
    {
        return $this->crontab;
    }
}
