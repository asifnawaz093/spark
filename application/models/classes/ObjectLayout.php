<?php
class ObjectLayout{
    public function greenDiv($text){ return '<div id="green" style="margin: 15px;padding: 15px 10px; border: 1px solid #22831d; background: #d1fad9; border-radius:10px; width: 400px;">&nbsp;'.$text.'</div>'; }
    public function redDiv($text){ return '<div id="green" style="margin: 15px;padding: 15px 10px; border: 1px solid #d81712; background: #f9ccd9; border-radius:10px; width: 400px;">&nbsp;'.$text .'</div>'; }
    public function green($text){ return "<div id='green' style='margin:10px 0px; color:green; font-weight:bold'>$text</div>"; }
    public function red($text){ return "<div id='green' style='margin:10px 0px; color:red; font-weight:bold'>$text</div>"; }
    
    public function popUpDivTop($id, $width, $title, $label){ ?>
        <table id="popTable" width="<?php echo $width; ?>" border=0 cellpadding=0 cellspacing=0><tr><td><div id="<?php echo $id; ?>"
        style="display:none; min-height: 200px;  position: absolute; top: 20%; left: 50%; z-index:5000; background: #ffffff; border: 1px solid #a9a9a9; /*-moz-border-radius: 10px; -webkit-border-radius:10px;*/
        /*-webkit-box-shadow: 3px 3px 5px 5px #a9a9a9;  -moz-box-shadow: 3px 3px 5px 5px #a9a9a9;*/ opacity: 1; padding-bottom:20px;">
        <div id="head" style="height:20px; background: rgb(234,234,234); background: rgba(234, 234, 234, 0.7); padding:10px 20px 10px 20px;">
        <div class="" style=" height:18px"><table border=0 cellpadding=0 cellspacing=0><tr><td width="<?php echo $width-10; ?>"><h6><?php echo $title; ?></h6></td><td align="right">
        <div align="right" style="float:right;"><a href="javascript:popupClear('<?php echo $id; ?>')"
         id="stay"><img src='<?php echo SITEURL; ?>images/close.jpg' height='15px' width='15px'></a></div></td></tr></table></div>
        <hr></div><?php if($label!=""){ ?><div style="padding:3px 10px 10px 20px; "><h6><?php echo $label; ?></h6></div> <?php } ?>
        <div id="cont" style="padding:3px 10px 10px 20px;">
        <script type="text/javascript">
        $('div#<?php echo $id; ?>').css("width","<?php echo $width; ?>");
        getDoc('<?php echo $id; ?>').style.marginLeft="<?php echo '-'.($width/2).'px';?>";
        var height=getDoc('<?php echo $id; ?>').offsetHeight; var scHeight=window.screen.height;
        getDoc('<?php echo $id; ?>').style.top=Number(scHeight)/4+'px';
        </script><?php }
        
public function popUpDivBottom(){?></div></div></td></tr></table><?php }

public function uploadFile($id=''){ ?> 
    <form action="uploadimage.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
<p id="f1_upload_process" style="display:none">Loading...<br/><img src="images/loader.jpg" /><br/></p>
<div id="f1_upload_form_content"></div><p id="f1_upload_form" align="center"><br/>
<label>File: <input name="upfile" type="file" size="30" /></label><input type="hidden" name="material" value="<?php echo $id; ?>"><br><br><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /></label></p>
<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
 </form><?php
}


}
?>