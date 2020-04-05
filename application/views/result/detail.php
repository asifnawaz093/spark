<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Result Detail</h3></div>
			<div class="pull-right">
                <a href="<?php echo SITEURL . 'result/'; ?>" class="btn btn-primary oc-button navlink">View All Results</a>
                <a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / <a href="<?php echo SITEURL . 'result/';?>">result</a>
			</div>
		</div>

		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
          <?php
            if(isset($this->result) && $this->result){ ?>
                <div class="row">
					<div class="col-md-9">
						<table class="table table-bordered">
                            <tr><th>LAW: </th><td><?php echo ucwords($this->law['law']); ?></td></tr>
                            <tr><th>Nature: </th><td><?php echo ucwords($this->nature['nature']); ?></td></tr>
                            <tr><th>Result: </th><td><?php echo ucwords($this->result['result']); ?></td></tr>
                            <!--	<tr><th>Address: </th><td><?php echo $this->result['address']; ?></td></tr>
							<tr><th>Phone Number: </th><td><?php echo $this->result['phone']; ?></td></tr> -->
							<?php if(isset($this->result['meta']) && $this->result['meta']){
								foreach($this->result['meta'] as $cm){
									echo "<tr><th>{$cm['name']}</th><td>{$cm['value']}</td></tr>";
								}
							} ?>
                          <!--  <tr><th>Date Created: </th><td><?php echo Tools::printDate($this->result['date']); ?></td></tr>
						--></table>
					</div>
					<div class="col-md-3">
						<?php if($this->result['logo']){ echo "<img src='".SITEURL.$this->result['logo']."' style='max-width:100%'>"; } ?>
					</div>
				</div>
            <?php } ?>
			</div>
		</div>
	</div>
</div>