<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Section Detail</h3></div>
			<div class="pull-right">
                <a href="<?php echo SITEURL . 'section/'; ?>" class="btn btn-primary oc-button navlink">View All Section</a>
                <a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / <a href="<?php echo SITEURL . 'section/';?>">section</a>
			</div>
		</div>

		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
          <?php
            if(isset($this->section) && $this->section){ ?>
                <div class="row">
					<div class="col-md-9">
						<table class="table table-bordered">
                            <tr><th>LAW: </th><td><?php echo ucwords($this->law['law']); ?></td></tr>
                            <tr><th>Section: </th><td><?php echo ucwords($this->section['section']); ?></td></tr>
                            <!--	<tr><th>Address: </th><td><?php echo $this->section['address']; ?></td></tr>
							<tr><th>Phone Number: </th><td><?php echo $this->section['phone']; ?></td></tr> -->
							<?php if(isset($this->section['meta']) && $this->section['meta']){
								foreach($this->section['meta'] as $cm){
									echo "<tr><th>{$cm['name']}</th><td>{$cm['value']}</td></tr>";
								}
							} ?>
                          <!--  <tr><th>Date Created: </th><td><?php echo Tools::printDate($this->section['date']); ?></td></tr>
						--></table>
					</div>
					<div class="col-md-3">
						<?php if($this->section['logo']){ echo "<img src='".SITEURL.$this->section['logo']."' style='max-width:100%'>"; } ?>
					</div>
				</div>
            <?php } ?>
			</div>
		</div>
	</div>
</div>