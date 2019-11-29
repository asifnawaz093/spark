<?php
class Url{
    public function currentUrl($full=true){
        $fc = FC::getInstance();
        $url = SITEURL . $fc->getController() . ($_SERVER['QUERY_STRING'] ? "/?". $_SERVER['QUERY_STRING'] : "");
        return $url;
    }
}