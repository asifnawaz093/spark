

function getIt(div, page){
	if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
  
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(div).innerHTML=xmlhttp.responseText;     }
     else
  document.getElementById(div).innerHTML = 'Loading content - please wait  <img src="'+SITEURL+'images/ajaxload.gif">';
  
   if(xmlhttp.status==404){document.getElementById(div).innerHTML = '<red>Sorry, but the requested page doesn\'t exist.</red>';}
    if(xmlhttp.status==500){document.getElementById(div).innerHTML = '<red>Sorry, but there was a problem in connecting to external server. Please check your internet connection.</red>';}
  }
  
xmlhttp.open("GET",page ,true);
xmlhttp.send();
}




function getItFalse(div, page){
	if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
  
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(div).innerHTML=xmlhttp.responseText;     }
     else
  document.getElementById(div).innerHTML = 'Loading content - please wait  <img src="'+SITEURL+'images/ajaxload.gif">';
  
   if(xmlhttp.status==404){document.getElementById(div).innerHTML = '<red>Sorry, but the requested page doesn\'t exist.</red>';}
    if(xmlhttp.status==500){document.getElementById(div).innerHTML = '<red>Sorry, but there was a problem in connecting to external server. Please check your internet connection.</red>';}
  }
  
xmlhttp.open("GET",page ,false);
xmlhttp.send();
}





function loadPage(div, page, scrollTarget) {        currentPage = $("#currentPage").val();
        pagesToMake = $("#pagesToMake").val();
        if (currentPage<pagesToMake) {
            $("#currentPage").val(page);
            getItLoad(div,page,scrollTarget);
        }
	
}

function getItLoad(div, page,scrollTarget){
    image = "<img src='"+SITEURL+"images/ajax-loader.gif'>";
    $('.'+div).append("<div id='fadeWhite' style='padding:0;'>"+image+"</div>");
    $('#fadeWhite').fadeIn('slow');
    
    $.ajax({
        url: page,
        dataType: "html",
        type: "get",
        success: function(data){
            $("."+div).append(data);
            $('#fadeWhite').fadeOut('slow');
            requiredActions();
        },
        error: function(){alert("Something went wrong. Please try again later."); }
        });}


function loadDiv(div, page,img){
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else { xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function(){
			
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById(div).innerHTML=xmlhttp.responseText;
			$('#fadeWhite2').fadeOut('slow');
		}
		else{
			if(img){ image = "<img src='"+SITEURL+"images/ajax-loader.gif'>";}else{image="";}
			$('#'+div).append("<div id='fadeWhite2' style='padding:0;'>"+image+"</div>");
			$('#fadeWhite2').fadeIn('slow');
		}
	     
		if(xmlhttp.status==404){document.getElementById(div).innerHTML = '<red>Sorry, but the requested page doesn\'t exist.</red>';$('#fadeWhite2').fadeIn('slow');}
		else if(xmlhttp.status==500){document.getElementById(div).innerHTML = '<red>Sorry, but there was a problem in connecting to external server. Please check your internet connection.</red>';$('#fadeWhite2').fadeIn('slow');}
	}
	xmlhttp.open("GET",page ,true);
	xmlhttp.send();
}




function getItResponse(div, page){
    
    if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
  
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
   
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	var resText = xmlhttp.responseText;
	if($("#hid_back").length > 0 && $("#hid_back").val() !="" )
	{
		var url = $("#hid_back").val();
		if(resText != 0 &&resText != 1 &&resText != 2)
			document.getElementById(div).innerHTML=resText;
		else
			window.location = url;
        }
	else
	{
		if(resText > 1){ window.location = SITEURL+"dashboard/"; }
		else if(resText == 0){ window.location = SITEURL+"cp/"; }
		else if(resText == 1){ window.location = SITEURL+"dashboard/"; }
		if(resText != 0 &&resText != 1 &&resText != 2) document.getElementById(div).innerHTML=resText;
	}

 }
    else
  document.getElementById(div).innerHTML = 'Loading content - please wait  <img src="'+SITEURL+'images/ajaxload.gif">';
  
  if(xmlhttp.status==404){document.getElementById(div).innerHTML = '<red>Sorry, but the requested page doesn\'t exist.</red>';}
    if(xmlhttp.status==500){document.getElementById(div).innerHTML = '<red>Sorry, but there was a problem in connecting to external server. Please check your internet connection.</red>';}
  }
  
    xmlhttp.open("GET",page,true);
    xmlhttp.send();

}

