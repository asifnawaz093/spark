<?php global $data; $word = "Sales"; ?>
<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
	<div class="breadcrumb clearfix">
            <div class="pull-left textorange"><h3>Dashboard</h3></div>
            <div class="pull-right">Navigation / <a href="<?php echo SITEURL; ?>dashboard">Dashboard</a></div>
        </div>
        <div id="contents">
            <?php FC::getInstance()->loadTemplate("alerts"); ?>
            <div class="bgwhite clearfix" id="block1">
                <div class="part1 part col-md-4">
                    <div class="padded borderright">
                        <div class="clearfix">
                            <div class="pull-left"><i class="ocicon-wallet ocicon fontsmall"></i></div>
                            <div class="pull-right">
                                <h4>Income</h4>
                            </div>
                        </div>
                        <div class="gap2 textcyan fontsmall"><?php echo Tools::price(98347392, CURRENCY); ?></div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
                <div class="part1 part col-md-4">
                    <div class="padded borderright">
                        <div class="clearfix">
                            <div class="pull-left"><i class="ocicon-ecommerce_money ocicon fontsmall"></i></div>
                            <div class="pull-right">
                                <h4>Last Sale</h4>
                            </div>
                        </div>
                        <div class="gap2 textorange fontsmall"><?php echo Tools::price(8472, CURRENCY); ?></div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
                <div class="part1 part col-md-4">
                    <div class="padded">
                        <div class="clearfix">
                            <div class="pull-left">
                                <i class="ocicon-ecommerce_sales ocicon fontsmall"></i>
                                <div class="fontsmall"><h4><?php echo "Sales";?>,<br> This Month</h4></div>
                            </div>
                            <div class="pull-right fontbig textpurple"><?php echo $this->sales; ?></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-purple" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
            </div> <!--/#block1-->
            <div id="btblock2" class="gap2 row">
                <div class="col-md-8">
                    <div class="padding bgwhite"><b>Date Range: </b>&nbsp;<input type="text" id="daterange" class="form-control" style="display: inline-block;width:220px;" name="daterange" value='<?php echo date("m/1/Y", time())." - ". date("m/d/Y", time()); ?>' /></div>
                    <div class="bgwhite padding" id="stats_earning" style="height: 330px;"></div>
                </div>
                <div class="col-md-4" style="padding-left: 0">
                    <div class="bgwhite padding" id="account-info">
                        <table class="table nomargin table-condensed">
                            <thead><tr><th colspan=2>Account Information</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td class="center">Status:</td>
                                    <td>
                                        <?php if(isset($_SESSION['subaccount'])){ echo "Sub Account"; }
                                        else{ echo $data['userStatus'][Session::user("status")]; } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="center">Email:</td>
                                    <td><?php echo Session::get("ad_user");?></td>
                                </tr>
                                <tr><td class="center">Last Login: </td><td><?php $lastlog = $this->last_login['time'];
                                if($lastlog && strtotime($lastlog) > 0){
                                    $lastlogin = date("F d, Y", strtotime($lastlog));
                                    echo $lastlogin; } ?></td></tr>
                                <tr><td class="center">Last IP: </td><td><?php echo $this->last_login['ip']; ?></td></tr>
                                <?php
                                    if(isset($this->plan['name'])){
                                        echo "<tr><td class='center'>Membership: </td><td>".$this->plan['name'];
                                        if($this->plan['id'] != 3){ echo " <a href='".SITEURL."buyplan'>Upgrade</a>";}
                                        echo "</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="padding bgpurple gap2" id="stats-info">
                        <h4><a class="textwhite" href="<?php echo SITEURL;?>transactions">Visit Statistics</a></h4>
                        <div class="row">
                            <div class="col-md-6 textwhite sales_stats"><div class="fontsmall marginbottom"><?php echo Tools::Price($this->sales_earning,Session::user('curr')); ?></div><?php echo date("F Y", time()); ?><br>(<?php echo $this->sales; ?> <?php echo $word;?>)</div>
                            <div class="col-md-6"><img src="<?php echo SITEURL?>/images/pie.png"></div>
                        </div>
                    </div>
                </div>
            </div> <!--/#btblock2-->
            <?php if($this->invoices){?>
            <div id="pending_transactions"  class="padding3 paddingtop bgwhite gap2 table-responsive">
                <h3>Pending Invoices</h3>
                <table class="table table-striped table-hover">
                    <thead><tr><th>Direction</th><th>Email ID</th><th>Amount</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($this->invoices as $value){ ?>
                        <tr>
                            <td><?php $dir  =  ($value['receiver'] == Session::user("ad_email")) ? "From" : "To"; echo $dir; ?></td>
                            <td><?php if($dir == "From"){echo Fc::getClass("Users")->get($value['sender'],"ad_email");}else{ echo $value['receiver'];} ?></td>
                            <td><span title="<?php echo Tools::Price($value['amount'], $value['currency']); ?>" data-toggle="tooltip"><?php echo Tools::Price($value['amount'], $value['currency'],2,true);?></span></td>
                            <td><span data-toggle="tooltip" title="<?php echo date("h:i:s A", strtotime($value['tdate'])); ?>"><i class="ocicon-clock3 ocicon"></i> <?php echo date("F d, Y", strtotime($value['tdate'])); ?></span></td>
                            <td>
                                <?php if($value['status'] == '0'){echo "<div class='label label-rounded label-info'>Pending</div>";}
                                elseif($value['status'] == '1'){echo "<div class='label label-rounded label-success'>Paid</div>";}
                                else{echo"<div class='label label-rounded label-danger'>Rejected</div>";} ?>
                            </td>
                            <td>
                                <?php if($value['status'] == 0 && $dir == "From"){
                                    ?><a href="send.php?pay&id=<?php echo $value['id']; ?>" class="label label-rounded label-success">Pay Now</a>
                                    <a href="send.php?cancel&id=<?php echo $value['id']; ?>" onclick="return confirm('Are you sure?')" class="label label-rounded label-danger">Reject</a>
                                <?php }else{ ?>
                                    <a href="send.php?cancel&id=<?php echo $value['id']; ?>" onclick="return confirm('Are you sure?')" class="label label-rounded label-danger">Cancel</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div> <!--/#pending_transactions-->
            <?php } ?>
            <div id="transactions" class="padding3 paddingtop bgwhite gap2 table-responsive">
                <div class="clearfix">
                    <div class="pull-left"><h3 class="margintop">Transactions</h3></div>
                    <div class="pull-right fontmini margintop"><a href="transactions">View All</a></div>
                </div>
            <?php if($this->transactions){ ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr><th>Reference #</th><th>Name</th><th>Date</th><!--<th>Amount</th><th>Fee</th>--><th>Net</th><!--<th>Net (DIR)</th>--><th>Type</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($this->transactions as $value){
                            $sign = ($value['sender'] == Session::get("id_user")) ? "-" : "+";
                            $dir = ($value['sender'] == Session::get("id_user")) ? "out" : "in";
							$name = "Customer";
                            if($dir == "out"){
								if($value['type']==2){//its a withdrawal request
                                    $value['nets'] = $value['amount'];
									$value['amount'] = $value['amount'] - $value['fees'];
                                }else{
									$value['fees'] = 0;
									$value['nets'] = $value['amount'];
								}
                            }else{
                                $value['nets'] = $value['amount'] - $value['fees'];
                            }
                            ?>
                            <tr>
                                <td><?php $ref_no = (!empty($value['transaction_id'])) ? $value['transaction_id'] : $value['id'];
                                echo "<a href='javascript:void(0)' onclick='loadTDetails(".$value['id'].")'>$ref_no</a>"; ?></td>
                                <td><?php echo ucfirst($name); ?></td>
                                <td><span data-toggle="tooltip" title="<?php echo date("h:i:s A", strtotime($value['tdate'])); ?>"><i class="ocicon-clock3 ocicon"></i> <?php echo date("M d, Y", strtotime($value['tdate'])); ?></span></td>
                                <td><span title="<?php echo $sign. Tools::price($value['nets'], CURRENCY)?>" data-toggle="tooltip"><?php echo $sign . Tools::price($value['nets'], CURRENCY,2,true);?></span></td>
                                <td>
                                    <?php echo $this->data['TransactionType'][$value['type']]; ?>
                                    <?php if($value['type'] == 4){ ?><span title="You can't withdraw signup bonus. You can transfer this moeny to someone or use it to shop online." data-toggle="tooltip"><i class="glyphicon glyphicon-info"></i></span><?php } ?>
                                </td>
                                <td><?php echo $this->data['TransactionStatus'][$value['status']];?><?php if($value['comments']){ echo " <span class='glyphicon glyphicon-info-sign' data-toggle='tooltip' title='".$value['error'] . " " . $value['comments'] ."'></span>"; } ?></td>
								
                            </tr>
                        
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            </div> <!--/#transactions-->
        </div> <!--/#contents-->
    </div>
</div>
<div class="gap3"></div>
<div class="modal fade" id="transaction_detail" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalLabel">Transaction Detail</h4>
      </div>
      <div class="modal-body" id="tcontents">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    function loadTDetails(id) {
        $("#transaction_detail").modal();
        $("#tcontents").html("<div class='alert alert-info'>Loading, Please Wait.. " +loader+ "</div>");
        $.ajax({
            url: "<?php echo SITEURL;?>getStuff.php",
            data: {loadTDetails:true,id:id},
            type: "post",
            dataType: "html",
            success: function(data){
                $('#tcontents').html(data);
            },
            error: function(){ $('#tcontents').html("Something went wrong. Please try again later"); },
        });
    }
    $(function () {
        $('#daterange').daterangepicker({ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
	"alwaysShowCalendars": true
	});
        $("#daterange").on("apply.daterangepicker",function(ev,picker){
            start = picker.startDate.format('YYYY-MM-DD');
            end = picker.endDate.format('YYYY-MM-DD');
            $('.sales_stats').html("Loading...");
             $.ajax({
                url: "<?php echo SITEURL;?>getStuff.php",
                data: {start:start,end:end,stat_sales:true},
                type: "post",
                dataType: "html",
                success: function(data){
                    $('.sales_stats').html(data);
                },
                error: function(){ $('.sales_stats').html("Something went wrong. Please try again later"); },
            });
             $.getJSON("<?php echo SITEURL ;?>getStuff.php?stats_earning&start="+start+"&end="+end, function (edata) {
                composeChart(edata);
            });
        });

    $.getJSON("<?php echo SITEURL ;?>getStuff.php?stats_earning", function (edata) {
       composeChart(edata);
   });
 
 });
    function composeChart(edata){
        if (!edata || typeof edata === "undefined" || edata.error) {
            $("#stats_earning").html("No history was found in selected date range");
            return false;
        }
        e_data=[];
       for (d in edata) {
           e_data.push( [Date.parse(edata[d][0]), edata[d][1]] );
		   console.log(Date.parse(edata[d][0]));
       }
	   
        $('#stats_earning').highcharts({chart: {zoomType: 'x'},
            title:"<?php echo $word; ?>",
            //subtitle: { text: document.ontouchstart === undefined ?
            //    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            //},
            credits: {
                enabled: false
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: '<?php echo $word; ?>'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 5
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'area',
                name: '<?php echo $word . " " . Session::user("currency"); ?>',
                data: e_data
            }]
        });
    }
</script>