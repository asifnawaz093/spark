$(document).ready(function(){
                    $('#from_db').click(function(){
                                promptPopup('add_file');
                                $.ajax({
                                        type: "GET",
                                        url: SITEURL+"getStuff/?ajax&show_img_opt&type=from_db",
                                        dataType: "html",
                                        success: function(data){
                                               $('#options_imgs').html(data);
                                        }
                                });
                    });
                    
                    $('#menu1div-head').click(function(){
                        $('.menu1-item').slideToggle();
                    });
		    $('#menu1div-head1').click(function(){
                        $('.menu1-item1').slideToggle();
                    });
		    $('#menu2div-head').click(function(){
                        $('.menu3dmega2w').slideToggle();
                    });
		    $('#menu3div-head').click(function(){
                        $('#menu2-item').slideToggle();
                    });
        /*$( "#datepicker" ).datepicker({
                dateFormat: "yy-mm-dd"
        });
        $( "#docdate" ).datepicker({
                dateFormat: "yy-mm-dd"
        });
        $( "#oc_date" ).datepicker({
                dateFormat: "yy-mm-dd"
        });
        $( "#dateat" ).datepicker({
                dateFormat: "yy-mm-dd",
                yearRange: "1950:2015",
                changeYear: true
        });
        */
        $('.showmore span').click(function(){
                $('.time_contain').css({
                        'max-height'    : 'inherit',
                        'overflow'      : 'inherit'
                });
                $('.showmore').css({
                        'display'       :'none'
                });
        });
        
    });

function setSelectedImage(imgName) {
                    $('#from_pr').val(imgName);
                    $('.fileUpload').hide();
                    $('#uploadFile').val(imgName);
                    clearPopup('add_file');
}

function newslaterfunc()
{
	$('#div_error').hide();
	$('#div_success').hide();
	var email = $('#fo_mail').val();
	var chek = true;
	//chek = validate_form(['newsemail', 'email']);
	if (chek) {
	$.ajax({
		type: "GET",
		url: SITEURL+"getStuff/?ajax&newslatterem&email="+email,
		dataType: "text",
		success: function(data){
		   if(data == 0)
		   {
			$('#div_error').show();
			$('#div_error').text("Already Exist.");
		   }
		   else
		   {
			$('#div_success').show();
			$('#div_success').text("Thanks For subscription.");
		   }
		}
	    });
	}
	else
	{
	    
	}
}
function popupTD(idslot, stime, cdate) {
        var s_login = document.getElementById("s_login").value;
        if ( s_login == 1 ) {
                promptPopup('idpop');
                document.getElementById("book_date").innerHTML = cdate;
                document.getElementById("start_time").innerHTML = stime;
                document.getElementById("book_t").value = stime;
                document.getElementById("book_d").value = cdate;
                document.getElementById("id_slot").value = idslot;
        }
        else {
                promptPopup('s_log_form');
        }
}

function calcRate() {
        if ($('#call_type').val()=='' || $('#int_area').val()=='' || $('#t_duration').val()=='') {
                $('#b_charges').html("$0.00");
                $('#t_charges').val('');
        }
        else{
                var call_type = $('#call_type').val();
                var int_area = $('#int_area').val();
                var t_duration = $('#t_duration').val();
                $.ajax({
                        type: "GET",
                        url: SITEURL+"getStuff/?ajax&calc_rate&call_type="+call_type+"&int_area="+int_area+"&t_duration="+t_duration,
                        dataType: "html",
                        success: function(data){
                               $('#b_charges').html('$'+data);
                               $('#t_charges').val(data);
                        }
                });
        }
}

function setDuration() {
        if ($('#call_type').val()=='site') {
                $('#t_duration option[value!=""]').remove();
                $('#t_duration').append('<option value="2 hour">2 Hour</option>');
                $('#t_duration').append('<option value="3 hour">3 Hour</option>');
                $('#t_duration').append('<option value="4 hour">4 Hour</option>');
                $('#t_duration').append('<option value="8 hour">8 Hour</option>');
        }
        else {
                $('#t_duration option[value!=""]').remove();
                $('#t_duration').append('<option value="30 minutes">30 Minutes</option>');
                $('#t_duration').append('<option value="1 hour">1 Hour</option>');
        }
}

//in replying to support ticket
function replyTicket2(em, subject){
	if(isEmpty('replyTicketT')==true){
		var replyTicket = document.getElementById('replyTicketT').value;
		getIt('replyLbl', 'getStuff.php?saveReply&replyTicket='+replyTicket+'&email='+em+'&subject='+subject);
		
	} else{ lblMsg('replyTicketT', 'replyLbl', 'Required, Please enter the message.');}
}


