<?php
class CField {
    public $db;
    public $table;
    public function __construct(){
        $this->db = FC::getClassInstance("Db");
        $this->table = "custom_fields";
        $this->table2 = "fields_data";
    }
    
    public function addData($label, $type, $slug, $related){
        $data = array(
            'label' => $label,
            'field_type' => $type,
            'slug' => $slug,
            'related' => $related
            );
        $id = $this->db->insert(array($this->table => $data));
        return $id;
    }
    
    public function updateData($data, $where){
        return $this->db->update(array($this->table => $data), $where );        
    }
    
    public function getSingle($id){
        return $this->db->getRow("SELECT * FROM `".$this->table."` WHERE `id`='".$id."'");
    }
    
    public function getAllData(){
        return $this->db->getRows("SELECT * FROM `".$this->table."` ORDER BY `label`");
    }

    public function fielsArray()
    {
        $fields = $this->getAllData();
        $fldArray = [];
        if($fields){
            foreach ($fields as $fld) {
                $fldArray[$fld['id']] = $fld;
            }
        }
        return $fldArray;
    }

    public function byType($type=false)
    {
        $where = $type ? "WHERE `related`='$type'" : "";
        return $this->db->getRows("SELECT * FROM `".$this->table."` $where");
    }

    public function delete($id)
    {
        return $this->db->execute("DELETE FROM `custom_fields` WHERE `id`='$id' LIMIT 1 ");
    }

    public function addFieldData($data)
    {
        return $this->db->insert(array($this->table2 => $data));
    }

    public function getByUser($id)
    {
        return $this->db->getRows("SELECT * FROM `".$this->table2."` WHERE `id_user`='$id' ");
    }
   public function updateFieldData($value, $where){
        $data = array(
            "value"    => $value
            );
        return $this->db->update(array($this->table2 => $data), $where );        
    }

}