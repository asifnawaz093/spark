var keylist="abcdefghijklmnopqrstuvwxyz123456789"; var temp='';
var call_picked = false;
var timeOutOnlineStatus = 4000;
var timeOutRecallGap	= 3000;
var timeOutRecall	= 13000;
var timeOutCallPicked	= 5000;
var timeOutCallDiv	= 5000;
//ACTIVE_TIME_LIMIT	= ACTIVE_TIME_LIMIT; //10000;
CALL_INTERVAL		= 5000; //4000 //check if the call is comming

var TORecallTimer;
var TORecall;
var reCallLimits	= 2;
var isCallDivActive	= false;
var snd			= true;
var invokedCallPicked 	= false;
var ivokedMakeCall	= false;
var invokedReCallTimer	= false;
var loader = "<img src='"+SITEURL+"images/loader.gif'>";
function generatepass(){ temp=''; for (i=0;i<9;i++) temp+=keylist.charAt(Math.floor(Math.random()*keylist.length)); return temp; }
function call(){ document.getElementById('act_code').value = generatepass(); }function confirm_delete(stuff){	var del = confirm("Are you sure you want to delete " + stuff); if(del==true){ return true;} else{ return false;}}
function check_all(field){
	var checkboxes = document.getElementsByName(field);
	for(var i=0; i<checkboxes.length;i++){
		if(getDoc("check_all").checked)
			checkboxes[i].checked=true;
		else
			checkboxes[i].checked=false;
	}
}
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }      
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function rescuefieldvalues(idarray){ for (var i=0; i<idarray.length; i++){ var el=document.getElementById(idarray[i]); if (/(password)/.test(el.type)){ continue; } if (el.addBehavior && !window.sessionStorage){  el.style.behavior='url(#default#userData)'; el.load("userentereddata"); } var persisteddata=(window.sessionStorage)? sessionStorage[idarray[i]+'data'] : (el.addBehavior)? el.getAttribute('dataattr') : null;  if (persisteddata){ el.value=persisteddata; }
		el.onkeyup=function(){
			if (window.sessionStorage)
				sessionStorage[this.id+'data']=this.value
			else if (this.addBehavior){
				this.setAttribute("dataattr", this.value)
				this.save("userentereddata")
			}
		} //onkeyup
	} //for loop
}
function getURLParameters(index){ var sURL = window.document.URL.toString();
	if (sURL.indexOf("?") > 0){ var arrParams = sURL.split("?"); var arrURLParams = arrParams[1].split("&");
		var arrParamNames = new Array(arrURLParams.length); var arrParamValues = new Array(arrURLParams.length);
		var i = 0; for (i=0;i<arrURLParams.length;i++) { var sParam =  arrURLParams[i].split("="); arrParamNames[i] = sParam[0];
			if (sParam[1] != "") arrParamValues[i] = unescape(sParam[1]);
			else arrParamValues[i] = "No Value"; }
		for (i=0;i<arrURLParams.length;i++){
			if(arrParamNames[i]==index) return arrParamNames[i]+"="+ arrParamValues[i]; } }
}
function numbersonly(myfield, e, dec){ 	//activate the function by putting this statement in the field code, onkeypress = "return numbersonly(this, event)"
var key;var keychar;if (window.event)    key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);

// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if (((".0123456789").indexOf(keychar) > -1))
   return true;

// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;
}


//=================================VALIDATIONS===============================
function login(){
	scData = getScreenValues('loginTable');
	$("#loginError").html("Checking your login details " + loader);
	$.ajax({
		url: SITEURL+'getStuff/?login&ajax&'+scData,
		type: "get",
		dataType: "json",
		cache: false,
		success: function(data){
			if (data.success) {
				$("#loginError").html("<div class='alert alert-success'>Login Successful, Redirecting...</div>");
				if($("#redirect").length > 0 && $("#redirect").val() !="" )
				{
					var url = $("#redirect").val();
					window.location = url;
				}else{
					window.location = SITEURL+data.page;
				}
			}
			else{
				$("#loginError").html("<div class='alert alert-danger'>"+data.msg+"</div>");
			}
		},
		error: function(a,e,i){ $("#loginError").html("Something went wrong, please try again later." + a + e + i); }
	});
	return false;
}
function recoverPassword(){ if(!validate_email("rec_ad_email")){ $("#forgetPTable #rec_ad_email").focus();
$("#forgetPTable #error_rec_ad_email").show(); return false;} scData = getScreenValues('forgetPTable'); getIt('recError', SITEURL + 'forgetp.php?ajax&'+scData); return false; }
function showPrompt(div){ document.getElementById(div).style.display = 'block'; }
function showPopup(div){ $('body').append('<div id="fade"></div>'); $('#fade').fadeIn(700); $("#"+div).fadeIn(200); }
function popupClear(div){ $('#fade').fadeOut(700); $('#'+div).fadeOut(600); $('#fade').remove(); }
function showDiv(div){ 	document.getElementById(div).style.display = ''; }
function PromptClear(div){ document.getElementById(div).style.display = 'none';  }
function preScreen(hideThis, showThis){
	document.getElementById(hideThis).style.display = 'none';
	document.getElementById(showThis).style.display = ''; thisScreen = showThis; }
