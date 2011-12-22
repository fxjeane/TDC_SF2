<?php
namespace tdc\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

require_once(dirname(__file__)."/../Lib/confirm.php");


class DefaultController extends Controller
{
    public function subscribeAction()
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();
             
        return $this->render('tdcUserBundle:Default:subscribe.html.twig',
                            array("user"=>$userObj));
    }

    public function subscribeConfirmAction()
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();

        $confData = confirmSubscription(); 
        $arrayData = array('user'=>$userObj,
                           'confData'=>$confData);

        return $this->render('tdcUserBundle:Default:subscribeConfirm.html.twig',$arrayData);
    }
}