/////////////////////////////////
// getvalues.js//////////////////
////////////////////////////////
function getScreenValues(screenID){
    scData = "";
	var reqTable = document.getElementById(screenID);
	var rows = reqTable.getElementsByTagName('tr');
	var valueInput = reqTable.getElementsByTagName('input');
	var valueSelect = reqTable.getElementsByTagName('select');
	var valueTextArea = reqTable.getElementsByTagName('textarea');
	
	for(var i=0; i<valueInput.length; i++){
		if(valueInput[i].type=='radio'){
		if(valueInput[i].checked){
		scData += '&'+valueInput[i].name+'='+ valueInput[i].value;}
		}
		else if(valueInput[i].type=='checkbox'){
		    if(valueInput[i].checked){
		scData += '&'+valueInput[i].name+'='+ valueInput[i].value;}
		}
		else{
		    if(valueInput[i].type != 'submit' && valueInput[i].type != 'reset' && valueInput[i].type != 'button' ){
			var val = valueInput[i].value.replace(/&/g, '^**^^**^');
			var val = val.replace(/#/g, '^**--**^');
			scData += '&'+valueInput[i].name+'='+ val; }
		}
	}
	
	for(var i=0; i<valueSelect.length; i++){
		
		scData += '&'+valueSelect[i].name+'='+ valueSelect[i].value;
		}
		
		for(var i=0; i<valueTextArea.length; i++){
		var val = valueTextArea[i].value.replace(/&/g, '^**^^**^');
		scData += '&'+valueTextArea[i].name+'='+ val;
		}
	
	
   //  alert(scData);
	return scData;
	}



function getValuesByIDs(screenID){
	var scDataByIDs = "";
	var reqTable = document.getElementById(screenID);
	var valueInput = reqTable.getElementsByTagName('input');
	
	for(var i=0; i<valueInput.length; i++){
		if(valueInput[i].type=='radio'){
		if(valueInput[i].checked){
		scDataByIDs += '&'+valueInput[i].id+'='+ valueInput[i].value;
		}}
		else{
			scDataByIDs += '&'+valueInput[i].id+'='+ valueInput[i].value;
		}
	}
	//alert(scDataByIDs);
	return scDataByIDs;
	}

function valueById(fid){ var value = document.getElementById(fid).value; return value; }

function getTable(tableId, field){
			
		var tab = document.getElementById(tableId);
                var tabTr = tab.getElementsByTagName("tr");
		var tabTd = tab.getElementsByTagName("td");
		var tdVal = ""; var lineVal = "";
		//var tabInput = tab.getElementsByTagName("input");
		
                for(var i=0; i<tabTr.length; i++){
			//alert("rows"+tabTr.length);
			var trTd = tabTr[i].getElementsByTagName("td");
			for(var j=0; j<trTd.length;j++){
			//alert("tds"+trTd.length);
			if((!trTd[j].innerHTML.match("<input"))&&(!trTd[j].innerHTML.match("<textarea"))){
			tdVal+="<td>"+trTd[j].innerHTML+"</td>";/* alert("tdValue!in"+tdVal);*/ }
			else if(trTd[j].innerHTML.match("<input")){
			var tdInput = trTd[j].getElementsByTagName("input");
			for(var k=0; k<tdInput.length; k++){
			 if(tdInput[k].type=='radio'||tdInput[k].type=='checkbox'){ //getting the input value of radio. the value of checked radio will save in tabInputValue
			 if(tdInput[k].checked){
			 tdVal += "<td>"+tdInput[k].value+"</td>";} else{tdVal += "<td>&nbsp</td>";} }
			 else if(tdInput[k].type=='text'){ tdVal += "<td>"+tdInput[k].value+"</td>"; }
			 
			} /*alert(tdVal);*/
			}
			else if(trTd[j].innerHTML.match("<textarea")){tdVal += "<td>"+trTd[j].getElementsByTagName("textarea")[0].value+"</td>";}
			//alert(trTd.length)
			//if(trTd.length==1){tdVal += "<td>&nbsp</td>";}
			}
			lineVal+="<tr>"+tdVal+"</tr>";
			tdVal = "";
		}
		document.getElementById(field).value = lineVal;
}











$(document).ready(function(){
    $("#panelOptionsss a[class!='stay']").click(function(){ 
        $('#panelOptions').append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>"); $('#fadeWhite').fadeIn('slow');
       if(/(\?)/.test(this.href)){ var inset = "&inset";} else{ var inset = "?inset";}  // alert(inset);
       $('#panelOptions').load(this.href+inset); $('#fadeWhite').fadeOut('slow'); return false;  });
       
    
   // $('#nav li').hoverIntent(function(){ $('ul', this).slideDown(300);}, function(){$('ul', this).slideUp(300);});
function hide(){ $('ul', this).slideUp(300); } function show(){ $('ul', this).slideDown(300); }
//$("#nav li").hoverIntent({ sensitivity: 7,  interval: 100, over: show, timeout: 100, out: hide  });

    $('#nav a').click(function(){
	if(!$(this).hasClass('stay')){
	getItLoad("panelOptions",this.href+'?inset');  return false; }
      });
    


});


function loadThisLink(v){
	if(!$(this).hasClass('stay')){
    $('#panelOptions').append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>"); $('#fadeWhite').fadeIn('slow');
    if(/(\?)/.test(v.href)){ var inset = "&inset";} else{ var inset = "?inset"; } alert(inset);
    $('#panelOptions').load(v.href+inset);$('#fadeWhite').fadeOut('slow');return false; } }
    
function enableEdit(){ $("#defRes input[type=text]").css({"width":"100%","border":"solid 1px #a0a0a0"});
$("#defRes input").removeAttr('disabled'); $("#enable_edit").hide('fast'); $("#disable_edit").show('fast');}

function disableEdit(){ $("#defRes input[type=text]").css({"width":"40px","border":"none"});
$("#defRes input").attr('disabled','disabled'); $("#disable_edit").hide('fast'); $("#enable_edit").show('fast');$("#enable_edit").removeAttr('disabled');}




