<?php
FC::loadClass("FormTable");
class Table extends FormTable{
    private $tds = "";
    //$tabAttr = array(arrar(),array(),..,array())
    public function createTable($border,$cellpadding,$cellspacing,$tabAttrs,$tabId='',$extra='',$isEvOdd=false){
        FormTable::table($border,$cellpadding,$cellspacing,$tabId,$extra);
        foreach($tabAttrs as $tdValues){
            $this->tds="";
            foreach($tdValues as $value) { $this->tds .=  FormTable::singleTd($value); }
            
            if($isEvOdd){ FormTable::trEvOd($this->tds); }
            else{ FormTable::singleTr($this->tds); }
        }
        FormTable::endForm(true,false);
    }
    
    //string of attributes
    public function sTable($attr){ return "<table $attr>"; }
    
    //array of tds
    //if !array then put the complete td wrappers:- <td>Data</td>
    public function Row($rows,$rowAttr=""){ $row = "<tr $rowAttr>";
        if(is_array($rows))
            foreach($rows as $r){ $row.= "<td>" . $r . "</td>"; }
        else
            $row .= $rows;
        $row .="</tr>";
        return $row;
    }
    
    public function Column($colData,$colsAttr=""){ return "<td $colsAttr>".$colData."</td>"; }
    
    //closing the table
    public function cTable(){ return "</table>"; }
    
}
?>