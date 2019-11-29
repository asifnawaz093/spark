<?php
class Upload{
    public $directory =false;
    public $fileName;
    public $fileType;
    public $fileTmp;
    public $ext;
    public $file;
    public $path;
    public $directoryExtension;
    public $allowed = false;
    public $error = false;
    public function __construct($file){
        $this->file = $file;
        if($this->file){
            $this->fileName = time() . $this->file['name'];
            $this->fileType = $this->file['type'];
            $this->fileTmp = $this->file['tmp_name'];
            $this->ext = explode(".",$this->fileName);
            $this->ext = end($this->ext);
        }else{
            $this->error = "No file attached."; return false;
        }
    }
    public function isValid(){
        if(!$this->allowed){
            $this->allowed = array('doc','docx','xls','xlsx','pdf','ppt','jpg','png','jpeg','gif');
        }
        if(in_array($this->ext, $this->allowed)) return true;
        else{
            $this->error = "Invalid file type " .$this->ext. ". Only " . implode(", ",$this->allowed) . " files are allowed";
            return false;
        }
    }
    public function prepare(){
        if(!$this->directory){
            $dir = "uploads/files/";
            $year	= date("Y", time());
            $month 	= date("m", time());
            $day 	= date("d", time());
            $hour 	= date("H", time());
            if(!file_exists($dir . "$year")){ mkdir($dir . "$year");}
            if(!file_exists($dir . "$year/$month")){ mkdir($dir . "$year/$month");}
            if(!file_exists($dir . "$year/$month/$day")){ mkdir($dir . "$year/$month/$day");}
            if(!file_exists($dir . "$year/$month/$day/$hour")){ mkdir($dir . "$year/$month/$day/$hour");}
            $this->directoryExtension = "$year/$month/$day/$hour/";
            $this->directory = $dir . $this->directoryExtension;
        } 
        $this->fileName = FC::getClass("ObjectPhp")->trimImageName($this->fileName);
        $this->path = $this->directory . $this->fileName;
    }
    public function upload(){
        if($this->isValid()){
            $this->prepare();
            if(move_uploaded_file($this->fileTmp, $this->path)){
                return $this->path;
            }else{
                $this->error = "Upload failed: Can't upload file.";
                return false;
            }
        }
        return false;
    }
}