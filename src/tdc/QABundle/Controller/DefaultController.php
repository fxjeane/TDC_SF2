<?php
namespace tdc\QABundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Query\ResultSetMapping;
use tdc\QABundle\Entity\Question;

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
        
        $popularTags = $this->getPopularTags();

        return $this->render('tdcQABundle:Default:index.html.twig',
                              array('questions'=> $questions,
                                    'tags'=>$popularTags,
                                    'start'=>$start,
                                    'max'=>$max));
    }
    
    public function viewAction($id)
    {
        $em =  $this->getDoctrine()->getEntityManager();
        $question = $this->getdoctrine()->getrepository('tdcQABundle:Question')
                    ->find($id);

        $question->setViews($question->getViews()+1);
        $em->flush();

        $popularTags = $this->getPopularTags();
        return $this->render('tdcQABundle:Default:view.html.twig',
                              array('question'=> $question,
                                    'tags'=>$popularTags));
    }

    public function askAction()
    {
        // create a task and give it some dummy data for this example
        $question = new Question();
        $question->setTitle('Title');

        $form = $this->createFormBuilder($question)
            ->add('title', 'text')
            ->add('text', 'textarea')
            ->add('tags', 'text')
            ->getForm();

        $popularTags = $this->getPopularTags();
        return $this->render('tdcQABundle:Default:ask.html.twig',
                              array('tags'=>$popularTags,
                                    'questionForm' => $form->createView()));
    }

    public function taggedAction($tag,$start,$max)
    {
        $tagval = null;
        $questions = array();
        if ($tag === "all") {
            $rep = $this->getdoctrine()->getrepository('tdcQABundle:QuestionTag');

            $tagval = $rep->createQueryBuilder('p')
                         ->setFirstResult(($start -1) * $max)
                         ->setMaxResults($max)
                         ->getQuery()
                         ->getResult(); 
        } else {
            $tagval = $this->getdoctrine()->getrepository('tdcQABundle:QuestionTag')
                         ->findOneByValue($tag);
            $allQuestions = $tagval->getQuestions();
            $startval = ($start - 1) * $max;
            $endval = min($startval + $max,count($allQuestions));
            for ($i = $startval; $i < $endval;$i +=1) {
                $questions[] = $allQuestions[$i];
            }
        }

        $popularTags = $this->getPopularTags();
        return $this->render('tdcQABundle:Default:tagged.html.twig',
                              array('tag'=> $tagval,
                                    'tags'=>$popularTags,
                                    'questions'=>$questions,
                                    'start'=>$start,
                                    'max'=> $max));
    }

    private function getPopularTags($limit=5) {
        $rep = $this->getdoctrine()->getrepository('tdcQABundle:Question');
        $tagval = $rep->createQueryBuilder('q')
                     ->select('COUNT(q.id) as tag_count, t.value')
                     ->innerJoin('q.tags','t')
                     ->groupBy('t.value')
                     ->orderBy('tag_count','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults($limit)
                     ->getQuery()
                     ->getResult(); 
        return $tagval;
    }
}
