<?php
class aboutus implements IController {
    public function main()
    {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClass("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        global $globals;
        $aboutus['name']="Sardar Muhammad Saleem Khan";
        $aboutus['information']="Advocate High Court";
        $aboutus['contact']="0300-9759597";
        $aboutus['img']=SITEURL."images/lawyer.jpg";
        $view->aboutus=$aboutus;
        $result 					= $view->render('../views/aboutus.php');
        $fc->setBody($result);
    }

}