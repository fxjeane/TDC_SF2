<?php
namespace tdc\QABundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $rep = $this->getdoctrine()->getrepository('tdcQABundle:Question');
            
        $questions = $rep->createQueryBuilder('p')
                      ->setFirstResult(0)
                      ->setMaxResults(10)
                      ->orderBy('p.updated', 'DESC')
                      ->getQuery()
                      ->getResult(); 
        
        return $this->render('tdcQABundle:Default:index.html.twig',
                              array('questions'=> $questions));
    }
}
