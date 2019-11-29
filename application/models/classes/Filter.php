<?php
class Filter {
    public $filters;
    public $search_operator = "OR";
    public $filter_operator = "AND";
    public $sort_operator   = "OR";
    public $only_pagination = false;
    public $seperate_pagination = false;
    public $pagination = "";
    public $count_select = false;
    public $action = false;
    public $seachLabel = "";
    public $daterangeLabel = "Date Range";
    public $daterange_string = "";
    public $dateLabel = "Date";
    public $date_string = "";
    public $filterLbl = "Filter By";
    public $select;
    public $where;
    public $groupby = "";
    public $filter_where;
    public $order;
    public $limit;
    public $search_string;
    public $sort_string;
    public $filter_string;
    public $filters_string;
    public $from = 0;
    public $num = 20;
    public $container = false;
    public $count = 0;
    public $selected_params;
    public $selectedValue;
    public $paginationLabels=false;
    public $params = false;
    public $start_date = false;
    public $end_date = false;
    public $numsArr = false;
    public $exclude_query = array();
    public $unique_column;
    public $labels;
    public $printable;
    private $urlHash;
    public $expanded = false;
    public $filtered=false;
    public function __construct(){
        $this->urlHash = md5( FC::getInstance()->getSelfLink() . Tools::getValue('action') );
        if(isset($_GET['ft_filter'])){
            $_SESSION['Filter']['search']       = $this->search_string        = Tools::getValue('search');
            $_SESSION['Filter']['daterange']    = $this->daterange_string     = Tools::getValue('daterange');
            $_SESSION['Filter']['date']         = $this->date                 = Tools::getValue('date');
            $_SESSION['Filter']['sort']         = $this->sort_string          = isset($_GET['sort']) ?   check_form($_GET['sort']) : false;
            $_SESSION['Filter']['filter']       = $this->filter_string        = isset($_GET['filter']) ? check_form($_GET['filter']) : false;
            $_SESSION['Filter']['filters']      = $this->filters_string       = isset($_GET['filters']) ? $_GET['filters'] : false;
            if(isset($_GET['rows'])){   $_SESSION['Filter']['rows']         = Tools::getValue("rows");}
            if(isset($_GET['p'])){      $_SESSION['Filter']['p']            = Tools::getValue("p"); }
            $_SESSION['Filter']['hash']         = $this->urlHash;
        }elseif(isset($_SESSION['Filter'])){
            if(isset($_GET['ft_reset']) && $_GET['ft_reset'] == 1){
                unset($_SESSION['Filter']);
            }else{
                if($this->urlHash == $_SESSION['Filter']['hash']){
                    if($_SESSION['Filter']['search']){       $_GET['search']         = $this->search_string    = $_SESSION['Filter']['search'];}
                    if($_SESSION['Filter']['daterange']){    $_GET['daterange']      = $this->daterange_string = $_SESSION['Filter']['daterange'];}
                    if($_SESSION['Filter']['date']){         $_GET['date']           = $this->date             = $_SESSION['Filter']['date'];}
                    if($_SESSION['Filter']['sort']){         $_GET['sort']           = $this->sort_string      = $_SESSION['Filter']['sort'];}
                    if($_SESSION['Filter']['filter']){       $_GET['filter']         = $this->filter_string    = $_SESSION['Filter']['filter'];}
                    if($_SESSION['Filter']['filters']){      $_GET['filters']        = $this->filters_string   = $_SESSION['Filter']['filters'];}
                    if(isset($_SESSION['Filter']['rows']) && $_SESSION['Filter']['rows']){     $_GET['rows']           = $_SESSION['Filter']['rows'];}
                    if(isset($_SESSION['Filter']['p']) && $_SESSION['Filter']['p']){           $_GET['p']              = $_SESSION['Filter']['p']; }
                    $_GET['ft_filter']      = "Go";
                }
            }
        }
        $this->filtered = isset($_GET['ft_filter']);
    }
    public function getFilterParams(){
        $filter_params = array();
        $this->num                  = isset($_GET['rows']) ? $_GET['rows'] : $this->num;
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $params = "";
            $columns = $this->filters['search'];
            if(is_array($columns)){
                foreach($columns as $column){
                    if(preg_match("/(^[0-9])/",$_GET['search'])){
                        if(empty($_GET['search']))
                            continue;
                        $params .= $column . " = '" . check_form($_GET['search']) . "' " . $this->search_operator. " ";
                    }
                    else{
                        if(empty($_GET['search']))
                            continue;
                        $params .= $column . " LIKE '%" . check_form($_GET['search']) . "%' " . $this->search_operator. " ";
                    }
                }
            }
            $params = preg_replace("/( " . $this->search_operator. " )$/","", $params);
            $filter_params['search'] = $params;
        }
        if((isset($_GET['daterange']) && !empty($_GET['daterange']))|| (isset($this->filters['daterange_default']) && $this->filters['daterange_default'])){
            $params = "";
            $column = $this->filters['daterange'];
            $fdate = (isset($_GET['daterange']) ? $_GET['daterange'] : (isset($this->filters['daterange_default'])?$this->filters['daterange_default']:""));
            $date = explode("-",urldecode($fdate));
            if(count($date) != 2){ FC::getInstance()->error[] = "Invalid date range $fdate. Format must be m/d/y-m/d/y or d/m/y-d/m/y or y/m/d-y/m/d"; }
            else{
                $this->start_date = $start_date = trim(date("Y-m-d 00:00:00",strtotime($date[0])));
                $this->end_date = $end_date = trim(date("Y-m-d 23:59:59",strtotime($date[1])));
                if($column){
                    $params .= $column . " BETWEEN '" . check_form($start_date) . "' AND '" . check_form($end_date) ."' " . $this->search_operator. " ";
                    $params = preg_replace("/( " . $this->search_operator. " )$/","", $params);
                }
                $filter_params['daterange'] = $params;
            }
        }
        if((isset($_GET['date']) && !empty($_GET['date']))|| (isset($this->filters['date_default']) && $this->filters['date_default'])){
            $params = "";
            $column = $this->filters['date'];
            $this->date = $date = (isset($_GET['date']) ? $_GET['date'] : (isset($this->filters['date_default'])?$this->filters['date_default']:""));
            if(!date("Y-m-d", strtotime($date))){ FC::getInstance()->error[] = "Invalid date $date. Format must be m/d/y or d/m/y or y/m/d"; }
            else{
                if($column){
                    $params .= $column . " = '" . check_form($date) ."' ". $this->search_operator. " ";
                    $params = preg_replace("/( " . $this->search_operator. " )$/","", $params);
                }
                $filter_params['date'] = $params;
            }
        }
        if(isset($_GET['filter']) && $_GET['filter'] !== ""){
            $params = "";
            $columns = $this->filters['filter'];
            if(is_array($columns)){
                foreach($columns as $column => $values){
                    if($_GET['filter']==="")
                            continue;
                    $params .= $column . " = '" . check_form($_GET['filter']) . "' " . $this->filter_operator. " ";
                }
            }
            $params = preg_replace("/( " . $this->filter_operator. " )$/","", $params);
            $filter_params['filter'] = $params;
        }
        if((isset($_GET['filters']) && $_GET['filters'] !== "") || (isset($this->filters['filters_default']) && $this->filters['filters_default']) || (isset($this->filters['field_name']) && Tools::isSubmit($this->filters['field_name'])) ){
            $params = "";
            $filters = $this->filters['filters'];
            $i=0;
            foreach($filters as $title => $columns){
                if(is_array($columns)){
                    foreach($columns as $column => $values){
                        if(in_array($column,$this->exclude_query)){ continue; }
                        if(!in_array($column,array("field_id","field_attributes", "field_name"))){ 
                            $fil =(isset($_GET['filters']) ? $_GET['filters'][$i] : ( isset($columns['field_name']) && Tools::getValue($columns['field_name'])?: (isset($this->filters['filters_default'][$title])?$this->filters['filters_default'][$title] : "") ) );
                            if($fil !=="" ){
                                $params .= $column . " = '" . check_form($fil) . "' " . $this->filter_operator. " ";
                            }
                            $i++;
                        }
                    }
                }
            }
            $params = preg_replace("/( " . $this->filter_operator. " )$/","", $params);
            $filter_params['filter'] = $params;
        }
        
