<?php

namespace tdc\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('tdcFrontEndBundle:Default:index.html.twig');
    }

    public function catalogAction()
    {
        $videos = $this->getdoctrine()
            ->getrepository('tdcVideoBundle:Video')
            ->findall();
        return $this->render('tdcFrontEndBundle:Default:catalog.html.twig',
                             array("videos"=>$videos));
    }

    public function catalogJsonAction($start,$max)
    {
        $dql = 'SELECT p FROM tdc\VideoBundle\Entity\Video p ' .
               'ORDER BY p.name DESC';
                               
        $query = $this->getDoctrine()->getEntityManager()->createQuery($dql);
        $query->setMaxResults($max);
        $query->setFirstResult($start);

        $videos = $query->getResult();
        $json = array();
        foreach ($videos as $vid) {
            $json[]= $vid->asArray();
        }
        //return new Response(get_class($videos));
        return new Response(json_encode($json));
    }
    public function aboutAction()
    {
        return $this->render('tdcFrontEndBundle:Default:about.html.twig');
    }

    public function faqAction()
    {
        return $this->render('tdcFrontEndBundle:Default:faq.html.twig');
    }
    public function videoAction($id)
    {
        $video = $this->getDoctrine()
            ->getRepository('tdcVideoBundle:Video')
            ->find($id);
        return $this->render('tdcFrontEndBundle:Default:video.html.twig',
                array("video"=>$video));
    }
    
    public function homeAction()
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();

        $roles = $userObj->getRoles();
        if (in_array("ROLE_ADMIN",$roles)) {
            return $this->redirect($this->generateUrl('tdc_admin'));
        } 
        else
        {
            $user = $this->getdoctrine()
                ->getrepository('tdcUserBundle:User')
                ->findOneByUsername($userObj->getUsername());
            
            $data = array("user"=>$user);

            return $this->render('tdcFrontEndBundle:Default:home.html.twig',
                                $data);
        }
    }
}
