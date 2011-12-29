<?php
namespace tdc\ThemeBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Selector extends Controller 
{
/*
    public function __construct($)
    {
      $this->mood = $mood;
    }
*/
    public function getOs()
    {
        $info = explode(" ",$_SERVER['HTTP_USER_AGENT']); 
        return $info[4]."  -  ".$_SERVER['HTTP_USER_AGENT'];
    }

    public function getLiveTheme()
    {
        //return print_r($arguments,true);
        return $this;
    }
}
