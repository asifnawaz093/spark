<?php
class Ob{
    public static function printDate($date=false){
        $time = ($date ? strtotime($date) : time());
        return "<span class='print_date' data-toggle='tooltip' title='".date("F d, Y", $time)."'>".date("d-m-Y",$time)."</span>";
    }
    public static function dbDate($date=false){
        $time = ($date ? strtotime($date) : time());
        return date("Y-m-d", $time);
    }
}