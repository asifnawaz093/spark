<?php
class DefaultClass implements IController{
public function main() {
$view = new View();
$fc = FC::getInstance();
$params = $fc->getParams();

if(!file_exists('application/views/'.$fc->_controllerFile.'.php')){
    $fc->_controllerFile = "404page";
}
$result = $view->render('../views/'.$fc->_controllerFile.'.php');
$fc->setBody($result);
}
}
?>