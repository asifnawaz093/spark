<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Profile Information</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / Profile Information
			</div>
		</div>
		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
			  <?php
				if(isset($this->profile) && $this->profile){ ?>
					<table class="table table-bordered">
						<tr><th>First Name: </th><td><?php echo $this->profile['first_name']; ?></td></tr>
						<tr><th>Last Name: </th><td><?php echo $this->profile['last_name']; ?></td></tr>
						<tr><th>Email Address: </th><td><?php echo $this->profile['first_name']; ?></td></tr>
						<tr><th>Phone: </th><td><?php echo $this->profile['first_name']; ?></td></tr>
						<tr><th>Address: </th><td><?php echo $this->profile['address']; ?></td></tr>
						<tr><th>City: </th><td><?php echo $this->profile['city']; ?></td></tr>
						<tr><th>State: </th><td><?php echo $this->profile['state']; ?></td></tr>
						<tr><th>Company: </th><td><?php echo $this->profile['company']; ?></td></tr>
						<?php if(in_array($this->profile['word'], ['broker', 'staff'])){ ?>
						<tr><th>Comission: </th><td><?php echo $this->profile['comission']; ?></td></tr>
						<?php } ?>
						<tr><th>Account Type: </th><td><?php echo $this->profile['word']; ?></td></tr>
						<tr><th>Account Status: </th><td><?php echo $this->profile['user_status']; ?></td></tr>
						<?php if(isset($this->profile['meta']) && $this->profile['meta']){
							foreach($this->profile['meta'] as $cm){
								echo "<tr><th>{$cm['name']}</th><td>{$cm['value']}</td></tr>";
							}
						} ?>
					</table>
				<?php }
			  ?>
		  </div>
		</div>
    </div>
</div>