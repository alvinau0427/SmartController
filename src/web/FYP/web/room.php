<?php 
	if($this->user == null) {
		return $response->withHeader('Location', URLROOT . '/login');
	} 
?>
<!DOCTYPE html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo TITLE; ?></title>
<link href="<?php echo URLROOT; ?>/web/css/styles.css" rel="stylesheet" type="text/css">

<link rel="shortcut icon" type="image/x-icon" href="<?php echo URLROOT; ?>/web/digitalhome_logo.ico" />
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/modernizr/modernizr.custom.js"></script>

<style>
.slow .toggle-group { transition: left 0.7s; -webkit-transition: left 0.7s; }
.nomargin {
	margin:0;
}
.powerwidget-as-portlet-white ul li {
	background-color: white;
}

.actuator-status {
	position: absolute;
	top: 0; 
	right: 15px;
}


.sensor-unit {
	font-size: 60%;
	opacity: 0.75;
}
.sensor-main {
	margin-bottom:10px;
}
.sensor-bottom-block {
	font-size:80%;
}


.vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
}
</style>

</head>
<body>
	<?php
		require_once __DIR__ . '/header.php';
	?>

        <!--Breadcrumb-->
        <div class="breadcrumb clearfix">
          <ul>
            <li><a href="<?php echo URLROOT; ?>"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Room</a></li>
            <li class="active"><?php echo $room->getName(); ?></li>
          </ul>
        </div>
        <!--/Breadcrumb-->
        
        <div class="page-header">
			<?php echo '<h1>Rooms<small>' . $room->getName() . '</small></h1>'; ?>
        </div>
		
		
        <!-- Widget Row Start grid -->
        <div class="row" id="powerwidgets">
          
			<?php 
				foreach($actuatorArr as $actuator) {
					
					if($actuator->getDisplay() != 1) {	//if it is not show continue to next
						continue;
					}
			?>
			
			
			<div class="col-lg-3 col-md-6 col-sm-6 bootstrap-grid">
				
				<!-- Actuator widget -->
				<div id="actuator-<?php echo $actuator->getID(); ?>" class="actuator powerwidget powerwidget-as-portlet powerwidget-as-portlet-white" data-widget-sortable="false" data-widget-editbutton="false" data-widget-deletebutton="false">
				  <header>
					<h2><?php echo $actuator->getName(); ?><small><?php echo $actuator->getPermissionDescription(); ?></small></h2>
				  </header>
				  
				  <div class="inner-spacer nopadding">
					<form class="nomargin">
						
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="portlet-big-icon">
								<?php 
									$actuatorImage = ($actuator->getStatus() == "1") ? 'on/' : 'off/';
								?>
								<img class="actuator-img" src="<?php echo URLROOT; ?>/web/images/actuators/<?php echo $actuatorImage . $actuator->getImage(); ?>" height="64px" />
							</div>
							
							<h3><center><?php echo $actuator->getName(); ?></center></h3>
							
							<div class="actuator-status text-right">
								<span class="label <?php echo $actuator->checkTime() ? 'label-success':'label-default'; ?>">
									<span class="glyphicon glyphicon-time"></span> TIME
								</span><br/><br/>
							</div>
						</div>
						
						<ul class="portlet-bottom-block">
						  <li class="col-md-12 col-sm-12 col-xs-12">
							<input type="hidden" name="actuatorID" value="<?php echo $actuator->getID(); ?>" />
							<input type="hidden" name="userID" value="<?php echo $this->user->getID(); ?>">
							<input type="hidden" name="permissionID" value="<?php echo $actuator->getPermission(); ?>">
							<div class="switch">
								<input type="checkbox" class="bootstrap-switch actuator-status-btn" data-on-color="success" data-label-width="0" data-handle-width="70" data-size="large"
								<?php 
									echo ($actuator->getStatus() == "1") ? 'checked':''; 
									if( ($actuator->getPermission() == "1") ||
										($actuator->getPermission() == "2" && $actuator->getStatus() == "1") || 
										($actuator->getPermission() == "3" && $actuator->getStatus() == "2") ) {
										echo 'disabled';
									}
								?> />
							</div>
							
						  </li>
						</ul>
						
					</form>
				  </div>
				</div>
				<!-- Actuator widget -->
				
				<?php
					$conditionArr = array();
					if($regulation = $actuator->checkAuto()) {
						$conditionArr = $regulation->getCondition();
					}
					
					foreach($actuator->getSensor() as $sensor) {
				?>
				
					<!-- Sensor widget -->
					<?php 
						$sensorColor = 'powerwidget-as-portlet-purple';
						foreach($conditionArr as $condition) {
							//if is this sensor
							if($sensor === $condition->getSensor()) {
								//if condition is true
								if($condition->check()) {
									$sensorColor = 'powerwidget-as-portlet-green';
								}
								break;
							}
						}
						
					?>
					<div id="sensor-<?php echo $sensor->getID(); ?>" class="sensor powerwidget powerwidget-as-portlet <?php echo $sensorColor;?>" data-widget-sortable="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false">
						<header> </header>
						<div class="row inner-spacer">
							<form class="nomargin">
								<input type="hidden" name="sensorID" value="<?php echo $sensor->getID(); ?>" />
								
								<div class="col-md-4 col-sm-4 col-xs-4 sensor-main vcenter">
									<div class="portlet-big-icon">
										<img class="sensor-img" src="<?php echo URLROOT; ?>/web/images/sensors/<?php echo $sensor->getImage(); ?>" height="64px" />
									</div>
								</div><!--
							--><div class="col-md-8 col-sm-8 col-xs-8 sensor-main vcenter">
									<strong><?php echo $sensor->getName(); ?></strong>
									<?php 
										if(empty($sensor->getUnit())) {
											$value = ($sensor->getValue() == '1') ? "Detected":"Normal";
									?>
											<h2>
												<span class="sensor-value"><?php echo $value;?></span>
												<span class="sensor-unit"></span>
											</h2>
									<?php		
										} else {
									?>
											<h2>
												<span class="sensor-value"><?php echo round($sensor->getValue(), 0, PHP_ROUND_HALF_DOWN); ?></span>
												<span class="sensor-unit"><?php echo $sensor->getUnit(); ?></span>
											</h2>
									<?php				
										}
									?>
									
								</div>
							</form>
						</div>
						<ul class="portlet-bottom-block">
						</ul>
					</div>
					<!-- /Sensor widget --> 

				<?php
					}
				?>
				
			</div>
			<!-- /Inner Row Col-md-6 -->
			
			<?php	
				}
			?>
          
            
            
            
          <!--</div>-->
        </div>
        <!-- /Widgets Row End Grid--> 
      </div>
      <!-- / Content Wrapper --> 
    </div>
    <!--/MainWrapper--> 
  </div>
