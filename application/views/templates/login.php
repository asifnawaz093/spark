<div class="login-form minheight">
				<form id="login_form" action="">
                                  
					<div id="signin"><div id="loginError" style="color:red"></div></div>
                                        <table class="login_table" id="loginTable">
					    <tbody><tr><td><label>Email Address</label></td><td><input type="text" name="ad_email" id="ad_email" class="form-control"></td></tr><tr><td><label>Password</label></td><td>
						<input type="password" name="ad_pwd" id="ad_pwd" class="form-control">
                                                <?php $redirect = FC::getClass("Session")->get("redirect");
                                                    if(!$redirect){$redirect = Tools::getValue("redirect"); }
                                                    if($redirect){ echo "<input type='hidden' id='redirect' name='redirect' value='".$redirect."'>"; }
                                                ?>
                                                </td></tr>
							<tr><td></td><td><br>
						
						<input type="submit" id="login-btn" class="submit button btn btn-primary" onclick="return login()" value="Login"><br><br></td></tr>
						<tr><td></td><td><a href="<?php echo SITEURL; ?>signup/" rel="next"><b>Register here</b></a> &nbsp | &nbsp
                                                 <a href="javascript:void(0)" onclick="recover()" rel="nofollow" class="forgot linkform"><b>Recover password</b></a>
															</td></tr>
                                            </tbody>
                                        </table>
                                       
																</div>
				</form>
				<form id="recover_pass" style="display: none">
                                   
			<h3>Recover Password</h3>
			<div style="padding: 30px 0px; padding-right: 0px;">
				<div id="recError" style="color:red"></div>
				<table id="forgetPTable" style="border-spacing:5px;">
						<tbody>
								<tr>
										<td><b>Email ID:</b></td>
										<td>
												<input type="text" name="rec_ad_email" id="rec_ad_email" class="form-control">
												<div class="error" id="error_rec_ad_email">Please enter your valid email id</div>
										</td>
								</tr>
								<tr>
										<td></td><td><br><input type="submit" class="submit btn btn-primary" onclick="return recoverPassword()" value="Send Password"></td>
								</tr>
						</tbody>
				</table>
				<input type="hidden" name="act_code" id="act_code">
			</div>
					<div class="bottom">
						<a href="<?php echo SITEURL; ?>signup" rel="next"><b>Register here</b></a> &nbsp | &nbsp
						<a href="javascript:void(0)" onclick="relogin()" rel="nofollow" class="forgot linkform"><b>Login</b></a>
					</div>
					<div class="gap3"></div>
				</div>
		
				</form>
			</div>