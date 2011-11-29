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
}
