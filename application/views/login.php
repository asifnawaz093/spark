<div class="contents loginbox">
	<div class="row">
		<div class="col-md-4 gap3 col-md-offset-4">
	<div class="row">
		<div class="col-md-8 gap3 col-md-offset-2">
			<h3>Welcome back!</h3>
	<?php FC::getInstance()->loadTemplate("alerts");?>
	<div  class="login_table gap3" id="loginTable">
		<form id="login_form" action="">
			<div id="signin"><div id="loginError" style="color:red"></div></div>
			<div class="row">
				<div class="col-md-12">Email Address</div>
				<div class="col-md-12"><input type="text" name="ad_email" id="ad_email" class="form-control"></div>
			</div>
			<div class="row gap">
				<div class="col-md-12">Password</div>
				<div class="col-md-12"><input type="password" name="ad_pwd" id="ad_pwd" class="form-control"></div>
			</div>
			<?php $redirect = FC::getClass("Session")->get("redirect");
				if(!$redirect){ $redirect = Tools::getValue("redirect"); }
				if($redirect){ echo "<input type='hidden' id='redirect' name='redirect' value='".$redirect."'>"; }
			?>
			<div class="row">
				<div class="col-md-12 gap2">
					<input type="submit" id="login-btn" class="btn-full btn btn-success" onclick="return login()" value="Sign In">
					<div class="pull-right clearfix gap"><a href="javascript:void(0)" onclick="recover()" rel="nofollow" class="forgot linkform"><b>Forgot password?</b></a></div>
					<div class="gap3 clear textcenter"><br>
						Don't have an account? <a href="<?php echo SITEURL; ?>signup/" rel="next"><b>Sign Up</b></a>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="forgetPTable">
		<form id="recover_pass" style="display: none">
			<h3>Recover Password</h3>
			<div id="recError" style="color:red"></div>
			<div class="row">
				<div class="col-md-12">Email Address</div>
				<div class="col-md-12">
					<input type="text" name="rec_ad_email" id="rec_ad_email" class="form-control">
					<div class="error" id="error_rec_ad_email">Please enter your valid email id</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 gap">
					<input type="hidden" name="act_code" id="act_code">
					<input type="submit" class="submit btn-full btn btn-success" onclick="return recoverPassword()" value="Send Password"></div>
			</div>
			<div class="row gap3">
				<div class="col-md-12 bottom">
					<a href="<?php echo SITEURL; ?>signup" rel="next"><b>Sign Up</b></a> &nbsp | &nbsp
					<a href="javascript:void(0)" onclick="relogin()" rel="nofollow" class="forgot linkform"><b>Login</b></a>
				</div>
			</div>
			</div>
		</form>
		</div>
	</div>
</div>
	</div>
</div>