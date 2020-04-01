<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Add New nature</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL . 'nature/';?>" class="btn btn-primary oc-button navlink">View nature</a>
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / Add New nature</div>
			
		</div>
		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
			<?php
				echo $this->form;
			?>
		  </div>
		</div>
    </div>
</div>
<script>
    function getsection()
    {
        if($("#id_law").val()) {
            $("#id_section").removeClass("nodisplay");
            $.ajax(
                {
                    url: "<?php echo SITEURL; ?>nature/?action=getsection&value=" + $("#id_law").val(),
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
        }
    }
</script>