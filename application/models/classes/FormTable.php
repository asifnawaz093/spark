<?php

class FormTable{
    public $border;
    public $cellpadding;
    public $cellspacing;
    public $enctype = "";
    public $title;
    public $message;
    public $type="text";
    public $name; //name and id will be same..
    public $size = false;
    public $function = "onclick=''"; // any JS function
    public $action = "";
    public $target = "";
    public $mxlength;
    public $trId;
    public $width1 = "auto";
    public $width2 = "auto";
    public $width3 = "auto";
    public $i = 0; //using to count the number of rows
    
    public function tableForm($border, $cellpadding, $cellspacing, $fid='',$tid='',$extra=''){?>
      <form action="<?php if($this->action!=""){ echo $this->action; } else{ echo $_SERVER['PHP_SELF']; } ?>" <?php echo $extra; ?> method="post" <?php if(!empty($this->enctype)){ echo 'enctype="'. $this->enctype.'"'; }
      if(!empty($fid)){ echo "id='$fid'"; } ?>>
      <table <?php if(!empty($tid)){ echo "id='$tid'"; } ?> <?php if($border>0){echo 'border="'.$border.'"';} ?> <?php if($cellpadding>0){ echo 'style="border-spacing:'.$cellpadding.'px;"'; } ?>>
      
      
    <?php }
    
    public function row($title, $name, $msg='',$value='',$extra='',$comment=''){ ?>
        <tr><td style='width:<?php echo $this->width1; ?>'><?php echo $title; ?></td>
        <td  style='width:<?php echo $this->width2; ?>'>
        <input type="<?php echo $this->type; ?>" value="<?php echo $value; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php echo $extra; ?>
         <?php if($this->size){ echo "size='".$this->size."'"; } echo $this->function; ?>>
        <?php echo $comment; ?><span class="error" style="display:none" id="<?php echo "error_$name";?>"><?php echo $msg; ?></span></td></tr>
    <?php }
    
    
        public function rowEvOd($title, $name, $msg='',$value=''){ if(($this->i)%(2)==0) $id=''; else $id='row1';?>
        <tr <?php if(!empty($id)){echo 'id="'.$id.'"'; } ?>><td style="width: <?php echo $this->width1; ?>"><?php echo $title; ?></td><td style="width: <?php echo $this->width2; ?>">
        <input type="<?php echo $this->type; ?>" value="<?php echo $value; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" size="<?php echo $this->size; ?>" <?php echo $this->function; ?>>
        </td><td class="error" style="display:''" id="<?php echo "error_$name";?>" width="<?php echo $this->width3; ?>"><?php echo $msg; ?></td>
    <?php  $this->i++; }
    
    public function formButtons($value, $name, $onclick,$type='submit',$reset=1,$extra=''){ ?>
        <tr <?php if(!empty($this->trId)){ echo 'id="'.$this->trId.'"';} ?>><td></td><td><input type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php echo $extra; ?> value="<?php echo $value; ?>" onclick="<?php echo $onclick; ?>">
<?php if($reset==1){ ?><input type="reset" name="reset" id="reset" value="Reset"><?php } ?>
</td></tr>
    <?php }
    
    public function table($border, $cellpadding, $cellspacing, $tid='', $extra=''){ ?>
    <table <?php if(!empty($tid)){ echo "id='$tid'"; } echo $extra; ?> <?php if($border>0){echo 'border="'.$border.'"';} ?> <?php if($cellpadding>0){ echo 'style="border-spacing:'.$cellpadding.'px;"'; } ?>>
    <?php }
    
    public function rows($value1, $value2,$extraTd2=""){ ?>
        <tr><td style="width: <?php echo $this->width1; ?>"><?php echo $value1; ?></td><td <?php echo $extraTd2; ?>><?php echo $value2; ?></td></tr>
    <?php }
    public function rowsEvOd($value1, $value2){ if(($this->i)%(2)==0) $id=''; else $id='row1';?>
        <tr <?php if(!empty($id)){echo 'id="'.$id.'"'; } ?> ><td style="width: <?php echo $this->width1; ?>"><?php echo $value1; ?></td><td style="width: <?php echo $this->width2; ?>"><?php echo $value2; ?></td></tr>
    <?php $this->i++; }
      public function rows1($value1, $value2){ ?>
        <tr id="row1"><td style="width: <?php echo $this->width1; ?>"><?php echo $value1; ?></td><td style="width: <?php echo $this->width2; ?>"><?php echo $value2; ?></td></tr>
    <?php }
    
     public function rowSelect($title, $name, $optArray, $msg=''){ ?>
        <tr <?php if(!empty($this->trId)){echo 'id="'.$this->trId.'"'; } ?> ><td style="width: <?php echo $this->width1; ?>"><?php echo $title; ?></td><td style="width: <?php echo $this->width2; ?>">
        <select  name="<?php echo $name; ?>" id="<?php echo $name; ?>" onclick="<?php $this->function; ?>"><?php
        foreach($optArray as $opt=>$key){  echo "<option value='$key'>$opt</option>"; } ?></select>
        <span class="error" style="display:none" id="<?php echo "error_$name";?>"><?php echo $msg; ?></span></td></tr>
    <?php }
    
