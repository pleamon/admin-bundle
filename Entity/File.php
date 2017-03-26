<?php

namespace P\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File
 */
class File
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $md5;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \P\AdminBundle\Entity\FileCategory
     */
    private $category;

    private $file;

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
     * Set filename
     *
     * @param string $filename
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set suffix
     *
     * @param string $suffix
     * @return File
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix
     *
     * @return string 
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Set md5
     *
     * @param string $md5
     * @return File
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string 
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    public function getPrettySize()
    {
        $k = 1024;
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = floor(log($this->size) / log($k));
        $rs = round($this->size / pow($k, $i), 2) . ' ' . $sizes[$i];
        return $rs;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return File
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
     * Set category
     *
     * @param \P\AdminBundle\Entity\FileCategory $category
     * @return File
     */
    public function setCategory(\P\AdminBundle\Entity\FileCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \P\AdminBundle\Entity\FileCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @ORM\PrePersist
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    public function __tostring()
    {
        return $this->filename;
    }

    public function __construct(UploadedFile $file = null, $category = null, $tags = null)
    {
        $this->createdAt = new \DateTime();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();

        if($file) {
            $this->file = $file;
        }

        if($category) {
            $this->category = $category;
        } else {
            $this->category = new FileCategory('default', 'default', 'default');
        }

        if($tags) {
            $this->tags = $tags;
        }
    }

    public function getAbsolute()
    {
        $root = __DIR__.'/../../../../web/pfile/';
        return $root . $this->category->getDir() . '/' . $this->getRealFilename();
    }

    public function getAccessPath()
    {
        return sprintf("/pfile/%s/%s", $this->category->getDir(), $this->getRealFilename());
    }

    public function getRealFilename()
    {
        $firstname = substr($this->filename, 0, strrpos($this->filename, '.'));
        return sprintf("%s_%s_%s.%s", $firstname, $this->md5, $this->createdAt->format('YmdHisu'), $this->suffix);
    }

    public function upload()
    {
        if(empty($this->file)) {
            return;
        }
        $this->createdAt = new \DateTime();
        $root = __DIR__.'/../../../../web/pfile/';
        $dir = $root . $this->category->getDir();
        $file = $this->file;
        $this->filename = $file->getClientOriginalName();
        $path = $dir . '/' . $this->filename;
        $this->md5 = md5($path);
        $this->size = $file->getSize();
        $this->suffix = substr(strrchr($this->filename, '.'), 1);
        $this->mimeType = $file->getMimeType();
        $file->move($dir, $this->getRealFilename());
        $this->file = null;
    }

    public function setFile($file)
    {
        if($file instanceof UploadedFile) {
            $this->file = $file;
        } else if($file instanceof File) {
            $this->file = $file->getFile();
            $this->category = $file->getCategory();
            $this->tags = $file->getTags();
        }
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getPath(){
        return "/".$this->getCategory()->getDir().$this->getMd5();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;


    /**
     * Add tag
     *
     * @param \P\AdminBundle\Entity\FileTag $tag
     *
     * @return File
     */
    public function addTag(\P\AdminBundle\Entity\FileTag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \P\AdminBundle\Entity\FileTag $tag
     */
    public function removeTag(\P\AdminBundle\Entity\FileTag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