//posting the ticket

function postTicket(){
	if(isEmpty('dep')==true){
	if(isEmpty('subject')==true){
	if(isEmpty('message')==true){
		var scData = getScreenValues('ticketTable'); //alert(scData);
		getIt('ticketLbl', 'getPages/getStuff.php?'+scData+'&post_ticket');
		$("#ticketTable").slideUp(300);
		
	} else{lblMsg('message', 'ticketLbl', 'Required: Please enter the message.');}
	} else{lblMsg('subject', 'ticketLbl', 'Required: Please Select the Subject.');}
	} else{lblMsg('dep', 'ticketLbl', 'Required: Please Select the department.');}
}


function togglePrompt(div){
	
	if(document.getElementById(div).style.display == 'none'){ showPrompt(div); } else{ PromptClear(div); }
}



function replyTicket(id) {
        promptPopup('popTable');
        document.getElementById("ticket_id").value = id;
}

function messageDetail(msg) {
        promptPopup('msg_detail');
        document.getElementById("detail").innerHTML = msg;
        
}


function clProfileUpdate() {
        var error = true;
        error = validate_form([['first_n',''], ['last_n', ''], ['bus_n', ''], ['contact', '']]);
        if ($('#language').val()==0) {
                showPrompt('error_language');
                error = false;
        }
        else{
                PromptClear('error_language');
        }
        if ( error ) {
                return true;
        }
        else {
                return false;
        }
}
//send mail from aaaaa
function sendMail(em){
	if(isEmpty('subject')==true){
        if(isEmpty('replyTicketT')==true){
        	var replyTicket = document.getElementById('replyTicketT').value;
        	var subject = document.getElementById('subject').value;
        	getIt('msgLbl2', 'getStuff.php?ajax&sendReply&replyTicket='+replyTicket+'&email='+em+'&subject='+subject);
		
        }
        else{
                document.getElementById('msgLbl2').innerHTML = "Message is required.";
                //lblMsg('replyTicketT', 'msgLbl2', 'Required, Please enter the message.');
        }
	}
    else{
        document.getElementById('msgLbl2').innerHTML = "Subject is required.";
        //lblMsg('subject', 'msgLbl2', 'Required, Please enter the Subject.');
        }
}

function msgSend() {
        promptPopup('send_msg');
}

function colorStar( stars ) {
        for (var i=1; i<=stars; i++) {
                $("#star"+i).css('background-image','url('+SITEURL+'./images/rate-btn2-hover.png)');
        }
}

function emptyStar() {
        for (var i=1; i<=5; i++) {
                $("#star"+i).css('background-image','url('+SITEURL+'./images/rate-btn2.png)');
        }
}

function rateValue( stars ) {
        $('#rate').val( stars );
        for (var i=1; i<=5; i++) {
                $("#star"+i).removeAttr("onmouseout");
                $("#star"+i).removeAttr("onmouseover");
        }
}

function rateValidation(){
        var chek = true;
        if($('#rate').val()==0){
                showPrompt('error_rate');
                chek = false;
        }
        else {
                PromptClear('error_rate');
        }
        if($('#feedback').val()==''){
                showPrompt('error_feedback');
                chek = false;
        }
        else {
                PromptClear('error_feedback');
        }
        return chek;
}

function addCommission() {
        var chek = true;
        var comm = $('#def_com').val();
        chek = validate_form([['def_com','']]);
        if ( chek==true && comm<100 ) {
                PromptClear('error_def_com');
                return true;
        }
        else{
                showPrompt('error_def_com');
                return false;
        }
}

function validateWithdrawal() {
        var chek = true;
        chek = validate_form([['amount',''], ['id_paypal', 'email']]);
        var amnt = $('#amount').val();
        var oc_min_withdraw = $('#oc_min_withdraw').val();
        if (amnt < oc_min_withdraw) {
            showPrompt('error_amount');
            chek = false;
        }
        else {
            PromptClear('error_amount');
        }
        return chek;
}

function validateMethod() {
        if ($("#paypal").is(':checked')) {
                return true;
        }
        else {
                alert("Only paypal is available");
                return false;
        }
}

