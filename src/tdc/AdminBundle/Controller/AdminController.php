<?php

namespace tdc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT p FROM tdcVideoBundle:Video p')
                     ->setMaxResults(5);

        $videos = $query->getResult();
        
        $query = $em->createQuery('SELECT p FROM tdcUserBundle:User p')
                     ->setMaxResults(5);
        $users = $query->getResult();

        return $this->render('tdcAdminBundle:'.
                            $this->container->getParameter('tdc.liveTheme').
                            ':index.html.twig',
                             array("videos"=> $videos,
                                   "users"=>$users)
                            );
    }

    public function videoAction($id)
    {
        if ($id == -1 ) {
            $videos = $this->getDoctrine()
                ->getRepository('tdcVideoBundle:Video')
                ->findAll();

            $foo = array();
            foreach ($videos as $video) {
                //$foo[$video->getId()] = $video->asArray();
                $foo[] = $video->asArray();
            }
            return $this->render('tdcAdminBundle:Default:videoIndex.html.twig',
                                 array("videos"=>$videos,
                                       "json"=>json_encode($foo)));
        }
        else {
            $video = $this->getDoctrine()
                ->getRepository('tdcVideoBundle:Video')
                ->find($id);
            return $this->render('tdcAdminBundle:'.
                                $this->container->getParameter('tdc.liveTheme').
                                ':video.html.twig',
                                 array("video"=>$video));
        }
    }
    public function videoEditAction($id)
    {
        $video = $this->getDoctrine()
            ->getRepository('tdcVideoBundle:Video')
            ->find($id);
        return $this->render('tdcAdminBundle:'.
                            $this->container->getParameter('tdc.liveTheme').
                            ':videoEdit.html.twig',
                             array("video"=>$video));
    }
}
