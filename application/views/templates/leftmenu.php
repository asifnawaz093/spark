<?php 
$session = FC::getClassInstance("Session"); ?>
<div class="newintermenu visible-xs visible-sm">
		<a href="javascript:void(0)" onclick="showMenu('newintermenu')">
			<span aria-hidden="true" class="glyphicon glyphicon-list list"></span>Menu
		</a>
	</div>
	<div id="newintermenu" class="newintermenu hidden-xs hidden-sm">
  	<?php if( $session->isAdmin() ) { ?>
			<a href="<?php echo SITEURL; ?>cp"><span aria-hidden="true" class="glyphicon dslc-icon-ext-basic_accelerator"></span>Dashboard</a>
		<a href="<?php echo SITEURL; ?>users/?type=drivers"><span aria-hidden="true" class="glyphicon dslc-icon-ext-users"></span><span>View Clients</span></a>
		<a href="<?php echo SITEURL; ?>users/?type=broker"><span aria-hidden="true" class="glyphicon dslc-icon-ext-users"></span><span>View Brokers</span></a>
		<a href="<?php echo SITEURL; ?>users/?type=staff"><span aria-hidden="true" class="glyphicon dslc-icon-ext-users"></span><span>View Staff</span></a>
		<a href="<?php echo SITEURL; ?>companies/"><span aria-hidden="true" class="glyphicon dslc-icon-ext-uniE7D2"></span><span>View Companies</span></a>
		<a href="<?php echo SITEURL; ?>cars/"><span aria-hidden="true" class="glyphicon dslc-icon-ext-uniE7D2"></span><span>View Cars</span></a>
		<a href="<?php echo SITEURL; ?>policies"><span aria-hidden="true" class="glyphicon dslc-icon-ext-newspaper2"></span><span>Policies</span></a>
		<a href="<?php echo SITEURL; ?>products"><span aria-hidden="true" class="glyphicon dslc-icon-ext-bag"></span><span>Products</span></a>
		<a href="<?php echo SITEURL; ?>categories"><span aria-hidden="true" class="glyphicon dslc-icon-ext-suitcase"></span><span>Categories</span></a>
		<a href="<?php echo SITEURL; ?>settings/?action=customfields"><span aria-hidden="true" class="glyphicon dslc-icon-ext-cog"></span><span>Custom Fields</span></a>
		<a href="<?php echo SITEURL; ?>settings/?action=policytypes"><span aria-hidden="true" class="glyphicon dslc-icon-ext-cog"></span><span>Policy Types</span></a>
		<a href="<?php echo SITEURL; ?>settings"><span aria-hidden="true" class="glyphicon dslc-icon-ext-cog"></span><span>Settings</span></a>
		
		<a href="<?php echo SITEURL; ?>changep"><span aria-hidden="true" class="glyphicon dslc-icon-ext-lock"></span><span>Change Password</span></a>
			
		<?php
	}
?>
</div>
<script>
	function showMenu(){
		if($('#newintermenu').hasClass('hidden-xs')){
			$("#newintermenu").removeClass('hidden-xs');
			$("#newintermenu").removeClass('hidden-sm');
			$('#newintermenu').slideDown();
		}else{
			$("#newintermenu").addClass('hidden-xs');
			$("#newintermenu").addClass('hidden-sm');
			$('#newintermenu').slideUp();
		}
	}
</script>