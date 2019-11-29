



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
			var val = valueInput[i].value.replace(/#/g, '^**--**^');
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


