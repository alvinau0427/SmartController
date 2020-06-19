<!--User Setting Modal -->
<div id="user-setting-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">User Settings</h4>
			</div>
			<div class="modal-body" style="max-width: 500px; margin: 0px auto;">
				<h4 class="vcenter">Receive Notification</h4>
				<div class="switch vcenter" style="float: right;">
					<input type="checkbox" id="user-receiveNotification-btn" class="bootstrap-switch" data-on-color="success" data-label-width="0" data-handle-width="100" 
					<?php 
						echo $this->user->getReceiveNotification() == "1" ? 'checked':''; 
					?> />
				</div>
				<hr/>
				
				<h4 class="vcenter">Receive Email</h4>
				<div class="switch vcenter" style="float: right;">
					<input type="checkbox" id="user-receiveEmail-btn" class="bootstrap-switch" data-on-color="success" data-label-width="0" data-handle-width="100" 
					<?php 
						echo $this->user->getReceiveEmail() == "1" ? 'checked':''; 
					?> />
				</div>
				<hr/>
				
				<h4 class="vcenter">Location Display</h4>
				<div class="switch vcenter" style="float: right;">
					<input type="checkbox" id="user-locationDisplay-btn" class="bootstrap-switch" data-on-color="success" data-label-width="0" data-handle-width="100" 
					<?php 
						echo $this->user->getLocationDisplay() == "1" ? 'checked':''; 
					?> />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- /.User Setting Modal -->

<!--Smooth Scroll-->
<div>
	<!--Navigation-->
	<nav class="main-header clearfix" role="navigation">
	  <a class="navbar-brand" href="<?php echo URLROOT; ?>"><span class="text-blue"><?php echo TITLE; ?></span></a> 
	  
	  <!--Navigation Itself-->
	  <div class="navbar-content">
		<!--Sidebar Toggler--> 
		<a href="#" class="btn btn-default left-toggler"><i class="fa fa-bars"></i></a> 
		<!--Right Userbar Toggler--> 
		<a href="#" class="btn btn-user right-toggler pull-right"><i class="entypo-vcard"></i> <span class="logged-as hidden-xs">Logged as</span><span class="logged-as-name hidden-xs"><?= $this->user->getName() ?></span></a> 
		<!--Fullscreen Trigger-->
		<button type="button" class="btn btn-default hidden-xs pull-right" id="toggle-fullscreen"> <i class=" entypo-popup"></i> </button>
		
		<div class="btn-group">
		  <button type="button" id="notification-button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <i class="glyphicon glyphicon-globe"></i><span id="notification-new" class=""></span></button>
		  <div id="notification-dropdown" class="dropdown-menu">
			<div class="dropdown-header">Notifications <span id="notification-num" class="badge pull-right">0</span></div>
			<div class="dropdown-container">
			  <div class="nano">
				<div class="nano-content">
				  <ul class="notification-dropdown">
				  </ul>
				</div>
			  </div>
			</div>
			<div class="dropdown-footer"><a class="btn btn-dark" href="<?php echo URLROOT?>/notification">See All</a></div>
		  </div>
		</div>
		
	  </div>
	</nav>
	<!--/Navigation-->

    <!--MainWrapper-->
    <div class="main-wrap"> 
      
      <!--OffCanvas Menu -->
      <aside class="user-menu"> 
        
        <!-- Tabs -->
        <div class="tabs-offcanvas">
          <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#userbar-one" data-toggle="tab">Main</a></li>
          </ul>
          <div class="tab-content"> 
            
            <!--User Primary Panel-->
            <div class="tab-pane active" id="userbar-one">
              <div class="main-info">
				<div class="user-img"><img src="<?php echo URLROOT; ?>/web/images/users/<?= $this->user->getImage() ?>" alt="User Picture" /></div>
                <h1><?= $this->user->getName() ?><small><?php  echo $this->user->getTypeDescription(); ?></small></h1>
              </div>
              	<div class="list-group"> 
					<a href="<?php echo URLROOT; ?>/profile" class="list-group-item"><i class="fa fa-user"></i>Profile</a> 
					<div class="empthy"></div>
	                <a href="#" data-toggle="modal" data-target="#user-setting-modal" class="list-group-item"><i class="fa fa-cog"></i> User Settings</a>
	                <a href="<?php echo URLROOT; ?>/login" class="list-group-item goaway"><i class="fa fa-power-off"></i> Sign Out</a> 
	            </div>
            </div>
            
            
          </div>
        </div>
        
        <!-- /tabs --> 
        
      </aside>
      <!-- /Offcanvas user menu--> 
	  <!--Main Menu-->
      <div class="responsive-admin-menu">
        <div class="responsive-menu"><?php echo TITLE; ?>
          <div class="menuicon"><i class="fa fa-angle-down"></i></div>
        </div>
		<ul id="menu">
		<li><a class="menu-index" href="<?php echo URLROOT; ?>" title="Dashboard"><i class="glyphicon glyphicon-dashboard"></i><span> Dashboard </span></a></li>
           
          <li><a href="#" class="submenu menu-room" data-id="rooms-sub" title="Rooms"><i class="fa fa-home"></i><span> Rooms</span></a> 
            <!-- Rooms Sub-Menu -->
            <ul id="rooms-sub" class="accordion">
			<?php
			
				foreach($this->roomArr as $roomMenu) {
					echo '<li>';
					echo '<a href="'.URLROOT.'/room/'.$roomMenu->getID().'" title="'.$roomMenu->getName().'">';
					echo '<i class="fa fa-home"></i><span>'.$roomMenu->getName().'</span></a>';
					echo '</li>';
				}
			?>
            </ul>
          </li>
		  <li><a class="menu-notification" href="<?php echo URLROOT; ?>/notification" title="Notifications"><i class="glyphicon glyphicon-globe"></i><span> Notifications </span></a></li>
		  <?php if($this->user->getType() == 2) { ?>
		  <li><a class="menu-location" href="<?php echo URLROOT; ?>/location" title="Location"><i class="glyphicon glyphicon-map-marker"></i><span> Location </span></a></li>
		  
		  <li><a href="#" class="submenu menu-setting" data-id="settings-sub" title="Advanced Settings"><i class="glyphicon glyphicon-cog"></i><span> Advanced Settings</span></a> 
            <!-- Settings Sub-Menu -->
            <ul id="settings-sub" class="accordion">
              <li><a href="<?php echo URLROOT; ?>/setting/actuator" title="Actuators"><i class="glyphicon glyphicon-wrench"></i><span> Actuators </span></a></li>
			  <li><a href="<?php echo URLROOT; ?>/setting/room" title="Rooms"><i class="glyphicon glyphicon-wrench"></i><span> Rooms </span></a></li>
            </ul>
          </li>
		 
          <?php } ?>
		</ul>
      </div>
      <!--/MainMenu-->
      
      <!--Content Wrapper-->
      <div class="content-wrapper"> 
        