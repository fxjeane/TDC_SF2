<?php

namespace tdc\VideoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('tdcMembersBundle:Default:index.html.twig');
    }
}
