<?php
define('MEMCACHED_HOST', '127.0.0.1');
define('MEMCACHED_PORT', '11211');
class MemcacheCore{
    public $memcache;
    public function __construct(){
        $this->memcache = new Memcache();
       // $this->memcache->addServer(MEMCACHED_HOST);
        $this->memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT);
    }
    function set($key, $data){
        $this->memcache->set($key, $data, 0, 60*60);
    }
    function get($key){
        return $this->memcache->get($key);
    }
    function delete($key){
        $this->memcache->delete($key);
    }
    function getCached($query){
        $key = md5($query);
        return $this->get($key);
    }
    function setCached($query, $data){
        $key = md5($query);
        if($this->get($key)){
            $this->delete($key);
        }
        $this->set($key, $data);
    }
    public function cacheAllowed(){
        if(!FC::getInstance()->cache){ return false; }
        return (FC::getClass("Session")->isLoggedIn()) ? false : true;
        //return true;
    }
}
?>