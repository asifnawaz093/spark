<?php
class Meta{
    public $page;
    public $mov_name = false;
    public $keyword1 = "Bylyngo";
    public $keyword2 = "Bylyngo";
    public $keyword3 = "Bylyngo";
    public $meta_keyword1 = "Bylyngo ";
    public $meta_keyword2 = "Bylyngo ";
    public $meta_keyword3 = "Bylyngo ";
    public $meta_keyword4 = "Bylyngo ";
    public $meta_keyword5 = "Bylyngo ";
    
    public $og_image    = "";
    public $og_url      = "";
    public $description = "Bylyngo Interpreting and Translation";
    public $title       = SITE_PAGE_TILE;
    public $keywords    = "Bylyngo Interpreting and Translation";
    
    public function getKeywordsArray(){
        return array(strtolower(trim($this->keyword1)), strtolower(trim($this->keyword2)), strtolower(trim($this->keyword3)),
                     strtolower(trim($this->meta_keyword1)), strtolower(trim($this->meta_keyword2)),
                     strtolower(trim($this->meta_keyword3)), strtolower(trim($this->meta_keyword4)),
                       strtolower(trim($this->meta_keyword5)));
    }
    
    public function getMovName(){ return "";}
  
    public function getMetaTitle(){
        if(!$this->getMovName()){ return false; }
        return $this->meta_keyword1.$this->getMovName();
        
    }
    
    public function getMetaOgTitle(){
        if(!$this->getMovName()) return false;
        return $this->meta_keyword3.$this->getMovName();
    }
    
    public function getMetaKeywords(){
        if(!$this->getMovName()) return false;
        return $this->meta_keyword3.$this->getMovName()."," . $this->meta_keyword2.$this->getMovName()."," .
                $this->meta_keyword3.$this->getMovName()."," . $this->meta_keyword4.$this->getMovName()."," .
                $this->meta_keyword5.$this->getMovName();
    }
    
    
    public function getMetaDescription(){        $description = "";
        return $this->meta_keyword3 . $this->getMovName().". " . $description;
    }
    
    public function getMetaImage(){
        //return Db::getValue("SELECT `mov_image` FROM `movies` WHERE `mov_slug` = '".$this->page."'");
    }
    
    public function getMeta(){
        return '<title>'.$this->getMetaTitle().'</title>
        <meta name="DESCRIPTION" content="'.$this->getMetaDescription().'">
        <meta name="KEYWORDS" content="'.$this->getMetaKeywords().'">';       
    }
    
    public function getPostTags(){
        FC::loadClass("ObjectAgile");
        return "<h1><a href='".ObjectAgile::makeSlug(trim($this->meta_keyword1))."-".$this->page."' itemprop='keywords'>".$this->meta_keyword1.$this->getMovName()."</a></h1>, " .
                "<h2><a href='".ObjectAgile::makeSlug(trim($this->meta_keyword2))."-".$this->page."' itemprop='keywords'>".$this->meta_keyword2.$this->getMovName()."</a></h2>, " .
                "<h3><a href='".ObjectAgile::makeSlug(trim($this->meta_keyword3))."-".$this->page."' itemprop='keywords'>".$this->meta_keyword3.$this->getMovName()."</a></h3>, " .
                "<h1><a href='".ObjectAgile::makeSlug(trim($this->meta_keyword4))."-".$this->page."' itemprop='keywords'>".$this->meta_keyword4.$this->getMovName()."</a></h1>, " .
                "<h3><a href='".ObjectAgile::makeSlug(trim($this->meta_keyword5))."-".$this->page."' itemprop='keywords'>".$this->meta_keyword5.$this->getMovName()."</a></h3>";
    }
    
    public function getTags(){
        return "<h1>".$this->keyword1."</h1>," . "<h2>".$this->keyword2."</h2>, " .
                "<h3>".$this->keyword3."</h3> ";
    }

}
