<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>View Cases</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL . 'addcase/?action=add'; ?>" class="btn btn-primary oc-button navlink">Add New Case</a>
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / cases</div>
			
		</div>
		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
              <div class="row">
                  <div class="col-md-12">
                      <div class="loading" id="loading">

                      </div>
                  </div>

              </div>
              <?php
				echo $this->filter; 
				echo $this->table;
				echo $this->pagination;
			?>
		  </div>
		</div>
    </div>
</div>
<script>

    function filterFieldsub()
    {
        $( document ).ready(function() {
            if ($("#id_law").val() && $("#id_nature").val()) {
                $("#id_result").removeClass("nodisplay");
                $.ajax(
                    {
                        url: "<?php echo SITEURL; ?>addcase/?action=getresultlist&filter=1&id_law=" + $("#id_law").val() + "&id_nature=" + $("#id_nature").val(),
                        dataType: "html",
                        type: "get",
                        success: function (data) {
                            if (data) {
                                $("#id_result").html(data);
                            }
                        },
                        error: function (data) {
                            alert("System error, please refresh page or try again.");
                        }
                    });
            }
        });
    }
    function filterField()
    {
        $( document ).ready(function() {
            if($("#id_law").val()) {
                $("#id_title").removeClass("nodisplay");
                $("#id_section").removeClass("nodisplay");
                $("#id_nature").removeClass("nodisplay");
                $("#id_title").empty();
                $("#id_section").empty();
                $("#id_nature").empty();
                //$('#id_result').empty();


                // PARAM [title=1,section=2, nature=3]
                //TITLE
                $.ajax(
                    {
                        url: "<?php echo SITEURL; ?>addcase/?action=getdata&param=1&filter=1&value=" + $("#id_law").val(),
                        dataType: "html",
                        type: "get",
                        success: function (data) {
                            if(data){
                                $("#id_title").html(data);
                            }
                        },
                        error: function (data) {
                            alert("System error, please refresh page or try again.");
                        }
                    });

                // SECTIONS
                $.ajax(
                    {
                        url: "<?php echo SITEURL; ?>addcase/?action=getdata&param=2&filter=1&value=" + $("#id_law").val(),
                        dataType: "html",
                        type: "get",
                        success: function (data) {
                            if(data){
                                $("#id_section").html(data);

                            }
                        },
                        error: function (data) {
                            alert("System error, please refresh page or try again.");
                        }
                    });
                // NATURE
                $.ajax(
                    {
                        url: "<?php echo SITEURL; ?>addcase/?action=getdata&param=3&filter=1&value=" + $("#id_law").val(),
                        dataType: "html",
                        type: "get",
                        success: function (data) {
                            if(data){
                                $("#id_nature").html(data);
                            }
                        },
                        error: function (data) {
                            alert("System error, please refresh page or try again.");
                        }
                    });

            }
        });

    }
</script>