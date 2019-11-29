<?php
    
class Db{
    
    public $formValues;
    public $link;
    public $result;
    public $insertId;
    public $error = false;
    
    public function __construct(){
        if(!$this->link)
            $this->link = mysqli_connect(HOSTNAME, DBUSER, DBPASS, DBNAME);
			if(SQL_PROFILING){
				$this->link->query("SET profiling = 1;");
			}
		date_default_timezone_set("Asia/Karachi");
		$this->link->query("SET time_zone = '+5:00';");
        return $this->link;
    }
    
    public function __destruct()
    {
	//if($this->getError())
	   // echo $this->getError();
        if ($this->link)
               $this->disconnect();
    }
    
    public function disconnect()
    {
	$this->link->close();
    }
    
    public function setFormAttr($formArray){ foreach($formArray as $key => $value){
        $$key = Tools::sanitizeInput($value);
        $this->formValues[] = $$key;
        }
        return $this->formValues;
    }
    
    public function insert($data){
        foreach($data as $table_name => $table){
        $i=0;
        foreach($table as $entity => $vals){
            $value[$i]=$vals; $field[$i]=$entity; $i++; }
            $values = "";  $fields="";
            for($j=0;$j<$i; $j++){ if($j!=$i-1){$values .= "'".$value[$j]."',";
            $fields.="`".check_form($field[$j])."`,";} else{$values .= "'".$value[$j]."'";
            $fields .= "`".check_form($field[$j])."`";}}
			// echo "insert into $table_name($fields) values($values)<br>"; return true;
            $this->result = $this->query("insert into $table_name($fields) values($values)");
            if(!$this->result){
                $this->error = mysqli_error($this->result);
            }
            $this->insertId = $this->link->insert_id;
            return $this->insertId ;
        }
    }
    
    public function getError(){
	//echo $this->link->query;
	return (empty($this->link->error)) ? false : $this->link->error;
    }
    
    public function displayError(){
        echo $this->error;
    }
    
    public function updateData($data, $where){ foreach($data as $table_name => $table){
        $i=0;
        $updates = "";
        foreach($table as $entity => $vals){
            $updates .= "`$entity` = '".$vals."', ";
        }
        $updates = preg_replace("/, $/","",$updates);
        $this->result = $this->query("UPDATE `$table_name` SET $updates $where") or die("error ");
        return $this->result;
        }
    }
    
    public function update($data, $where, $sanitize=false){ foreach($data as $table_name => $table){
        $i=0;
        $updates = "";
        foreach($table as $entity => $vals){
            $updates .= "`$entity` = '".(($sanitize) ? Tools::sanitizeInput($vals): $vals)."', ";
        }
        $updates = preg_replace("/, $/","",$updates);
		//echo "UPDATE `$table_name` SET $updates $where <br>"; return true;
        $this->result = $this->query("UPDATE `$table_name` SET $updates $where") or die("error ");
        return $this->result;
        }
    }
    function autoInsert($table,$rest = 'submit'){
		$values="'',";
		$i=0;
		foreach($_POST as $post => $val) {
			if($post !=$rest){
				$value[$i]=$val;
				$i++;
			}
		}
		for($j=0;$j<$i; $j++) {
			if($j!=$i-1){
				$values .= "'".$value[$j]."',";
			}
			else{
				$values .= "'".$value[$j]."'";
			}
		}
		$this->result = $this->query("insert into $table values($values)");
		if(!$this->result){
            $this->error = mysqli_error($this->result);
        }
        $this->insertId = $this->link->insert_id;
        return $this->insertId ;
	}
    public function autoUpdate($table, $var,$column,$match,$post = ""){ $insert='';
        if(empty($post)) $post = $_REQUEST;
        $i=0; foreach($post as $get => $val){ if(!in_array($get,$var)){ $value[$i]=$val; $field[$i]=$get; $i++;} }
        for($j=0;$j<$i; $j++){ if($j!=$i-1){$values = "'".Tools::sanitizeInput($value[$j])."'"; $fields="`".Tools::sanitizeInput($field[$j])."`";
        if($fields!=""&&$values!=""){$insert .= $fields."=".$values.",";} } else{$values = "'".Tools::sanitizeInput($value[$j])."'";
        $fields = "`".Tools::sanitizeInput($field[$j])."`"; if($fields!=""&&$values!=""){$insert .= $fields."=".$values;}}}
        // echo "update $table set $insert where `$column`='$match'";
        $this->result = $this->query("update $table set $insert where `$column`='$match'");
        return $this->result;
    }
    