function startTimer(mints) {
        document.getElementById("timer").innerHTML = "";
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var h = date.getHours();
        var m = date.getMinutes();
        var mins = m + mints%60;
        var hours = h + parseInt(mints/60);
        var reserv = new Date(y,m,d,hours,mins);
        // YYYY/MM/DD hh:mm:ss
        $("#timer").countdown(reserv, function(event) {
                $(this).text(
                        event.strftime('%H:%M:%S')
                );
        });
	milisec = mints * 60 * 1000;
	setTimeout(function(){
		alert("You call time is over.");
		url = $("#endcall_link").attr("href");
		window.location = url;
		}, milisec)
}

function loadChat(id_project) {
	setTimeout(function(){
        $.ajax({
                type: "GET",
                url: SITEURL+"getStuff/?ajax&load_chat&id_project="+id_project,
                dataType: "html",
                success: function(data){
                       $('#chat_div').html(data);
                }
        });
	loadChat(id_project);
	}, 3000);
}

function sendChatMsg(id_project) {
        var msg = $('#chat_msg').val();
        if (msg != "") {
                document.getElementById("chat_msg").value = "";
                $.ajax({
                        type: "GET",
                        url: SITEURL+"getStuff/?ajax&send_chat&id_project="+id_project+"&msg="+msg,
                        dataType: "html"
                });
                loadChat(id_project);
        }
}

function showAlert() {
        alert("hello");
}

function validateTicket() {
        var chek = true;
        chek = validate_form([['t_subject',''], ['t_message', '']]);
        if ($('#t_subject').val().length > 99) {
                showPrompt('error_t_subject');
                chek = false;
        }
        return chek;
}

function validateSettings() {
	var error = true;
	error = validate_form([['int_pay',''], ['int_min', '']]);

}

function showProjects() {
        $('.project-body').css({
                        'height'        : 'inherit',
                        'overflow'      : 'inherit'
        });
        $('.seemore').css({
                        'display'        : 'none'
        });
}

function validCreditCard() {
        return validate_form([['datepicker',''], ['amount', ''], ['credit_crd', '']]);
}

function calculateRate(){
        var time = $('#duration').val();
	var type = $("#type").val();
	if (type=="" || time == "") {
		//requireField("type");
		return false;
	}
        $.ajax({
                type: "GET",
                url: SITEURL+"getStuff/?ajax&calculateRate&time="+time+"&type="+type,
                dataType: "html",
		cache: false,
                success: function(data){
                       $('#rate-div').html(data);
                }
        });
}

function selectLanguage() {
        if ($('#t_lang').val()=='') {
                showPrompt("error_t_lang");
                return false;
        }
        else{
                return true;
        }
}

function showMethod(){
        var type = $("#pay_type").val();
        if (type == 1) {
		$("#d-creditcard").fadeOut(300, function(){
			$("#d-paypal").slideDown(300);
		});
	}
	else if(type==2){
		$("#d-paypal").slideUp(300, function(){
			$("#d-creditcard").fadeIn(300);
		});
	}
	return false;
}

function fullScreen() {
        $('.video_call .clientsession').addClass("full_screen");
        $('.video_call .mysession').addClass("right_screen");
        $('#full_screen').css({
                'display'       :'none'
        });
        $('#exit_full_screen').css({
                'display'       :'inline-block'
        });
        return false;
}
function exitFullScreen() {
        $('.video_call .clientsession').removeClass("full_screen");
        $('.video_call .mysession').removeClass("right_screen");
        $('#full_screen').css({
                'display'       :'inline-block'
        });
        $('#exit_full_screen').css({
                'display'       :'none'
        });
        return false;
}
function validateContact() {
	return validate_form([['name',''], ['email', 'email'], ['message', '']]);
}
function showFile1() {
        $("#uploadFile1").val($("#uploadBtn1").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile2() {
        $("#uploadFile2").val($("#uploadBtn2").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile3() {
        $("#uploadFile3").val($("#uploadBtn3").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile4() {
        $("#uploadFile4").val($("#uploadBtn4").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile5() {
        $("#uploadFile5").val($("#uploadBtn5").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile6() {
        $("#uploadFile6").val($("#uploadBtn6").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile7() {
        $("#uploadFile7").val($("#uploadBtn7").val().replace(/C:\\fakepath\\/i, ''));
}
function showFile8() {
        $("#uploadFile8").val($("#uploadBtn8").val().replace(/C:\\fakepath\\/i, ''));
}

function validateDocument() {
	return validate_form([['fname',''], ['lname',''], ['email', 'email'], ['phone', '']]);
}

$(document).ready(function() {
    $('#selecctall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.intertime').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.intertime').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
    
});