<?php
namespace tdc\WebServiceBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('tdcWebServiceBundle:Default:index.html.twig', array('name' => $name));
    }

    public function videoListAction($start,$max,$category)
    {
        $repository = $this->getDoctrine()
                      ->getRepository('tdcVideoBundle:Video');
        
        $query = $repository->createQueryBuilder('p');
        if ($category != -1) {
                $query->leftJoin('p.categories','c')
                ->where('c.id = :catid')
                ->setParameter('catid', $category);
        }

        if ($max != -1) 
            $query->setFirstResult($start)
                  ->setMaxResults($max);

        $qq = $query->getQuery();

        $videos = $qq->getResult();
        $json = array();
        foreach ($videos as $vid) {
            $json[]= $vid->asArray();
        }

        return new Response(json_encode($json));
    }

    public function videoAction($id)
    {
        $videos = $this->getDoctrine()
            ->getRepository('tdcVideoBundle:Video')
            ->find($id);
        return new Response(json_encode($videos->asArray()));
    }

    public function categoryListAction($start,$max)
    {
        $repository = $this->getDoctrine()
                      ->getRepository('tdcVideoBundle:Category');
        
        $query = $repository->createQueryBuilder('p');

        if ($max != -1) 
            $query->setFirstResult($start)
                  ->setMaxResults($max);

        $categories = $query->getQuery()->getResult();
        
        # prepare output
        $json = array();
        foreach ($categories as $cat) {
            $tmp= $cat->asArray();
            $videos = array();
            foreach ($cat->getVideos() as $vid)
                $videos[] = $vid->asArray();
            $tmp['videos'] = $videos;
            $json[] = $tmp;
        }

        return new Response(json_encode($json));
    }

    public function categoryAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository('tdcVideoBundle:Category')
            ->find($id);
        $json = $category->asArray();
        $videos = array();
        foreach ($category->getVideos() as $vid)
            $videos[] = $vid->asArray();
        $json['videos'] = $videos;
        
        return new Response(json_encode($json));
    }

    public function questionListAction($start,$max)
    {
        $repository = $this->getDoctrine()
                      ->getRepository('tdcQABundle:Question');
        
        $query = $repository->createQueryBuilder('p');

        if ($max != -1) 
            $query->setFirstResult($start)
                  ->setMaxResults($max);

        $questions = $query->getQuery()->getResult();
        
        # prepare output
        $json = array();
        foreach ($questions as $quest) {
            $tmp= $quest->asArray();
            $answers = array();
            foreach ($quest->getAnswers() as $ans)
                $answers[] = $ans->asArray();
            $tmp['answers'] = $answers;
            $json[] = $tmp;
        }

        return new Response(json_encode($json));
    }

    public function questionAction($id)
    {
        $question = $this->getDoctrine()
            ->getRepository('tdcQABundle:Question')
            ->find($id);
        $json = $question->asArray();
        $answers = array();
        foreach ($question->getAnswers() as $ans)
            $answers[] = $ans->asArray();
        $json['answers'] = $answers;
        
        return new Response(json_encode($json));
    }

    public function taggedListAction($tag,$start,$max)
    {
        $json = array();
        if ($tag == "all") {
            $repository = $this->getDoctrine()
                          ->getRepository('tdcQABundle:QuestionTag');
            
            $query = $repository->createQueryBuilder('p');

            if ($max != -1) 
                $query->setFirstResult($start)
                      ->setMaxResults($max);
            $tagvals = $query->getQuery()->getResult();
            if ($tagvals != null) {
                foreach ($tagvals as $tagval) {
                    $allQuestions = $tagval->getQuestions();
                    $startval = ($start) * $max;
                    $endval = min($startval + $max,count($allQuestions));
                    if ($max === -1) {
                        $startval = 0;
                        $endval = count($allQuestions);
                    }

                    $questions = array();
                    for ($i = $startval; $i < $endval;$i +=1) {
                        $qq = $allQuestions[$i];
                        $qqar = $qq->asArray();
                        $ans = array();
                        foreach ($qq->getAnswers() as $aa) {
                            $qqar['answers'] = $aa->asArray();
                        }
                        $questions[] = $qqar;
                    }
                    $json[$tagval->getValue()]=$questions;
                }
            }

        } else {
            $tagval = $this->getdoctrine()->getrepository('tdcQABundle:QuestionTag')
                         ->findOneByValue($tag);
            if ($tagval != null) {
                $allQuestions = $tagval->getQuestions();
                $startval = ($start) * $max;
                $endval = min($startval + $max,count($allQuestions));
                if ($max === -1) {
                    $startval = 0;
                    $endval = count($allQuestions);
                }

                $questions = array();
                for ($i = $startval; $i < $endval;$i +=1) {
                    $qq = $allQuestions[$i];
                    $qqar = $qq->asArray();
                    $ans = array();
                    foreach ($qq->getAnswers() as $aa) {
                        $qqar['answers'] = $aa->asArray();
                    }
                    $questions[] = $qqar;
                }
                $json=$questions;
            }
        }
        return new Response(json_encode($json));
    }

    public function tagsAction($searchval,$start,$max)
    {
        $json = array();
        if ($searchval == "all") {
            $repository = $this->getDoctrine()
                          ->getRepository('tdcQABundle:QuestionTag');
            
            $query = $repository->createQueryBuilder('p');

            if ($max != -1) 
                $query->setFirstResult($start)
                      ->setMaxResults($max);
            $tagvals = $query->getQuery()->getResult();

            if ($tagvals != null) {
                foreach ($tagvals as $tagval) {
                    $json[]=$tagval->getValue();
                }
            }
        }
        else
        {
            $qb = $this->getdoctrine()
                   ->getrepository('tdcQABundle:QuestionTag')
                   ->createQueryBuilder('t');

            $qb->where('t.value LIKE :wildcard')
               ->setParameter('wildcard',"%".$searchval."%");

            if ($max != -1) 
                $qb->setFirstResult($start)
                      ->setMaxResults($max);
            
            $tagvals = $qb->getQuery()->getResult();
            
            if ($tagvals != null) {
                foreach ($tagvals as $tagval) {
                    $json[]=$tagval->getValue();
                }
            }
        }
        return new Response(json_encode($json));
    }
}
