<?php
class ObjectHtml{
    public static function call_url($url,$anchor,$extra="",$trimming=false){
        if($trimming){ $url = strtolower(trim($url)); $url = preg_replace("/ /","-",$url);}
        $href = ($url=="false") ? "javascript:return false;" : SITEURL.$url;
        return "<a href='".$href."' $extra >$anchor</a>";
    }
    
    public function pageTitle($title){ echo "<div id='page-title'>$title</div>"; }
    
    public function subTitle($title){ echo "<div class='sub-title'>$title</div>"; }
    public function rSubTitle($title){ return "<div class='sub-title'>$title</div>"; }
    
    public function sideTitle($title){ echo "<div class='sidebar-title'>$title</div>"; }
    
    public function inPageTitle($title,$width="60%"){ echo "<div style='width:$width' id='bar'>$title</div>"; }
    
    public static function error($msg){
        return '<div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>'.$msg.'
        </div>';
    }
    
    public static function success($msg){
        return '<div class="alert alert-success" role="alert">
            <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            <span class="sr-only">Ok:</span>'.$msg.'
        </div>';
    }
    public static function warning($msg){
        return '<div class="alert alert-warning" role="alert">
            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
            <span class="sr-only">Warning:</span>'.$msg.'
        </div>';
    }
    public static function info($msg){
        return '<div class="alert alert-info" role="alert">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
            <span class="sr-only">Ok:</span>'.$msg.'
        </div>';
    }
    public static function pageHead($title, &$view){ ?>
        <div class="row pagehead">
            <div class="col-md-2 col-sm-2 col-xs-2">
                <img class="prg-logo" alt="<?php echo $view->school_name; ?>" src="<?php echo SITEURL.'images/'.$view->logo; ?>">
            </div>
            <div class="col-md-7 col-sm-7 col-xs-6 text-center">
                <h4><?php echo $view->school_name; ?></h4>
                <?php echo (isset($view->labels['Branch'])&&$view->labels['Branch'] ? '<h5>'.$view->labels['Branch'].'</h5>' : ''); ?>
                <?php echo (isset($view->labels['Class'])&&$view->labels['Class'] ? '<h5>Class: '.$view->labels['Class'] . (isset($view->labels['Section']) && $view->labels['Section'] ? " - ". $view->labels['Section'] : "") .'</h5>' : ''); ?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-4 text-right">
                <h5><?php echo $title; ?></h5>
                <?php echo (isset($view->labels['Term'])&&$view->labels['Term'] ? '<h5>'.$view->labels['Term'] . (isset($view->labels['Exam']) && $view->labels['Exam'] ? " - ". $view->labels['Exam'] : "") .'</h5>' : ''); ?>
                <h5><?php echo $view->labels['Session']; ?></h5>
            </div>
        </div>
    <?php }
    public static function page($title, $menu=array(), $alerts=false, $filters=false){ ?>
        <div class="page-header cl-title noprint row">
            <div class="col-md-6 title"><h2><?php echo $title; ?></h2></div>
            <div class="col-md-6 menus">
                <?php
                $menuParams = $menu;
                if(isset($menuParams['controller'])){ $controller = $menuParams['controller'];$menu = 1; }
                if(isset($menuParams['index'])){ $index = $menuParams['index']; }
                if(is_array($menu)){ echo implode("", $menu); }
                ?>
            </div>
        </div>
        <?php if($filters){ ?>
            <div class="row page_filters gap">
                <div class="col-md-12">
                    <?php echo FC::getInstance()->view->filter; ?>
                </div>
            </div>
        <?php }
         if($alerts){ ?>
            <div class="row paddingbottom gap">
                <div class="col-md-12">
                    <?php FC::getInstance()->loadTemplate("alerts"); ?>
                </div>
            </div>
        <?php }
    }
    public static function queryToTable($query){
        $rows = FC::getClass("Db")->getRows($query);
        $table = "<table class='table table-bordered'>";
        foreach($rows as $row){
            $table .= "<tr><td>".print_r($row, 1)."</td></tr>";
        }
        $table .= "</table>";
        return $table;
    }
    public static function studentNavigation($std, $print=false){
        $id_student = (isset($std['id']) ? $std['id'] : $std); ?>
        <div class="hidden-print">
    <div class="lead quicknav">
         <a href="<?php echo SITEURL.'studentsummary/?action=single&student='.$id_student; ?>" title="View Student Info">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View Student Info"></span>
        </a>
        <a href="<?php echo SITEURL.'studentsummary/?action=viewSlip&id_student='.$id_student; ?>" title="View Admission Slip">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View Admission Slip"></span>
        </a>
        <a href="<?php echo SITEURL.'students/?action=edit&id_update='.$id_student; ?>" title="Edit Info">
            <span class="glyphicon glyphicon-edit" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="Edit Info"></span>
        </a>
        <a href="<?php echo SITEURL.'rstsummary/?action=stdTermResults&student='.$id_student; ?>" title="View Progress Report">
            <span class="glyphicon glyphicon-education" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View Progress Report"></span>
        </a>
        <a href="<?php echo SITEURL.'vouchers/?action=viewVoucher&id_student='.$id_student; ?>" title="View Fee Vouchers">
            <span class="glyphicon glyphicon-usd" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View Fee Vouchers"></span>
        </a>
        <a href="<?php echo SITEURL.'rstsummary/?action=stdCompleteResult&student='.$id_student; ?>" title="View All Results">
            <span class="glyphicon glyphicon-file" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View All Results"></span>
        </a>
        <a href="<?php echo SITEURL.'rstsummary/?action=stdTestReport&student='.$id_student; ?>" title="View Test Results">
            <span class="glyphicon glyphicon-file" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View Test Results"></span>
        </a>
        <a href="<?php echo SITEURL.'attendanceinfo/?action=viewSingleStdAttendance&student='.$id_student; ?>" title="View All Attendance">
            <span class="glyphicon glyphicon-record" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="View All Attendance"></span>
        </a>
        <a href="<?php echo SITEURL.'students/?action=changePassword&std_id='.$id_student; ?>" title="Change Parent Passowrd">
            <span class="glyphicon glyphicon-lock" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="Change Parent Passowrd"></span>
        </a>
       <?php if($print){ ?> <a href="javascript:void(0)" onclick="window.print();">
                <span aria-hidden="true" class="glyphicon glyphicon-print" aria-hidden="true" data-toggle='tooltip' data-placement='top' title="Print"></span>
            </a><?php } ?>
    </div>
</div>
    <?php }
    public static function printButton(){ ?>
        <button type="button" class="btn btn-primary pull-right" onclick="window.print();">
                <span aria-hidden="true" class="glyphicon glyphicon-print"></span> Print
            </button>
    <?php }
}
?>