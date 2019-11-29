$(document).ready(function(){

    
    $(".ajax").click(function(e){
        e.preventDefault();
        container = "#panelOptions";
        if ($(this).attr("data-container")) {
            container = $(this).data("container");
        }
        $(container).append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>");
        $('#fadeWhite').fadeIn('slow');
        url = $(this).attr("href");
        if(/\?/.test(url)){ var inset = "&ajax";} else{
            if(/\/$/.test(url))
                var inset = "?ajax";
            else
                var inset = "/?ajax";
        }
       $(container).load(url+inset); $('#fadeWhite').fadeOut('slow');
       
       return false;
    });
    
    $(".ajax-form").submit(function(e){
        $('ajax-form').append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>");
        $('#fadeWhite').fadeIn('slow');
        url = $(this).attr("action");
        
       if(/\?/.test(url)){ var inset = "&ajax";} else{
            if(/\/$/.test(url))
                var inset = "?ajax";
            else
                var inset = "/?ajax";
        }
       $('#panelOptions').load(url+inset); $('#fadeWhite').fadeOut('slow');
       e.preventDefault();
       return false;
    });
    
});

function loadThisLink(v){
    if(this.id!='stay'){
        $('#panelOptions').append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>"); $('#fadeWhite').fadeIn('slow');
        if(/(.php)$/.test(v.href)){ var inset = "?inset";} else{ var inset = "&inset"; } 
        $('#panelOptions').load(v.href+inset);$('#fadeWhite').fadeOut('slow');return false;
    }
}
function ajaxLoad(page, options, type){
        $('#panelOptions').append("<div id='fadeWhite'><img src='images/ajax-loader.gif'></div>");
        $('#fadeWhite').fadeIn('slow');
        $.ajax({
            url: page,
            data: options,
            type: type,
            dataType: "html",
            success: function(data){
                $('#panelOptions').html(data);
            },
            error: function(){ showError("Something went wrong. Please try again later"); },
            complete: function(){$('#fadeWhite').fadeOut('slow');}
	});
    }
    
