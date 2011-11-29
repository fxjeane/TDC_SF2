<?
namespace tdc\VideoBundle\Entity;

class Rating
{
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
     * @var tdc\VideoBundle\Entity\Video
     */
    private $videos;

    public function __construct()
    {
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Get videos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }
    /**
     * @var tdc\VideoBundle\Entity\User
     */
    private $users;


    /**
     * Add users
     *
     * @param tdc\VideoBundle\Entity\User $users
     */
    public function addUser(\tdc\VideoBundle\Entity\User $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    /**
     * @var integer $user
     */
    private $user;

    /**
     * @var tdc\VideoBundle\Entity\Video
     */
    private $video;


    /**
     * Set user
     *
     * @param integer $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get video
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set video
     *
     * @param integer $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }
    /**
     * @var integer $value
     */
    private $value;


    /**
     * Set value
     *
     * @param integer $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }
}