<?php
class EasyImage{
    public $ThumbSquareSize 		= IMGTHUMBSIZE;                  //Thumbnail will be 200x200
    public $BigImageMaxSize 		= IMGSIZE;                  //Image Maximum height or width 500x500
    public $ThumbPrefix			= IMGTHUMBPREFIX;             //Normal thumb Prefix
    public $DestinationDirectory	= PHOTODIR;             //Upload Directory
    public $directoryExtension;
    public $Quality 			= IMGQUALITY;
    public $imgPrefix                   = IMGPREFIX;
    public $watermark			= false;
    public $imgArray;
    public $imgName;
    public $imgType;
    public $imgTmp; 
    public $CreatedImage;
    public $imgThumbFullName;
    public $imgThumbUrl;
    public $imgFullName;
    public $imgUrl;
    public $changeExtension = true;
    
public function __construct($imgArray=false){
    $year	= date("Y", time());
    $month 	= date("m", time());
    $day 	= date("d", time());
    $hour 	= date("H", time());
    if(!file_exists(PHOTODIR . "$year")){mkdir(PHOTODIR . "$year");}
    if(!file_exists(PHOTODIR . "$year/$month")){ mkdir(PHOTODIR . "$year/$month");}
    if(!file_exists(PHOTODIR . "$year/$month/$day")){ mkdir(PHOTODIR . "$year/$month/$day");}
    if(!file_exists(PHOTODIR . "$year/$month/$day/$hour")){ mkdir(PHOTODIR . "$year/$month/$day/$hour");}
    $this->directoryExtension = "$year/$month/$day/$hour/";
    $this->DestinationDirectory = PHOTODIR . $this->directoryExtension;
    if($imgArray) $this->imgArray = $imgArray;
    if($this->imgArray){
	$this->imgName = $this->imgArray['name'];
	$this->imgType = $this->imgArray['type'];
	$this->imgTmp = $this->imgArray['tmp_name'];
    }
    $this->imgName = FC::getClassInstance("ObjectPhp")->trimImageName($this->imgName);
}

public function isImageValid(){
    $return = true;
    	switch(strtolower($this->imgType))
	{
		case 'image/png':
			$this->CreatedImage =  imagecreatefrompng($this->imgTmp);
			break;
		case 'image/gif':
			$this->CreatedImage =  imagecreatefromgif($this->imgTmp);
			break;			
		case 'image/jpeg':
		case 'image/pjpeg':
			$this->CreatedImage = imagecreatefromjpeg($this->imgTmp);
			break;
		default:
			return false;
	}
        return true;
}

public function isImage($img = false){
    if(!$img) $img = $this->imgTmp;
    $size	= getimagesize($img);
    list($CurWidth,$CurHeight) = $size;
    if($CurWidth <= 0 || $CurHeight <= 0){return false;}
    $mime	= $size['mime'];
    if (substr($mime, 0, 6) != 'image/')
    {
	return false;
    }
    return true;
}
    
public function resizeImage()
{
    $this->imgFullName =  $this->imgPrefix . $this->imgName;
    $imgDest = $this->imgUrl = $this->DestinationDirectory . $this->imgFullName;
    list($CurWidth,$CurHeight)=getimagesize($this->imgTmp);
    
	if($CurWidth <= 0 || $CurHeight <= 0){ return false; }
	
	//Construct a proportional size of new image
	$ImageScale      	        = min($this->BigImageMaxSize/$CurWidth, $this->BigImageMaxSize/$CurHeight); 
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	
	// Resize Image
	if(imagecopyresampled($NewCanves, $this->CreatedImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{
		switch(strtolower($this->imgType))
		{
			case 'image/png':
				imagepng($NewCanves,$imgDest);
				break;
			case 'image/gif':
				imagegif($NewCanves,$imgDest);
				break;			
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$imgDest,$this->Quality);
				break;
			default:
				return false;
		}
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;
	}

}

public function cropImage($iSize = false)
{
	$this->imgThumbFullName = $this->ThumbPrefix .  $this->imgPrefix . $this->imgName;
        $imgDest = $this->imgThumbUrl = $this->DestinationDirectory . $this->imgThumbFullName;
        if($iSize){
	    $exp = explode(".", $this->imgName);
	    $extension = end($exp);
	    $basename = basename($this->imgName, ".$extension");
	    $thumbname = $basename . "-" . $iSize . ".$extension";
	    $imgDest = $this->DestinationDirectory .$this->ThumbPrefix .  $this->imgPrefix . $thumbname;
        }
	else{
	    $iSize = $this->ThumbSquareSize;
	}
        $SrcImage = $this->CreatedImage;
        list($CurWidth,$CurHeight)=getimagesize($this->imgTmp);
	if($CurWidth <= 0 || $CurHeight <= 0){ return false; }
        if($CurWidth>$CurHeight)
	{
		$y_offset = 0;
		$x_offset = ($CurWidth - $CurHeight) / 2;
		$square_size 	= $CurWidth - ($x_offset * 2);
	}else{
		$x_offset = 0;
		$y_offset = ($CurHeight - $CurWidth) / 2;
		$square_size = $CurHeight - ($y_offset * 2);
	}
	
	$NewCanves 	= imagecreatetruecolor($iSize, $iSize);	
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
	{
		switch(strtolower($this->imgType))
		{
			case 'image/png':
				imagepng($NewCanves,$imgDest);
				break;
			case 'image/gif':
				imagegif($NewCanves,$imgDest);
				break;			
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$imgDest,$this->Quality);
				break;
			default:
				return false;
		}
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);}  return true;
    
    }
}

