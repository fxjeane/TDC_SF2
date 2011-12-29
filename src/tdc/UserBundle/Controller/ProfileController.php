<?php

namespace tdc\UserBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends BaseController
{
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $roles = $user->getRoles();
        if (in_array("ROLE_ADMIN",$roles)) {
            return new RedirectResponse($this->container->get('router')
                                        ->generate('tdc_admin'));
        } 
        else
        {
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

            $justSubscribed = isset($_GET['txn_type']);

            return $this->container->get('templating')
                    ->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),
                    array('user' => $user,
                          'questions'=>$questions,
                          'answers'=>$answers,
                          'justSubscribed'=>$justSubscribed));
        }
    }
}
