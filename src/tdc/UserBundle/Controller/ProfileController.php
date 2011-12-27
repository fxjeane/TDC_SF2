<?php

namespace tdc\UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        // get latest questions
        $em = $this->container->get('doctrine')->getEntityManager();
        $qrep = $this->container->get('doctrine')->getrepository('tdcQABundle:Question');
        $questions = $qrep->createQueryBuilder('q')
                     ->where("q.user = ?1")
                     ->orderBy('q.updated','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults(5)
                     ->setParameter(1,$user->getId())
                     ->getQuery()
                     ->getResult(); 

        $arep = $this->container->get('doctrine')->getrepository('tdcQABundle:Answer');
        $answers = $arep->createQueryBuilder('q')
                     ->where("q.user = ?1")
                     ->orderBy('q.updated','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults(5)
                     ->setParameter(1,$user->getId())
                     ->getQuery()
                     ->getResult(); 

        return $this->container->get('templating')
                ->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),
                array('user' => $user,
                      'questions'=>$questions,
                      'answers'=>$answers));
    }
}
