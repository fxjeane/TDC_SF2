<?php
namespace tdc\VideoBundle\Entity;

class Video
{
    protected $name;
    protected $author;
    protected $summary;
    protected $description;
    protected $toc;
    protected $status;
    protected $views;

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
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
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
     * Set toc
     *
     * @param string $toc
     */
    public function setToc($toc)
    {
        $this->toc = $toc;
    }

    /**
     * Get toc
     *
     * @return string 
     */
    public function getToc()
    {
        return $this->toc;
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
    /**
     * @var integer $category
     */
    private $category;


    /**
     * Set category
     *
     * @param integer $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var tdc\VideoBundle\Entity\Category
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add categories
     *
     * @param tdc\VideoBundle\Entity\Category $categories
     */
    public function addCategory(\tdc\VideoBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set views
     *
     * @param integer $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }
    /**
     * @var tdc\VideoBundle\Entity\Rating
     */
    private $ratings;


    /**
     * Add ratings
     *
     * @param tdc\VideoBundle\Entity\Rating $ratings
     */
    public function addRating(\tdc\VideoBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;
    }

    /**
     * Get ratings
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }
    /**
     * @var string $icon
     */
    private $icon;


    /**
     * Set icon
     *
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }
    /**
     * @var string $trt
     */
    private $trt;


    /**
     * Set trt
     *
     * @param string $trt
     */
    public function setTrt($trt)
    {
        $this->trt = $trt;
    }

    /**
     * Get trt
     *
     * @return string 
     */
    public function getTrt()
    {
        return $this->trt;
    }
    /**
     * @var tdc\QABundle\Entity\Question
     */
    private $questions;

    /**
     * @var tdc\QABundle\Entity\Answer
     */
    private $answers;


    /**
     * Add questions
     *
     * @param tdc\QABundle\Entity\Question $questions
     */
    public function addQuestion(\tdc\QABundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    }

    /**
     * Get questions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add answers
     *
     * @param tdc\QABundle\Entity\Answer $answers
     */
    public function addAnswer(\tdc\QABundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;
    }

    /**
     * Get answers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }
    /**
     * @var string $filepath
     */
    private $filepath;


    /**
     * Set filepath
     *
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }
    /**
     * @var string $subtitle
     */
    private $subtitle;


    /**
     * Set subtitle
     *
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }
}