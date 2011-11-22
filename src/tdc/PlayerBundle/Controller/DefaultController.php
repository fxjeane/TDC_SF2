<?php
namespace tdc\PlayerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('tdcPlayerBundle:Default:index.html.twig');
    }
}
