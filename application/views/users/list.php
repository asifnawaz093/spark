<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3><?php echo $this->page_title; ?></h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL . 'users/?action=add&type='. $this->word; ?>" class="btn btn-primary oc-button navlink">Add New <?php echo $this->word; ?></a>
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / <?php echo $this->page_title; ?></div>
			
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