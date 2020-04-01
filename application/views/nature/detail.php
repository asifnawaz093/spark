<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>nature Detail</h3></div>
			<div class="pull-right">
                <a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / <a href="<?php echo SITEURL . 'nature/';?>">nature</a>
			</div>
		</div>

		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
          <?php
            if(isset($this->nature) && $this->nature){ ?>
                <div class="row">
					<div class="col-md-9">
						<table class="table table-bordered">
							<tr><th>ID: </th><td><?php echo $this->nature['id']; ?></td></tr>
						    <tr><th>LAW: </th><td><?php echo $this->law['law']; ?></td></tr>
                            <tr><th>Section: </th><td><?php echo $this->section['section']; ?></td></tr>
                            <tr><th>Nature: </th><td><?php echo $this->nature['section']; ?></td></tr>
                              <!--	<tr><th>Address: </th><td><?php echo $this->nature['address']; ?></td></tr>
							<tr><th>Phone Number: </th><td><?php echo $this->nature['phone']; ?></td></tr> -->
							<?php if(isset($this->nature['meta']) && $this->nature['meta']){
								foreach($this->nature['meta'] as $cm){
									echo "<tr><th>{$cm['name']}</th><td>{$cm['value']}</td></tr>";
								}
							} ?>
                          <!--  <tr><th>Date Created: </th><td><?php echo Tools::printDate($this->nature['date']); ?></td></tr>
						--></table>
					</div>
					<div class="col-md-3">
						<?php if($this->nature['logo']){ echo "<img src='".SITEURL.$this->nature['logo']."' style='max-width:100%'>"; } ?>
					</div>
				</div>
            <?php } ?>
			</div>
		</div>
	</div>
</div>