<?php
namespace tdc\QABundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
#use Doctrine\ORM\Query\ResultSetMapping;
use tdc\QABundle\Entity\Question;
use tdc\QABundle\Entity\Answer;
use tdc\QABundle\Entity\QuestionTag;

use Symfony\Component\HttpFoundation\Response;

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
        $popularQuestions = $this->getPopularQuestions();
        return $this->render('tdcQABundle:Default:index.html.twig',
                              array('questions'=> $questions,
                                    'tags'=>$popularTags,
                                    'start'=>$start,
                                    'max'=>$max,
                                    'popularQuestions'=>$popularQuestions));
    }
    
    public function viewAction($id)
    {
        $em =  $this->getDoctrine()->getEntityManager();
        $question = $this->getdoctrine()->getrepository('tdcQABundle:Question')
                    ->find($id);

        $question->setViews($question->getViews()+1);
        $em->flush();

        $popularTags = $this->getPopularTags();
        $popularQuestions = $this->getPopularQuestions();
        return $this->render('tdcQABundle:Default:view.html.twig',
                              array('question'=> $question,
                                    'tags'=>$popularTags,
                                    'popularQuestions'=>$popularQuestions));
    }

    public function askAction(Request $request)
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();
        // create a task and give it some dummy data for this example
        $question = new Question();
        $question->setUser($userObj);
        $now   = new \DateTime('now');
        $question->setCreated($now);
        $question->setUpdated($now);
        $question->setViews(0);

        $form = $this->createFormBuilder($question)
            ->add('title', 'text')
            ->add('text', 'textarea')
            ->add('tags', 'text')
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                for ($i = 0;$i < 5; $i+=1) {
                    $tagval = $request->get("tag_".$i);

                    if ($tagval != "") {
                        $foundTag = $this->getDoctrine()
                        ->getRepository('tdcQABundle:QuestionTag')
                        ->findByValue($tagval);
                        if (!$foundTag) {
                            $newt = new QuestionTag();
                            $newt->setValue($tagval);
                            $question->addQuestionTag($newt);
                            $em->persist($newt);
                        } else {
                            $question->addQuestionTag($foundTag[0]);
                        }
                    }
                }
                $question->setTitle(stripslashes($question->getTitle()));
                $question->setText(stripslashes($question->getText()));
                $em->persist($question);
                $em->flush();

                // perform some action, such as saving the task to the database
                return $this->redirect($this->generateUrl('tdc_qa_view',
                                       array('id' => $question->getId())));
            }
        }

        $popularTags = $this->getPopularTags();
        $popularQuestions = $this->getPopularQuestions();
        return $this->render('tdcQABundle:Default:ask.html.twig',
                              array('tags'=>$popularTags,
                                    'popularQuestions'=>$popularQuestions,
                                    'questionForm' => $form->createView()));
    }
    
    public function answerAction($id,Request $request) 
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();
        $em =  $this->getDoctrine()->getEntityManager();
        $question = $this->getdoctrine()
                    ->getrepository('tdcQABundle:Question')
                    ->find($id);

        $answer = new Answer();
        $answer->setTitle($question->getTitle());
        $answer->setUser($userObj);
        $now   = new \DateTime('now');
        $answer->setCreated($now);
        $answer->setUpdated($now);
        $answer->setQuestion($question);

        $form = $this->createFormBuilder($answer)
            ->add('title', 'text')
            ->add('text', 'textarea')
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $question->addAnswer($answer);
                $answer->setTitle(stripslashes($answer->getTitle()));
                $answer->setText(stripslashes($answer->getText()));
                $em->persist($answer);
                $em->persist($question);
                $em->flush();
                // perform some action, such as saving the task to the database
                return $this->redirect($this->generateUrl('tdc_qa_view',
                                       array('id' => $question->getId())));
            }
        }

        $popularTags = $this->getPopularTags();
        $popularQuestions = $this->getPopularQuestions();
        return $this->render('tdcQABundle:Default:answer.html.twig',
                            array('tags'=>$popularTags,
                                  'popularQuestions'=>$popularQuestions,
                                  'question'=>$question,
                                  'answerForm'=> $form->createView()));
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
        $popularQuestions = $this->getPopularQuestions();
        return $this->render('tdcQABundle:Default:tagged.html.twig',
                              array('tag'=> $tagval,
                                    'tags'=>$popularTags,
                                    'popularQuestions'=>$popularQuestions,
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
    
    private function getPopularQuestions($limit=5) {
        $rep = $this->getdoctrine()->getrepository('tdcQABundle:Question');
        $tagval = $rep->createQueryBuilder('q')
                     ->select('q')
                     ->orderBy('q.views','DESC')
                     ->setFirstResult(0)
                     ->setMaxResults($limit)
                     ->getQuery()
                     ->getResult(); 
        return $tagval;
    }
}
