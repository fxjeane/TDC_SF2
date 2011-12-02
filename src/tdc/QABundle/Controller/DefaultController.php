<?php
namespace tdc\QABundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($start,$max)
    {
        $rep = $this->getdoctrine()->getrepository('tdcQABundle:Question');

        $questions = $rep->createQueryBuilder('p')
                     ->setFirstResult(($start -1) * $max)
                     ->setMaxResults($max)
                     ->orderBy('p.updated', 'DESC')
                     ->getQuery()
                     ->getResult(); 
            
        return $this->render('tdcQABundle:Default:index.html.twig',
                              array('questions'=> $questions,
                                    'start'=>$start,
                                    'max'=>$max));
    }

    public function viewAction($id)
    {
        $question = $this->getdoctrine()->getrepository('tdcQABundle:Question')
                    ->find($id);

        return $this->render('tdcQABundle:Default:view.html.twig',
                              array('question'=> $question));
    }

    public function taggedAction($tag,$start,$max)
    {
        $tagval = null;
        if ($tag === "all") {
            $rep = $this->getdoctrine()->getrepository('tdcQABundle:QuestionTag');

            $tagval = $rep->createQueryBuilder('p')
                         ->setFirstResult(($start -1) * $max)
                         ->setMaxResults($max)
                         ->orderBy('p.updated', 'DESC')
                         ->getQuery()
                         ->getResult(); 
        } else {
            $tagval = $this->getdoctrine()->getrepository('tdcQABundle:QuestionTag')
                         ->findOneByValue($tag);
            $allQuestions = $tagval->getQuestions();
            $startval = ($start - 1) * $max;
            $endval = min($startval + $max,count($allQuestions));
            $questions = array();
            for ($i = $startval; $i < $endval;$i +=1) {
                $questions[] = $allQuestions[$i];
            }
        }

        return $this->render('tdcQABundle:Default:tagged.html.twig',
                              array('tag'=> $tagval,
                                    'questions'=>$questions,
                                    'start'=>$start,
                                    'max'=> $max));
    }
}
