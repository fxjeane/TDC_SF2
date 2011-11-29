<?php

namespace tdc\QABundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use tdc\UserBundle\Entity\User;
use tdc\QABundle\Entity\Question;
use tdc\QABundle\Entity\Answer;

class LoadVideoData implements FixtureInterface
{
    public function load($manager)
    {
        $qb = $manager->createQueryBuilder();
        $qb->add('select', 'u')
           ->add('from', 'tdc\UserBundle\Entity\User u');
        $query = $qb->getQuery();
        $result = $query->getResult();
        
        // lets create a bunch of questions
        $questions = array();
        for ($i = 0 ; $i < 100; $i +=1) {
            // radom user
            $u = $result[rand(0,count($result)-1)];
            $q = new Question();
            $q->setUser($u);
            $q->setCreated(new \DateTime('now'));
            $q->setUpdated(new \DateTime('now'));
            $q->setText("This is question $i?");
            $manager->persist($q);
            $questions[] = $q;
        }

        // create some answers
        for ($i = 0 ; $i < 200; $i +=1) {
            // random question
            $q = $questions[rand(0,count($questions)-1)];
            // radom user
            $u = $result[rand(0,count($result)-1)];

            $a = new Answer();
            $a->setUser($u);
            $a->setQuestion($q);
            $a->setCreated(new \DateTime('now'));
            $a->setUpdated(new \DateTime('now'));
            $a->setText("This is answer $i for question ".$q->getId());
            $manager->persist($a);
        }

        $manager->flush();
    }
}
