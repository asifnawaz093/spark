<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>View Titles</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL . 'title/?action=add'; ?>" class="btn btn-primary oc-button navlink">Add New Title</a>
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / title</div>
			
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