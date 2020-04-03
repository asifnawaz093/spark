<?php
class index implements IController {
    public function main() {
        $view = new View();
        $fc = FC::getInstance();
        $session = FC::getClass("Session");
        $db = FC::getClass("Db");
        Tools::redirect("addcase");
		$result = $view->render('../views/index.php');
        $fc = FC::getInstance();
        $fc->setBody($result);
    }
}
?>