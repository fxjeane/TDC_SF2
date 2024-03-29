<?php
namespace tdc\VideoBundle\Entity;

class Category
{
    protected $name;
    protected $summary;
    protected $description;
    protected $status;
    protected $children;
    protected $parent;


    public function asArray() {
        return get_object_vars($this);
    }

    /**
     * @var integer $id
     */
    private $id;


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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set summary
     *
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add children
     *
     * @param tdc\VideoBundle\Entity\Category $children
     */
    public function addCategory(\tdc\VideoBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param tdc\MembersBundle\Entity\Category $parent
     */
    public function setParent(\tdc\VideoBundle\Entity\Category $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return tdc\MembersBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * @var tdc\VideoBundle\Entity\Video
     */
    private $videos;


    /**
     * Set videos
     *
     * @param tdc\VideoBundle\Entity\Video $videos
     */
    public function setVideos(\tdc\VideoBundle\Entity\Video $videos)
    {
        $this->videos = $videos;
    }

    /**
     * Get videos
     *
     * @return tdc\VideoBundle\Entity\Video 
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add videos
     *
     * @param tdc\VideoBundle\Entity\Video $videos
     */
    public function addVideo(\tdc\VideoBundle\Entity\Video $videos)
    {
        $this->videos[] = $videos;
    }
}