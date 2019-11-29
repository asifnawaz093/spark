function add_lang()
{
    var error = true;
    var tar_lan = $('#tlang').val();
    var noyexp = $('#noyexp').val();
    var oc_user_id = $('#oc_user_id').val();
        if (tar_lan==0) {
                showPrompt('error_tlang');
                error = false;
        }
        else{
                PromptClear('error_tlang');
        }
        if (noyexp==0) {
                showPrompt('error_noyexp');
                error = false;
        }
        else{
                PromptClear('error_noyexp');
        }
        
    
        if (error) {
        
            var src_lan = '40';
            var checked = "";
            $('.lang_srvc').each(function(){
                if($(this).is(":checked")){
                    checked = $(this).val();
                }    
            });
            
            $.ajax({
                type: "GET",
                url: SITEURL+"getStuff/?ajax&add_int_lang&tar_lan="+tar_lan+"&src_lan="+src_lan+"&lang_src="+checked+"&numyexp="+noyexp+"&id="+oc_user_id,
                dataType: "html",
                success: function(data){
                    $('#already_exist').html(data.trim());
                    if ($('#already_exist').text()=="exists") {
                        $('#already_exist').html('This pair already exists');
                        showPrompt('already_exist');
                    }
                    else {
                        PromptClear('already_exist');
                        $('#int_lang_tab').html(data);
                    }
                }
            });
           }
}
function removeLang( id ) {
    var oc_user_id = $('#oc_user_id').val();
    if (confirm("Are you sure, you want to delete?") ) {
    $.ajax({
        type: "GET",
        url: SITEURL+"getStuff/?ajax&remove_int_lang&id_row="+id+"&id="+oc_user_id,
        dataType: "html",
        success: function(data){
            $('#int_lang_tab').html(data);
        }
    });
}
}
function qulatificationCertify() {
        var error = true;
        error = validate_form([['name_of_ins',''], ['city', ''], ['qualobt', ''], ['dateat', '']]);
        if ($('#country').val()==0) {
                showPrompt('error_country');
                error = false;
        }
        else{
                PromptClear('error_country');
        }
        if ( error ) {
                return true;
        }
        else {
                return false;
        }
}

function profesionalReferences() {
        var error = true;
        error = validate_form([['full_name',''], ['relation', ''], ['company', ''], ['phone', ''], ['address', '']]);
        if ( error ) {
                return true;
        }
        else {
                return false;
        }
}
function mailnewsalter() {
    //alert("knknc");
    var error = true;
        error = validate_form([['subject',''], ['message', '']]);
        if ( error ) {
                return true;
        }
        else {
                return false;
        }
}
function addi_languages() {
    //alert("knknc");
    var error = true;
        error = validate_form([['lang_name',''], ['lang_nativename', ''], ['lang_iso', ''], ['lang_code', '']]);
        if ( error ) {
                return true;
        }
        else {
                return false;
        }
}
function intprofile_book() {
    var chek = true;
    chek = validate_form([['name',''],['call_type',''],['int_area',''],['t_duration','']]);
    if ($('#name').val()=='') {
        showPrompt('error_name');
        chek = false;
    }
    else
    {
        
    }
    return chek;
}
function signupform_val() {
    var chek = true;
    chek = validate_form([['first_n','alpha'], ['last_n', 'alpha'], ['email', 'email'], ['pwd', 'password'], ['contact', ''], ['overview', ''], ['city', '']]);
    if ($('#email').val()==$('#cemail').val()) {
        PromptClear('error_cemail');
    }
    else {
        showPrompt('error_cemail');
        error = false;
    }
    if ($('#pwd').val()==$('#cpwd').val()) {
        PromptClear('error_cpwd');
    }
    else {
        showPrompt('error_cpwd');
        error = false;
    }
    if ($('#nat_lang').val()==0) {
        showPrompt('error_nat_lang');
        chek = false;
    }
    else{
        PromptClear('error_nat_lang');
    }
    if ($('#experience').val()=='') {
        showPrompt('error_experience');
        chek = false;
    }
    else{
        PromptClear('error_experience');
    }
    if ($('#country').val()=='') {
        showPrompt('error_country');
        chek = false;
    }
    else{
        PromptClear('error_country');
    }
    if ($('#state').val()=='') {
        showPrompt('error_state');
        chek = false;
    }
    else{
        PromptClear('error_state');
    }
    if ( checkChecked('.avlfor') ) {
        PromptClear('error_avlfor');
    }
    else {
        showPrompt('error_avlfor');
        chek = false;
    }
    if ( checkChecked('.genara') ) {
        PromptClear('error_intarea');
    }
    else {
        showPrompt('error_intarea');
        chek = false;
    }
    if ( chek ) {
        return true;
    }
    else {
        return false;
    }
}
function validate_reg_form(){
    
}
function checkChecked(element) {
    var anyBoxesChecked = false;
    $(element).each(function() {
        if ($(this).is(":checked")) {
            anyBoxesChecked = true;
        }
    });
 
    return anyBoxesChecked;
}
function client_sginup() {
    var chk = true;
    chk = validate_form([['first_n','alpha'],['last_n','alpha'],['address1',''],['address2',''],['city',''],['state',''],['country','alpha'],['zip',''],['email','email'],['language',''],['user_n',''],['password','password'],['password_m','password'],['paypal_id',''],['contact','']]);
    return chk;
}
function client_edsginup() {
    var chk = true;
    chk = validate_form([['first_n','alpha'],['last_n','alpha'],['address1',''],['address2',''],['city',''],['state',''],['country','alpha'],['zip',''],['email','email'],['language',''],['contact','']]);
    return chk;
}
/*--stars system--*/
$(document).ready(function(){
        $('.rate-btn').hover(function(){
            $('.rate-btn').removeClass('rate-btn-hover');
            var therate = $(this).attr('id');
            for (var i = therate; i >= 0; i--) {
                $('.rate-btn-'+i).addClass('rate-btn-hover');
            };
        });
        $('.rate-btn').click(function(){
            var therate = $(this).attr('id');
            var inter_id = $('#inter_id').val();
            var dataRate = 'act=rate&post_id='+inter_id+'&rate='+therate;
            $('.rate-btn').removeClass('rate-btn-active');
            for (var i = therate; i >= 0; i--) {
                $('.rate-btn-'+i).addClass('rate-btn-active');
            };
            $.ajax({
                type : "POST",
                url : SITEURL+"getStuff/?ajax&add_starrate",
                data: dataRate,
                success:function(data){
                }
            });    
        });
       
    });