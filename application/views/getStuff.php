<?php
    FC::loadClass("Db");
    FC::loadClass("ObjectLayout");
    FC::loadClass("Tools");
    $session=FC::getClassInstance("Session");
    $Messages = FC::getClassInstance("Messages");
    $db=FC::getClassInstance("Db");    $fc = FC::getInstance();
    $id_user = Session::user("id");

if(isset($_GET['logOnlineStatus'])){
    $id_user = Tools::getValue("id_user");
    FC::getClassInstance("Users")->updateOnlineStatus($id_user);
}

    
elseif(isset($_GET['validate_username'])){ $ad_user = Tools::getValue('validate_username');
    if(strlen($ad_user)>0){
	if(!FC::getClassInstance("Users")->isUserNameAvailable($ad_user)){
	    red("$ad_user is already taken, Please try another one."); }
	else{ green("$ad_user is available."); } }
    }
elseif(Tools::isSubmit("isUnique")){
    $c = urldecode(Tools::getValue("c"));
    $v = urldecode(Tools::getValue("v"));
    if($c == "ad_user" && strlen($v) < 3){ echo json_encode( array('success'=>false, 'msg'=>'<div class="red">Subdomain must contain at least 3 characters.</div>') ); exit(); }
    if($c && $v){
	if( $db->getValue("SELECT count(`id`) from user_pro where `$c` = '$v'") > 0 ){
	    if($c=="ad_user") { echo json_encode( array('success'=>false, 'msg'=>"<div class='red'>$v is already taken. Please try someother subdomain name.</div>") ); exit(); }
	    elseif($c=="ad_email"){  echo json_encode( array('success'=>false, 'msg'=>"<div class='red'>An account already exist with this email id. Please try login or recover your password.</div>") );exit(); }
	    else{  echo json_encode( array("success"=>false, "msg"=>"<div class='red'>$v is not available.</div>") ); exit(); }
	}
	else{
	    echo json_encode( array('success'=>true, 'msg'=>"<div class='textgreen'>$v is available.</div>") );
	}
    }else{
	 echo json_encode( array('success'=>false, 'msg'=>"<div class='textred'>Invalid values.</div>") );
    }
    exit();
}
//statuses = array(0=>"email_not_verified",1=>"email_verified","2"=>"verified_account",3=>"suspended")
elseif(isset($_GET['login'])){
    $login = FC::getClassInstance("Login");
    $login->ad_pwd = Tools::getValue('ad_pwd');
    $login->ad_email = Tools::getValue('ad_email');
    $login->rememberme = (isset($_GET['rememberme'])) ? true : false;
    sleep(2);
	$authentica = $login->authenticate();
    if($authentica){
        $status = $login->checkStatus();
        if ($status==2 || $status==1 || $status==3){
                $login->addLoginTime();
                $login->createLoginInstance();
                $acc_type = FC::getClassInstance("Session")->getMy('acc_typ');
                if($acc_type == "2" || $acc_type == "3" ){$url = "dashboard"; }
				//elseif($acc_type==4){$url="mcp";} elseif($acc_type==5){$url="ecp";}
                else{$url = "cp";}
                echo json_encode( array("success"=>true, "account"=>$acc_type, "page"=>$url) );
		exit();
        }
        //elseif ($status==1){
            //die( json_encode( array("success"=>false, "msg"=> "Your account is not verified") ) );
        //}
        //elseif ($status==3){
        //        die( json_encode( array("success"=>false, "msg"=> "Your account has been suspended. Please contact our support team for more information") ) );
        //}
        elseif ($status==0){
                die( json_encode( array("success"=>false, "msg"=> "Please verify your email address before you can login. <a href='".SITEURL."reverification/?email=".$login->ad_email."' class='btn btn-primary'>Resend Verification Email</a>") ) );
        }
		else{
			die( json_encode( array("success"=>false, "msg"=> "No record found. Invalid login") ) );
		}
    }
    else{
	die( json_encode( array("success"=>false, "msg"=> "Invalid login details.")) );
    }
}
elseif(Tools::isSubmit("stats_earning")){
    $out = array();
    $date = date("Y-m", time());
    $date = $date . "-1 00:00:00";
    $start  = Tools::getValue("start");
    $end    = Tools::getValue("end");
    $myId = $session->myId();
    if(!$start){ $date_query = "AND `date_added` >= '$date'"; }
    else $date_query = "AND `date_added` BETWEEN '".$start." 00:00:00' AND '".$end." 23:59:59'";
    if($session->isAdmin()){
       // $rows = $db->getRows("SELECT SUM(  `amount` - `fees` ) as amount , DATE(  `tdate` ) AS paid_date FROM  `".DBPREFIX."transactions`
    //        WHERE `type` = 0 $date_query AND status != 10 GROUP BY paid_date");
    $rows=$db->getRows("SELECT count(`id`) as 'amount',  DATE(  `date_added` ) AS paid_date from routing
                 WHERE `arrived`=1 $date_query GROUP BY paid_date");

    }else{
//        $rows = $db->getRows("SELECT SUM(  `amount` ) as amount , DATE(  `tdate` ) AS paid_date FROM  `".DBPREFIX."transactions`
  //      WHERE `type` = 0 AND `sender` = '{$id_user}' $date_query AND status != 10 GROUP BY paid_date");
         $rows=$db->getRows("SELECT count(r.`id`) as 'amount', DATE( r.`date_added` ) AS paid_date from routing r LEFT JOIN van v on v.id=r.id_van LEFT JOIN association a on a.id_user=v.driver WHERE r.`arrived`=1 AND a.associate_company_id='$myId' $date_query  GROUP BY paid_date");
    }
    if($rows){
		foreach($rows as $row){
			$date = date('Y-m-d', strtotime($row['paid_date']) );
			$out[] = array($date, (FLOAT)number_format($row['amount'],2,".",""));
		}
		echo json_encode($out);
    }
    else { echo json_encode(array("error"=>"There isn't enough data to display.")); }
    exit();
}
// CHAT APP START
// CHAT APP END



elseif(Tools::isSubmit("stat_sales")){
    $date = date("Y-m", time());
    $date = $date . "-1 00:00:00";
    $start  = Tools::getValue("start");
    $end    = Tools::getValue("end");
    $word = ($session->isMerchant()) ? "Sales" : "Purchases";
    if(!$start){ $date_query = "AND `tdate` >= '$date'"; }
    else $date_query = "AND `tdate` BETWEEN '".$start." 00:00:00' AND '".$end." 23:59:59'";
      if($session->isMerchant()){
        $row = $db->getRow("SELECT COUNT(`id`) as sales, SUM(  `amount` - `fees` ) as amount FROM  `".DBPREFIX."transactions`
            WHERE `type` = 0 AND `receiver` = '{$id_user}' $date_query AND status != 10");
    }else{
        $row = $db->getRow("SELECT  COUNT(`id`) as sales, SUM(  `amount` - `fees` ) as amount FROM  `".DBPREFIX."transactions`
            WHERE `type` = 0 AND `sender` = '{$id_user}' $date_query AND status != 10");
    }
    ?>
    <div class="fontsmall marginbottom">
        <?php echo Tools::price($row['amount'], Session::user('curr')); ?>
    </div>
    (<?php echo $row['sales'] . " " .$word; ?>)
    <?php
	exit();
}
elseif(Tools::isSubmit("loadhDetails")){
    $id = Tools::getValue("id");
    global $data;
    $row = $db->getRow("SELECT * FROM `routing` WHERE `id` = '$id' LIMIT 1");
    if($row){
        $value = &$row;
        ?>
         <table class="table table-bordered">
                 <tr><td colspan="2" align="center"><label>User Details</label></td></tr>
                <tr><td><label>ID #</label></td><td><?php echo $row['id']; ?></td></tr>
                <tr><td><label>UserName</label></td><td class="capitalize"><?php $username=$db->getRow("SELECT * FROM user_pro WHERE id='$row[user_id]'"); echo $username['ad_user']; ?></td></tr>
                <tr><td><label>First Name</label></td><td><?php echo $username['first_name'];?></td></tr>
                <tr><td><label>Last Name</label></td><td><?php echo $username['Last_name'];?></td></tr>
                <tr><td><label>Email</label></td><td><?php echo $username['ad_email'];?></td></tr>
                <tr><td><label>Contact</label></td><td><?php echo $username['phone']?></td></tr>
                <tr><td colspan="2" align="center"><label>Driver Details</label></td></tr>
                <tr><td><label>Driver Name</label></td><td><?php $vanname=$db->getRow("SELECT van_name, driver FROM van WHERE id='$row[id_van]'"); $drivername=$db->getRow("SELECT ad_user, phone FROM user_pro WHERE id='$vanname[driver]'"); echo $drivername['ad_user'];?></td></tr>
                <tr><td><label>Contact</label></td><td><?php echo $drivername['phone'];?></td></tr>
                <tr><td colspan="2" align="center"><label>VAN Details</label></td></tr>
                <tr><td><label>Name</label></td><td><?php $vanname=$db->getRow("SELECT * FROM van WHERE id='$row[id_van]'"); echo $vanname['van_name']; ?></td></tr>
                <tr><td><label>Make</label></td><td><?php echo $vanname['make'];?></td></tr>
                <tr><td><label>Model</label></td><td><?php echo $vanname['model'];?></td></tr>
                <tr><td><label>Route</label></td><td><?php $routename=$db->getRow("SELECT `name` FROM `routedefine` WHERE `id`='$vanname[route]'");echo $routename['name'];?></td></tr>
                <tr><td colspan="2" align="center"><label>RIDE Details</label></td></tr>
                <tr><td><label>From</label></td><td><?php $station=$db->getRow("SELECT `station` FROM `route` WHERE `id`='$row[nearpick]'");echo $station['station'];?></td></tr>
                <tr><td><label>TO</label></td><td><?php $station=$db->getRow("SELECT `station` FROM `route` WHERE `id`='$row[neardrop]'");echo $station['station'];?></td></tr>
                <tr><td><label>Amount</label></td><td><?php echo CURR. $value['amount']; ?></td></tr>
                <tr><td><label>Commession</label></td><td><?php echo CURR. $value['commission']; ?></td></tr>
                <tr><td><label>Date</label></td><td><?php echo date("l, j F Y", strtotime($value['date_added']));?></td></tr>
        <?php
    }else{
        echo "<div class='alert alert-danger'>No information available</div>";
    }
}
elseif(Tools::isSubmit("loadTDetails")){
    $id = Tools::getValue("id");
    $id_user = Session::user("id");
    global $data;
    $row = $db->getRow("SELECT * FROM `".DBPREFIX."transactions` WHERE `id` = '$id' LIMIT 1");
    if($row){
        $value = &$row;
        if($row['sender'] == $id_user || $row['receiver'] == $id_user){
            $sign = ($value['sender'] == Session::get("id_user")) ? "-" : "+";
            $dir = ($value['sender'] == Session::get("id_user")) ? "out" : "in";
            if($value['sender'] == Session::get("id_user")){ $id_name = $value['receiver']; }
            else{ $id_name = $value['sender'];}
            $name = $row['names'];
            if($id_name == "1"){ $name = "BttPay";}
            if($dir == "out"){
                if($value['type']==2){//its a withdrawal request
					$value['nets'] = $value['amount'];
					$value['amount'] = $value['amount'] - $value['fees'];
                }
                elseif($value['type']==9){//its a bank transfer
                    $value['amount']    =  $value['amount'] - $value['fees'];
                    $bt = $db->getRow("SELECT * FROM bk_transfers where tid = '".$value['id']."'");
                }else{
					$value['fees'] = 0;
					$value['nets'] = $value['amount'];
				}
            }else{
                $value['nets'] = $value['amount'] - $value['fees'];
            }
            ?>
            <table class="table table-bordered">
                <tr><td><label>Reference #</label></td><td><?php echo $row['transaction_id']; ?></td></tr>
                <tr><td><label><?php echo ($dir=='out') ? 'Sent To' : 'Sent From'; ?></label></td><td class="capitalize"><?php  echo ucfirst($name); ?></td></tr>
                <?php if(!isset($bt)){ ?><tr><td><label>Email</label></td><td><?php echo $row['email_add']; ?></td></tr>
                <tr><td><label>Address</label></td><td><?php echo $row['address']; ?></td></tr>
                <tr><td><label>City</label></td><td class="capitalize"><?php echo $row['city']; ?></td></tr>
                <tr><td><label>Postal Code</label></td><td><?php echo $row['zip']; ?></td></tr>
                <tr><td><label>State</label></td><td class="capitalize"><?php echo $row['state']; ?></td></tr>
                <tr><td><label>Country</label></td><td class="capitalize"><?php echo $row['country']; ?></td></tr>
                <tr><td><label>Date</label></td><td><?php echo date("M d, Y", strtotime($value['date'])); ?> at <?php echo date("h:i:sa", strtotime($value['date'])); ?></td></tr>
                <tr><td><label>Amount</label></td><td><?php echo Tools::price($value['amount'], CURRENCY); ?></td></tr>
                
                <?php } else{ ?>
                    <tr><td><label>Bank Name</label></td><td><?php echo $bt['b_name']; ?></td></tr>
                    <tr><td><label>Account Title</label></td><td><?php echo $bt['b_title']; ?></td></tr>
                    <tr><td><label>SWIFT/Bank Sort Number</label></td><td><?php echo $bt['swift']; ?></td></tr>
                    <tr><td><label>IBAN/Account Number</label></td><td><?php echo $bt['iban']; ?></td></tr>
                    <tr><td><label>Country</label></td><td><?php echo $bt['country']; ?></td></tr>
                    <tr><td><label>Remarks</label></td><td><?php echo $bt['remarks']; ?></td></tr>
                    <tr><td><label>Date</label></td><td><?php echo date("M d, Y", strtotime($value['date'])); ?> at <?php echo date("h:i:sa", strtotime($value['date'])); ?></td></tr>
                    <tr><td><label>Amount</label></td><td><?php echo Tools::price($value['amount']-$value['fees'], CURRENCY); ?></td></tr>
                
                <?php } ?>
                <tr><td><label>Fee</label></td><td><?php echo Tools::price($value['fees'], CURRENCY); ?></td></tr>
                <tr><td><label>Net Amount</label></td><td><?php echo $sign . " " . Tools::price($value['nets'], CURRENCY); ?></td></tr>
                <tr><td><label>Transaction Type</label></td><td>
                        <?php echo $data['TransactionType'][$value['type']]; ?>
                        <?php if($value['type'] == 4){ ?><span title="You can't withdraw signup bonus. You can transfer this moeny to someone or use it to shop online." data-toggle="tooltip"><i class="glyphicon glyphicon-info"></i></span><?php } ?>
                    </td>
                </tr>
                <tr><td><label>Status</label></td><td><?php echo $data['TransactionStatus'][$value['status']];?></td></tr>
            </table>
            <?php if($value['type']=="0" && $value['status'] == "1" && $value['receiver'] == $id_user){ ?>
            <div class="gap2 clearfix">
                <a href="<?php echo SITEURL; ?>transactions/?refund=<?php echo $id; ?>" onclick="return confirm('Are you sure?')" class='pull-right btn btn-primary'>Refund</a>
            </div>
            <?php }
        }
        else{
            echo "<div class='alert alert-danger'>Invalid Request</div>";
        }
    }else{
        echo "<div class='alert alert-danger'>No information available</div>";
    }
}
elseif(Tools::isSubmit("loadbookDetails")){
    $id = 1;//Tools::getValue("id");
    $plat=Tools::getValue('plat');
    $plng=Tools::getValue('plng');
    $dlat=Tools::getValue('dlat');
    $dlng=Tools::getValue('dlng');
    global $data;
    $db = FC::getClass("Db");
    $pickuppoint=$db->getRow("SELECT * from  (select `id`,`station`,slat AS 'lat',slng AS 'lng',( 6373 * acos( cos( radians('$plat') ) * cos( radians( 	slat) ) * cos( radians( slng ) - radians('$plng') ) + sin( radians('$plat') ) * sin( radians( slat ) ) ) ) AS distance FROM route HAVING distance < 2) as d order by distance");
    $neardrop=$db->getRow("SELECT * from  (select `id`, `station`,slat AS 'lat',slng AS 'lng',( 6373 * acos( cos( radians('$dlat') ) * cos( radians( 	slat) ) * cos( radians( slng ) - radians('$dlng') ) + sin( radians('$dlat') ) * sin( radians( slat ) ) ) ) AS distance FROM route HAVING distance < 2) as d order by distance");
    if($pickuppoint && $neardrop){
         $myId = $session->myId();
          if(!$session->isAdmin())
        {
            $id_van = $db->getRows("SELECT v.id as 'id_van' FROM `van` v  LEFT JOIN association a on a.id_user=v.driver LEFT JOIN route r on r.routeid=v.route WHERE a.associate_company_id='$myId' and lower(r.station) = '".strtolower($pickuppoint['station'])."'");
       }
        else
        {
       $id_van = $db->getRows("SELECT v.id as 'id_van' FROM `van` v  LEFT JOIN association a on a.id_user=v.driver LEFT JOIN route r on r.routeid=v.route WHERE  lower(r.station)= '".strtolower($pickuppoint['station'])."'");
       }
        //$id_van = $db->getRows("SELECT van.id id_van FROM route a LEFT JOIN van ON a.routeid = van.route WHERE lower(station) = '".strtolower($pickuppoint['station'])."'");
        $word = strtolower("user");
        $role=$db->getValue("SELECT `id` FROM `role` WHERE `url`='$word'");

         if(!$session->isAdmin())
        {
            $users=$db->getRows("SELECT a.`id`, a.`ad_user`, a.`ad_email` FROM `user_pro` a LEFT JOIN association b ON b.id_user=a.id WHERE b.associate_company_id='$myId' AND a.`acc_typ`='$role'");
        }
        else
        {
             $users=$db->getRows("SELECT a.`id`, a.`ad_user`, a.`ad_email` FROM `user_pro` a LEFT JOIN association b ON b.id_user=a.id WHERE a.`acc_typ`='$role'");
        }

        $van=[];
        $ser=[];
                foreach($id_van as $key=>$vanid){
                     $van[] = $db->getRow("SELECT * From van WHERE id = '$vanid[id_van]'");
                     }
                ?>
                <form id="booking" method="post" action="" enctype="multipart/form-data">
                    <div class="bookingform">
                    <div class="row form-group">
                        <div class="col-md-12"></div>
                    </div>
                <div class="row form-group">
                <div class="col-md-2"><label for="title">UserID</label></div>
                <div class="col-md-5"><select name="user_id"class="form-control">
                 <?php
                  foreach($users as $item){
                      ?>
                     <option value="<?php echo $item['id']; ?>"><?php echo $item['ad_user']; ?></option>
                      <?php
                  }
                  ?>
                  </select>
                </div></div>
                 <div class="row form-group">
                     <div class="col-md-8"><a href="<?php echo SITEURL;?>users/?action=add&type=user" style="float: right">Add New User</a></div></div>
                    <div class="row form-group">
                    <div class="col-md-2"><label for="title">PickUp Point: </label></div>
                <div class="col-md-5"><input type="text" id="pickstation" name="pickstation" readonly value="<?php echo $pickuppoint['station']?>" class="form-control">
                <input type="hidden" name="pickstationid" id="pickstationid" value="<?php echo $pickuppoint['id'];?>">
                </div></div>
                <div class="row form-group">
                <div class="col-md-2"><label for="title">Drop Point: </label></div>
                <div class="col-md-5"><input type="text" id="dropstation" name="dropstation" readonly value="<?php echo $neardrop['station'];?>" class="form-control">
                <input type="hidden" name="dropstationid" id="dropstationid" value="<?php echo $neardrop['id'];?>"></div>
                </div>
                <div class="row form-group">
                <div class="col-md-2"><label for="title">Select Van: </label></div>
                <div class="col-md-5"><select name="van_id" id="vanid" class="form-control">
                <?php
                ?>
                <option>Select One</option>
                <?php
                  foreach($van as $item){
                      ?>
                      <option value="<?php echo $item['id']; ?>"><?php echo $item['van_name'].' '.$item['make'].' '.$item['model']; ?></option>
                      <?php
                  }
                  ?>
                </select></div></div>

                <div class="van_details" id="van_details">
                </div>
                        <div class="row form-group">
                            <div class="col-md-2"></div>
                            <div class="col-md-7 text-right">
                                <input type="submit" name="submit" value="Confirm" class="btn btn-primary">
                                <!--<input type="button" onclick="confirmation()" value="Book Ride" class="btn btn-primary">--></div>
                        </div>

                </div>
                    </div>
                    <div class="confirmationform nodisplay">
                        <div class="confimationdisplay" id="confimationdisplay">
                            <div class="row form-group">
                            <div class="col-md-12"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2"><label for="title">PickUp Point: </label></div>
                                <div class="col-md-5"><input type="text" id="c_pickstation" name="c_pickstation" readonly value="" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2"><label for="title">Drop Point: </label></div>
                                <div class="col-md-5"><input type="text" id="c_dropstation" name="c_dropstation" readonly value="" class="form-control">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2"><label for="title">Total Amount: </label></div>
                                <div class="col-md-5"><input type="text" id="c_total" name="c_total" readonly value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-8 text-right"><input type="button" onclick="bookingform()" value="Back" class="btn btn-primary"></div>
                            <div class="col-md-4 text-left"><input type="submit" name="submit" value="Confirm" class="btn btn-primary"> </div>
                        </div>
                    </div>
               </form>
                <script>
                    function confirmation(){
                        $("div.bookingform").addClass("nodisplay");
                        $("div.bookingform").addClass("notadded");
                        $("div.confirmationform").removeClass("nodisplay");
                        var c=10;
                        var passenger=document.getElementById("priceperseat").value;
                        var pickup=document.getElementById("pickstation").value;
                        var drop=document.getElementById("dropstation").value;
                        var total=document.getElementById("total").value;
                       document.getElementById("c_pickstation").value=pickup;
                       document.getElementById("c_dropstation").value=drop;
                       document.getElementById("c_total").value=total;

                      //  console.log(passenger);
                    }
                    function bookingform(){
                        $("div.confirmationform").addClass("nodisplay");
                        $("div.bookingform").removeClass("nodisplay");
                        // $("div.bookingform").addClass("notadded");


                    }

                     $("#vanid").change(function(){
                      var id = $(this).val();
                      $.ajax({
                         url:'<?php echo SITEURL;?>getStuff.php?ajax',
                         type:'post',
                         data: {vaninfo:true,id:id},
                        success:function(response){
                             $("#van_details").html(response);
                        },error: function(){ $('#van_details').html("Something went wrong. Please try again later"); },
                      });
                    });
                </script>
                <?php
                }else{
                    echo "<div class='alert alert-danger'>No information available</div>";
                }
}
elseif(Tools::isSubmit("vaninfo")){
    $id = Tools::getValue("id");
    global $data;
    $db = FC::getClass("Db");
    $van=$db->getRow("SELECT * FROM `van` WHERE `id`=$id");
    $servicesid=$db->getRows("SELECT `id_service` as 'id' FROM `vanservice` WHERE `id_van`='$id'");
    $noofseats=$van['seat'];
    $seatsbooked=$db->getRows("SELECT `seatno` FROM `seatbooked` WHERE `van_id`='$id' AND `isExpired`=0");
    $seatno=[];
    $booked=[];
    foreach($seatsbooked as $key=>$seat){
        $seatno[]=$seat['seatno'];
    }
    foreach($servicesid as $sid){
        $vanservices[]=$db->getRow("SELECT * FROM `services` WHERE `id`='$sid[id]'");
    }
    ?>
       <style>
            /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
   // background:url("../../images/seat.jpg") ;
  width: 34px;
  height: 25px;
    border-radius: 50%;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
    width: 34px;
    height: 39px;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
    border-radius: 50%;
   // background:url("../../images/seat.jpg") ;
}

.slider:before {
  visibility: hidden;
    position: absolute;
  content: "";
  height: 34px;
  width: 25px;
  left: 4px;
  bottom: 4px;
  background-color: white;
 // -webkit-transition: .4s;
  //transition: .4s;
}

input:checked + .slider {
  background-color: #5FCC23;;

}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {

    visibility: hidden;
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 34%;
}

        </style>
         <div class="row form-group">
       <div class="col-md-3"><label for="title" hidden >No of Passengers</label></div>
      <div class="col-md-9" hidden>
      <select  name="passengers" id="passengers" class="form-control">
       <option value="0">Select Seats </option>
       <?php for($i=1;$i<=$noofseats;$i++){ ?>
       <option value="<?php echo $i;?>"><?php echo $i;?></option>
       <?php } ?>
       </select>
      </div>
      </div>
    <div class="row form-group">
        <div class="col-md-2"><label for="title" >Price Per Seat</label></div>
        <div class="col-md-5"><input type="hidden" name="fares" readonly id="fares" value="0"  hidden class="form-control">
            <input type="text" name="priceperseat" readonly id="priceperseat" value="<?php echo $van['price'];?>" class="form-control">
        </div></div>
        <div class="row form-group">
            <div class="col-md-12" >

                <div class="col-md-4">
                    <div class="col-sm-12 row form-group" align="center"><label for="title" >Floor Plan<?php echo "(".$noofseats." seats)";?></label></div>
                    <div class="col-sm-12"></div>

                    <?php  for($i=1;$i<=$noofseats-4;$i++){ $j=$i;?>
                        <div class="row form-group">
                            <?php if($i<=$noofseats-4){?>
                                <div class="col-md-3">
                                <label class="switch">
                                    <input type="checkbox"  name="seat[<?php echo $i;?>]" onchange="calculateprice(<?php echo $i; ?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $i; ?>]" <?php if(in_array($i, $seatno)) {echo'disabled checked';$i++;} else{$i++;} ?>>
                                    <span class="slider"></span>
                                </label></div><?php } ?>
                            <div class="col-md-3"></div>
                            <?php if($i<=$noofseats-4){?>
                                <div class="col-md-3">
                                <label class="switch">
                                    <input  type="checkbox" name="seat[<?php echo $i;?>]" onchange="calculateprice(<?php echo $i; ?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $i; ?>]" <?php if(in_array($i, $seatno)) {echo'disabled checked';$i++;} else{$i++;} ?>>
                                    <span class="slider"></span>
                                </label></div><?php } ?>
                            <?php if($i<=$noofseats-4){?>
                                <div class="col-md-3">
                                <label class="switch" >
                                    <input type="checkbox" name="seat[<?php echo $i;?>]" onchange="calculateprice(<?php echo $i; ?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $i; ?>]" <?php if(in_array($i, $seatno)) {echo'disabled checked';} else{ } ?>>
                                    <span class="slider"></span>
                                </label></div><?php } ?>
                        </div>
                    <?php }?>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label class="switch">
                                <input type="checkbox" name="seat[<?php echo $noofseats-3;?>]" onclick="calculateprice(<?php echo $noofseats-3;?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $noofseats-3;?>]" <?php if(in_array($noofseats-3, $seatno)) echo'disabled checked'; ?>>
                                <span class="slider"></span>
                            </label></div>
                        <div class="col-md-3">
                            <label class="switch">
                                <input type="checkbox" name="seat[<?php echo $noofseats-2;?>]" onclick="calculateprice(<?php echo $noofseats-2;?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $noofseats-2;?>]" <?php if(in_array($noofseats-2, $seatno)) echo'disabled checked'; ?>>
                                <span class="slider"></span>
                            </label></div>
                    <div class="col-md-3">
                        <label class="switch">
                            <input type="checkbox" name="seat[<?php echo $noofseats-1;?>]" onclick="calculateprice(<?php echo $noofseats-1;?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $noofseats-1;?>]" <?php if(in_array($noofseats-1, $seatno)) echo'disabled checked'; ?>>
                            <span class="slider"></span>
                        </label></div>
                    <div class="col-md-3">
                        <label class="switch">
                            <input type="checkbox" name="seat[<?php echo $noofseats-0;?>]" onclick="calculateprice(<?php echo $noofseats-0;?>, <?php echo $van['price'] ;?>)" id="seat[<?php echo $noofseats-0;?>]" <?php if(in_array($noofseats-0, $seatno)) echo'disabled checked'; ?>>
                            <span class="slider"></span>
                        </label></div></div>
                </div>
                <div class="col-md-6">
                    <div class="row-form-group">
                        <div class="col-sm-12" align="center"><label for="title" >Extra Services</label></div>
                        <?php
                        foreach($vanservices as $v_servies){
                            ?>

                            <div class="row form-group">
                            <div class="col-sm-3"><label for="title" ><?php echo ucwords($v_servies['servicename']);?></label></div>
                            <div class="col-sm-2"><input type="text" name="<?php echo "services[".$v_servies['id']."]";?>" onchange="update(<?php echo $v_servies['id'];?>,<?php echo $v_servies['serviceprice'];?> )" value="0" placeholder="Enter Amount of Services"  id="<?php echo $v_servies['id'];?>" class="form-control">
                                <input type="hidden" name="<?php echo "services[".$v_servies['id']."]n";?>" value="0" placeholder="Enter Amount of Services"  id="<?php echo $v_servies['id']."n";?>" class="form-control">
                            </div>
                            <!--<div class="number"><span class="minus">-</span><input type="text" value="1"/><span class="plus">+</span></div>
                             -->
                            <div class="col-sm-1"><label for="title">Price</label></div>
                            <div class="col-sm-2"><input type="text" name="<?php echo "priceof".$v_servies['id'];?>" readonly value="<?php echo $v_servies['serviceprice'] ;?>" class="form-control"></div>
                            <div class="col-sm-2"><label for="title" >Total Price</label></div>
                            <div class="col-sm-2"><input type="text" name="<?php echo $v_servies['id']."_p";?>" readonly value="0" class="form-control"></div>
                            </div>
                           <?php
                        }
                        ?>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-2"><label for="title" >No. Of Services</label></div>
                        <div class="col-sm-4"><input type="text" name="no_ser" readonly id="no_ser" class="form-control"></div>
                        <div class="col-sm-2"><label for="title" >Services Amount</label></div>
                        <div class="col-sm-4"><input type="text" name="ser_amount" readonly id="ser_amount" class="form-control"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-2"><label for="title" >Total Amount</label></div>
                        <div class="col-sm-10"><input type="text" name="total" readonly id="total" class="form-control"></div>
                    </div>
                </div>
            </div>
        </div>









      <!--<input type="text" name="ser" id="ser" value="<?php echo "ser";?>" class="form-control"></div>-->

      <?php ?>
      <script>
        	$(document).ready(function() {
			$('.minus').click(function () {
				var $input = $(this).parent().find('input');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
		});
      </script>
        <script>
            var TotalAmount=0;
            var basefares=0;
            var servicetotal=0;
            var numofservices=0;
           $("#passengers").change(function(){
                      var passenger = $(this).val();
                      var fares=<?php echo $van['price'] ?>;
                       var basetotal=$('#fares').val();
                      TotalAmount-=basetotal;
                      var total=passenger*fares;
                      $("#fares").val(total);
                      TotalAmount+=total;
                      $("#total").val(TotalAmount);
                    });

            function calculateprice(id, price){
               var newname="seat["+id+"]";


               var c=document.getElementById(newname);
                if(c.checked==true){
                    TotalAmount+=price;

                    console.log("checked"+id);
                }else if(c.checked==false){
                    console.log("unchecked"+id);
                    TotalAmount-=price;
                }
                $("#total").val(TotalAmount);
                }

                   function update(name, price)
                    {
                    var c=document.getElementById(name).value;
                    var numname=name+'n';
                    var oldnum=document.getElementById(numname).value;
                    var amount=parseInt(c);
                    var total=amount*price;
                    var p_name='_p';
                    var newname=name+p_name;
                    var t=$("#total").val();
                  var pricefield= document.getElementsByName(newname)[0].value;
                  var previousnum=document.getElementById(name).value;
                  //  var temp=document.getElementById(name).value;
                   numofservices-=oldnum;
                  numofservices+=amount;
                     t-=pricefield;
                     t+=total;
                     TotalAmount=t;
                     document.getElementById(numname).value=amount;
                     $("#ser_amount").val(t);
                     $("#no_ser").val(numofservices);
                        $("#total").val(TotalAmount);
                    document.getElementsByName(newname)[0].value = total;
                    }

        </script>

      <?php

}

//send sms
elseif(Tools::isSubmit("sendsms")){
        $id = Tools::getValue("userid");
        $message = Tools::getValue("msg");
        $subject = Tools::getValue("sub");

        $myId = $session->myId();
        global $data;
        $db = FC::getClass("Db");
    $msg=array(
        'comment_subject'=>$subject,
        'comment_text'=>$message,
    );
         if(!$session->isAdmin())
            {
            $msg['associate_company_id']='1';
            $msg['sendby']=$myId;
            }
        else
        {
            $msg['associate_company_id']=$id;
            $msg['sendby']=$myId;
        }
    if($id = $db->insert(array("comments"=>$msg))){
        echo " <div class='alert alert-success'>Message Sent</div>";
    }else{
        echo " <div class='alert alert-danger'>some error occur</div>";
    }

    
        }

//UPLOADING ATTACHMENT FILES FOR DRIVERS AND SERVICE-PROVIDE
        elseif(Tools::isSubmit("uploaddoc")){
            $id = Tools::getValue("userid");
           $uploads['id_user']=$id;
            if($_FILES['cv']['size']>0){
                $file = FC::classInstance("Upload", $_FILES["cv"]);
                $filePath = $file->upload();
                $uploads['cv']=$filePath;
            }
            if($_FILES['other']['size']>0){
                $file = FC::classInstance("Upload", $_FILES["other"]);
                $filePath = $file->upload();
                $uploads['other']=$filePath;
            }
            if($_FILES['misc']['size']>0){
                $file = FC::classInstance("Upload", $_FILES["misc"]);
                $filePath = $file->upload();
                $uploads['misc']=$filePath;
            }
            print_r($uploads);
         //   exit();

            if($db->insert(array("attachment"=>$uploads))){
                echo " <div class='alert alert-success'>Document Uploaded Successfully.</div>";
            }else{
                echo " <div class='alert alert-success'>Some Error Occur.</div>";
            }

            $fc->success = "New $word created successfully";


        }

elseif(Tools::isSubmit("dashboard")){
            $id = Tools::getValue("id");
            global $data;
            $db = FC::getClass("Db");

        }


// Loading Notification
elseif(Tools::isSubmit("loadnotification")){

         $db = FC::getClass("Db");
           $myId = $session->myId();
        if(isset($_POST['view'])){
            if($_POST["view"] != '')
            {
              $update_query=$db->execute("UPDATE comments SET comment_status = 1 WHERE 	associate_company_id= '$myId'AND  comment_status=0");

            }
        $result = $db->getRows("SELECT * FROM comments WHERE 	associate_company_id='$myId' ORDER BY `date_added` DESC LIMIT 5");
             $output = '';

        if($result)
        {
        foreach($result as $row)
        {
                if($session->isAdmin()){
                    $acc_type=$db->getValue("SELECT `acc_typ` from user_pro WHERE `id`='$row[sendby]'");
                    $url=$db->getValue("SELECT `url` from role WHERE `id`='$acc_type'");
                    $output .= '
                              <li>
                              <a href="'.SITEURL.'users/?action=profile&type='.$url.'&id='.$row["sendby"].' ">
                              <strong>'.ucwords($row["comment_subject"]).'&nbsp&nbsp<small>'.time_elapsed_string($row["date_added"]).'</small></strong><br />
                              <small><em>'.ucwords($row["comment_text"]).'</em></small>
                              </a>
                              </li>
                              ';
                }else{
                    $output .= '
                          <li>
                          <a href="'.SITEURL.'profile">
                          <strong>'.ucwords($row["comment_subject"]).'&nbsp&nbsp<small>'.time_elapsed_string($row["date_added"]).'</small></strong><br />
                          <small><em>'.ucwords($row["comment_text"]).'</em></small>
                          </a>
                          </li>
                          ';
                }

        }
        }
        else{
            $output .= '<li><a href="#" class="text-bold text-italic">No Notification Found</a></li>';
        }
        $result_query = $db->getRows("SELECT * FROM comments WHERE 	associate_company_id='$myId' AND comment_status=0");

        $count = count($result_query);
        $data = array(
           'notification' => $output,
           'unseen_notification'  => $count
        );
        echo json_encode($data);
        }
}


//LOAD ARCHIVES
        elseif(Tools::isSubmit("showarchives")){
            $db = FC::getClass("Db");
            $id = Tools::getValue("userid");
            $archive=$db->getRows("SELECT * FROM `attachment` WHERE `id_user`='$id' ORDER BY `date_uploaded` DESC");

            if($archive){
            //return $archive;
                $tr=[];
                foreach ($archive as $arc){
                    $tr.=" <tr><td>Archive</td><td>".$arc['cv'] ."</td></tr>";
                    ?>
                    <?php
                }


            }else{
                ?>
             <tr><td>Archive</td><td>No Archive Found</td></tr>
                <?php
            }


        }


elseif(Tools::isSubmit("fbshared")){
    $payment = FC::getClass("Payment");
    $id_user = Session::user("id");
    if($db->getValue("SELECT `bonus_paid` FROM `user_pro` WHERE `id` = '$id_user'") == 1){
        echo "Thank you for sharing";
    }else{
        $db->execute("UPDATE `user_pro` SET `bonus_paid` = 1 WHERE `id` = '$id_user' LIMIT 1");
        $payment->params = array("sender"=>"-10", "receiver"=>$id_user, "amount"=>"5", "type"=>"5","status"=>"1","currency"=>"MYR","names"=>"System");
        $payment->add();
        echo "Thank you for sharing, Your commission has been added to your account.";
    }
}
elseif(isset($_GET['newslatterem'])){
    $db  = FC::getClassInstance("Db");
    $email = $_GET['email'];
    $user = $db->getRows("SELECT `id` FROM `nl_email` where `email`='$email'");
    if(count($user) == 0)
    {
	$val = $db->insert(array("nl_email" =>  array(
		"email" => $email,
		"date" => date("y-m-d")
	)));
	echo "1";
    }
    else
    {
	echo "0";
    }
}
elseif(isset($_GET['test'])){ echo "You got here"; }
elseif(isset($_GET['updateProfile'])){

    if(Db::update("user_pro",array('updateProfile','inset'),"ad_email",$_SESSION['ad_email']))

        echo ObjectLayout::greenDiv($Messages->editProfileSuccess);

    else

        echo ObjectLayout::redDiv($Messages->editProfileFailed);

}
elseif(isset($_GET['cpass'])){
    $old = hashing(Tools::getValue('old'));
    $new = Tools::getValue('new');
    $cnew = Tools::getValue('cnew');
    $hash = hashing(Tools::getValue('new'));
    if(strlen($new)>0){
	    if($new==$cnew){
		$my_id = $session->myId();
		if (row_count2('user_pro','id',$my_id,'ad_pwd',$old)>0) {
		    if( $db->execute("UPDATE `user_pro` SET `ad_pwd` = '$hash' WHERE `id` = '$my_id' LIMIT 1") )
		    {
			echo "<div class='alert alert-success'>Profile password has been successfully updated.</div>";
		    }
		    else{
			echo "error updating password.";
		    }
		}
		else{
		    echo "<div id='red'>You profile password is incorrect</div>";
		}
	}
	else{
	    echo "<div id='red'>Password does not match </div>";
	}
    }
    else{
	echo "<div id='red'>Please enter your password.</div>";
    }
}
elseif(isset($_GET['proattr'])){
    $_SESSION["ad_ad"] = true;
    $_SESSION['acc_typ'] = "0";
}



elseif(isset($_GET['divCPass'])){ ?>

 <div id="lblDiv"></div><table id='changepass' cellspacing="0" cellpadding="2" width="400px" border="0">

  <tr><td><input type="hidden" name="pid" id="pid" value=""></td></tr>

  <tr><td>Old Password:</td><td><input type="password" name="old" id="old"></td></tr>

  <tr><td>New Password:</td><td><input type="password" name="new" id="new"></td></tr>

  <tr><td>Confirm Password:</td><td><input type="password" name="cnew" id="cnew"></td></tr>

  <tr height="50" align="center" valign="middle"><td></td><td>

  <input name="lpLogin:btnLogin" id="lpLogin_btnLogin" type="submit" onClick="return cpass()" style="WIDTH:100px" value="Change"> <label id="lblButtonSpacer"></label>

  <input name="lpLogin:btnCancel" id="lpLogin_btnCancel" type="button" title="Cancel" onClick="popupClear('divCPass')" style=" WIDTH:100px" value="Cancel"></td></tr>

  </tbody></table><?php }





elseif(isset($_GET['sendMsg'])){ $id=check_form($_GET['id']); $email=user_info('ad_email',$id); ?><div id="msgLbl2"></div><form onsubmit="javascript:return false">Subject:<br><input type="text" id="subject" size='50'><br>Enter the Message: <br><textarea rows="13" cols="50" name="replyTicketT" id="replyTicketT"></textarea><br>

<input type="submit" value="Send" onclick="sendMail('<?php echo $email; ?>')"></form><?php } 

elseif(isset($_GET['sendReply'])){
    $subject = urldecode($_GET['subject']);
    $email = $_GET['email'];
    $replyTicket = urldecode($_GET['replyTicket']);
    $mails = FC::getClassInstance("Mail");
    $mails->to = $email; $mails->subject = $subject; $mails->message=$replyTicket;
    if($mails->sendMail()){ green("Your message has been mailed to $email."); }
    else{ red(FC::getClassInstance("Messages")->mailSendingFail); }
    exit();
}



elseif(isset($_GET['sendReply2'])){ $to = check_form($_GET['to']); $subject = check_form($_GET['subject']); $replyTicket = check_form($_GET['replyTicket']); $mails = FC::getClassInstance("Mail"); $mails->to = $to; $mails->subject = $subject; $mails->message=$replyTicket; if($mails->sendMail()){ green("Your message has been sent"); } else{ red("error."); } }



elseif(isset($_GET['suspend'])){ $id= check_form($_GET['id']); if(update_single_by_id('user_pro', 'is_active','1',$id)){ green("Account suspended. Please refresh the page to view changes."); } else{ red($Messages->generalActionError);} }

elseif(isset($_GET['activate'])){ $id= check_form($_GET['id']); if(update_single_by_id('user_pro', 'is_active','2', $id)){ green("Account activated. Please refresh the page to view changes."); }

else{ red($Messages->generalActionError);} }

elseif(isset($_GET['deleteUser'])){ $id= check_form($_GET['id']); if(delete_single('user_pro','id',$id)){ echo "<div class='green'>User deleted. Please <a href=''>refresh</a> the page to view changes.</div>";} }



elseif(isset($_GET['delMulti'])){ if(!isset($_GET['mem'])){ echo "<div id='red'>Please select the members.</div>"; exit();}

$count = count($_GET['mem']); for($i=0; $i<$count; $i++) {$toDel=$_GET['mem'][$i]; $delete = mysql_query("DELETE FROM `user_pro` where `id`='$toDel'");}

if($delete){echo "<div id='green'>Member(s) deleted successfully. Please refresh the page to see changes.</div>";} else{echo "<div id='red'>Sorry, but the system was unable to perform requested action.</div>";}}



elseif(isset($_GET['susMulti'])){ if(!isset($_GET['mem'])){echo "<div id='red'>Please select the members.</div>"; exit();}

  $count = count($_GET['mem']); for($i=0; $i<$count; $i++) {$toDel=$_GET['mem'][$i]; $delete = mysql_query("UPDATE `user_pro` set `active` = '1' where `id`='$toDel'");}

  if($delete){echo "<div id='green'>Member(s) successfully Suspended. Please refresh the page to see changes.</div>";} else{echo "<div id='red'>Sorry, but the system was unable to perform requested action.</div>";}}



elseif(isset($_GET['actMulti'])){  if(!isset($_GET['mem'])){echo "<div id='red'>Please select the members.</div>"; exit();}

 $count = count($_GET['mem']); for($i=0; $i<$count; $i++){ $toDel=$_GET['mem'][$i]; $delete = mysql_query("UPDATE `user_pro` set `active`='2' where `id`='$toDel'");}

 if($delete){echo "<div id='green'>Account(s) successfully Activated. Please refresh the page to see changes.</div>";} else{echo "<div id='red'>Sorry, but the system was unable to perform requested action.</div>";} }



elseif(isset($_GET['msgMulti'])){  if(!isset($_GET['mem'])){echo "<div id='red'>Please select the members.</div>"; exit();}

$toMail=''; $count = count($_GET['mem']); for($i=0; $i<$count; $i++){ $id=$_GET['mem'][$i]; $toEmail=user_info("ad_email", "id"); if(strlen($toEmail['ad_email'])>0){ $toMail .=$toEmail['ad_email'].","; } }

?><form onsubmit="javascript:return false">Subject:<br><input type="text" id="subject" size='50'><br>Enter the Message: <br><textarea rows="13" cols="50" name="replyTicketT" id="replyTicketT"></textarea><br>

<input type="submit" value="Send" onclick="sendMail('<?php echo $toMail; ?>')"></form><?php

}



elseif(isset($_GET['delete'])){

    if((!isset($_GET['t'])&&!empty($_GET['t']))||(!isset($_GET['c'])&&!empty($_GET['c']))||(!isset($_GET['v'])&&!empty($_GET['v']))){ exit(); }$table = $_GET['t'];

    $column = $_GET['c'];$value = $_GET['v'];delete_single($table,$column,$value); }



elseif(isset($_GET['saveCategory'])){ $cat_name = check_form($_GET['cat_name']);

    if(row_count("categories","cat_name",$cat_name)>0){ red("Category already exist. Please try another name");exit(); }

        Db::insert(array("categories"=>array("cat_name"=>$cat_name))) ?

        green("Category created.") : red(FC::messages()->msgInsertFail);

    }

elseif(isset($_GET['reload_cats'])){

    echo FC::getClassInstance("Categories")->getCategoryOptions();

}













//-------------------------------View support tickets ----------------------------
if(isset($_GET['tickets'])){ ?>
    <a href="javascript:return false" onclick="preScreen(thisScreen, 'panelOptions')">
        <img src="../images/back.png" width="30px" alt="Back" height="25px" title="Back">
    </a>
    <h5>Support Tickets</h5>
    <div id='ticketLbl'></div>
    <?php
    $products = $db->getRows("SELECT * FROM `tickets`");
    ?>
    <table border=0 style='padding-top:20px;' cellpadding=3 cellspacing=3>
    <?php
    $i=0;
    foreach($products as $product){ ?>
        <tr onclick="togglePrompt('ticTr_<?php echo $i; ?>'); getIt('ticDet_<?php echo $i; ?>', 'getStuff.php?id=<?php echo $product['id']; ?>&ticDet');" id="row_<?php echo $i; ?>" class="row" style="padding:15px; line-height:25px; margin-top:2px;"><td width="700px" style="padding-left:20px; cursor:pointer;">Ticket ID: #<?php echo $product['id']; ?>&nbsp&nbsp<b><?php echo $product['subject']; ?></b>&nbsp<div style="float:right;">Ticket Status: <?php $status = $product['status']; echo $status; ?>&nbsp; <?php if(count_unread($product['id'])>0){ echo count_unread($product['id'])." <img src='../images/message.gif'>";} ?>&nbsp;
        <?php if($status=='Open'){ ?><a href="#" onclick="javascript:if(confirm('Are you sure you want to close this ticket.')==true){ getIt('ticketLbl', 'getPages/getStuff.php?id=<?php echo $product['id'];?>&close');}"><img src="../images/cancel.png" width="15px" height="15px" title="Close Ticket"></a><?php } else { ?><a href="#" onclick="javascript:if(confirm('Are you sure you want to close this ticket.')==true){ PromptClear('row_<?php echo $i; ?>'); getIt('ticketLbl', 'getStuff.php?id=<?php echo $product['id'];?>&delTicket');}"><img src="../images/cancel.png" width="15px" height="15px" title="Delete this ticket"></a><?php } ?></div></td></tr>
        <tr id="ticTr_<?php echo $i; ?>" style="display:none;"><td id="ticDet_<?php echo $i; ?>"></td></tr>
        <?php $i++;
    }
    echo "<table>";
}

if(isset($_GET['ticDet'])){
    $id=$_GET['id']; ?>
    <div id='ticTr'><table class="table" cellpadding=3 cellspacing=3>
    <?php
    $products = $db->getRows("SELECT * FROM `ticket_msg` WHERE `ticket_id`='$id'");
    $j=0;
    foreach($products as $product){ ?>
        <tr id="subTr_<?php echo $j; ?>">
            <td>
                <div id="Ticmsg">
                <?php    $unread = $db->getValue("SELECT COUNT(*) FROM  `ticket_msg` WHERE  `ticket_id`='$id' AND  `isRead`='0'");
                if( $unread > 0 ){
                    echo "<div id='subMsg_$j' style='font-weight:bold;'>".$product['msg']."</div>";
                }
                else{
                    echo $product['msg'];
                } ?>
                </div>
                <div class="left" style="float:left; width:60%; margin-top: 10px;">
                    From: <?php echo $product['id_user']; ?>&nbsp;&nbsp;
                    <a href="#" id="replyTic" onclick="replyTicket('<?php echo $id; ?>'); document.getElementById('subMsg_<?php echo $j; ?>').style.fontWeight='normal';"><img src="<?php echo SITEURL; ?>images/reply.png" width="18px" height="18px" title="Reply"></a>&nbsp;&nbsp;
                    <a href="#" onclick="if(confirm_delete('this message')==true){ PromptClear('subTr_<?php echo $j; ?>'); getIt('subTr_<?php echo $j; ?>', 'getStuff.php?id=<?php echo $product['id']; ?>&delReply');}"><img src="<?php echo SITEURL; ?>images/cancel.png" height="18px" width="18px" title="Delete"></a>
                    &nbsp;<a href="#" onclick="document.getElementById('subMsg_<?php echo $j; ?>').style.fontWeight='normal'; getIt('ticketLbl', 'getStuff.php?thisId=<?php echo $product['id']; ?>&markRead');"><img src="<?php echo SITEURL; ?>images/pen.png" height="18px" width="18px" title="Mark Read"></a>
                <?php if($product["file_name"]) { ?><a href='<?php echo SITEURL."uploads/".$product["file_name"]; ?>' download><img src="<?php echo SITEURL; ?>images/attachment.png" title="Download Attachment."></a><?php } ?></div>
                <div class="date text-right" style="float:left; width: 40%; margin-top: 10px;">
                    <?php echo $product['date']; ?>
                </div>
				<div class="base"></div>
            </td>
			
			<div class="clear"></div>
        </tr>
		
        <?php $j++;
    }
    echo "</table></div>";
}

if(isset($_GET['replyMyTicket'])){
    $thisId=$_GET['thisId'];
    $id=$_GET['id'];
    echo "Please enter the message.";
    ?>
    <div id="repTicLbl"></div>
    <textarea cols="70" rows="14" name="repTic" id="repTic"></textarea><br>
    <input type="button" value="Submit" onclick="javascript: var val=getDocValue('repTic'); if(val!=''){ getIt('repTicLbl', 'getStuff.php?ajax&repTic&id=<?php echo $id; ?>&msg='+val);} else{ getDoc('repTicLbl').innerHTML='<red>Please enter the message.</red>'; }">
    <?php $db->execute("update `ticket_msg` set `isRead`='1' where `id`='$thisId'");
}

if(isset($_GET['markRead'])){
    $thisId=$_GET['thisId'];
    $db->execute("update `ticket_msg` set `isRead`='1' where `id`='$thisId'");
}

if(isset($_GET['close'])){
    $id = $_GET['id'];
    $db->execute("UPDATE `tickets` SET `status`='Close' WHERE `id`='$id'");
}

if(isset($_GET['delReply'])){
    $id =  $_GET['id'];
    $db->execute("DELETE FROM `ticket_msg` WHERE `id`='$id' LIMIT 1");
}

if(isset($_GET['delTicket'])){
    $id = $_GET['id'];
    $db->execute("DELETE FROM `tickets` WHERE `id`='$id' LIMIT 1");
}
//---------------------------------user support tickets--------------------------------

if(isset($_GET['ticUser'])){
    $id=$_GET['id']; ?>
    <div id='ticTr'><table class="table" cellpadding=3 cellspacing=3>
    <?php
    $products = $db->getRows("SELECT * FROM `ticket_msg` WHERE `ticket_id`='$id'");
    $j=0;
    foreach($products as $product){ ?>
        <tr id="subTr_<?php echo $j; ?>">
            <td>
                <div id="Ticmsg">
                <?php    $unread = $db->getValue("SELECT COUNT(*) FROM  `ticket_msg` WHERE  `ticket_id`='$id' AND  `isRead`='0'");
                if( $unread > 0 ){
                    echo "<div id='subMsg_$j' style='font-weight:bold;'>".$product['msg']."</div>";
                }
                else{
                    echo $product['msg'];
                } ?>
                </div>
                <div class="left" style="float:left; width:60%; margin-top: 10px;">
                    From: <?php echo FC::getClassInstance("Users")->getName($product['id_user']); ?>&nbsp;&nbsp;
                    <a href="#" id="replyTic" onclick="replyTicket('<?php echo $id; ?>'); document.getElementById('subMsg_<?php echo $j; ?>').style.fontWeight='normal';"><img src="<?php echo SITEURL; ?>images/reply.png" width="18px" height="18px" title="Reply"></a>&nbsp;&nbsp;
                    &nbsp;<a href="#" onclick="document.getElementById('subMsg_<?php echo $j; ?>').style.fontWeight='normal'; getIt('ticketLbl', 'getStuff.php?thisId=<?php echo $product['id']; ?>&markRead');"><img src="<?php echo SITEURL; ?>images/pen.png" height="18px" width="18px" title="Mark Read"></a>
                &nbsp;<?php if($product["file_name"]) { ?><a href='<?php echo SITEURL."uploads/".$product["file_name"]; ?>' download><img src="<?php echo SITEURL; ?>images/attachment.png" title="Download Attachment."></a><?php } ?></div>
                <div class="date text-right" style="float:left; width: 40%; margin-top: 10px;">
                    <?php echo $product['date']; ?>
                </div>
				<div class="base"></div>
            </td>
			
			<div class="clear"></div>
        </tr>
		
        <?php $j++;
    }
    echo "</table></div>";
}

//---------------------------------Ending support tickets ---------------------------
if(isset($_GET['sendMsg1'])){
	$id_user = check_form($_GET['id_user']);
	$name = FC::getClassInstance("Users")->getName($id_user);
	$email = $db->getValue("SELECT `ad_email` FROM `user_pro` WHERE `id`='$id_user'"); ?>
	<div class="text-center bg_orange" style=" height: 60px; padding-top: 1px;"><h3>Send Message</h3></div>
	<div><b>Receiver: </b><?php echo $name; ?></div>
	<div id="msgLbl2"></div>
	<form onsubmit="javascript:return false">Subject:<br>
		<input type="text" id="subject" style="width: 100%;" size='50'><br>
		Enter the Message: <br>
		<textarea rows="10" cols="50" name="replyTicketT" style="width: 100%;" id="replyTicketT"></textarea><br>
		<div class="text-right"><br>
			<input type="button" value="Cancel" class="btn btn-default save-btn" onclick="clearPopup('send_msg')">
			<input type="submit" value="Send" class="btn btn-default save-btn" onclick="sendMail('<?php echo $email; ?>')">
		</div>
	</form> <?php
} 
if(isset($_GET['sendReply'])){
	$subject = check_form($_GET['subject']);
	$email = check_form($_GET['email']);
	$replyTicket = check_form($_GET['replyTicket']);
	$mails = FC::getClassInstance("Mail");
	$mails->to = $email;
	$mails->subject = $subject;
	$mails->message=$replyTicket;
	if($mails->sendMail()){
		echo "Your message has been mailed to ".$email;
	}
}

elseif( isset( $_GET['showMethod'] ) ) {
	$pay_type = $_GET['pay_type'];
	if( $pay_type=='2' ){ ?>
		<form action="<?php echo SITEURL.'creditcard.php'; ?>" method="post" id="payment-form">
			<div class="alert-danger payment-errors"></div>
			<?php FC::getInstance()->loadTemplate("creditcard"); ?>
			<div class="row">
				<div class="col-md-3 text-right"><b>Amount:</b></div>
				<div class="col-md-7">
					<input class="form-control rest" type="text" name="amount" id="amount" onkeypress="return numbersonly(this, event)">
					<div class="error" id="error_amount">Required, Please enter amount to deposit.</div><br>
				</div>
				<div class="col-md-10 text-right"><input type="submit" name="stripeToken" value="Deposit Now"  class="btn btn-default save-btn"></div>
			</div>
			</form>
		<?php
	}
	else { ?>
		<form action="<?php echo SITEURL.'deposit.php'; ?>" method="post">
			<div class="row">
				<div class="col-md-3 text-right"><b>Amount:</b></div>
				<div class="col-md-7">
					<input class="form-control rest" type="text" name="amount" id="amount" onkeypress="return numbersonly(this, event)">
					<div class="error" id="error_amount">Please enter amount.</div><br>
				</div>
                <div class="col-md-10 text-right"><input type="submit" name="depositFunds" value="Deposit Now" onclick="return validate_form([['amount','']]);" class="btn btn-default save-btn"></div>
			</div>
		</form>
		<?php
		
	}
}



elseif(isset($_GET['show_img_opt'])){
	$typeimg = Tools::getValue('type');
    $id_user = FC::getClassInstance("Session")->myId();
    $db = FC::getClassInstance("Db");
	if( $typeimg == 'from_db' ) {
		$imgs = $db->getRows("SELECT `post`, `description` FROM `site_posts` WHERE `id_user`='$id_user' AND `post_type`='image'");
		if ( $imgs ) {
			echo "<div class='img-view'>";
			foreach( $imgs as $img ){
				echo "<img src='".SITEURL."uploads/images/".$img['post']."' onclick='setSelectedImage(\"".$img['post']."\")'>";
			}
			echo "</div>";
		}
		else {
			echo "<lable>You have no previous images.</lable>";
		}
	}
    
}

elseif(isset($_GET['show_cat_pages'])){
	$cat_id = Tools::getValue('cat_id');
    $id_user = FC::getClassInstance("Session")->myId();
	$fbpg = FC::getClassInstance("FacebookPages");
	$fb = FC::getClassInstance("Fb");
	$pages = $fbpg->getPagesByCategory($id_user, $cat_id);
	echo "<table class='table table-responsive'>";
    foreach( $pages as $page ){
		$img_url = $fb->getProfileImage($page['page_id']);
		echo "<tr><td class='width30'><input type='checkbox' name='page_cats[]' value='".$page['page_id']."' onclick='excludePages(this)' checked></td>";
		echo "<td>".$page['page_link']."</td></tr>";
		//echo "<td class='page-img'><img src='".$img_url."'></td></tr>";
	}
	echo "<tr><td colspan=2 class='text-right'><input type='button' class='btn save-btn' value='Ok' onclick='showPageNames()'></td></tr>";
	echo "</table>";
}

elseif(isset($_GET['remove_cat_pages'])){
	$cat_id = Tools::getValue('cat_id');
    $id_user = FC::getClassInstance("Session")->myId();
	echo $id_user.$cat_id;
}

elseif(isset($_GET['show_pages_names'])){
	$pages = Tools::getValue('id_array');
	$pages = explode(",",$pages);
	$id_user = FC::getClassInstance("Session")->myId();
    $fbpg = FC::getClassInstance("FacebookPages");
	$names = "";
	for( $i=0; $i<count($pages); $i++ ){
		$names .= $fbpg->getPageName($id_user, $pages[$i]).", ";
	}
	echo $names;
}

elseif(isset($_GET['show_post_places'])){
	$id_post = Tools::getValue('id_post');
	$option = Tools::getValue('option');
	$fb = FC::getClassInstance("Fb");
	$pages = FC::getClassInstance("Posts")->getPostPlaces($id_post);
	if( $pages ){
		echo "<table class='table table-responsive'>";
		echo "<input type='hidden' value='".$option."' name='both_opts'>";
		echo "<input type='hidden' value='".$id_post."' name='id_post'>";
		foreach( $pages as $page ){
			$img_url = $fb->getProfileImage($page['post_place']);
			$id_fb = (empty($page['fb_id_posted'])) ? "0" : $page['fb_id_posted'] ;
			echo "<tr><td class='width30'><input type='checkbox' value='".$page['id']."___".$id_fb."' name='post_place[]' checked></td>";
			echo "<td>".$page['page_link']."</td></tr>";
			//echo "<td class='page-img'><img src='".$img_url."'></td></tr>";
		}
		echo "<tr><td colspan=2 class='text-right'><input type='submit' value='Remove' name='from_fb' class='btn save-btn'></td></tr>";
		echo "</table>";
	}
	else {
		echo "No more post pages are available.";
	}
}

elseif(isset($_GET['set_sub_categories'])){
	$cat_id = Tools::getValue('parent_id');
	$cat = FC::getClassInstance("Category");
	$subCats = $cat->getSubCategories($cat_id);
	if( $subCats ){
		foreach( $subCats as $sub ){
			echo "<option value='".$sub['id']."'>".$sub['name']."</option>";
		}
	}
	else {
		echo "<option value='0'>No sub category</option>";
	}
}

elseif(isset($_GET['show_ad_imgs'])){
	$cat_id = Tools::getValue('parent_cat');
	$sub_cat = Tools::getValue('sub_cat');
	$cat = FC::getClassInstance("Category");
	$images = $cat->getCategoryImages($cat_id, $sub_cat);
	if( $images ){
		$i = 0;
		echo "<div class='table-responsive'><table class='table'><tr>";
		foreach( $images as $img ){
			echo ($i%5==0) ? "</tr><tr>" : "";
			echo "<td><img class='img-responsive width100' src='".SITEURL."uploads/images/".$img['name']."'>";
			echo "<input type='checkbox' name='site_imgs[]' value='".$img['name']."' id='786".$img['id']."'>";
			echo "<label class='select_img' for='786".$img['id']."'>Select</label></td>";
			$i++;
		}
		echo "</tr></table></div>";
		echo "<div class='text-right'><input type='button' id='img_chosed' value='Choose' class='btn save-btn'></div>";
	}
	else {
		echo "No images available in this category.";
	}
}
elseif(isset($_GET['upload_dropzone_img'])){
    if(isset($_FILES["file"])){
	$Image = FC::getClass("Image", $_FILES["file"]);
	$data = $Image->uploadImage();
    echo json_encode(array("imgUrl"=>$Image->imgUrl));
    }
}
elseif(isset($_GET['upload_gallery_img'])){
    $data = array("success"=>false,"imgName"=>"");
   // echo "<pre>"; print_r($_POST);
    if(isset($_FILES["gallery_files"])){
	$Image = FC::getClass("Image", $_FILES["gallery_files"]);
	$data = $Image->processBasic();
    }
    echo json_encode($data);
}

elseif(isset($_GET['upload_gallery_video'])){
	$data = false;
	$output_dir = "uploads/videos/";
	if(isset($_FILES["gallery_files"])){
		//Filter the file types , if you want.
		if ($_FILES["gallery_files"]["error"] > 0){
			echo "Error: " .$_FILES["gallery_files"]["error"] . "";
		}
		else{
		    $filename = FC::getClass("ObjectPhp")->trimImageName( IMGPREFIX . $_FILES["gallery_files"]["name"] );
		    $file_path = $output_dir.$filename;
		    move_uploaded_file($_FILES["gallery_files"]["tmp_name"], $file_path);
		    //generating thumbnail..
		    $file_name = pathinfo($filename, PATHINFO_FILENAME);
		    $img_name = $file_name . ".jpg";
		    $img_path = PHOTODIR . $img_name;
		    $pics_needed = 3;
		    $cmd = "ffmpeg -i $file_path 2>&1";
		    if (preg_match('/Duration: ((\d+):(\d+):(\d+))/s', `$cmd`, $time)) {
			$total = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
		    }
		    $interval = $total / $pics_needed;
		    for($i=1; $i <= $pics_needed; $i++){
			$cmd = "ffmpeg -ss ".$i * $interval." -i $file_path -vf 'scale=640:360' -vframes 1 ".PHOTODIR . $file_name."$i.jpg";
			exec($cmd);
			$images[] = PHOTODIR . $file_name . $i . ".jpg";
		    }
		    $data = array("success"=>true,"filename"=>$filename, "images"=>$images);
		}
	}
    echo json_encode($data);
}

elseif(isset($_GET['fetchYTVideo'])){
    $url = urldecode(Tools::getValue("url"));
    $path = "/home/gocips/public_html/uploads/videos/";
    $res_file = exec('youtube-dl --get-filename -f mp4/ogg/webm/wav/ogv '.$url);
    $ext = pathinfo($res_file, PATHINFO_EXTENSION);
    $filename = rand(00000, 99999) . "_" . time() . "." . $ext;
    $res = exec('youtube-dl -o "'.$path . $filename . '" -k -f '.$ext.' '.$url);
    //generating thumbnail..
    $file_name = pathinfo($filename, PATHINFO_FILENAME);
    $img_name = $file_name . ".jpg";
    $img_path = PHOTODIR . $img_name;
    $pics_needed = 3;
    $file_path = "uploads/videos/".$filename;
    $cmd = "ffmpeg -i $file_path 2>&1";
    if (preg_match('/Duration: ((\d+):(\d+):(\d+))/s', `$cmd`, $time)) {
	$total = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
    }
    $interval = $total / $pics_needed;
    for($i=1; $i <= $pics_needed; $i++){
	$cmd = "ffmpeg -ss ".$i * $interval." -i $file_path -vf 'scale=640:360' -vframes 1 ".PHOTODIR . $file_name."$i.jpg";
	exec($cmd);
	$images[] = PHOTODIR . $file_name . $i . ".jpg";
    }
    $data = array("success"=>true,"filename"=>$filename, "images"=>$images);
    echo json_encode($data);
}

elseif(isset($_GET['save_thumbs'])){
    $img_name = Tools::getValue("img_name");
    $image = FC::getClass("Image")->processFromUrl(PHOTOURL . $img_name);
    $data = array("success"=>true, "thumb_path"=>$image['imgUrl'], "thumb_name"=>$image['imgName']);
    echo json_encode($data);
}

elseif(isset($_GET['img_url'])) {	
   $main_url = Tools::getValue('img_url');
   @$str = file_get_contents($main_url);
	// This Code Block is used to extract title
   if(strlen($str)>0) {
		$str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
		preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title);
	}
	// This Code block is used to extract description 
	$b =$main_url;
	@$url = parse_url( $b ) ;
	@$tags = get_meta_tags( $url['scheme'].'://'.$url['host'] );
	// This Code Block is used to extract any image 1st image of the webpage
	$dom = new domDocument;
	@$dom->loadHTML($str);
	$images = $dom->getElementsByTagName('img');
	foreach ($images as $image) {
		$l1=@parse_url($image->getAttribute('src'));
		if(isset($l1['scheme']) && $l1['scheme']) {
			$img[]=$image->getAttribute('src');
		}
	}
	// This Code Block is used to extract og:image which facebook extracts from webpage it is also considered 
	// the default image of the webpage
	$d = new DomDocument();
	@$d->loadHTML($str);
	$xp = new domxpath($d);
	foreach ($xp->query("//meta[@property='og:image']") as $el) {
		$l2=parse_url($el->getAttribute("content"));
		if(isset($l2['scheme'])) {
			$img2[]=$el->getAttribute("content");
		}
	}
?>
	
	<?php
	if(isset($img2)) {
	   echo "<img src='".$img2[0]."'><div>".$title[1]."</div>";
	}
	elseif(isset($img)) {
	  echo "<img src='".$img[0]."'><div>".$title[1]."</div>";
	}
	else {
		echo $main_url;
	}
	?>
	<?php
 
	exit();
}
elseif(Tools::isSubmit('getMethodInfo')){
    $id_processor = Tools::getValue("payment_method");
    $output = "No inforamtion available";
    if($id_processor){
        $processor = FC::getClass("Payment")->getProcessor($id_processor);
	if($processor['short_name'] != "bt"){
	    $output = "<div class='col-md-3'><label for='pm_email'>Email ID</label></div><div class='col-md-9'><input  class='form-control' type='text' name='pm_email' id='pm_email'></div>
	    <div class='error' id='error_pm_email'>Required, Please enter your email ID associated with your ".$processor['name']." account</div>";
	}
	elseif($processor['short_name'] == "bt"){
	    $output = "";
	    echo "<div class='col-md-3'><label for='country'>Select Country</label></div><div class='col-md-9'>
		<select class='form-control' id='country' name='country' onchange='changeCountry(this.value)'>";
		    print_countries();
	    echo "</select></div>";
	}
	echo $output;
    }
}
elseif(isset($_GET['changeCountry'])){
    $country = strtolower(urldecode(Tools::getValue('c')));
    echo "<div class='row form_element'><div class='col-md-3'><label for='b_name'>Bank Name</label></div>
	    <div class='col-md-9'><input class='form-control' name='b_name' id='b_name'></div>
    </div>";
    if($country == "united states"){
	echo "<div class='row form_element'><div class='col-md-3'><label for='swift'>Bank sort code/Routing Number</label></div>
	    <div class='col-md-9'><input type='text' class='form-control' name='swift' id='swift'>
		<div class='clear text-info'>The routing number is the first 9 digits of the number along the bottom left section of your checks.</div>
	    </div>
	    </div>
	    <div class='row form_element'><div class='col-md-3'><label for='iban'>Account number</label></div>
		<div class='col-md-9'><input type='text' class='form-control' name='iban' id='iban'></div></div>
	    <div class='row form_element'><div class='col-md-3'><label>Account type</label></div>
		<div class='col-md-9'>
		    <input type='radio' name='account_type' id='savings' value='savings'> <label for='savings'>Savings</label>
		    <span class='paddingleft'></span><input type='radio' name='account_type' id='checking' value='checking'> <label for='checking'>Checking</label>
		</div>
	    </div>
	    ";
    }
    elseif($country == "united kingdom"){
	echo "<div class='row form_element'><div class='col-md-3'><label for='swift'>Bank sort code</label></div>
	    <div class='col-md-9'><input class='form-control' name='swift' id='swift'></div></div>
	    <div class='row form_element'><div class='col-md-3'><label for='iban'>Account number</label></div>
	    <div class='col-md-9'><input type='text' class='form-control' name='iban' id='iban'></div></div>";
    }
    else{
	echo "<div class='row form_element'><div class='col-md-3'><label for='swift'>SWIFT</label></div>
	    <div class='col-md-9'><input class='form-control' name='swift' id='swift'></div></div>
	    <div class='row form_element'><div class='col-md-3'><label for='iban'>IBAN</label></div>
	    <div class='col-md-9'><input type='text' class='form-control' name='iban' id='iban'></div></div>";
    }
}
elseif(isset($_GET['getWEmail'])){
    $id_processor = Tools::getValue("w_meth");
    FC::getClass("Session")->session_getpages();
    $id_user = FC::getClass("Session")->myId();
    $success = false;
    $error = false;
    $summary = "";
    if($id_processor){
        $processor = FC::getClass("Payment")->getUserProcessor($id_user, $id_processor);
        if($processor){
	    $summary = ($processor['short_name'] == "bt") ? "Account No. "  : "Account ID: ";
	    $summary .= $processor['email'];
            $success = true;
        }else{
	    $msg = "We could not retrieve information about this payment method. Please try again later";
	}
    }
    else{
        $error = "Processor not found. Please contact our support";
    }
    echo json_encode(array("success"=>$success, "summary"=>$summary,"fee"=>$processor['fee'], "error"=>$error)); exit();
}
elseif(isset($_GET['show_post_detail'])){
	$id_post = Tools::getValue('id_post');
	$db = FC::getClassInstance("Db");
	$post = FC::getClassInstance("Posts");
	$postDetail = $post->getSinglePost($id_post);
	$cj = FC::getClass("Cj");
	$domains = $db->getRows("SELECT * FROM `domains` where id_user = 0 AND `active` =  1 order by `position` ASC");
	$id_user = FC::getClass("Session")->myId();
	$cDomains = false;
	$subdomain = "";
	$user_track = "";
	if($id_user){
	    $user_track = "u/$id_user/";
	    $cDomains = FC::getClass("Users")->getUserDomains($id_user);
	    //$subdomain = FC::getClass("Users")->getColumn($id_user, "ad_user");
	    //$subdomain = $subdomain . ".";
	}
	?>
	<form method="post" id="share_post_form">
		<h4 id="spf_post_title" class="marginbottom"><?php echo $postDetail['post_title']; ?></h4>
		<div class="row">
			<div class="col-md-3 gap2">
				<?php 
                $image_name = pathinfo($postDetail['cover_image'], PATHINFO_BASENAME);
                $path = pathinfo($postDetail['cover_image'], PATHINFO_DIRNAME);
                if($path != "") $path = $path."/";
                echo "<img class='site-img' src='".PHOTOURL . $path . "thumb_". $image_name ."'>"; ?>
			</div>
			<div class="col-md-9">
				<input type="hidden" id="id_post" value="<?php echo $id_post; ?>">
				<input type="hidden" id="post_slug" value="<?php echo $postDetail['slug']; ?>">
				
				<?php if($cDomains){ ?>
				    <div class="bold">Custom Domains</div>
					<div class="domain-list">
					    <?php foreach( $cDomains as $domain ) { ?>
						    <input type="radio" id="<?php echo $domain['id']; ?>" name="domain" onclick="generateLink()" data-track="<?php echo $user_track; ?>" value="<?php echo 'http://' . $domain['name']; ?>">
						    <label for="<?php echo $domain['id']; ?>"><?php echo $domain['name']; ?></label><br>
					    <?php } ?>
					</div>
					<div class="bordergap"></div>
				<?php } ?>
				<div class='bold'>Select a domain</div>
				<div class="domain-list">
				    <input type="radio" id="default" name="domain" onclick="generateLink()" data-track="<?php echo $user_track; ?>" value="<?php echo PROTOCOL . '://' . URLSIMPLE . '/'; ?>" checked>
				    <label for="default"><?php echo URLSIMPLE; ?></label><br>
				    <?php if( $domains ) {
					    foreach( $domains as $dom ) { ?>
						    <input type="radio" id="<?php echo $dom['id']; ?>" name="domain" onclick="generateLink()" data-track="<?php echo $user_track; ?>" value="<?php echo 'http://' . $dom['name'] . "/"; ?>">
						    <label for="<?php echo $dom['id']; ?>"><?php echo $dom['name']; ?></label><br>
					    <?php }
				    } ?>
				</div>
				<div class="bordergap gap2"></div>
				<?php if(!$cDomains){ echo "Need a custom domain? <a href='".SITEURL."contact/?subject=addon-domain'>Contact US</a>"; }?>
				
			</div>
		</div>
		<?php if(!$id_user){ ?>
		    <div class="text-danger bg-danger paddingleft">You are not logged in. <a href="<?php echo SITEURL ;?>login">Login</a> or <a href="<?php echo SITEURL ;?>signup">Create an account</a> to earn money for your shared links.</div>
		<?php } ?>
		<div class="bold">Copy the link and share it</div><input type="text" class="form-control" id="spf_link">
		<?php if($postDetail['post_type'] == 'video'){ ?><div class="bold gap2">Embedded Code</div><textarea class="form-control" id="spf_embed"></textarea><?php } ?>
		<?php if($id_user){ ?><div class="gap2 cjconnect">
		    <div id="cj_lbl"></div>
		    <span class="bold">Or add link to Queue for auto posting: </span><?php if($cj->getAccount($id_user)){
			    $queues = $cj->getQueues($id_user);
			    $queues = json_decode($queues);
			   // print_r($queues);
			    if($queues->success == "1"){
				if($queues->queues != "0"){
				    echo "<select id='cj_queue' class='form-control' style='display:inline-block;width:auto;'>";
				    foreach($queues->queues as $q){
					echo "<option value='".$q->id."'>" . $q->reference . "</option>";
				    }
				    echo "</select> &nbsp; <input type='button' class='btn btn-primary' value='Add' onclick='addToCjQueue()'> &nbsp;
				    <a href='http://cronjobz.com/queues' target='_blank'>View Queues</a>";
				}
				else{
				    echo "<div class='bg-warning paddingleft'>You don't have any saved queue. <a target='_blank' href='http://cronjobz.com/createqueue'>Create Now</a></div>";
				}
			    }else{
				echo $queues->error;
			    }
			}else{
			    echo "<div class='bg-info padding'>Connect CronJobz account to manage auto posting and schedules <a class='btn btn-primary' href='".SITEURL."gconnect/?redirect=explore'>Connect Now</a></div>";
			}
			
			?>
		    
		</div><?php } ?>
		<div class="bold gap2">Or click on icons bellow to quickly share it on your social media profile</div>
		<div id="share"></div>
	</form>
<?php
}

elseif(isset($_GET["social_share"])){
    $link = urldecode($_GET['link']);
    $desc = urldecode($_GET['desc']);
    echo "<div id='share'>" . FC::getClass("Social")->jsSocial($link, $desc) . "</div>";
}

elseif(isset($_GET['remove_post_img'])){
	$id_img = Tools::getValue('id_img');
	$db = FC::getClassInstance("Db");
	if(FC::getClass("Posts")->deleteGalleryImage($id_img)){ echo "success"; }
	else{ echo "error"; }
}
elseif(Tools::isSubmit("getSubCats")){
    $id_parent = Tools::getValue("id_parent");
    $subs = FC::getClassInstance("Category")->getSubCategories($id_parent);
    echo "<select id='sub_cats' name='sub_cats' class='form-control'>";
    echo "<option value=''>Select Sub Category (Optional)</option>";
    foreach($subs as $sub){
	echo "<option value='".$sub['id']."'>".$sub['name']."</option>";
    }
    echo "</select>";
}
elseif(Tools::isSubmit("get_stats")){
    $db = FC::getClassInstance("Db");
    $date = urldecode(Tools::getValue("date"));
    $domain = urldecode(Tools::getValue("domain"));
    if(preg_match("/_/",$date)){
	$dates = explode("_",$date);
	$date_query = "AND `date` BETWEEN '".$dates[0]." 00:00:00' AND '".$dates[1]." 23:59:59'";
    }
    else{
	$date_query = "AND `date` LIKE '%".$date."%'";
    }
    $type = Tools::getValue("type");
    if($type=="stats_countries"){
	//echo "<!-- SELECT COUNT(`id`) as visitors,`date`,`country` as country_code 
	//		FROM `views` where name = '$domain' $date_query group by `country` -->";
	$q = "SELECT COUNT(visitorss) as visitors, date, country FROM ( SELECT COUNT(`id`) as visitorss,`date`,`country` FROM `views`
	    where name = '$domain' $date_query and country != '' group by country, ip, id_post) as io group by country";
	$stat_countries = $db->getRows($q);
	$countries = decodeCountry();
	$scnt= array();
	if($stat_countries){
	    foreach($stat_countries as $sc){ $code = $sc['country'];
		if(!isset($countries[$code])) continue;
		$scnt[] = array("code"=>$code, "value"=>$sc['visitors'], "name"=>$countries[$code]);
	    }
	    echo json_encode($scnt);
	}
	else {echo json_encode(array("error"=>"Not engough stats to load countries.")); }
    }
    elseif($type == "analytics"){
	$rows = $db->getRows("select sum(`counts`) as visitors, tdate FROM (SELECT COUNT(DISTINCT(`ip`)) as counts, DATE(`date`) as tdate  FROM `views` WHERE `name` = '$domain' AND `country` != '' group by `id_post`, tdate) as pv group by tdate");
	if($rows){
	    $out = array();
	    foreach($rows as $row){
		$out[] = array($row['tdate'], (INT)$row['visitors']);
	    }
	    echo json_encode($out);
	}
	else { echo json_encode(array("error"=>"You don't have any published posts so far.")); }
    }
    elseif($type=="analytics_factors"){
	
    }
    exit();
}
elseif(Tools::isSubmit("statsranges")){
    /*$range = urldecode(Tools::getValue("range"));
    if(!preg_match("/-/",$range)){
	echo "<div class='alert alert-danger'>Invalid date range</div>";exit();
    }
    else{
	$ranges = explode("-", $range);
	if(checkdate(preg_replace("/\//",",",$ranges[0])) && checkdate(preg_replace("/\//",",",$ranges[1])){
	    $range1 = preg_replace("/\//","-",$ranges[1]);
	    $date_query = "AND `date` LIKE '%".$date."%'";
	}
	else{ echo "<div class='alert alert-danger'>Invalid date format</div>";exit(); }
    }
    */
}
?>
            <?php
            function time_elapsed_string($datetime, $full = false) {
                $now = new DateTime;
                $ago = new DateTime($datetime);
                $diff = $now->diff($ago);

                $diff->w = floor($diff->d / 7);
                $diff->d -= $diff->w * 7;

                $string = array(
                    'y' => 'year',
                    'm' => 'month',
                    'w' => 'week',
                    'd' => 'day',
                    'h' => 'hour',
                    'i' => 'minute',
                    's' => 'second',
                );
                foreach ($string as $k => &$v) {
                    if ($diff->$k) {
                        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                    } else {
                        unset($string[$k]);
                    }
                }

                if (!$full) $string = array_slice($string, 0, 1);
                return $string ? implode(', ', $string) . ' ago' : 'just now';
            }

            ?>
