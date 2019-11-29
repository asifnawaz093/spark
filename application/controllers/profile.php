<?php
class profile implements IController{
    public function main() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClassInstance("Session");
        $session->session();
        echo "Under construction"; exit();
        if($session->isAdmin()||$session->isManager()||$session->isExecutive()){
            $view->admin = true;
            $id_user = $id = Tools::getValue("id");
            $payment = FC::getClassInstance("Payment");
            $wallet = FC::getClassInstance("Wallet");
            $view->user_processors = $payment->getUserProcessors($id);
            $view->processors = $payment->getProcessors($id_user);
            $view->user_processors = $payment->getUserProcessors($id_user);
        }
        else{
            $id = $session->myId();
        }
        $view->row = $db->getRow("SELECT * FROM user_pro WHERE `id`='$id' LIMIT 1");
        if(!file_exists('../views/profile.php')){
            $fc->_controllerFile = "404page";
        }
        $result = $view->render('../views/profile.php');
        $fc->setBody($result);
    }
}
?>