<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Add New Title</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL . 'title/';?>" class="btn btn-primary oc-button navlink">View Title</a>
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / Add New title</div>
			
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