<?php
namespace tdc\PlayerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($id)
    {
        $newId = 1;
        if ($id == -1) {
            // create random selection
            $newId = 1;
        } else {
            $newId = $id;
        }
        $user = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();
        $roles = $user->getRoles();

        $router = $this->get('router');
        $cancelurl = $router->generate('tdc_user_subscribe', array(),true);
        $notifyurl = $router->generate('tdc_user_subscribe_confirm', array(),true);
        $completeurl = $router->generate('fos_user_profile_show',array(), true);
        $paramArray = array("cancelUrl"=>$cancelurl,
                            "notifyUrl"=>$notifyurl,
                            "completeUrl"=>$completeurl);

        if (in_array("ROLE_ADMIN",$roles)) {
            return $this->render('tdcPlayerBundle:Default:index.html.twig',
                                array("videoId"=>$newId));
        } else { 
            $subscription = $user->getSubscription();
            if ($subscription){
                if ($subscription->getTdcStatus() == 1)  {
                    return $this->render('tdcPlayerBundle:Default:index.html.twig',
                                        array("videoId"=>$newId));
                } else {
                    $this->get('session')->setFlash('expiredSubscription', 'Your subscription has expired');
                    return $this->render('tdcUserBundle:Default:subscribe.html.twig',
                                        $paramArray);
                }
            } else {
                return $this->render('tdcUserBundle:Default:subscribe.html.twig',
                                     $paramArray);
            }
        }
    }
}

