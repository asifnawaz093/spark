<?php
class FC {
   protected $_controller, $_action, $_params, $_body;
   public $_controllerFile, $_index = INDEX, $admin_call = false, $dir_prefix="", $_requireFile, $page;
   static $_instance;
   static $instance;
   static $instances = array();
   public $css_files = array(), $min_css = array();
   public $js_files = array(), $min_js = array();
   public $template = "default";
   public $error = false;
   public $success = false;
   public $msg = false;
   public $extra = array();
   public $warning = false;
   public $info = false; public $redirect = false;
   public $onload = "";
   public $cache = false;
   public $vars = array();
   public $var;
   public $meta = array("title"=>SITE_PAGE_TILE, "description"=>"", "keywords" => "", "og_image"=>"", "og_url"=>""); 
   
   
   public static function getInstance() {
      if( ! (self::$_instance instanceof self) ) {
         self::$_instance = new self();
      }
      return self::$_instance;
   }
   
   private function __construct(){
      $this->view = new ArrayObject();
      $request = $_SERVER['REQUEST_URI'];
      $splits = explode('/', trim($request,'/'));
      if(!empty($splits[$this->_index])){
         $file=explode(".php",$splits[$this->_index]);
         $this->page = $this->_controller = $file[0];
      } else{
         $this->page = $this->_controller = "index";
      }
      $this->_controllerFile = !empty($splits[$this->_index])?$splits[$this->_index]:'index.php';
      $this->_action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : 'main';
      $this->_params = array_slice($splits, $this->_index + 1);
      $session = FC::getClass("Session");
      $this->success = $session->getOnce("success"); $this->error = $session->getOnce("error");
      $this->warning = $session->getOnce("warning");$this->info = $session->getOnce("info");
      $this->redirect = $session->get("redirect");
      $this->msg = $session->getOnce("msg"); 
   }
   
   public function getParams(){
      return $this->_params;
   }
   public function getController(){
      return $this->_controller;
   }
   public function getControllerFile() {
      return $this->_controllerFile;
   }
   public function getAction(){
      return $this->_action;
   }
   public function getBody(){
      return $this->_body;
   }
   public function setBody($body){
      $this->_body =  $body;
   }
   
   //should be use in future versions
   //compiles the css and js files
   public function getHeaderParams(){
      return array("css_files" => $this->css_files, "js_files" => $this->js_files);
   }
   
   public function pageNotFound($controller){
      $url = FC::getClass("Url")->currentUrl();
      $this->error = "Url: $url <a href='$url' class='btn btn-primary'>Retry</a>";
      Tools::redirect(SITEURL."404page");
   }
   
   public function autoLoadClass($file){
      if(file_exists(($this->dir_prefix . "application/controllers/".$file.".php"))){
      require_once($this->dir_prefix . "application/controllers/".$file.".php"); }
      else{ require_once($this->dir_prefix . "application/controllers/DefaultClass.php");
      $this->_controllerFile=$this->getController();
      $this->_requireFile=$file;
      $this->_controller="DefaultClass"; }
   }
   public function loadConfig($file){
      if(file_exists(("includes/config/".$file.".php"))){
      require_once("includes/config/".$file.".php"); }
      else{ die("$file Configuratoin file does not exist"); }
   }
   
    public function loadTemplate($file = false, $admin=false){
       if(!$file){ $file = $this->template; }
       if($admin){
        include(ADMINDIR."application/views/templates/".$file.".php");
       }
       else
        include("application/views/templates/".$file.".php");
    }
    
    public function getSelfLink(){
       return SITEURL . $this->getController() ."/";
    }
    
    public function loadHeader($file = false){
       if(!$file){ $file = $this->template; }
       $this->loadTemplate($file . "-header");
    }
    
    public function loadFooter($file = false){
       if(!$file){ $file = $this->template; }
       $this->loadTemplate($file. "-footer");
    }
   
    public static function loadClass($file,$subdir=false){
        $dir = ($subdir) ? $subdir . "/" : "";
        $path = "application/models/classes/". $dir . $file.".php";
        if(file_exists(($path))){
        require_once($path); }
        else{ die("$file Class in $path does not exist"); }
    }
 
    
    public static function getClassInstance($file, $params=false, $subdir=false){
         $class = ($subdir) ? ucfirst($subdir).$file : $file;
       if(!isset(self::$instances[$class])){
             self::loadClass($file, $subdir);
             if(class_exists($class."Core")){ $class .= "Core"; }
             self::$instances[$class] = new $class($params);
       }else{
         if(!(self::$instances[$class] instanceof $class) ) {
             self::loadClass($file, $subdir);
             if(class_exists($class."Core")){ $class .= "Core"; }
             self::$instances[$class] = new $class($params);
         }
       }
       return self::$instances[$class]; 
    }
    
    public static function getClass($file, $params = false, $subdir=false){
       return self::getClassInstance($file, $params,$subdir);
    }
    public static function getYodlee($file, $params = false){
         return self::getClass($file, $params, "yodlee");
    }
    public static function loadYodlee($file){
         return self::loadClass($file, "yodlee");
    }
   
   public function route(){
       if($this->getController() ."/"==ADMINDIR){
           $this->_index = 5; $this->dir_prefix = ADMINDIR; $this->__construct();
        }
       $this->autoLoadClass($this->getController());
      if(class_exists($this->getController())){
          
      $rc = new ReflectionClass($this->getController());
      if($rc->implementsInterface('IController')) {
      if($rc->hasMethod($this->getAction())){
         $controller = $rc->newInstance();
         $method = $rc->getMethod($this->getAction());
         $method->invoke($controller);
      } else {
      $this->pageNotFound($this->getAction());
      }
      } else {
      throw new Exception("Interface");
      }
      } else {
      //throw new Exception("Controller");
      $this->pageNotFound($this->getController());
      //echo "Controller not exist";
      }
   }
}
?>