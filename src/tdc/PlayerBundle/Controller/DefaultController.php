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

        return $this->render('tdcPlayerBundle:Default:index.html.twig',
                            array("videoId"=>$newId));
    }
}
