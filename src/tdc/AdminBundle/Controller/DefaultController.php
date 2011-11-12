<?php

namespace tdc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('tdcAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