<!--/Smooth Scroll--> 


       <div class="scroll-to-top">
		<!-- <p>Scroll to top</p> *Use this as a text option, instead of the image icon.*-->
		</div>



<!-- scroll top -->
<div class="scroll-top-wrapper hidden-xs">
    <i class="fa fa-angle-up"></i>
</div>
<!-- /scroll top -->



<!--Modals-->

<!--Time Range Modal -->
<div id="actuator-time-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Time Range Setting</h4>
			</div>
			<div class="modal-body">
				<h5>AUTO mode operates only in the following time range.</h5>
				<p>(The actuator can be triggered by sensor(s).)</p>
				<hr/>
				<div>
					<h4 class="vcenter" style="display:inline-block; margin-right:20px; margin-bottom:20px">Time Range</h4>
					<button type="button" class="vcenter btn btn-primary actuator-time-add-btn" style="display:inline-block;">Add Time Range</button>
					
				</div>
				
				<div id="actuator-timeMessage" style="height:60px;"><div class="alert alert-info" role="alert">The following are the existing Time Ranges.</div></div>
				
				<div id="timeRange">
					
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.modal -->


<!--Scripts--> 

<!--JQuery--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/jquery/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/jquery/jquery-ui.min.js"></script>

<!--Fullscreen--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/fullscreen/screenfull.min.js"></script> 

<!--NanoScroller--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/nanoscroller/jquery.nanoscroller.min.js"></script> 

<!--PowerWidgets--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/powerwidgets/powerwidgets.min.js"></script> 

<!--Bootstrap--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/bootstrap/bootstrap.min.js"></script> 

<!--Main App--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/scripts.js"></script>



<link href="<?php echo URLROOT; ?>/web/css/vendors/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet">
<script src="<?php echo URLROOT; ?>/web/js/vendors/bootstrap-switch/bootstrap-switch.min.js"></script>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/web/css/vendors/toastr/toastr.min.css">
<script src="<?php echo URLROOT; ?>/web/js/vendors/toastr/toastr.min.js"></script>

<script src="<?php echo URLROOT; ?>/web/js/vendors/jquery-timeago/jquery.timeago.min.js"></script>

<script src="<?php echo URLROOT; ?>/web/js/vendors/pusher/pusher.min.js"></script>

<script src="<?php echo URLROOT; ?>/web/js/all.php"></script>

<script>
$(document).ready(function() {
	$('.menu-room').addClass('active');
});


//pusher
Pusher.logToConsole = true;
	
var pusher = new Pusher('1bc88481522c1ebbf138', {
	encrypted: true
});
var sensorChannel = pusher.subscribe('sensor');
sensorChannel.bind('updateValue', function(data) {
	
	for(i in data.devices) {
		var sensor = data.devices[i];
		
		var sensorWidget = $('#sensor-' + sensor.sensorID);
		
		if(sensorWidget.find('.sensor-unit').html()) {
			var val = sensor.value;
		} else {
			if(sensor.value == 1) {
				//sensorWidget.switchClass('powerwidget-as-portlet-purple', "powerwidget-as-portlet-green", 1000);
				var val = 'Detected';
			} else {
				//sensorWidget.switchClass("powerwidget-as-portlet-green", 'powerwidget-as-portlet-purple', 1000);
				var val = 'Normal';
			}
		}
		var form = sensorWidget.find('form');
		
		//update the sensor value
		form.find('.sensor-value').fadeOut('slow', function () {
			//var val = (sensor.value == 1) ? 'Detected' : 'Normal';
			$(this).html(val);
			$(this).fadeIn('slow');
		});
		
	}
});
</script>

</body>
</html>