public function uploadImage(){
    $this->imgFullName =  $this->imgPrefix . $this->imgName;
    if($this->changeExtension){ $this->imgFullName = $this->changeExtension($this->imgFullName); }
    $imgDest = $this->imgUrl = $this->DestinationDirectory . $this->imgFullName;
    list($width,$height)=getimagesize($this->imgTmp);
    return move_uploaded_file($this->imgTmp, $imgDest);
}

public function processImgUpload(){
    if($this->isImageValid()){
        if($this->resizeImage()){
            if($this->cropImage()){
                return array("imgUrl"=>$this->imgUrl, "imgName"=>$this->imgFullName, "imgThumbUrl"=>$this->imgThumbUrl, "imgThumbName"=>$this->imgThumbFullName, "directory"=>$this->DestinationDirectory);
            } else{return false; }
        } else{return false; }
    }
    else{ return false; }
}

public function processBase64Image($data)
{
    $image_parts = explode(";base64,", $data);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
	$imgDest = $this->DestinationDirectory .  $this->imgPrefix . time() . "." . $image_type;
	file_put_contents($imgDest, $data);
	return ["image_path"=>$imgDest];
}

public function extension($path){
    $exp = explode(".",$path);
    return end($exp);
}
public function changeExtension($path, $to = "jpg", $from="png"){
    $ext = $this->extension($path);
    if($ext == $from){
	$path = preg_replace("/($from)$/",$to, $path);
    }
    return $path;
}

public function process(){
    if($this->isImage()){
	if($this->uploadImage()){
	    return array("success"=>true, "imgUrl"=>$this->imgUrl, "imgName"=>$this->directoryExtension . $this->imgFullName,"imgPath"=>$this->directoryExtension);
	}
	else{
	    return array("success"=>false, "error"=>"Something went wrong while uploading your image. Please try again.");
	}
    }
    else{
	return array("success"=>false, "error"=>"Action Denied, Your file is not a valid image.");
    }
}

public function processBasic(){
    if($this->isImage()){
	if($this->uploadImage()){
	    return array("success"=>true, "imgUrl"=>$this->imgUrl, "imgName"=>$this->directoryExtension . $this->imgFullName,"imgPath"=>$this->directoryExtension);
	}
	else{
	    return array("success"=>false, "error"=>"Something went wrong while uploading your image. Please try again.");
	}
    }
    else{
	return array("success"=>false, "error"=>"Action Denied, Your file is not a valid image.");
    }
}

    
}


?>
