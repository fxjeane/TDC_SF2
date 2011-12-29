<?php
namespace tdc\FrontEndBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
    public function indexAction()
    {
        $themeSelector = $this->get('tdcThemeSelector');
        return $this->render('tdcFrontEndBundle:Default:index.html.twig',
                            array('os'=>php_uname('s')));
    }

    public function catalogListAction($entity,$start,$max)
    {
        $rep = $this->getdoctrine()->getrepository('tdcVideoBundle:'.
                                                    ucfirst($entity));
            
        $values = $rep->createQueryBuilder('p')
                      ->setFirstResult(($start - 1) * $max)
                      ->setMaxResults($max)
                      ->getQuery()
                      ->getResult();

            return $this->render('tdcFrontEndBundle:Default:catalog.html.twig',
                                 array("entity"=>$entity,
                                       "start"=>$start,
                                       "max"=>$max,
                                       "values"=>$values));
    }

    public function catalogItemAction($entity,$id)
    {
        $item = $this->getdoctrine()->getrepository('tdcVideoBundle:'.
                                                    ucfirst($entity))
                     ->find($id);
        return $this->render('tdcFrontEndBundle:Default:item.html.twig',
                             array("item"=>$item,"entity"=>$entity));
    }


    public function aboutAction()
    {
        return $this->render('tdcFrontEndBundle:Default:about.html.twig');
    }

    public function faqAction()
    {
        return $this->render('tdcFrontEndBundle:Default:faq.html.twig');
    }
    
    public function profileAction()
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

    public function profileEditAction()
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();

            $user = $this->getdoctrine()
                ->getrepository('tdcUserBundle:User')
                ->findOneByUsername($userObj->getUsername());
            
            $data = array("user"=>$user);

            return $this->render('tdcFrontEndBundle:Default:profileEdit.html.twig',
                                $data);
    }
}
