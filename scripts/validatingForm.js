            
            
            var mszReqFirstName = 'Required: Please enter your first name.';
            var mszReqLastName = 'Required: Please enter your last name.';
            var mszReqEmail = 'Required: Please enter your e-mail address.';
            var mszReqValidEmail = 'Required: Please enter a valid e-mail address.';
            var mszReqPassword = 'Required: Please enter a password.';
	    var mszReqRePassword = 'Required: Please re-enter a password.';
            var mszReqPassMatch = 'Required: Your passwords do not match.';
            var mszReqAppType = 'Please select an application type.';
            var mszReqPhone = 'Required: Please enter your phone number.';
            var mszReqSSN = 'Required: Please enter your valid social security number.';
            var mszReqNOD = 'Required: Number of Dependents must be completed.';
            mszReqAddress = 'Required: Please enter your address.'
            mszReqCity = 'Required: Please enter the city name.'
            mszReqZipCode = 'Required: Please enter Zip Code.'
            








//=================================VALIDATIONS===============================

function valiEmail(id){

   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById(id).value;
   if(reg.test(address) == false) {
 
      document.getElementById('divRequiredErrors').innerHTML = mszReqValidEmail;
      document.getElementById(id).focus();
      return false;
   }
   else{
	document.getElementById('divRequiredErrors').innerHTML = "";
	return true; }

}


function valiEmpty(id){
   alert('val')
	//var field = document.getElementById(id);
        alert('jjj')
	if(document.getElementById(id).value==''){
         alert('ret fals')
	return false;
	 }
	 else{
            alert('tr')
	 return true;
}
return;
alert('where')
}



function valiPh(id, msg){
	var field = document.getElementById(id);
	if((field.value.length==0)||(field.value.length!=9)){
	document.getElementById('divRequiredErrors').innerHTML = msg;
	 }
	 else{ document.getElementById('divRequiredErrors').innerHTML = "";
	 return true;
}
}





function isNumeric(id){
    var num = document.getElementById(id);
    var pattern = /^([0-9]+)$/;
    return pattern.test(num.value)
	
}


function goNext(field, msg){
   
	if(field==''){
	document.getElementById('divRequiredErrors').innerHTML = msg;
	return false;
	}
	else{
		document.getElementById('divRequiredErrors').innerHTML = "";
		return true;
	}
	
}


function matchPassword(){
	cre_pass = document.getElementById('textPass');
	re_pass = document.getElementById('txtConfirm');
	if(cre_pass.value == re_pass.value){
		 document.getElementById('divRequiredErrors').innerHTML = "";
	 return true;
	 }
	 else{ document.getElementById('divRequiredErrors').innerHTML = mszReqPassMatch;
	 return false;
}	
	}




function ValidatePhone(){
	if(valiPh('txtPhone', mszReqPhone)==true){
	 if((isNumeric('txtPhone')==true)){
	 document.getElementById('divRequiredErrors').innerHTML = "";
	 return true;
	 }
	 else{ document.getElementById('divRequiredErrors').innerHTML = "Invalid contact number";
	 return false;
}
	}
}

function validate(){
  alert(1)
    var inputFirstName = document.getElementById('App_Ctrl_1').value;
	var inputLastName = document.getElementById('App_Ctrl_3').value;
	var inputSSN = document.getElementById('App_Ctrl_4').value;
	var inputNOD = document.getElementById('App_Ctrl_11').value;
	var inputAges = document.getElementById('App_Ctrl_12').value;
	
	if(goNext(inputFirstName, mszReqFirstName)==true){
	if(goNext(inputLastName, mszReqLastName)==true){
	if(goNext(inputSSN, mszReqSSN)==true){
	if(goNext(inputNOD, mszReqNOD)==true){
        
	if(valiPh('App_Ctrl_4', mszReqSSN)==true){
          
	    
		
	} else return false;
	}else return false;
	} else return false;
	
	
	
	} else return false;
	 
        }  
}




//function validationsForScreen4(){
//   return true}
//
//
//function validationsForScreen5(){
//   return true}

function validationsForScreen4(){
	var inputFirstName = document.getElementById('App_Ctrl_1').value;
	var inputLastName = document.getElementById('App_Ctrl_3').value;
	var inputSSN = document.getElementById('App_Ctrl_4').value;
	var inputNOD = document.getElementById('App_Ctrl_11').value;
	var inputAges = document.getElementById('App_Ctrl_12').value;
	
	if(goNext(inputFirstName, mszReqFirstName)==true){
	if(goNext(inputLastName, mszReqLastName)==true){
	if(goNext(inputSSN, mszReqSSN)==true){
	if(goNext(inputNOD, mszReqNOD)==true){
        if(valiPh('App_Ctrl_4', mszReqSSN)==true){
		return true;
	} else return false;
	}else return false;
	} else return false;
	} else return false;
	} else return false; 
}


function validationsForScreen5(){
	var inputFirstName = document.getElementById('App_Ctrl_22').value;
	var inputLastName = document.getElementById('App_Ctrl_24').value;
	var inputSSN = document.getElementById('App_Ctrl_25').value;
	var inputNOD = document.getElementById('App_Ctrl_11').value;
	
	
	if(goNext(inputFirstName, mszReqFirstName)==true){
	if(goNext(inputLastName, mszReqLastName)==true){
	if(goNext(inputSSN, mszReqSSN)==true){
	//if(goNext(inputNOD, mszReqNOD)==true){
        if(valiPh('App_Ctrl_25', mszReqSSN)==true){
		return true;
	} else return false;
	}else return false;
	} else return false;
	//} else return false;
	} else return false; 
}



function validationsForScreen29(){
  
 
	var inputFirstName = document.getElementById('App_Ctrl_1').value;
	var inputLastName = document.getElementById('App_Ctrl_3').value;
	var inputSSN = document.getElementById('App_Ctrl_4').value;
	var inputAddress = document.getElementById('App_Ctrl_15').value;
	var inputCity= document.getElementById('App_Ctrl_17').value;
	var inputZipCode = document.getElementById('App_Ctrl_19').value;
        
        
	
	if(goNext(inputFirstName, mszReqFirstName)==true){
	if(goNext(inputLastName, mszReqLastName)==true){
	if(goNext(inputSSN, mszReqSSN)==true){
         
	if(goNext(inputAddress, mszReqAddress)==true){
        if(goNext(inputCity, mszReqCity)==true){
         if(goNext(inputZipCode, mszReqZipCode)==true){
            if(valiPh('App_Ctrl_4', mszReqSSN)==true){
		return true;
	} else return false;
	}else return false;
	} else return false;
	//} else return false;
	} else return false; 
         } else return false;
         } else return false;
         } else return false;
}
