<?php
class Time {
    
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
	
	// $mins will be integer value
	public function addMinsInCurrentTime($mins){
		$timeP = new DateTime();
		$timeP->add(new DateInterval('PT' . $mins . 'M'));
		$stampP = $timeP->format('Y-m-d H:i:s');
		return $stampP;
	}
	public function addMins($time, $mins){
		$timeP = new DateTime($time);
		$timeP->add(new DateInterval('PT' . $mins . 'M'));
		$stampP = $timeP->format('Y-m-d H:i:s');
		return $stampP;
	}
	// $hours will be integer value
	public function addHoursInCurrentTime($hours){
		$hours = $hours * 60;
		$timeP = new DateTime();
		$timeP->add(new DateInterval('PT' . $hours . 'M'));
		$stampP = $timeP->format('Y-m-d H:i:s');
		return $stampP;
	}
	
	public function addHours($time, $hours){
		$hours = $hours * 60;
		$timeP = new DateTime($time);
		$timeP->add(new DateInterval('PT' . $hours . 'M'));
		$stampP = $timeP->format('Y-m-d H:i:s');
		return $stampP;
	}
	
	public function isFutureTime($time){
	    $time_now = time();
	    $time = strtotime($time);
	    return ($time > $time_now) ? true : false;
	}
	
}

?>