<?php

namespace P\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 */
class Region
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
     * @var integer
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $childs;

    /**
     * @var \P\AdminBundle\Entity\Region
     */
    private $parent;
    public function __tostring()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setId()
    {
        $this->id;
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

    /**
     * Set name
     *
     * @param string $name
     * @return Region
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
     * Set type
     *
     * @param integer $type
     * @return Region
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
     * Add childs
     *
     * @param \P\AdminBundle\Entity\Region $childs
     * @return Region
     */
    public function addChild(\P\AdminBundle\Entity\Region $childs)
    {
        $this->childs[] = $childs;

        return $this;
    }

    /**
     * Remove childs
     *
     * @param \P\AdminBundle\Entity\Region $childs
     */
    public function removeChild(\P\AdminBundle\Entity\Region $childs)
    {
        $this->childs->removeElement($childs);
    }

    /**
     * Get childs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set parent
     *
     * @param \P\AdminBundle\Entity\Region $parent
     * @return Region
     */
    public function setParent(\P\AdminBundle\Entity\Region $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \P\AdminBundle\Entity\Region 
     */
    public function getParent()
    {
        return $this->parent;
    }
}

