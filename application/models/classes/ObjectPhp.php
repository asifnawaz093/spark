<?php
class ObjectPhp {
    public $db;
    public function __construct(){
        $this->db = FC::getClass("Db");
    }
    public function redirect($page){
        header("Location:".$page);
    }
    public function removeSpecialChars($value){
        return  preg_replace ('/[^\p{L}\p{N} -]/u', '', $value);
    }
    public function removeChars($value){
        return  preg_replace('/[^\p{L}\p{N}]/u', '', $value);
    }
    public function getSlug($title){
        $title =  strtolower( preg_replace ('/[^\p{L}\p{N} -]/u', '', $title) );
        return preg_replace('/\s+/', '-', $title);
    }
    public function trimImageName($name){
        $name = strtolower($name);
        $split = explode(".",$name);
        $ext = end($split);
        $nameWOExt = str_replace($ext,"",$name);
        $name = $this->getSlug($nameWOExt);
        return $name . "." . $ext;
    }
    public  function Dot2LongIP ($IPaddr) 
    {
        if ($IPaddr == ""){ return 0; }
        else { $ip = explode(".", $IPaddr);
        if(isset($ip[3]))
            return ($ip[3] + $ip[2] * 256 + $ip[1] * 256 * 256 + $ip[0] * 256 * 256 * 256);
        }
    }
    public function getVisitorCountry(){
        global $data;
        $ip = $_SERVER['REMOTE_ADDR'];
        if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $dot_ip = FC::getClass("ObjectPhp")->Dot2LongIP($ip);
        $country_code = $this->db->getValue("SELECT `country_code` FROM `ip2location_db1` WHERE '$dot_ip' BETWEEN `ip_from` AND `ip_to` order by ip_to limit 1",false);
        return (isset($data['pricing'][$country_code])) ? $country_code : false;
    }
    public function getUniqueArray($array, $key){
        $existings = array();
        foreach($array as $int => $arr){
                if(in_array($arr[$key], $existings)){
                    unset($array[$int]);
                }
                else
                    $existings[] = $arr[$key];
        }
        return $array;
    }
    public function getSubdomain($subdomain=false){
        $url=$_SERVER["SERVER_NAME"];
        $explode = explode(".",$url);
        if($subdomain){
            $id_user = FC::getClass("Users")->isSubdomainExist($subdomain);
            $url_f = PROTOCOL ."://". $subdomain . "." . URLSIMPLE . "/";
            if(Tools::isSubmit("rd")){ $url = $subdomain .".". Tools::getValue("rd");}
            $url_server = PROTOCOL ."://". $url . "/";
            return ($id_user) ? array("id_user"=>$id_user, "subdomain"=>$subdomain, "url"=>$url_f, "url_server"=>$url_server) : false;
        }
        elseif(count($explode) > 1){
            for($i=0; $i<count($explode);$i++){
                if($explode[$i] != "www" && $explode[$i] != SITEHOST && $explode[$i] != "" && $explode[$i] != TLD){
                    $subdomain = $explode[$i];
                    $id_user = FC::getClass("Users")->isSubdomainExist($subdomain);
                    $url_f = PROTOCOL ."://". $subdomain . "." . URLSIMPLE . "/";
                    if(Tools::isSubmit("rd")){ $url = $subdomain .".". Tools::getValue("rd");}
                    $url_server = PROTOCOL ."://". $url . "/";
                    return ($id_user) ? array("id_user"=>$id_user, "subdomain"=>$subdomain, "url"=>$url_f, "url_server"=>$url_server) : false;
                }
            }
        }
        return false;
    }
    public function numberAbr($number) {
        $abbrevs = array(12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => "");
        foreach($abbrevs as $exponent => $abbrev) {
            if($number >= pow(10, $exponent)) {
                $res = $number / pow(10, $exponent);
                $result =  $res . $abbrev;
                if(is_float($res)) return number_format($res, 1) . $abbrev;
                else return $result;
            }
        }
    }
    public function logError($error){
        FC::getClass("Db")->insert(array("error_logs"=>array("error" => $error)));
    }
    public function curl($url, $array){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array) );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }
}

?>