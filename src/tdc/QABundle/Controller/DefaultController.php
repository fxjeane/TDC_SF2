<?php

namespace tdc\QABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('tdcQABundle:Default:index.html.twig');
    }
}
