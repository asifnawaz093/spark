<?php
class dashboard implements IController{
    public function main() {
        $view = new View();
        $fc = FC::getInstance();
        $db = FC::getClassInstance("Db");
        $session = FC::getClass("Session");
        $payment = FC::getClass("Payment");
        $session->session();
        $fc->js_files = array("//cdn.jsdelivr.net/momentjs/latest/moment.min.js",
                    "//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js",
                    SITEURL . "scripts/highcharts/highcharts.js");
        $fc->css_files = array("//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css");
        $id_user = Session::get("id_user");
        $view->balance1 = $payment->getBalance($id_user);
        global $data;
        $view->data = $data;
        $view->transactions = $payment->getTransctions($id_user);
        if($view->transactions)
            $view->last_transaction = $view->transactions[0];
        else $view->last_transaction = false;
        if($view->last_transaction){
            $lt = $view->last_transaction;
            $dir = ($lt['sender'] == Session::get("id_user")) ? "out" : "in";
            $sign = ($lt['sender'] == Session::get("id_user")) ? "-" : "+";
            $view->last_transaction = "$sign" . Tools::price($lt['amount'], CURRENCY);
        }
        $view->invoices = $payment->getInvoices($id_user, Session::user("ad_email"));
        $view->last_login = $db->getRow("SELECT * FROM `inlogs` WHERE `id_user`='$id_user' ORDER BY `id` DESC LIMIT 1,1");
        $date = date("Y-m", time());
        $date = $date . "-1 00:00:00";
        $row = $db->getRow("SELECT COUNT(`id`) as sales, SUM(`amount`) as amount FROM  `".DBPREFIX."transactions` WHERE `type` = 0 AND `tdate` >= '$date' and status != 10");
        $view->sales = $row['sales'];
        $view->sales_earning = $row['amount'];
        if(!file_exists('../views/dashboard.php'))
        {
            $fc->_controllerFile = "404page";
        }
        
        $result = $view->render('../views/dashboard.php');
        $fc->setBody($result);
      
    }
    
}
?>