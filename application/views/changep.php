<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Change Password</h3></div>
			<div class="pull-right">
				<a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / Change Password
			</div>
		</div>
		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
			<form method="post" action="">
			   <div class="row form-group">
				  <div class="col-md-4"><label for="old" style="padding-top: 10px;">Old Password:</label></div>
				  <div class="col-md-8"> <input type="password" name="old" id="old" class="form-control"></div>
			  </div>
			  <div class="row form-group">
				  <div class="col-md-4"><label for="new" style="padding-top: 10px;">New Password:</label></div>
				  <div class="col-md-8"> <input type="password" name="new" id="new" class="form-control"></div>
			  </div>
			  <div class="row form-group">
				  <div class="col-md-4"><label for="cnew" style="padding-top: 10px;">Confirm Password:</label></div>
				  <div class="col-md-8"> <input type="password" name="cnew" id="cnew" class="form-control"></div>
			  </div>
			  
			  <div class="row form-group">
				  <div class="col-md-11 text-right">
					  <input type="submit" name="add_update" value="Save" class="btn btn-success" onclick="">
				  </div>
			  </div>
			</form>
        </div>
    </div>
</div>
</div>