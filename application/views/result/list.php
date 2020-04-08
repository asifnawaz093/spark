<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>View Result</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL . 'result/?action=add'; ?>" class="btn btn-primary oc-button navlink">Add New Result</a>
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / result</div>
			
		</div>
		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
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
    function filterField()
    {
        if($("#id_law").val()) {
            $("#id_nature").removeClass("nodisplay");
            $.ajax(
                {
                    url: "<?php echo SITEURL; ?>result/?action=getnaturelist&filter=1&value=" + $("#id_law").val(),
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
    }

</script>