    public function updatePost($table, $var,$column,$match){ $insert='';
        $i=0; foreach($_POST as $get => $val){ if(!in_array($get,$var)){ $value[$i]=$val; $field[$i]=$get; $i++;} }
        for($j=0;$j<$i; $j++){ if($j!=$i-1){$values = "'".Tools::sanitizeInput($value[$j])."'"; $fields="`".Tools::sanitizeInput($field[$j])."`";
        if($fields!=""&&$values!=""){$insert .= $fields."=".$values.",";} } else{$values = "'".Tools::sanitizeInput($value[$j])."'";
        $fields = "`".Tools::sanitizeInput($field[$j])."`"; if($fields!=""&&$values!=""){$insert .= $fields."=".$values;}}}
        // echo "update $table set $insert where `$column`='$match'";
        $this->result = $this->query("update $table set $insert where `$column`='$match'");
        return $this->result;
    }
    public function createQuery($data){ foreach($data as $table_name => $table){
        $i=0;
                foreach($table as $entity => $vals){
                       $value[$i]=$vals; $field[$i]=$entity; $i++; }
                    $values = "";  $fields="";
                    for($j=0;$j<$i; $j++){ if($j!=$i-1){$values .= "'".Tools::sanitizeInput($value[$j])."',";
                    $fields.="`".Tools::sanitizeInput($field[$j])."`,";} else{$values .= "'".Tools::sanitizeInput($value[$j])."'";
                    $fields .= "`".Tools::sanitizeInput($field[$j])."`";}}
                    return "insert into $table_name($fields) values($values)";
                }
        }
    
    public function numRows($result){ return $result->num_rows; }
    public function rowCount($q){ return $this->numRows( $this->query($q) ); }
    
    public function getValue($q){
	
            $this->result = $this->query($q);
            $row = ($this->result) ? $this->result->fetch_row() : false;
            return ($row) ? $row[0] : false;
    }
    
    public function getRow($q){
        $this->result = $this->query($q);
        return ($this->result) ? $this->result->fetch_assoc() : false;
    }
    public function dbResultArray($result){
	if(!$result)
	    return false;
        $resArray = array();
        for($count=0; $row = $result->fetch_assoc(); $count++){
            $resArray[$count] = $row;
        }
        return $resArray;
    }
    public function getRows($q){
	//	echo $q;
        $this->result = $this->query($q);
		//echo "<br>"; print_r($this->result);
        return ($this->result) ? $this->dbResultArray( $this->result ) : false;
    }
	public function getNameValue($q, $name='id', $value='name'){
		$rows = $this->getRows($q);
		$ret = array();
		if($rows){
			foreach($rows as $row){
				$ret[$row[$name]] = $row[$value];
			}
		}
		return $ret;
	}
    public function profiling($q){
       // echo " $q; <br>";
        //echo "<pre>";var_dump(debug_backtrace());echo "</pre>";
        $this->queries[] = $q;
    }
    public function query($q){ return $this->execute($q); }
    public function execute($q){ if(SQL_PROFILING){$this->profiling($q);}return $this->link->query($q); }
    public function executes($q){if(SQL_PROFILING){$this->profiling($q);} return $this->link->multi_query($q); }
    
    public function perfomTransaction($queryArrays,$displayError=false){
        $dbh = new PDO("mysql:host=".HOSTNAME.";dbname=".DBNAME, DBUSER, DBPASS);
        if(!$dbh){echo "<div id='red'>System has terminated all the processes, please contact our support center for further assistance.</div>";}
        try{$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->beginTransaction();
        foreach($queryArrays as $q){
            if($q!="")
        $dbh->exec($q);
        }
        $dbh->commit();
            return true;
        } catch(Exception $e)
        {
            $this->error = $e;
            $dbh->rollBack(); if($displayError) echo $e; exit(); return false;
        }
    }

    public function getColNames($table){
        $colArray = array();
        $columns = $this->getRows("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".DBNAME."' AND `TABLE_NAME`='".$table."' ");
        if (count($columns)>0){
            for ($i=0; $i< count($columns) ; $i++){
                $colArray[$columns[$i]['COLUMN_NAME']] = $columns[$i]['COLUMN_NAME'];
            }
        }
        return $colArray;
    }
	public function printProfiling(){ 
        if(SQL_PROFILING){
            echo implode(";<br>", $this->queries );
            unset($this->queries);
            $profile = $this->getRows("SHOW profiles;", false);
			//echo "<pre>";print_r($profile);echo "</pre>";
            echo "<table class='table table-bordered table-stipped table-condensed'>";
            foreach($profile as $p){
                echo "<tr><td>".$p['Query_ID']."</td><td>".$p['Query']."</td><td>".$p['Duration']."</td></tr>";
            }
            echo "</table>";
            $this->link->query("SET profiling = 0;");
        }
    }
}

?>
