<div class="body-contents container clearfix">
    <div id="menu-block"><?php FC::getInstance()->loadTemplate("sidebar"); ?></div>
    <div id="content-block">
		<div class="breadcrumb clearfix">
			<div class="pull-left textorange"><h3>Case Detail</h3></div>
			<div class="pull-right">
                <a href="<?php echo SITEURL; ?>dashboard">Dashboard</a> / <a href="<?php echo SITEURL . 'addcase/';?>">casedetail</a>
			</div>
		</div>

		<div id="contents">
		  <?php FC::getInstance()->loadTemplate("alerts"); ?>
		  <div class="bgwhite padding minheight">
          <?php
            if(isset($this->addcase) && $this->addcase){ ?>
                <div class="row">
					<div class="col-md-9">
						<table class="table table-bordered">
                            <tr><th>LAW: </th><td><?php echo ucwords($this->law['law']); ?></td></tr>
                            <tr><th>Title: </th><td><?php echo ucwords($this->title['title']); ?></td></tr>
                            <tr><th>Section: </th><td><?php echo ucwords($this->section['section']); ?></td></tr>
                            <tr><th>Nature: </th><td><?php echo ucwords($this->nature['nature']); ?></td></tr>
                            <tr><th>Result: </th><td><?php echo ucwords($this->result['result']); ?></td></tr>
                            <!--	<tr><th>Address: </th><td><?php echo $this->addcase['address']; ?></td></tr>
							<tr><th>Phone Number: </th><td><?php echo $this->addcase['phone']; ?></td></tr> -->
							<?php if(isset($this->addcase['meta']) && $this->addcase['meta']){
								foreach($this->addcase['meta'] as $cm){
									echo "<tr><th>{$cm['name']}</th><td>{$cm['value']}</td></tr>";
								}
							} ?>
                           <tr><th>Date Created: </th><td><?php echo Tools::printDate($this->addcase['date_added']); ?></td></tr>
						</table>
                        <table class="table table-bordered">
                            <tr><td><?php echo ucwords($this->addcase['details']); ?>
                                    </td></tr>
                        </table>

					</div>
					<div class="col-md-3">
						<?php if($this->addcase['logo']){ echo "<img src='".SITEURL.$this->addcase['logo']."' style='max-width:100%'>"; } ?>
					</div>
				</div>
            <?php } ?>
			</div>
		</div>
	</div>
</div>