    public function rowCountries($title, $name, $msg='',$extra='', $selected = ''){ ?>
        <tr <?php if(!empty($this->trId)){echo 'id="'.$this->trId.'"'; } ?> ><td style="width: <?php echo $this->width1; ?>"><?php echo $title; ?></td><td style="width: <?php echo $this->width2; ?>">
        <select onchange="if(this.value=='United States'){ showDiv('row_states'); } else{ PromptClear('row_states'); }" <?php echo $extra; ?>  name="<?php echo $name; ?>" id="<?php echo $name; ?>" onchange="<?php $this->function; ?>"><?php
        print_countries($selected); ?></select>
        <span class="error" style="display:none" id="<?php echo "error_$name";?>"><?php echo $msg; ?></span></td></tr>
    <?php }
    
     public function rowText($title, $name, $rows='13', $cols='40', $msg='',$value='',$extra=""){ ?>
        <tr><td style="width: <?php echo $this->width1; ?>"><?php echo $title; ?></td><td style="width: <?php echo $this->width2; ?>">
        <textarea <?php echo $extra; ?> name="<?php echo $name; ?>" id="<?php echo $name; ?>" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>"
        onclick="<?php $this->function; ?>"><?php echo $value; ?></textarea>
        </td><td class="error" style="display:''" id="<?php echo "error_$name";?>" width="<?php echo $this->width3; ?>"><?php echo $msg; ?></td></tr>
    <?php }
    
     public function rowCheck($title, $name,$value='', $msg='', $extra=''){?>
        <tr <?php if(!empty($this->trId)){echo 'id="'.$this->trId.'"'; } ?>><td style="width: <?php echo $this->width2; ?>"><?php echo $title; ?></td>
        <td style="width: <?php echo $this->width1; ?>"><input type="checkbox" <?php echo $extra; ?> value="<?php echo $value; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" size="<?php echo $this->size; ?>" <?php echo $this->function; ?>></td><td class="error" style="display:''" id="<?php echo "error_$name";?>" width="<?php echo $this->width3; ?>"><?php echo $msg; ?></td>
    <?php }
    
    public function rowRadio($title, $name,$value='', $msg='', $extra=''){?>
        <tr <?php if(!empty($this->trId)){echo 'id="'.$this->trId.'"'; } ?>><td style="width: <?php echo $this->width1; ?>"><input type="radio" <?php echo $extra; ?> value="<?php echo $value; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" size="<?php echo $this->size; ?>" <?php echo $this->function; ?>></td><td style="width: <?php echo $this->width2; ?>">
    <?php echo $title; ?></td><td class="error" style="display:''" id="<?php echo "error_$name";?>" width="<?php echo $this->width3; ?>"><?php echo $msg; ?></td>
    <?php }
    
     public function rowCheckArray($title, $name,$inputArray,$type="checkbox", $msg='', $extra=''){?>
        <tr <?php if(!empty($this->trId)){echo 'id="'.$this->trId.'"'; } ?>><td style="width: <?php echo $this->width1; ?>"><?php echo $title; ?></td>
        <td style="width: <?php echo $this->width2; ?>">
            <?php
            foreach($inputArray as $input => $value){
                echo "<input type='$type' name=$name value='$value'>$input &nbsp; ";
            }
            ?>
        <span class="error" style="display:''" id="<?php echo "error_$name";?>"><?php echo $msg; ?></span>
        </td></tr>
    <?php }
    
    public function singTdInput($name, $value, $extra=''){ ?><td style="width: <?php echo $this->width1; ?>"><input type="<?php echo $this->type; ?>" <?php echo $extra; ?> name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>"></td><?php }
    
     public function singleSelect($name, $optArray,$func=''){ ?>
        <td style="width: <?php echo $this->width1; ?>">
        <select  name="<?php echo $name; ?>" id="<?php echo $name; ?>" onchange="<?php echo $func; ?>"><?php
        foreach($optArray as $opt=>$key){  echo "<option value='$key'>$opt</option>"; } ?></select>
        </td> <?php }
    
    public function select($name, $optArray,$func=''){ ?>
        <select  name="<?php echo $name; ?>" id="<?php echo $name; ?>" onchange="<?php echo $func; ?>"><?php
        foreach($optArray as $opt=>$key){  echo "<option value='$key'>$opt</option>"; } ?></select><?php }
    
     public function singleTd($value){ return "<td>".$value."</td>"; }
     public function singleTr($v){ echo "<tr>$v</tr>"; }
     public function singleButton($name, $value, $func='',$extra=''){ ?><td><input type='button' value="<?php echo $value; ?>" name='<?php echo $name; ?>' <?php echo $extra; ?> id="<?php echo $name; ?>" onclick="<?php echo $func; ?>"></td><?php }
    public function tr($v,$id=''){ ?><tr <?php if(!empty($id)){echo 'id="'.$id.'"'; } ?>><?php echo $v; ?></tr><?php }
    public function td($v,$id=''){?><td width='<?php echo $this->width1; ?>' id='<?php echo $id; ?>'><?php echo $v; ?></td><?php }
    public function trEvOd($v){ if(($this->i)%(2)==0) $put='id="row_odd"'; else $put ='id="row_even" style="background:#fff;"';?><tr <?php echo $put; ?>><?php echo $v; ?></tr><?php  $this->i++;}
    //params: boolean
    public function endForm($table=true, $form=true){ if($table){ echo "</table>"; } if($form){ echo "</form>"; } }
}
    
?>