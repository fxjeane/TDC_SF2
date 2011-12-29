<?php
namespace tdc\UserBundle\Entity;

class Subscription {
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var date $created
     */
    private $created;

    /**
     * @var date $expires
     */
    private $expires;

    /**
     * @var string $transaction
     */
    private $transaction;

    /**
     * @var tdc\UserBundle\Entity\User
     */
    private $user_subscription;


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
     * Set created
     *
     * @param date $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set expires
     *
     * @param date $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     * Get expires
     *
     * @return date 
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set transaction
     *
     * @param string $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get transaction
     *
     * @return string 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set user_subscription
     *
     * @param tdc\UserBundle\Entity\User $userSubscription
     */
    public function setUserSubscription(\tdc\UserBundle\Entity\User $userSubscription)
    {
        $this->user_subscription = $userSubscription;
    }

    /**
     * Get user_subscription
     *
     * @return tdc\UserBundle\Entity\User 
     */
    public function getUserSubscription()
    {
        return $this->user_subscription;
    }
    /**
     * @var string $interval
     */
    private $interval;


    /**
     * Set interval
     *
     * @param string $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * Get interval
     *
     * @return string 
     */
    public function getInterval()
    {
        return $this->interval;
    }
    /**
     * @var string $duration
     */
    private $duration;


    /**
     * Set duration
     *
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Get duration
     *
     * @return string 
     */
    public function getDuration()
    {
        return $this->duration;
    }
    /**
     * @var string $status
     */
    private $status;


    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var boolean $tdcStatus
     */
    private $tdcStatus;


    /**
     * Set tdcStatus
     *
     * @param boolean $tdcStatus
     */
    public function setTdcStatus($tdcStatus)
    {
        $this->tdcStatus = $tdcStatus;
    }

    /**
     * Get tdcStatus
     *
     * @return boolean 
     */
    public function getTdcStatus()
    {
        return $this->tdcStatus;
    }
}