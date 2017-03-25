<?php

namespace P\AdminBundle\Entity;

use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\Mapping as ORM;

/**
 * FileCategory
 */
class FileCategory
{

    /**
     * Constructor
     */
    public function __construct($name = null, $keyName = null, $dir = null)
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->name = $name;
        $this->keyName = $keyName;
        $this->dir = $dir;
    }
    public function __tostring()
    {
        return "{$this->name} [ {$this->keyName} ]";
    }

    public function createCategoryDir()
    {
        $dir = $this->getDir();
        if(substr($dir, 0, 1) == '/') {
            $dir = substr($dir, 0, 1);
        }
        if(substr($dir, -1, 1) != '/') {
            $dir .= '/';
            $this->setDir($dir);
        }
        $fs = new Filesystem();
        if($dir) {
            if(!$fs->exists($dir)) {
                $fs->mkdir($dir, 0755);
            }
        }
    }
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
    private $dir;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files;

    private $keyName;


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
     * @return FileCategory
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
     * Set key_name
     *
     * @param string $keyName
     * @return FileCategory
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;

        return $this;
    }

    /**
     * Get key_name
     *
     * @return string 
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * Set dir
     *
     * @param string $dir
     * @return FileCategory
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get dir
     *
     * @return string 
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Add files
     *
     * @param \P\AdminBundle\Entity\File $files
     * @return FileCategory
     */
    public function addFile(\P\AdminBundle\Entity\File $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \P\AdminBundle\Entity\File $files
     */
    public function removeFile(\P\AdminBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }
}
