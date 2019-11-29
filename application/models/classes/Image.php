<?php
class Image{
    public $ThumbSquareSize 		= IMGTHUMBSIZE;                  //Thumbnail will be 200x200
    public $BigImageMaxSize 		= IMGSIZE;                  //Image Maximum height or width 500x500
    public $ThumbPrefix			= IMGTHUMBPREFIX;             //Normal thumb Prefix
    public $DestinationDirectory	= PHOTODIR;             //Upload Directory
    public $Quality 			= IMGQUALITY;
    public $imgPrefix                   = IMGPREFIX;
    public $imgArray;
    public $imgName;
    public $imgType;
    public $imgTmp; 
    public $CreatedImage;
    
public function __construct(){
    $this->imgName = FC::getClassInstance("ObjectPhp")->removeSpecialChars($this->imgName);
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


public function resizeImage()
{
    $imgDest = $this->DestinationDirectory . $this->imgPrefix . $this->imgName;
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
        $imgDest = $this->DestinationDirectory .$this->ThumbPrefix .  $this->imgPrefix . $this->imgName;
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

public function processImgUpload(){
    if($this->isImageValid()){
        if($this->resizeImage()){
            if($this->cropImage()){
                return true;
            } else{return false; }
        } else{return false; }
    }
    else{ return false; }
}

    
}


?>