function preScreen2(hideThis, showThis){
	document.getElementById(hideThis).style.display = 'none';
	document.getElementById(showThis).style.display = '';
    }function getDoc(id){ if(id) return document.getElementById(id); }
    function getDocValue(id){ if(id) return document.getElementById(id).value; }function getDocInner(id,txt){ return document.getElementById(id).innerHTML=txt; }function errShow(id){getDoc('error_'+id).style.display='block'; err=true;}function errClear(id){getDoc('error_'+id).style.display='none';}
    function validateUsername(){ var v=getDocValue('ad_user');getDocInner('error_ad_user','Checking availability..'); getDoc('error_ad_user').style.display="block";    getIt('error_ad_user', SITEURL+'getStuff/?ajax&validate_username='+v);}function fadeWhite(div){  $("#"+div).append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>");$('#fadeWhite').fadeIn('slow');}function fadeWhiteClear(){ $('#fadeWhite').fadeOut('slow');}function validateCompleteForm(arr){ var err = false; for(var i=0;i<arr.length; i++){ var ele = document.getElementById(arr[i]); if(ele.value==""){ getDoc('error_'+arr[i]).style.display='block'; err=true; } else{getDoc('error_'+arr[i]).style.display='none';} } if(err){ return false; } else return true; }function isEmpty(id){ if(document.getElementById(id).value == '') return false; else return true; }function valiPh(id, msg){ var field = document.getElementById(id); if((field.value.length==0)||(field.value.length!=10)){ document.getElementById('errorDiv').innerHTML = msg; } else{ document.getElementById('errorDiv').innerHTML = ""; return true; } }function validate_empty(id){ if(getDocValue(id)!=""){ return true; } else{ return false; } }
	function validate_confirmPassword(pass,cpass){
		if(getDocValue(pass)==getDocValue(cpass)){
			return true;
		}
		else{
			return false;
		}
	}
function number_format(number, decimals, dec_point, thousands_sep) {
 number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

function isUnique(v, c, callback){
	$('#validate_'+c).html("Checking availability " + loader);
	$.ajax({
		url: SITEURL+'getStuff/?isUnique&v='+encodeURIComponent(v)+'&c='+encodeURIComponent(c),
		type: "get",
		dataType: "json",
		success: function(data){
			if (data.success) {
				if(callback) callback();
			}
			$('#validate_'+c).html(data.msg);
		},
		error: function(){ $('#validate_'+c).html("Something went wrong, please try again later."); }
	});
}
function gcEmailAction() {
	if (validate_email("email")) {
		email = $("#email").val();
		$("#error_email").css("display","none");
		isUnique(email, 'ad_email');
		
	}else{
		$("#error_email").css("display","block");
	}
}
function updateProP(){ 
	scData = getScreenValues('changepass'); 
	getIt('cContent', SITEURL+'getStuff.php?cpass'+scData+'&ajax');
	return false;
}
function promptPopup(id, solid){
	$("#"+id).css("position","fixed");
	pop_position = $("#"+id).position();
	$("#"+id).fadeIn('slow');
	//$("#"+id).css("position","absolute");
	$("#"+id).css("top", (Number(pop_position.top) + Number(50) ) +"px");
	if (solid) {
		$('body').append("<div id='fade'></div>");
	}
	else{
		$('body').append("<div id='fade' onclick=clearPopup('"+id+"')></div>");
	}
	//closeImg(id);
}
function displayPopup(id){
	$("body").append("<div id='"+id+"' class='up_logos' style='display:none;'></div>");
	promptPopup(id);
}
function wait(msg) {
	$("body").append("<div style='display:none;' class='up_logos' id='wait_msg'></div>");
	$('body').append("<div id='upFade' style='display:none'></div>");
	$("#upFade").fadeIn('slow');
	$("#wait_msg").html("<h1 id='wait_msg_txt' style='text-align:center; font-size:20px;color:#222; line-height:50px;'>"+msg
			 +"</h1>");
	$("#wait_msg").fadeIn('slow');
}
function endWait(){
	$("#upFade").fadeOut('slow');
	$("#wait_msg").fadeOut('slow');
}
function waitImg(msg, div) {
	
	append1 = "<div style='display:none;background:none;z-index:80000; border:none;' class='up_logos' id='wait_msg'></div>";
	append2 = "<div id='upFade' style='display:none;z-index:70000; opacity:0.8'></div>";
	if (div) {
		$(div).css("position","relative");
		$(div).append(append1);
		$(div).append(append2);
		$("#upFade").css("position","absolute");
		$(".up_logos").css("position","absolute");
	
	}
	else{
		$("body").append(append1);
		$("body").append(append2);
	}
	$("#upFade").fadeIn('slow');
	$("#wait_msg").html("<p style='text-align:center;'><div id='wait_msg_img'><img src="+SITEURL+"images/ajax-loader.gif></div><h1 style='font-size:30px;color:#222; line-height:50px;' id='wait_msg_txt'>"+msg
			 +"</h1><p>");
	$("#wait_msg").fadeIn('slow');
}
function clearPopup(id){
	$('#fade').each(function(){ $(this).remove(); });
	$("#"+id).fadeOut('slow'); }
function changeWaitText(msg){
	$("#wait_msg_txt").html(msg);
}
function finishWaitImg(){
	$("#wait_msg_img").html('');
}
	function validate_numeric(id){
		var num = getDoc(id);
		var pattern = /^([0-9.]+)$/;
		return pattern.test(num.value);
	}
	function validate_alpha(id){
		var num = getDoc(id);
		var pattern = /^([a-zA-Z ]+)$/;
		return pattern.test(num.value);
	}
	function validate_password(id){
		if(getDocValue(id).length>5){
			return true;
		}
		else{
			return false;
		}
	}
	function validate_email(id){
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var address = getDocValue(id);
		return reg.test(address);
	}
function validate_entity(entities){
	switch(entities[1]){
		
		case 'alpha':
			return validate_alpha(entities[0]);
		break;
		case 'password':
			return validate_password(entities[0]);
		break;
		case 'email':
			return validate_email(entities[0]);
		break;
		case 'numeric':
			return validate_numeric(entities[0]);
		break;
		default:
		return validate_empty(entities[0]);
	}
}
//onclick = "retrun validate_form([['first_name','alpha'], ['last_name', ''], ['email', 'email']]);"
function validate_form(entities){
	var error = false;
	for(var i=0; i<entities.length; i++) {
		if(validate_entity(entities[i])) {
			PromptClear('error_'+entities[i][0]);
		}
		else {
			showPrompt('error_'+entities[i][0]);
			error = true;
		}
	}
	if(error) return false;
	else return true;
}


function validate_inputs(entities){
	var error = false;
	for(var i=0; i<entities.length; i++){
		if(validate_entity(entities[i])){
			actionMissingFieldOkay(entities[i][0]);
		}
		else{
			actionMissingField(entities[i][0]);
			error = true;
		}
	}
	if(error)
		return false;
	else
		return true;
}


function user_pro_submit(){ if(validate_form([['name','alpha'],['ad_email','email'],['ad_pwd','password']])){
	var scData = getScreenValues('user_pro_tab'); getIt("pageLbl","getStuff.php?ajax&join&"+scData); } }

function cpass(){ 
	scData = getScreenValues('changepass');
	getIt('lblDiv', 'getStuff.php?ajax&cpass&'+scData);
	return false;
}


function isAllChecked(name){
	var checks = document.getElementsByName(name);
	for(var i=0; i<checks.length;i++){
		if(checks[i].checked==false){
			return false;
		}
	}
	return true;
	
}


var innerDataLink = false;function addNewField(id) {
    if (!innerDataLink) {
        innerDataLink = getDoc(id).innerHTML;
    }
    $("#"+id).append("<br>"+innerDataLink);
}
function actionMissingField(id) {
    getDoc(id).style.border = "1px solid red";
    getDoc(id).foucs();
}
function actionMissingFieldOkay(id) {
    getDoc(id).style.border = "1px solid #a9a9a9";
}
function resetForm(id) { getDoc(id).reset(); }function forgetPForm() {
    var txt_email = getDocValue('txt_email')
	if (validate_email('txt_email')) {
		getIt("loginError",SITEURL+"getStuff/?ajax&forgetPForm&txt_email="+txt_email);
	}
	else{ getDocInner("loginError","Please enter a valid email id");}
	return false;
}

function changeScreen(hideThis, showThis){
	$("#"+hideThis).slideUp(300,function(){
		$("#"+showThis).fadeIn(300);});
	 }


function fixDate(v) {
	if (v!="") {
	now = new Date(v);
	month = now.getMonth() + 1;
	year = now.getFullYear();
	day = now.getDate();
	return year + "-" + month + "-" + day;
	}
	return false;
}


function setCookie(cname,cvalue,exdays)
{
var d = new Date();
d.setTime(d.getTime()+(exdays*24*60*60*1000));
var expires = "expires="+d.toGMTString();
document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

function scrollToLeft(){
    $("#sponsor_image").scrollTo('-=489px','duration:450'); }
function scrollRight() { $("#sponsor_image").scrollTo('+=489px','duration:450');    $('#left_sponsor').css('display','block');
}
function scrollToTop(){
    $("body").scrollTo('0','duration:450');
}


function recover() {
	$("#login_form").slideUp(300, function(){
		$("#recover_pass").fadeIn(300);
	})
}

function relogin() {
	$("#recover_pass").slideUp(300, function(){
		$("#login_form").fadeIn(300);
	})
}

function requireField(id){
	$("#error_"+id).show();
	$("#"+id).focus();
}

function showError(e, delay){
   showMsg(e, 'danger', delay);
}
function showSuccess(e, delay){
   showMsg(e, 'success', delay);
}
function showMsg(msg, cclass, delay){
   if (delay != 0) {
      delay = 6000;
   }
   var options = {delay: delay};
   $.simplyToast(msg, cclass, options);
}

function doRequiredActions(e){
	if ($("#qc_pm").length > 0) { //that means we are at quickconnect form
		if(validate_form([['language',''], ['type',''], ['duration',''], ['qc_pm','']])){
			if ($("#qc_pm").val() == "credit_card") {
				return true;
			}
			else{
				$("#payment-form").submit();
				return false;
			}
		}
		else{
			e.preventDefault();
			return false;
		}
	}
	return true;
}
function displayIt(ele, scroll){
    $(ele).slideDown(300, function(){
        if (scroll) {
            $("body").scrollTo(ele, {duration: 400});
        }
    });
}

function getMethodInfo(){
    displayIt("#add_payment_methods");
	payment_method = $("#payment_method").val();
	if (payment_method != "") {
		$("#w_lbl").addClass('alert alert-info').html("Loading "+ loader);
		$.ajax({
			url: SITEURL + "getStuff.php?getMethodInfo&payment_method="+payment_method,
			dataType: "html",
			type: "get",
			success: function(msg){
				$("#method_info").html(msg);
				if (payment_method == 6) {
					$("#bank_form").show();
					$("#actions").hide();
				}
				else{
					$("#bank_form").hide();
					$("#actions").show();
				}
			},
			error: function(){ $("#w_lbl").addClass('alert alert-danger').html("AJAX request failed."); },
			complete: function(){ $("#w_lbl").removeClass('alert').html(""); $("body").css("cursor","default"); }
		});
	}
}
function changeCountry(v){
	if (v !="") {
		$("#bank_form").addClass('alert alert-info').html("Loading "+ loader);
		$.ajax({
			url: SITEURL + "getStuff.php?changeCountry&c="+encodeURIComponent(v),
			dataType: "html",
			type: "get",
			success: function( msg ){
				$("#bank_form").removeClass('alert alert-info');
				$("#bank_form").html( msg );
			},
			error: function(){ $("#bank_form").addClass('alert alert-danger').html("AJAX request failed."); },
			complete: function(){ $("#actions").show(); $("body").css("cursor","default"); }
		});
	}
}
function vali_w(){
	return validate_form([['w_meth',''],['w_amount','numeric']]);
}
function getWEmail() {
	$("#w_lbl").html("Loading...");
	w_meth = $("#w_meth").val();
	if (w_meth != "") {
		$("body").css("cursor","wait");
		$.ajax({
			url: SITEURL + "getStuff.php?getWEmail&w_meth="+w_meth,
			dataType: "json",
			type: "get",
			success: function(msg){
				if (msg.success) {
					//$("#summary").fadeIn(300).html(msg.summary);
					$("#w_fee_div").fadeIn(300).html(msg.fee + "%");
				}else{
					$("#w_lbl").fadeIn(300).html("<div class='alert alert-danger'>" + msg.error + "</div>");
					$("#w_fee_div").fadeOut(300);
				}
			},
			error: function(){ $("#w_lbl").html("AJAX request failed."); },
			complete: function(){ $("#w_lbl").html(""); $("body").css("cursor","default"); }
		});
	}
}
function viewFiles() {
    var inp = document.getElementById('photos');
    for (var i = 0; i < inp.files.length; ++i) {
      var name = inp.files.item(i).name;
      var htmlName = "<span class='file-name'>"+name+"<img src='./images/document.png' onclick='setCover(\""+name+"\")' title='Select cover image'></span>";
      $("#fileNames").append(htmlName);
    }
    $("#cover").val("cover");
}

function setCover(name) {
    $("#cover").val(name);
}
function shareOption(id_post) {
	$("#show_opts").modal();
	$("#post_detail").html("Loading... " + loader);
	//waitImg('Loading...','#show_opts');
	$.ajax({
            type: "GET",
            url: SITEURL+"getStuff/?show_post_detail&id_post="+id_post,
            dataType: "html",
            success: function(data){
                $('#post_detail').html(data);
		generateLink();
            },
	    complete: function(){ //endWait();
	    }
	});
	//$("#show_opts").dialog({title: "Share the Link", width: 500, fluid: true,});
	return false;
}
function generateLink() {
	var id_post = $("#id_post").val();
	var domain = $("input:radio[name='domain']:checked").val();
	var post_slug = $("#post_slug").val();
	var longUrl = domain + "share/" + id_post + "/" + post_slug + "/";
	$("#spf_link").val(longUrl)
	var embedLink = domain + "embed/"+id_post;
	code = '<iframe width="560" height="315" src="'+embedLink+'" frameborder="0" allowfullscreen></iframe>';
	$("#spf_embed").val(code);
	title = $("#spf_post_title").text();
	socialShare(longUrl, title);
}
function socialShare(link, text){
	if (!link) {return false;}
	$("#share").jsSocials({shares: ["twitter", "facebook", "googleplus", "linkedin", "pinterest"],
            url: link,text: text, showLabel: true,showCount: true,smallScreenWidth: 640,
            largeScreenWidth: 1024,resizeTimeout: 200,
        });
}

function validationAlert(){
	showError("Please validate your form before submitting it.");
	$(document).scrollTo("#mega_main_menu_ul", {duration: 300});
}

function validateVideo() {
	if ($("#yt-dl").length == 0) {
		validate = validate_form([['video_title',''],['scategory',''],['video','']]);
	}
	else{
		validate = validate_form([['video_title',''],['scategory',''],['yt-dl','']]);
	}
	if (!validate) {
		validationAlert();
		return false;
	}
	return true;
}
function reportPost(id_post) {
	$("#reportPost").modal({backdrop: 'static'});
}

function validateGallery() {
        var validate = true;
	var go = false;
        validate = validate_form([['galler_title',''],['gallery_cats',''],['cover_image','']]);
	
        if ($('#input-id').length > 0) {
		if ($('.image_name_field').length > 0) {
			go = true;
		}
		else{
			$('#input-id').fileinput('upload');
		}
		PromptClear('error_gallery_files');
        }
        else{
            showPrompt('error_gallery_files');
            validate = false;
        }
	if (!validate) {
		validationAlert(); go = false;
	}
	return go;
}

function editGallery() {
        var validate = true;
	var go = false;
        validate = validate_form([['title',''],['gallery_cats','']]);
        if ($('#input-id').length > 0) {
		if ($('.image_name_field').length > 0) {
			go = true;
			console.log("1");
		}
		else{
			console.log("2");
			go = true;
			$('#input-id').fileinput('upload');
		}
		PromptClear('error_gallery_files');
		console.log("3");
        }
        else{
		console.log("4");
            showPrompt('error_gallery_files');
            validate = false;
        }
	if (!validate) {
		console.log("5");
		validationAlert(); go = false;
	}
	return go;
}

function validateStory() {
        validate = validate_form([['story_title',''], ['scategory',''], ['cover_image','']]);
//	if ($('.image_name_field').length > 0) {
//		PromptClear('error_gallery_files');
//        }
//        else{
//            showPrompt('error_gallery_files');
//            validate = false;
//        }
	if (!validate) {
		validationAlert();
		return false;
	}
	return true;
}

function loadHdImages() {
	$("img.lq").each(function(){
		var hd_img = $(this).data("src");
		$(this).attr("src", hd_img);
		$(this).removeClass("lq");
		$(this).addClass("hd");
	});
}
var totalClicks = 0;

function userClicked(){
        totalClicks = Number(totalClicks) + 1;
        setCookie("gc_total_clicks", totalClicks, clickLockoutTime);
        console.log("clicked");
        if (totalClicks >= maxAllowedClicks) {
                hideAds();
        }
}
function hideAds(){
        $(".oc-ad").each(function(){$(this).remove();});
}
function adSecureActions(){
        totalClicks = getCookie("gc_total_clicks");
        $(".oc-ad").each(function(){
                $("iframe", this).each(function(){
                       // console.log("found");
                        $(this).click(function(){ userClicked(); });
                });
        });
}
function userSignup() {
        var error = true;
        $account_type = $("#account_type").val();
        if ($account_type == "2") {
            error = validate_form([['first_n',''], ['last_n', ''],['phone2','numeric'],['national_id',''], ['email', 'email'], ['password', 'password'],['address',''],['city',''],['state',''],['country',''],['zipcode',''],['uploadBtn1',''],['uploadBtn2','']]);
        }
        else{
            error = validate_form([['first_n',''], ['last_n', ''], ['phone2','numeric'], ['national_id',''], ['email', 'email'], ['password', 'password'],['address',''],['city',''],['state',''],['country',''],['zipcode',''],['c_reg_no',''],['website',''],['uploadBtn1',''],['uploadBtn2',''],['uploadBtn3',''],['uploadBtn5',''],['b_name',''],['transactions','']]);
        }
        var idPattr = /^UA-([0-9]{8,8})-([0-9]{1,1})$/;
        if ($('#password').val()==$('#cpassword').val()) {
                PromptClear('error_cpassword');
        }
        else {
            showPrompt('error_cpassword');
            error = false;
        }
        
        if (!$("#terms").is(':checked')) {
                showPrompt('error_terms');
                error = false;
        }
        else {
                PromptClear('error_terms');
        }
        if ( error ) {
                return true;
        }
        else {
            showError("Validation failed, Please fill all the required fields");
                return false;
        }
}
window.onload = function(){
	adSecureActions();
	setTimeout(loadHdImages, 1000);
}
$(document).ready(function(){
	$("[data-toggle=popover]").popover();
        $("[data-toggle=tooltip]").tooltip();
	if ($(".numbersonly").length > 0) {
		$('.numbersonly').keypress(function(key) {
			if((key.charCode < 48 || key.charCode > 57) && key.charCode != 13 && key.charCode !== 0) return false;
		});
	}if ($(".priceonly").length > 0) {
		$('.priceonly').keypress(function(key) {
			//console.log(key.charCode);
			if( (key.charCode < 46 || key.charCode > 57) && key.charCode !== 0 && key.charCode != 13) return false;
		});
	}
if($(".daterange").length>0){
		$('.daterange').daterangepicker({
			showCustomRangeLabel: false,
			alwaysShowCalendars: true,
			autoUpdateInput:false,
			ranges: {
			   'Today': [moment(), moment()],
			   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			   'This Month': [moment().startOf('month'), moment().endOf('month')],
			   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		});
		$('.daterange').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
		});
	  
		$('.daterange').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});
	}
	if ($('.datepicker').length > 0) {
        $('.datepicker').datetimepicker({
            defaultDate: new Date(),
            format: 'YYYY-MM-DD'
        });
    }
	if ($('.datetimepicker').length > 0) {
        $('.datetimepicker').datetimepicker({
           // defaultDate: new Date(),
            format: 'YYYY-MM-DD LT'
        });
    }if ($('.timepicker').length > 0) {
        $('.timepicker').datetimepicker({
            format: 'LT'
        });
    }
	if ($(".nospecialchars").length > 0) {
		$('.nospecialchars').keypress(function(key) {
			if((key.charCode < 97 || key.charCode > 122) && (key.charCode < 65 || key.charCode > 90)  && (key.charCode < 48 || key.charCode > 57)) return false;
		});
	}
	if ($(".alphaonly").length > 0) {
		$('.alphaonly').keypress(function(key) {
			if((key.charCode < 97 || key.charCode > 122) && (key.charCode < 65 || key.charCode > 90) ) return false;
		});
	}
	
	if ($(".oci-img").length>0) {
		$(".grid").on("mouseover", ".oci-img", function(){ $(".oci-overlay", this).show(); });
		$(".grid").on("mouseout", ".oci-img", function(){ $(".oci-overlay", this).hide(); });
			//$(".oci-img").hover(function(){ $(".oci-overlay", this).show(); }, function(){ $(".oci-overlay", this).hide(); });
	}
	if ($(".confirm").length > 0) {
		$(".confirm").click(function(e){ if(!confirm("Are you sure?")){ e.preventDefault(); }});
	}
	if ($(".ccount").length > 0) {
		target = $(".ccount").data("target");
		$(target).keyup(function(){ $(".ccount").html( $(target).val().length ); });
	}
	if ($(".oc-ad").length > 0) {
		
	}
	if ($(".mobile-trigger").length > 0) {
		$(".mobile-trigger").click(function(){
			toTrigger = $(this).data("target");
			hiddenXs = $(toTrigger).hasClass('hidden-xs');
			hiddenSm = $(toTrigger).hasClass('hidden-sm');
			if ( $(this).hasClass("active") ) {
				$(this).removeClass("active");
				$(toTrigger).slideUp();
				if(hiddenXs) $(toTrigger).addClass("hidden-xs");
				if(hiddenSm) $(toTrigger).addClass("hidden-sm");
			}else{
				$(this).addClass("active");
				$(toTrigger).slideDown();
				if(hiddenXs) $(toTrigger).removeClass("hidden-xs");
				if(hiddenSm) $(toTrigger).removeClass("hidden-sm");
			}
			
		});
	}
	if ($("#submit_report").length > 0) {
		$("#submit_report").submit(function(e){
			e.preventDefault();
			validate = validate_form([["name",""],["email","email"],["message",""]]);
			if (validate) {
				$("#reportLbl").html("Sending report " + loader );
				scData = getScreenValues("reportPost");
				$.ajax({
					url: SITEURL + "contact/?ajax&send_msg"+scData,
					cache: false,
					type: "post",
					success: function(msg){
						$("#reportLbl").html(msg);
					},
					error: function(){ $("#reportLbl").html("AJAX request failed. Please try again"); }
				});
			}
		});
	}
	$("#mobile_trigger").click(function(){
		if($(this).hasClass("active")){
			//$("#mobile_menu_top").addClass("nodisplay");
			$("#mobile_menu_top").slideUp();
			$(this).removeClass("active");
			//$("#mobile_menu").addClass("hidden-xs");
		}
		else{
			//$("#mobile_menu_top").removeClass("nodisplay");
			$("#mobile_menu_top").slideDown(400).show();
			$(this).addClass("active");
			//$("#mobile_menu").removeClass("hidden-xs");
		}
	})
	postdivel = $(".post-detail .cl-title");
	if (postdivel.length > 0) {
		var elementPosition = postdivel.offset();
		$(window).scroll(function(){
			if($(window).scrollTop() > elementPosition.top){
			      postdivel.css('position','fixed').css('top','0');
			} else {
			    postdivel.css('position','static');
			}    
		});
	}
	if ($(".infinite-scroll").length > 0) {
		var container = document.querySelector('.infinite-scroll');
		var msnry = new Masonry( container, {
			itemSelector: '.item',
			//gutter: 10
		});
		var ias = $.ias({
			container: ".infinite-scroll",
			item: ".item",
			pagination: ".nav",
			next: ".nav a",
		      });
		ias.on('render', function(items) { $(items).css({ opacity: 0 }); });
		ias.on('rendered', function(items) {
			msnry.appended(items);
			loadHdImages();
		});
		ias.extension(new IASSpinnerExtension());
		ias.extension(new IASTriggerExtension({offset: 7}));
		ias.extension(new IASNoneLeftExtension({html: '<div class="ias-noneleft" style="text-align:center; font-size: 18px;"><p><em>That\'s it!</em></p></div>'}));
	}
	
	if ($("#cfrom").length>0) {
            $("#cfrom").change(function(){
                sel_curr = $("#cfrom").val();
                $("#incurr").text("in " + sel_curr);
            });   
        }
        if ($(".cchanger").length>0) {
            $(".cchanger").change(function(){
                sel_curr = $(".cchanger").val();
                $(".ccurr").text("in " + sel_curr);
            });   
        }
	if ($("#input-id").length > 0) {
	 var $el2 = $("#input-id");
	 var file_index = 0;
	 var img_position = 0;
		var footerTemplate = '<div class="file-thumbnail-footer">\n' +
		'   <div class="oc_extra_fields_div" style="margin:5px 0">\n' +
		'       <input type="text" name="image_title[]" id="kv_cat_title" class="image_title kv-input kv-new form-control input-sm {TAG_CSS_NEW}" value="" placeholder="Enter Image Title...">\n' +
		'       <input type="hidden" name="image_pos[]" id="kv_cat_pos" class="image_pos img_position kv-input kv-new form-control input-sm {TAG_CSS_NEW}" value="">\n' +
		'       <input type="hidden" name="file_index[]" id="kv_cat_file" class="file_index kv-input kv-new form-control input-sm {TAG_CSS_NEW}" value="">\n' +
		'       <textarea rows="5" name="image_desc[]" cols="5" id="kv_cat_desc" class="image_desc kv-textarea kv-new form-control input-sm {TAG_CSS_NEW}" value="" placeholder="Enter Image Descrption..."></textarea>\n' +
		'       <input class="kv-input kv-init form-control input-sm {TAG_CSS_INIT}" value="{TAG_VALUE}" placeholder="Enter caption...">\n' +
		'   </div>\n' +
		'   {actions}\n' +
		'</div>';
		$el2.fileinput({
		    uploadUrl: SITEURL+'getStuff/?upload_gallery_img',
		    uploadAsync: true,
		    overwriteInitial: false,
                    allowedFileExtensions: ['jpg', 'jpeg', 'png','gif'],
		    layoutTemplates: {footer: footerTemplate},
		    showUpload: false,
		    previewThumbTags: {
			'{TAG_VALUE}': '',        // no value
			'{TAG_CSS_NEW}': '',      // new thumbnail input
			'{TAG_CSS_INIT}': 'hide'  // hide the initial input
		    },
		    initialPreviewThumbTags:[
		    {
			'{CUSTOM_TAG_NEW}': ' ',
			'{CUSTOM_TAG_INIT}': 'lt;span class=\'custom-css\'>CUSTOM MARKUP 1lt;/span>'
		    },
		    {
			'{CUSTOM_TAG_NEW}': ' ',
			'{CUSTOM_TAG_INIT}': 'lt;span class=\'custom-css\'>CUSTOM MARKUP 2lt;/span>'
		    }
		],
		    uploadExtraData: function() {  // callback example
			var out = {};
			var key, i = 0 ,j=0;
			$(".oc_extra_fields_div").each(function(){
				$(".input-sm", this).each(function(){
					$el = $(this);
					key = $el.attr('id') + "_" + i;
					out[key] = $el.val();
				});
				i++;
			});
			return out;
		    },
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.filename, extra = data.extra,
			response = data.response, reader = data.reader;
                        if (!response.success) {
                              showError(response.error);
                              return {message: "error", data:{}};
                        }
			
			var imgfilenames = response.imgName;
			this.allfiles = $("#allfilees");
			var fileIndex = $('input[name="file_index[]"]:eq('+index+')').val();
			parentId = "#oc_extra_fields_div_"+index;
			var img_title = $(parentId + " .image_title").val();
			var img_desc = $(parentId + " .image_desc").val();
			var img_pos = $(parentId + " .image_pos").val();
			//alert(img_title + " " + img_pos);
			this.inputfield = $("<input type='hidden' id='"+previewId+"' class='image_name_field' name='fieldvalue[]'>").val(imgfilenames).appendTo(this.allfiles);
			this.inputfield = $("<input type='hidden' id='"+previewId+"' name='img_titles[]'>").val(img_title).appendTo(this.allfiles);
			this.inputfield = $("<input type='hidden' id='"+previewId+"' name='img_descriptions[]'>").val(img_desc).appendTo(this.allfiles);
			this.inputfield = $("<input type='hidden' id='"+previewId+"' name='img_position[]'>").val(img_pos).appendTo(this.allfiles);
			console.log(data);
			console.log("fileIndex " + fileIndex + "- parentId " + parentId + " - previewId"+ previewId + " - index = " + index + " - title = " + img_title + " - img = " + imgfilenames +  " - desc = " + img_desc + " -pos = " +  img_pos);
			$("#images").val("true");
			
		});
		$('#input-id').on('fileloaded', function(event, file, previewId, index, reader) {
			file_index = 0;
			$(".file_index").each(function(){ $(this).val(file_index);
				$(this).parent().attr("id","oc_extra_fields_div_"+file_index);
				file_index++;
			});
			
			assingPositions(".img_position");
			$( ".file-preview-thumbnails" ).sortable({
				cursor: "move",
				items: ".file-preview-frame"
			});
			$( ".file-preview-thumbnails" ).on( "sortstop", function( event, ui ) {
				assingPositions(".img_position");
				//console.log(ui);
			} );
		});
		$('#input-id').on('filebatchuploadcomplete', function(event, data, previewId, index) {
			$("#sub_gallery").attr("disabled",false);
                        $("#sub_gallery").removeClass("loading");
			$("#sub_gallery").trigger("click");
		});
                $('#input-id').on("filebatchpreupload", function(event, files) {
                       // if(files.length>0)
			//{
				$("#sub_gallery").attr("disabled", "disabled");
				$("#sub_gallery").addClass("loading");
			//}
                });
		$('#input-id').on('filesuccessremove', function(event, id) {
		      $("#"+id).remove();
		      $("#allfilees #"+id).remove();
		});
		$('#input-id').on('fileclear', function(event) {
		    $("#allfilees input").remove();
		});
		$(".file-drop-zone-title").html("Drag & Drop your images here.... <div class='fontsmall gap2'>Or click \"Browse\" button below to select.<br> Please click \"Upload\" button after selecting your images.</div>");
	}

});
function fetchYTVideo() {
	url = encodeURIComponent($("#yt-dl").val());
	if (url == "") {
		showError("Please enter video url");
	}else{
		$('#yt-dl-lbl').html("<div class='alert alert-info'>Downloading video, Please wait "+loader+"</div>");
		$.ajax({
			type: "GET",
			url: SITEURL+"getStuff/?fetchYTVideo&url="+url,
			dataType: "json",
			success: function(response){
			    $('#yt-dl-lbl').html("");
				var imgfilenames = response.filename;
				var cover_images = response.images;
				if(cover_images.length > 0){
				    for(i=0; i<cover_images.length; i++){
					    if (i==0) {var selectedClass = " selected";}else{selectedClass = "";}
					    imgDiv = "<div class='selectable-box"+selectedClass+"' onclick='makeSelectable(this, \""+cover_images[i]+"\")'><i class='glyphicon dslc-icon-ext-circle-check'></i><div class='slover'></div><div class='cover-image'><img src='"+SITEURL + cover_images[i]+"'></div></div>"
					    $("#coverImagesBlock").append(imgDiv);
				    }
				    $("#cover_image").val(cover_images[0]);
				}
				$("#c_im_ca").show();
				$("#video").val(imgfilenames);
			},
			error: function(){ showError("Ajax request failed. Please try again later."); }
		});
	}
}
function assingPositions(ele) {
	count = 0;
	$(ele).each(function(){
		count++;
		$(this).val(count);
	});
}
function makeSelectable(e, cover){
	$("#coverImagesBlock .selectable-box").each(function(){
		$(this).removeClass("selected");
	});
	console.log("adding class"); console.log(e);
	$(e).addClass("selected");
	$("#cover_image").val( cover );
}
function removePostImage(id) {
	if (confirm("Are you sure to remove this image?")) {
		$.ajax({
            type: "GET",
            url: SITEURL+"getStuff/?remove_post_img&id_img="+id,
            dataType: "html",
            success: function(data){
                $('#idimg_'+id).remove();
            }
		});
	}
}
function getSubCats(id) {
	if (id != "") {
		$.ajax({
			type: "GET",
			url: SITEURL+"getStuff/?getSubCats&id_parent="+id,
			dataType: "html",
			success: function(data){
			    $('#gallery_subcats_div').html(data);
			}
		});
	}
}
function showFile(id) {
        $("#uploadFile"+id).val($("#uploadBtn"+id).val().replace(/C:\\fakepath\\/i, ''));
}
/********************** Wab.host ******************/
function confirmDeposit(id) {
	$("#id_deposit").val(id);
	$("#id_user").val($("#user_"+id).val());
	$("#name").val($("#name_"+id).val());
	$("#email").val($("#email_"+id).val());
	$("#ref_no").val($("#ref_no_"+id).val());
	$("#amount").val($("#amount_"+id).val());
        $("#currency").val($("#currency_"+id).val());
	$("#date").val($("#date_"+id).val());
	if ($("#via_"+id).val()==2) {
		$("#via_ibft").prop("checked", true);
		$("#via_ibg").prop("checked", false);
	}
	else {
		$("#via_ibg").prop("checked", true);
		$("#via_ibft").prop("checked", false);
	}
}