        if((isset($_GET['sort']) && !empty($_GET['sort']))  || (isset($this->filters['sort_default']) && $this->filters['sort_default'])){
            $columns = $this->filters['sort'];
            $sort = (isset($_GET['sort']) ? check_form($_GET['sort']) : (isset($this->filters['sort_default'])?$this->filters['sort_default']:""));
            if(preg_match("/([a-z]):([a-z])/",$sort)){
                $sort_by = current(explode(":", $sort));
                $exp = explode(":", $sort);
                $sort_way = end($exp);
                $params = "ORDER BY $sort_by $sort_way";
                $filter_params['sort'] = $params;
            }
            else{
                $filter_params['sort'] = false;
            }
            
        }
        
        return $filter_params;
    }
    
    
    public function createFilter($printable=false){
        $this->expanded = ($this->expanded == "auto" ? ($this->filtered ? false : true) : $this->expanded);
        $spclasses = ($this->expanded ? "expanded":false);
        if(isset($_GET['rows'])){ $n = Tools::getValue('rows'); $this->num = ($n?$n:$this->num); }
        $html = "<div class='filter-container hidden-print'><form action='".FC::getInstance()->getSelfLink()."' method='get'>";
        if($this->action){
            $html .= "<input type='hidden' name='action' value='".$this->action."'>";
        }
        if($this->params){
            if(Tools::isAssociative($this->params)){
                foreach($this->params as $key=>$param){
                    $html .= "<input type='hidden' name='$key' value='$param'>";
                }
            } else {
                foreach($this->params as $param){
                    $html .= "<input type='hidden' name='$param' value='1'>";
                }
            }
        }
            foreach($this->filters as $filter => $filter_array){
                if(!$filter) continue;
                if($filter == "pagination"){
                    $additional_params = ( isset($_GET['search']) && !empty($_GET['search']) ) ? "&search=" . check_form($_GET['search']) : "";
                    $additional_params .= ( isset($_GET['filter']) && !empty($_GET['filter']) ) ? "&filter=" . check_form($_GET['filter']) : "";
                    $additional_params .= ( isset($_GET['sort']) && !empty($_GET['sort']) ) ? "&sort=" . check_form($_GET['sort']) : "";
                    $additional_params .= ( isset($_GET['daterange']) && !empty($_GET['daterange']) ) ? "&daterange=" . check_form($_GET['daterange']) : "";
                    $additional_params .= ( isset($_GET['date']) && !empty($_GET['date']) ) ? "&date=" . check_form($_GET['date']) : "";
                    $additional_params .= ( isset($_GET['rows']) && !empty($_GET['rows']) ) ? "&rows=" . check_form($_GET['rows']) : "";
                    if( isset($_GET['filters']) && !empty($_GET['filters']) ){
                        foreach($_GET['filters'] as $fil){
                            $additional_params .=  "&filters[]=" . check_form($fil);
                        }
                    }
                    $additional_params .= "&ft_filter=Go";
                    $pageLink = $this->filters['pagination']['pageLink'];
                    if( isset($_GET['p']) && !empty($_GET['p']) ){$this->from = (check_form($_GET['p']) - 1) * $this->num;} else{$this->from = 0;}
                    if($this->count_select){
                        $this->count = $rows = FC::getClassInstance("Db")->getValue("SELECT SUM(counts) FROM (". $this->count_select . " " . $this->getWhereString($this->exclude_query) .") tab");
                    }
                    else{
                        if(!$this->count){
                            if($this->unique_column){
                                $search = "/^(select)(.*)(from)/";
                                $replace = " ".$this->unique_column." ";
                                $count_query = preg_replace($search, $replace . " FROM ", strtolower($this->select));
                                $count_query = "SELECT " . $count_query  . $this->getWhereString($this->exclude_query) . " " .$this->groupby;
                                $this->count = $rows = count(FC::getClassInstance("Db")->getRows($count_query));
                            }else{
                                $this->count = $rows = count(FC::getClassInstance("Db")->getRows($this->select . " " . $this->getWhereString($this->exclude_query) . " " .$this->groupby));
                            }
                        }
                        else $rows = $this->count;
                    }
                    $pagination = "<span class='pages_nav hidden-print'><nav><ul class='pagination'>". $this->showPagination($rows, $pageLink, $this->num, $additional_params). "</ul></nav></span>";
                    if($this->seperate_pagination){
                        $this->pagination = $pagination;
                    }
                    else{
                        $html .= $pagination;
                    }
                }
                elseif($filter == "search"){
                    $html .= "<span class='search $spclasses'><label for='ft_search'>Search: </label><input style='/*width:300px;*/' class='form-control' value='".$this->search_string."' placeholder='".$this->seachLabel."' type='text' name='search' id='ft_search'></span>";
                    $this->labels["Search"]=$this->search_string;
                }
                elseif($filter == "rows"){
                    $numsArr = ($this->numsArr) ? $this->numsArr : array("Rows"=>"","10"=>"10", "20"=>"20", "50"=>"50", "100"=>"100", "200"=>"200", "500"=>"500", "1000"=>"1000","5000"=>"5000", "10000"=>"10000");
                    $html .= "<span class='search $spclasses'><select name='rows' class='form-control'>";
                        foreach($numsArr as $nname => $nvalue){
                            $html .= "<option ".($this->num == $nvalue? "selected":"")." value='".$nvalue."'>$nname</option>";
                        }
                    $html .= "</select></span>";
                }
                elseif($filter=="daterange"){
                    FC::getInstance()->js_files[] = "//cdn.jsdelivr.net/momentjs/latest/moment.min.js";
                    FC::getInstance()->js_files[] = "//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js";
                    FC::getInstance()->css_files[] = "//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css";
                    $date_string = (($this->daterange_string) ? $this->daterange_string : ((isset($this->filters['daterange_default']) && $this->filters['daterange_default']) ? $this->filters['daterange_default']: ""));
                    $html .= "<span class='spdaterange $spclasses'><input class='form-control daterange' autocomplete='off' data-value='".$date_string."' value='".$date_string."' placeholder='".$this->daterangeLabel."' type='text' name='daterange' id='ft_daterange'></span>";
                    $this->labels[$this->daterangeLabel]=$date_string;
                    $this->selected_params[$this->daterangeLabel] = $date_string;
                }
                elseif($filter=="date"){
                    FC::getInstance()->js_files[] = "//cdn.jsdelivr.net/momentjs/latest/moment.min.js";
                    FC::getInstance()->js_files[] = "//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js";
                    FC::getInstance()->css_files[] = "//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css";
                    $date_string = (($this->date) ? $this->date : ((isset($this->filters['date_default']) && $this->filters['date_default']) ? $this->filters['date_default']: ""));
                    $html .= "<span class='spdate $spclasses'><input class='form-control datepicker' autocomplete='off' data-value='".$date_string."' value='".$date_string."' placeholder='".$this->dateLabel."' type='text' name='date' id='ft_date'></span>";
                    $this->labels[$this->dateLabel]=$date_string;
                    $this->selected_params[$this->dateLabel] = $date_string;
                }
                elseif($filter == "filter"){
                    $html .= "<span class='filter $spclasses'><select class='form-control' name='filter' id='ft_filter'>
                        <option value=''>".$this->filterLbl."</option>";
                    foreach($filter_array as $column){
                        if(is_array($column)){
                            foreach($column as $value => $label){
                                if($value == $this->filter_string){
                                    $selected = "selected";
                                    $this->selected_params[$this->filterLbl] = $label;
                                    $this->selectedValue[$this->filterLbl] = $value;
                                } else{ $selected="";}
                                $html .= "<option $selected value='$value'>$label</option>";
                            }
                        }
                        else{
                            foreach($column as $value){
                                if($column == $this->filter_string){
                                    $selected = "selected";
                                    $this->selected_params[$this->filterLbl] = $value;
                                    $this->selectedValue[$this->filterLbl] = $value;
                                } else{ $selected="";}
                                $html .= "<option $selected value='$column'>$value</option>";
                            }
                        }
                    }
                    $html .= "</select></span>";
                    $this->labels[$this->filterLbl]=$this->selected_params[$this->filterLbl];
                }
                elseif($filter == "filters"){
                    $j=0;
                    foreach($filter_array as $filterLabel => $filters){ 
                        $html .= "<span class='filter $spclasses'><select ".(isset($this->filters['filters_required']) && in_array($filterLabel,$this->filters['filters_required']) ? "required":"")." class='form-control' ".(isset($filters['field_attributes']) ? $filters['field_attributes'] : "")." name='".(isset($filters['field_name']) ? $filters['field_name'] : 'filters[]')."' id='".(isset($filters['field_id']) ? $filters['field_id'] : 'ft_filter$j')."'>
                            <option value=''>".$filterLabel."</option>";
                        foreach($filters as $column_name => $column){
                            if(!in_array($column_name,array("field_id","field_attributes", "field_name"))){ 
                                if(is_array($column)){
                                    foreach($column as $value => $label){
                                        $fil = (isset($this->filters_string[$j]) ? ((isset($this->filters_string[$j]) && $this->filters_string[$j] !== "" && $value == $this->filters_string[$j]) ? 1 : 0) : ((isset($this->filters['filters_default'][$filterLabel]) && $this->filters['filters_default'][$filterLabel] == $value) ? 1 : (isset($filters['field_name']) && Tools::isSubmit($filters['field_name']) && Tools::getValue($filters['field_name']) == $value ? 1:0)));
                                        if($fil){
                                            $selected = "selected";
                                            $this->selected_params[$filterLabel] = $label;
                                            $this->selectedValue[$filterLabel] = $value;
                                        } else{ $selected="";}
                                        $html .= "<option $selected value='$value'>$label</option>";
                                    }
                                }
                                else{
                                    foreach($column as $value){
                                        $fil = (isset($this->filters_string[$j]) ? ((isset($this->filters_string[$j]) && $this->filters_string[$j] !== "" && $column == $this->filters_string[$j]) ? 1 : 0) : ((isset($this->filters['filters_default'][$filterLabel]) && $this->filters['filters_default'][$filterLabel] == $column) ? 1 : 0));
                                        if($fil){
                                            $selected = "selected";
                                            $this->selected_params[$filterLabel] = $value;
                                            $this->selectedValue[$filterLabel] = $value;
                                        } else{ $selected="";}
                                        $html .= "<option $selected value='$column'>$value</option>";
                                    }
                                }
                            }
                        }
                        $html .= "</select></span>";
                        if(isset($this->selected_params[$filterLabel])){ $this->labels[$filterLabel]=$this->selected_params[$filterLabel]; }
                        $j++;
                    }
                }
                elseif($filter == "sort"){
                    $html .= "<span class='sort $spclasses'><select class='form-control' name='sort' id='ft_sort'>
                        <option value=''>Sort By</option>";
                    foreach($filter_array as $column => $value){
                        $selected = (isset($this->sort_string) ? (($this->sort_string !== "" && $column == $this->sort_string) ? "selected" : "") : ((isset($this->filters['sort_default']) && $this->filters['sort_default']== $column) ? "selected": ""));
                        $html .= "<option $selected value='$column'>$value</option>";
                    }
                    $html .= "</select></span>";
                }
                
                
            }
            $resetUrl = ($this->action) ? FC::getInstance()->getSelfLink()."?action=".$this->action."&ft_reset=1" : FC::getInstance()->getSelfLink() . "?&ft_reset=1";
            if($this->params){
                if(Tools::isAssociative($this->params)){
                    foreach($this->params as $key=>$param){
                        $resetUrl .= "&$key=$param";
                    }
                } else {
                    foreach($this->params as $param){
                        $resetUrl .= "&$param";
                    }
                }
            }
            if($this->container){ $class="ajax"; $container = "data-container='$this->container'";}
            else { $container=""; $class = ""; }
        $html .= "<span class='filterbuttons $spclasses'><input type='submit' $container name='ft_filter' id='ft_filter' value='Go' class='btn btn-default $class'>
                <a href='$resetUrl' class='btn btn-default'>Reset</a></span>
                </form></div>";
            
        if($this->only_pagination)
            return $pagination;
        $this->printable = $this->printFiltered();
        return $html . ($printable ? $this->printable : "");
    }

    public function getWhereString($exclude=array()){
        $filter_params = $this->getFilterParams();
        $query_string = "";
        foreach($filter_params as $fp => $params){
            if($fp != "sort" && !empty($params) && (!in_array($fp,$exclude)) ){
                $query_string .= "( $params ) AND ";
            }
        }
        $query_string = preg_replace("/( AND )$/","", $query_string);
        
        if($this->where){
            if($query_string)
                $this->filter_where = "WHERE " . $this->where ." AND " . $query_string;
            else
                $this->filter_where = "WHERE " . $this->where;
            //echo "<br>--".$this->where . "<br>";
        }
        elseif($query_string)
            $this->filter_where = "WHERE ". $query_string;
        else
            $this->filter_where = "";
            
        return $this->filter_where;
    }
    
    public function getSortString(){
        $filter_params = $this->getFilterParams();
        $query_string = false;
        foreach($filter_params as $fp => $params){
            if($fp == "sort"){
                $query_string = $params;
            }
        }
        return $query_string ? $query_string : $this->order;
    }
    
    public function getLimitString(){
        if($this->num)
            return "LIMIT " . $this->from . ", " . $this->num;
    }
    
    public function getQuery($limit=true){
        return $this->select . " " . $this->getWhereString($this->exclude_query) ." ".$this->groupby." ". $this->getSortString() . " " . (($limit)? $this->getLimitString():"");
    }
    public function getActionMethod(){
        $actionMethod = ($this->action) ? "action=".$this->action."&" : "";
        if($this->params){
            if(Tools::isAssociative($this->params)){
                $prmArr = array();
                foreach($this->params as $key=>$param){
                    $prmArr[] = "$key=$param";
                }
                $actionMethod .= implode("&",$prmArr) . "&";
            } else {
                $actionMethod .= implode("&",$this->params) . "&";
            }
        }
        return $actionMethod;
    }
    public function showPagination($record_count, $page, $num_show, $additonal_params=false, $paging_links=6){
        $this->count = $record_count;
        $pagesToMake = ($num_show ? ceil($record_count / $num_show) : 0);
        $actionMethod=$this->getActionMethod();
        $ret = false;
        if($pagesToMake>1){
            
        if(isset($_GET['p'])&&$_GET['p']>1){
            $back = ($_GET['p'])-(1);
            $ret = "<li><a title='Back' aria-label='Previous' href='$page?".$actionMethod."p=$back"."$additonal_params'><span aria-hidden='true'>&laquo;</span></a></li>";
        }
        else{$_GET['p'] = 1; }
        //deciding links creation..
        if($_GET['p']<$paging_links){
            $start_paging_index = 1;
        }
        elseif($_GET['p']==$paging_links){
            $start_paging_index = $paging_links;
        }
        else{
            $multiple = ($_GET['p'])/($paging_links); $multiple = floor($multiple);
            $start_paging_index = ($multiple) * ($paging_links);
        }
        $end_paging_index = $start_paging_index + $paging_links;
        if($end_paging_index>=$pagesToMake){ $end_paging_index = $pagesToMake; }
        
        for($i=$start_paging_index; $i<=$end_paging_index; $i++){
            if($i==$_GET['p']){ $class="active";} else{$class = "";}
            $label = ($this->paginationLabels) ? $this->paginationLabels[$i] : $i;
            //echo ObjectHtml::call_url("false",$i," onclick=\"loadPage('page_movies','".SITEURL."index/?page=$i"."$additonal_params&amp;inset','page_movies');\" class='stay $class'");
            $ret .= "<li><a class='$class' href='$page?".$actionMethod."p=$i"."$additonal_params'>$label</a></li>";
        }
        if(isset($_GET['p'])){ $nextPage = ($_GET['p']) + 1; } else{ $nextPage = 2;}
            if($nextPage<=$pagesToMake){
                //echo ObjectHtml::call_url("false",">>"," onclick=\"loadPage('page_movies','".SITEURL."index/?page=$nextPage"."$additonal_params&amp;inset','page_movies');\" title='Next' class='stay'");
                $ret .= "<li><a title='Back' aria-label='Next' href='$page?".$actionMethod."p=$nextPage"."$additonal_params'><span aria-hidden='true'>&raquo;</span></a></li>";
            }    
      }
      return $ret;
    }
    public function printFiltered(){
        $ret = false;
        if($this->labels){
            $ret = "<ul class='ul printfiltered'>";
            foreach($this->labels as $label => $selected){
                if($selected) $ret .= "<li><span class='fplabel'>$label : </span><span class='fpvalue'>$selected</span></li>";
            }
            $ret .= "</ul>";
        }
        $this->printable = $ret;
        return $ret;
    }
}
//(FC::getClassInstance("Filter")->filters['filters']['Branch']['id_branch'][$_GET['filters'][0]] ? : "All");
?>