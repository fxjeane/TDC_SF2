<?php
namespace tdc\QABundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;

use tdc\QABundle\Entity\Question;
use tdc\QABundle\Entity\Answer;
use tdc\QABundle\Entity\QuestionTag;

class QAService extends ContainerAware
{
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function getPopularTags($limit=5) {
        $rep = $this->doctrine->getrepository('tdcQABundle:Question');
        $tagval = $rep->createQueryBuilder('q')
                     ->select('COUNT(q.id) as tag_count, t.value')
                     ->innerJoin('q.tags','t')
                     ->groupBy('t.value')
                     ->orderBy('tag_count','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults($limit)
                     ->getQuery()
                     ->getResult(); 
        return $tagval;
    }
    
    public function getRandomTags($limit=5) {
        $rep = $this->doctrine->getrepository('tdcQABundle:QuestiontTag');
        #$tagcount = $rep->createQueryBuilder('q')
        #             ->select('COUNT(q.id) as tag_count')
        #             ->getQuery()
        #             ->getResult(); 
        #$radar = array();
        #for ($i = 0, $i < $tagcount; $i++)
            #$radar[]

        $tagval =  $rep->createQueryBuilder('q')
                     ->orderBy('RAND()','DESC')
                     ->getQuery()
                     ->getResult();            
        
        return $tagval;                 
    }

    public function getPopularQuestions($limit=5) {
        $rep = $this->doctrine->getrepository('tdcQABundle:Question');
        $tagval = $rep->createQueryBuilder('q')
                     ->select('q')
                     ->orderBy('q.views','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults($limit)
                     ->getQuery()
                     ->getResult(); 
        return $tagval;
    }

    public function getLatestQuestions($limit=5) {
        $rep = $this->doctrine->getrepository('tdcQABundle:Question');
        $tagval = $rep->createQueryBuilder('q')
                     ->select('q')
                     ->orderBy('q.created','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults($limit)
                     ->getQuery()
                     ->getResult(); 
        return $tagval;
    }
}
