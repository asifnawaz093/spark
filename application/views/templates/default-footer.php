<?php global $data;
$fc = FC::getInstance();?>
<div class="gap3"></div>
<div class="gap3"></div>
<div class="gap3"></div>
<br><br><br><br>
<!-- <footer id="footer">
    <?php /*<div class="footer1">
        <div class="container clearfix">
            <div class="row">
                <div class="col-md-3">
                    <h4>About Us</h4>
                    <p>
                        Black Tie Technology Ltd is a regulated E-money licensed holder from the Financial Services Authority (FSA) United Kingdom. Our payment services licensed no is 606113.
                    </p>
                </div>
                <div class="col-md-3">
                    <h4>Navigation</h4>
                    <ul class="ul">
                        <li><a href="<?php echo SITEURL; ?>">Home</a></li>
                        <li><a href="<?php echo SITEURL; ?>contact">Report an Issue</a></li>
                        <li><a href="<?php echo SITEURL; ?>privacy">Privacy Policy</a></li>
                        <li><a href="<?php echo SITEURL; ?>terms">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4>Useful Links</h4>
                    <ul class="ul">
                        <li><a href="<?php echo SITEURL; ?>documentation">Documentation</a></li>
                        <li><a href="<?php echo SITEURL; ?>plugins">Plugins</a></li>
                        <li><a href="<?php echo SITEURL; ?>prohibited">Restricted Businesses</a></li>
                        <li><a href="<?php echo SITEURL; ?>contact">Support Center</a></li>
                        <li><a href="<?php echo SITEURL; ?>videos">Video Guides</a></li>
                    </ul>
                </div>
                <div class="col-md-3 gap3">
                    <div class="gap3"></div>
                    <p>Black Tie Technology Sdn Bhd is an investee of</p>
                    <img src="<?php echo SITEURL; ?>images/cradle-logo.png">
                </div>
            </div>
        </div>
    </div> */ ?>
    <div class="footer2">
        <div class="container clearfix">
            <div class="row">
                <div class="col-md-6">
                    Copyrights &copy 2019-2020, Tamin - All Rights Reserved
                </div>
                <div class="col-md-2">
                    <div class="dslc-button">
                        <a href="javascript:void(0)" target="_self" onclick="scrollToTop()" class="">
                            <span class="dslc-icon dslc-icon-ext-arrow-up7"></span>
                            <span class="dslca-editable-content" data-id="button_text" data-type="simple"></span>
                        </a>
                    </div>
                </div>
                <div class="col-md-4 textright c-images">
                    <span class="social"><a target="_blank" href="https://facebook.com/" class="facebook round"><i class="dslc-icon-ext-facebook2"></i></a></span>	
                </div>
            </div>
        </div>
    </div>
</footer> -->

<?php if(!FC::getClass("Session")->isAdmin()){ ?>
<script type='text/javascript'>
window.__lo_site_id = 91340;
	(function() {
		var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
		wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
	  })();
	</script>
<?php } ?>
<?php if(isset($fc->vars['tinymce'])){
		 if($fc->vars['tinymce'] === "1" || $fc->vars['tinymce'] === true){ $selector = ".tinymce";} else{$selector = $fc->vars['tinymce'] ;} ?>
	<script> tinymce.init({
            selector:'<?php echo $selector; ?>',
            plugins: ["advlist autolink lists link image charmap print preview anchor","searchreplace visualblocks code fullscreen","insertdatetime media table contextmenu paste jbimages"],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
            relative_urls: false
        });</script>
<?php
}
FC::getClass("Db")->printProfiling();