<?php
namespace tdc\FrontEndBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;

use tdc\QABundle\Entity\Question;
use tdc\QABundle\Entity\Answer;
use tdc\QABundle\Entity\QuestionTag;

class FrontEndService extends ContainerAware
{
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getStats()
    {
        $users = $this->doctrine->getrepository('tdcUserBundle:User')->findAll();
        $subs = $this->doctrine->getrepository('tdcUserBundle:Subscription')->findAll();
        $questions = $this->doctrine->getrepository('tdcQABundle:Question')->findAll();
        $answers = $this->doctrine->getrepository('tdcQABundle:Answer')->findAll();
        return array(
                    'userCount'=>count($users),
                    'subsCount'=>count($subs),
                    'questionCount'=>count($questions),
                    'answerCount'=>count($answers)
                     );
    }

    public function getTwitterFeed() {

        // Use Matt Harris' OAuth library to make the connection
        // This lives at: https://github.com/themattharris/tmhOAuth
        require_once(dirname(__FILE__).'/../Util/tmhOAuth/tmhOAuth.php');

        // Set the authorization values
        // In keeping with the OAuth tradition of maximum confusion, 
        // the names of some of these values are different from the Twitter Dev interface
        // user_token is called Access Token on the Dev site
        // user_secret is called Access Token Secret on the Dev site
        // The values here have asterisks to hide the true contents 
        // You need to use the actual values from Twitter
        $connection = new \tmhOAuth(array(
                    'consumer_key' => 'ZWyl69wAn0HLBDkU0WsAZw',
                    'consumer_secret' => 'mFNWYCYIaNM1lMHHzMAO4LqNmtwm85MxQ6eVVopP4',
                    'user_token' => '408606536-lwek6uuvAnski8Qkt3TBinKrT9cD9Q4wprk6B8pA',
                    'user_secret' => 'wg3JuUtP6WK67bFNQeBy7p58dpzvur2qqzDzzaM0Czs',
                    )); 

        // Make the API call
        $connection->request('GET', 
                $connection->url('1/statuses/user_timeline'), 
                array('screen_name' => 'TDChannel'));
        $user = json_decode($connection->response['response']);
        $connection->request('GET', 
                $connection->url('1/statuses/mentions'));
        $mentions = json_decode($connection->response['response']);
        $sa = array();
        if (($user) and ($mentions)) {
            $outArray = array_merge($user,$mentions);
            foreach ($outArray as $arr) {
                $sa[strtotime($arr->created_at)]= $arr;
            }
            ksort($sa);
            $sa = array_reverse($sa);
        }
        return $sa;
    }

    public function getPopularVideos($limit=5) {
        $rep = $this->doctrine->getrepository('tdcVideoBundle:Video');
        $videos = $rep->createQueryBuilder('p')
                      ->setFirstResult(0)
                      ->setMaxResults($limit)
                      ->orderBy('p.views','DESC')
                      ->getQuery()
                      ->getResult();
        return $videos;
    }
}

