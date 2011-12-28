<?php
namespace tdc\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


class User extends BaseUser
{
    protected $id;

    public function __construct()
    {
        parent::__construct();
        if (!$this->joined){
            $this->setJoined(new \DateTime('now'));
        }
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
     * @var string $name
     */
    private $name;

    /**
     * @var string $lastname
     */
    private $lastname;


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
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
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
     * @var tdc\UserBundle\Entity\Subscription
     */
    private $subscription;


    /**
     * Set subscription
     *
     * @param tdc\UserBundle\Entity\Subscription $subscription
     */
    public function setSubscription(\tdc\UserBundle\Entity\Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get subscription
     *
     * @return tdc\UserBundle\Entity\Subscription 
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
    /**
     * @var tdc\QABundle\Entity\Question
     */
    private $questions;


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
     * Add questions
     *
     * @param tdc\QABundle\Entity\Answer $questions
     */
    public function addAnswer(\tdc\QABundle\Entity\Answer $questions)
    {
        $this->questions[] = $questions;
    }
    /**
     * @var tdc\QABundle\Entity\Answer
     */
    private $answers;


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
     * @var date $joined
     */
    private $joined;


    /**
     * Set joined
     *
     * @param date $joined
     */
    public function setJoined($joined)
    {
        $this->joined = $joined;
    }

    /**
     * Get joined
     *
     * @return date 
     */
    public function getJoined()
    {
        return $this->joined;
    }
}
