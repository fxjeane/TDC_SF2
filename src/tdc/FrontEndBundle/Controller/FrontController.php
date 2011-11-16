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

    public function catalogAction($id)
    {
        if ($id == -1 ) {
            $videos = $this->getdoctrine()
                ->getrepository('tdcVideoBundle:Video')
                ->findall();
            return $this->render('tdcFrontEndBundle:Default:catalog.html.twig',
                                 array("videos"=>$videos));
        }
        else
        {
            $video = $this->getDoctrine()
                ->getRepository('tdcVideoBundle:Video')
                ->find($id);
            return $this->render('tdcFrontEndBundle:Default:video.html.twig',
                    array("video"=>$video));
        }
    }

    public function categoryAction($id)
    {
        if ($id == -1 ) {
            $categories = $this->getDoctrine()
                ->getrepository('tdcVideoBundle:Category')
                ->findall();
            return $this->render('tdcFrontEndBundle:Default:category.html.twig',
                                 array("index"=>true,"category"=>$categories));
        }
        else
        {
            $categories = $this->getDoctrine()
                ->getRepository('tdcVideoBundle:Category')
                ->find($id);
            $video = $categories->getVideos();

            return $this->render('tdcFrontEndBundle:Default:category.html.twig',
                    array("index"=>false,
                          "category"=>$categories,
                          "video"=>$video));
        }
    }

    public function aboutAction()
    {
        return $this->render('tdcFrontEndBundle:Default:about.html.twig');
    }

    public function faqAction()
    {
        return $this->render('tdcFrontEndBundle:Default:faq.html.twig');
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
