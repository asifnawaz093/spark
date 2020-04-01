<div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
    <h3>Navigation<span id="navigate" class="glyphicon glyphicon-th-list pull-right"></span></h3>
</div>
<div id="menu" class="hidden-phone navigation">
    <!--<div class="menu-caption">Main Menu</div>	-->
    <div id="leftmenu">
    <?php if(!Tools::getValue("new_menu")){ ?>
        <ul id="tree-menu">
            <?php
            $menuArray = Tools::menuArray();
            $session = FC::getClass("Session");
            if($session->isAdmin()) {
                $i=1;
                foreach($menuArray as $rkey => $menu){
                    echo "<li>";
                    if(count($menu)==1){
                        if($menu['root']['visible']){
                            echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
                            $i++;
                        }
                    } else {
                        if(!$menu['root']['visible']) continue;
                        echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
                        $i++;
                        echo "<ul>";
                        foreach($menu as $key => $submenu){
                            if(isset($submenu['root']['visible']) && $submenu['root']['visible'] === false) continue;
                            if(isset($submenu['visible']) && $submenu['visible'] === false) continue;
                            if($key!='root'){
                                if(count($submenu)==1){
                                    echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                                    $i++;
                                } else {
                                    if(isset($submenu['url'])){
                                        echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                                        $i++;
                                    } else {
                                        echo "<li>";
                                        echo "<a id='element_$i' class='menu-link' href='".$submenu['root']['url']."'>".$submenu['root']['title']."</a>";
                                        $i++;
                                        echo "<ul>";
                                        foreach($submenu as $skey => $ssmenu ){
                                            echo ($skey=='root') ? "" : "<li><a id='element_$i' class='menu-link' href='".$ssmenu['url']."'>".$ssmenu['title']."</a></li>";
                                            $i++;
                                        }
                                        echo "</ul>";
                                        echo "</li>";
                                    }
                                }
                            }
                        }
                        echo "</ul>";
                    }
                    echo "</li>";
                }
                echo "<li><a id='element_$i' class='menu-link' href='".SITEURL."logout/'>Logout</a></li>";
            }
            else {
                $prv = FC::getClassInstance("Privillage");
                $userRole = Session::my('acc_typ');
                $userPrv = $prv->getPrivillages($userRole);
                $options = $prv->getOptions($userPrv);
                $pages = $options['pages'];
                $i=1;
                foreach($menuArray as $rkey => $menu){
                    echo "<li>";
                    if(count($menu)==1){
                        $value = $rkey.',root';
                        if(in_array($value, $pages)){
                            echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
                            $i++;
                        }
                    } else {
                        $value = $rkey.',root';
                        if(in_array($value, $pages)){
                            echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
                            $i++;
                        }
                        echo "<ul>";
                        foreach($menu as $key => $submenu){
                            if($key!='root'){
                                if(count($submenu)==1){
                                    $value = $rkey.','.$key;
                                    if(in_array($value, $pages)){
                                        echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                                        $i++;
                                    }
                                } else {
                                    if(isset($submenu['url'])){
                                        $value = $rkey.','.$key;
                                        if(in_array($value, $pages)){
                                            echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                                            $i++;
                                        }
                                    } else {
                                        echo "<li>";
                                        $value = $rkey.','.$key.',root';
                                        if(in_array($value, $pages)){
                                            echo "<a id='element_$i' class='menu-link' href='".$submenu['root']['url']."'>".$submenu['root']['title']."</a>";
                                            $i++;
                                        }
                                        echo "<ul>";
                                        foreach($submenu as $skey => $ssmenu ){
                                            $value = $rkey.','.$key.','.$skey;
                                            if(in_array($value, $pages)){
                                                echo ($skey=='root') ? "" : "<li><a id='element_$i' class='menu-link' href='".$ssmenu['url']."'>".$ssmenu['title']."</a></li>";
                                                $i++;
                                            }
                                        }
                                        echo "</ul>";
                                        echo "</li>";
                                    }
                                }
                            }
                        }
                        echo "</ul>";
                    }
                    echo "</li>";
                }
                //echo "<li><a id='element_".$i+1 ."' class='menu-link' href='".SITEURL."logout/'>Logout</a></li>";
            }
            ?>
        </ul>
        <!--<ul class="mainmenu">
        <li><a href="<?php /*echo SITEURL; */?>cp" class="glyphicons home"><i></i><span>Dashboard</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>map/" class="glyphicons globe"><i></i><span>Map</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>bookride/" class="glyphicons bank"><i></i><span>Book A Ride</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>users/?type=user" class="glyphicons group"><i></i><span>Users / Passengers</span></a></li>
        <li><a href="#"  class="glyphicons group"><i></i><span>Business</span></a>
            <ul class="submenu">
                <li><a href="<?php /*echo SITEURL; */?>hotels/" class="glyphicons bank"><i></i><span>Hotel</span></a></li>
                <li><a href="<?php /*echo SITEURL; */?>airline/" class="glyphicons bank"><i></i><span>Airline</span></a></li>
                <li><a href="<?php /*echo SITEURL; */?>privatecompany/" class="glyphicons bank"><i></i><span>Private Co.</span></a></li>
                <li><a href="<?php /*echo SITEURL; */?>touroperator/" class="glyphicons bank"><i></i><span>Tour Operator</span></a></li>
            </ul>
        </li>
        <li><a href="#"  class="glyphicons group"><i></i><span>Education</span></a>
            <ul class="submenu">
                <li><a href="<?php /*echo SITEURL; */?>school/" class="glyphicons bank"><i></i><span>School / College</span></a></li>
                <li><a href="<?php /*echo SITEURL; */?>parents/" class="glyphicons bank"><i></i><span>Parent</span></a></li>
            </ul>
        </li>
        <li><a href="<?php /*echo SITEURL; */?>driver/" class="glyphicons group"><i></i><span>Driver</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>vans/" class="glyphicons bank"><i></i><span>Vehicles</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>route/" class="glyphicons bank"><i></i><span>Routes / Stations</span></a></li>

        <li><a href="<?php /*echo SITEURL; */?>users/?type=staff" class="glyphicons group"><i></i><span>Staff</span></a></li>
		<li><a href="<?php /*echo SITEURL; */?>ridehistory/" class="glyphicons bank"><i></i><span>Ride History</span></a></li>
            <li><a href="<?php /*echo SITEURL; */?>extraservices/" class="glyphicons bank"><i></i><span>Extra Services</span></a></li>
            <li><a href="<?php /*echo SITEURL; */?>products/" class="glyphicons cargo"><i></i><span>Dispute</span></a></li>
		<li><a href="<?php /*echo SITEURL; */?>packages/" class="glyphicons bank"><i></i><span>Promocode</span></a></li>
        <li><a href="#"  class="glyphicons group"><i></i><span>Rating</span></a>
            <ul class="submenu">
                <li><a href="<?php /*echo SITEURL; */?>userrating" class="glyphicons bank"><i></i><span>Users / Passengers</span></a></li>
                <li><a href="<?php /*echo SITEURL; */?>rating" class="glyphicons bank"><i></i><span>Driver</span></a></li>
            </ul>
        </li>


        <li><a href="<?php /*echo SITEURL; */?>settings/?action=customfields" class="glyphicons magic"><i></i><span>Custom Fields</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>role" class="glyphicons magic"><i></i><span>Manage User Role</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>updatesetting/" class="glyphicons settings"><i></i><span>Settings</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>changep/" class="glyphicons glass"><i></i><span>Change Password</span></a></li>
        <li><a href="<?php /*echo SITEURL; */?>claims/" class="glyphicons calculator"><i></i><span>Logout</span></a></li>
    </ul>-->
    <?php }else{ ?>
    <ul id="tree-menu">
       <?php
       $menuArray = Tools::menuArray();
       $session = FC::getClass("Session");
       if($session->isAdmin()) {
        $i=1;
        foreach($menuArray as $rkey => $menu){
        echo "<li>";
            if(count($menu)==1){
            if($menu['root']['visible']){
            echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
            $i++;
            }
            } else {
            if(!$menu['root']['visible']) continue;
            echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
            $i++;
            echo "<ul>";
                foreach($menu as $key => $submenu){
                if(isset($submenu['root']['visible']) && $submenu['root']['visible'] === false) continue;
                if(isset($submenu['visible']) && $submenu['visible'] === false) continue;
                if($key!='root'){
                if(count($submenu)==1){
                echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                $i++;
                } else {
                if(isset($submenu['url'])){
                echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                $i++;
                } else {
                echo "<li>";
                    echo "<a id='element_$i' class='menu-link' href='".$submenu['root']['url']."'>".$submenu['root']['title']."</a>";
                    $i++;
                    echo "<ul>";
                        foreach($submenu as $skey => $ssmenu ){
                        echo ($skey=='root') ? "" : "<li><a id='element_$i' class='menu-link' href='".$ssmenu['url']."'>".$ssmenu['title']."</a></li>";
                        $i++;
                        }
                        echo "</ul>";
                    echo "</li>";
                }
                }
                }
                }
                echo "</ul>";
            }
            echo "</li>";
        }
        echo "<li><a id='element_$i' class='menu-link' href='".SITEURL."privillages/'>Permissions</a></li>";
        echo "<li><a id='element_$i' class='menu-link' href='".SITEURL."logout/'>Logout</a></li>";
        }
       else {
           $prv = FC::getClassInstance("Privillage");
           $userRole = Session::my('acc_typ');
           $userPrv = $prv->getPrivillages($userRole);
           $options = $prv->getOptions($userPrv);
           $pages = $options['pages'];
           $i=1;
           foreach($menuArray as $rkey => $menu){
               echo "<li>";
               if(count($menu)==1){
                   $value = $rkey.',root';
                   if(in_array($value, $pages)){
                       echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
                       $i++;
                   }
               } else {
                   $value = $rkey.',root';
                   if(in_array($value, $pages)){
                       echo "<a id='element_$i' class='menu-link' href='".$menu['root']['url']."'>".$menu['root']['title']."</a>";
                       $i++;
                   }
                   echo "<ul>";
                   foreach($menu as $key => $submenu){
                       if($key!='root'){
                           if(count($submenu)==1){
                               $value = $rkey.','.$key;
                               if(in_array($value, $pages)){
                                   echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                                   $i++;
                               }
                           } else {
                               if(isset($submenu['url'])){
                                   $value = $rkey.','.$key;
                                   if(in_array($value, $pages)){
                                       echo "<li><a id='element_$i' class='menu-link' href='".$submenu['url']."'>".$submenu['title']."</a></li>";
                                       $i++;
                                   }
                               } else {
                                   echo "<li>";
                                   $value = $rkey.','.$key.',root';
                                   if(in_array($value, $pages)){
                                       echo "<a id='element_$i' class='menu-link' href='".$submenu['root']['url']."'>".$submenu['root']['title']."</a>";
                                       $i++;
                                   }
                                   echo "<ul>";
                                   foreach($submenu as $skey => $ssmenu ){
                                       $value = $rkey.','.$key.','.$skey;
                                       if(in_array($value, $pages)){
                                           echo ($skey=='root') ? "" : "<li><a id='element_$i' class='menu-link' href='".$ssmenu['url']."'>".$ssmenu['title']."</a></li>";
                                           $i++;
                                       }
                                   }
                                   echo "</ul>";
                                   echo "</li>";
                               }
                           }
                       }
                   }
                   echo "</ul>";
               }
               echo "</li>";
           }
           //echo "<li><a id='element_".$i+1 ."' class='menu-link' href='".SITEURL."logout/'>Logout</a></li>";
       }
       ?>
    </ul>
    <?php } ?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("#navigate").click(function(){
        $("#menu").slideToggle();
    });
});
</script>