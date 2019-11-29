<?php 
class TimeConverter {
    public function timeMinutes( $time ) {
        $timeArray = str_split( $time );
		return $timeArray[3].$timeArray[4]; 
    }
	
    public function timeHours( $time ) {
        $timeArray = str_split( $time );
            return ( $timeArray[0].$timeArray[1] );
    }
	
    public function durationValue( $time ) {
        $timeArray = str_split( $time );
            return $timeArray[0].$timeArray[1];
    }
    
    public function addMinutes( $time, $mints ) {
	    $s_mints = $this->timeMinutes( $time );
	    $n_mints = $s_mints + $mints;
	    $timeArray = str_split( $time );
	    $mintsArray = str_split( $n_mints );
	    $timeArray[3] = $mintsArray[0];
	    $timeArray[4] = $mintsArray[1];
	    return implode($timeArray);
    }
	
    public function cHrsInMins($hr){
	if(preg_match("/minutes/",$hr)){
	    return preg_replace("/[^0-9]/","",$hr);
	}
	elseif(preg_match("/hour/",$hr)){
	    $hr = preg_replace("/[^0-9]/","",$hr);
	    return $hr * 60;
	}
	else{
		return $hr * 60;
	}
    }
	
    public function cRatesInMin($per_hr){
	return ($per_hr / 60);
    }
	public function difference($startTime, $endTime = false){
		if(!$endTime) $endTime = time();
		$diff = abs($endTime - strtotime($startTime));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		return array($years, $months, $days);
	}
	//count weeks in daterange
	public function countWeeks($date1, $date2){
		$first = new DateTime($date1);
		$second = new DateTime($date2);
		if($date1 > $date2) return countWeeks($date2, $date1);
		return ceil($first->diff($second)->days/7);
	}
	function getStartOfWeekDate($date = null){
		$date = new \DateTime($date);
		$date->setTime(0, 0, 0);
		if ($date->format('N') == 1) {
			return $date->format("Y-m-d");
		}elseif ($date->format('N') == 7) {
			$date->modify("+1 day");
			return $date->format("Y-m-d");
		} else {
			$date->modify('monday this week');
			return $date->format("Y-m-d");
		}
	}
	function getEndOfWeekDate($date = null){
		$date = new \DateTime($date);
		$date->setTime(0, 0, 0);
		if ($date->format('N') == 6) {
			return $date->format("Y-m-d");
		}elseif ($date->format('N') == 7) {
			$date->modify("-1 day");
			return $date->format("Y-m-d");
		} else {
			$date->modify('saturday this week');
			return $date->format("Y-m-d");
		}
	}
	public function excludeSundays($first, $last) {
		$step = '+1 day';
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		$i=0;
		while( $current <= $last ) { 
		    if (date("D", $current) != "Sun")
		        $dates[] = date("Y-m-d", $current);
		    $current = strtotime($step, $current);
			$i++;
			if($i > 1000){ return $dates; }
		}
		return $dates;
	}
	//return dates in a date range
	public function getDates($first, $last) {
		$step = '+1 day';
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		$i=0;
		while( $current <= $last ) { 
		    $dates[] = date("Y-m-d", $current);
		    $current = strtotime($step, $current);
			$i++;
			if($i > 1000){ return $dates; }
		}
		return $dates;
	}
	
}

?>