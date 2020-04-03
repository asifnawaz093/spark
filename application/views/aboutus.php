<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>About Us</h3></div>
			<div class="pull-right">
                <a href="<?php echo SITEURL; ?>addcase">Main</a> / <a href="<?php echo SITEURL . 'aboutus/';?>">aboutus</a>
			</div>
		</div>

		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
          <?php
            if(isset($this->aboutus) && $this->aboutus){ ?>

                <br>
                <br><br><br>
              <div class="row">
              <div class="col-md-8">
                  <p align="left"><h4><b><?php echo $this->aboutus['name'];?></b></h4></p>
                  <p align="left"><h5><?php echo $this->aboutus['information'];?></h5></p>
                  <p align="left"><h5><?php echo $this->aboutus['contact'];?></h5></p>
              </div>
                  <div class="col-md-4">
                      <img src="<?php echo $this->aboutus['img'];?>" height="200" width="300" alt="No Image">
                  </div>

          </div>
            <?php } ?>
			</div>
		</div>
	</div>
</div>