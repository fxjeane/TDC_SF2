<?php
namespace tdc\FrontEndBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;


class FrontController extends Controller
{
    public function indexAction()
    {
        $bannerImages = glob(getcwd()."/public/images/TDC1/frontBanners/*.jpg");
        $photoData = array();
        foreach ($bannerImages as $bi) {
            $image = substr($bi,strripos($bi,"/"));
            $photo = array();
            $photo["content"] = "<div class='slide_inner'><img class='photo' src='../public/images/TDC1/frontBanners$image' alt='Bike'></div>";
            $photo["content_button"] = "<div class='thumb'></div><p>Agile Carousel Place Holder</p>";
            $photoData[] = $photo;
        }

    
        return $this->render('tdcFrontEndBundle:Default:index.html.twig',
                            array('bannerImages'=>json_encode($photoData)));
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

    public function siteStatsAction() {
        $stats = $this->get('tdc.FrontEndService')->getStats();       
        return $this->render(':TDC1:siteStats.html.twig',
                            array("stats"=>$stats));
    }

    public function siteFeedAction() {
        $twitter = $this->get('tdc.FrontEndService')->getTwitterFeed();
        return $this->render(':TDC1:siteFeed.html.twig',
                            array("tweets"=>$twitter));
    }
}
