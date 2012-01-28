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

        $quoters = glob(getcwd()."/public/images/quoters/*.png");
        $quoterFullPath = $quoters[array_rand($quoters)];
        $quoterImg = substr($quoterFullPath,strripos($quoterFullPath,"/"));
        $quoterPath = substr($quoterFullPath,0,strripos($quoterFullPath,"/"));
        $quote = file_get_contents($quoterPath.substr($quoterImg,0,strripos($quoterImg,".")).".txt");

        $videos = $this->get('tdc.FrontEndService')->getPopularVideos($limit=10);
        $questions = $this->get('tdc.QAService')->getLatestQuestions($limit=10);

        return $this->render('tdcFrontEndBundle:Default:index.html.twig',
                            array('bannerImages'=>json_encode($photoData),
                                'videos'=>$videos,
                                'questions'=>$questions,
                                'quoterImage'=>$quoterImg,
                                "quote"=>$quote
                                ));
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

    public function catalogItemAction($entity,$id,$start,$max)
    {
        $item = $this->getdoctrine()->getrepository('tdcVideoBundle:'.
                                                    ucfirst($entity))
                     ->find($id);
        $tempVars = array("item"=>$item,
                          "entity"=>$entity);
        
        if ($entity == "video") {
            $scdir = $item->getFilepath();
            # get all files in the screenshot folder
            $contents = glob(getcwd()."/public/images/videos/".$scdir."*");
            $screenshots = array();
            foreach ($contents as $sh) {
                if (!strpos($sh,"_icon")){
                    $screenshots[] = substr($sh,strlen(getcwd()));
                    }
            }
            $tempVars["screenshots"] = $screenshots;
        }
        if ($entity == "category") {
            $rep = $this->getdoctrine()->getrepository('tdcVideoBundle:Video');
                
            $videos = $rep->createQueryBuilder('p')
                          ->innerJoin('p.categories','cat')
                          ->where('cat.id = ?1')
                          ->setParameter(1,$id)
                          ->setFirstResult($max * ($start - 1))
                          ->setMaxResults($max)
                          ->getQuery()
                          ->getResult();
            $tempVars['videos'] = $videos;
            $tempVars['start'] = $start;
            $tempVars['max'] = $max;
        }

        return $this->render('tdcFrontEndBundle:Default:item.html.twig',
                             $tempVars);
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
