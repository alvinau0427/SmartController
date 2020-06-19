<?php 
	if($this->user == null) {
		return $response->withHeader('Location', URLROOT . '/login');
	} 
?>
<!DOCTYPE html>

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo TITLE; ?></title>
<link href="<?php echo URLROOT; ?>/web/css/styles.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo URLROOT; ?>/web/digitalhome_logo.ico" />
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/modernizr/modernizr.custom.js"></script>

<style>
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
          <li class="active">Dashboard</li>
        </ul>
      </div>
      <!--/Breadcrumb--> 
      
      
      <!-- Widget Row Start grid -->
      <div class="row" id="powerwidgets">
	  
        <div class="col-md-12 bootstrap-grid"> 
          
		  
			<!-- New widget -->
			<div class="powerwidget powerwidget-as-portlet powerwidget-as-portlet-blue" data-widget-editbutton="false" data-widget-deletebutton="false">
			  <header>
				<h2>Weather<small>with future 6 days</small></h2>
				<a href="#simulation-modal" role="button" class="btn btn-xs btn-primary" data-toggle="modal" style="margin-left:30px">
					<span class="fa fa-key"></span> Simulate
				</a>
			  </header>
			  <div class="inner-spacer nopadding">
				<div class="weather-current-city" style="padding:50px;">
				  <div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<span class="current-city">Hong Kong</span>
						<span class="current-temp"><?php echo round($weather[0]['CurrentTemp']); ?>&deg;C</span>
						<span class="current-day"><?php echo date('l, d F', $weather[0]['TimeStamp']); ?></span><br/>
						<span class="small">Last Updated at <?php echo substr($weather[0]['UpdateDateTime'], 11, 5); ?></span>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6"><span class="current-day-icon">
					  <canvas id="weather-icon-<?php echo $weather[0]['RecordID']; ?>" width="120" height="120"><?php echo $weather[0]['Icon'] ?></canvas>
					  </span></div>
				  </div>
				</div>
				<div class="row">
				  <ul class="days">
				<?php 
					for($i=1;$i<7;$i++) {
				?>
					<li class="col-md-2 col-sm-2 col-xs-2">
					  <strong><?php echo date('D, d', $weather[$i]['TimeStamp']); ?></strong>
					  <canvas id="weather-icon-<?php echo $weather[$i]['RecordID']; ?>" width="50" height="50"><?php echo $weather[$i]['Icon'] ?></canvas>
					  <span><?php echo round($weather[$i]['CurrentTemp']); ?>&deg;</span>
					</li>
				<?php 
					} 
				?>
				  </ul>
				</div>
			  </div>
			</div>
			<!-- /New widget --> 
		  
		  
          <!-- New widget -->
          <div class="powerwidget powerwidget-as-portlet-white" data-widget-editbutton="false" data-widget-deletebutton="false">
            <header>
              <h2>Actuators<small>switch on/off</small></h2>
            </header>
            <div>
              <div class="inner-spacer">
                <div class="row"> 
                  <!--Row-->
                  
                  <div class="col-md-12">
                    
                    <div class="row margin-bottom-10px">
                      <ul class="countries-demo" id="choices">
                        
						<?php
							foreach($actuatorArr as $actuator) {
						?>
						
                        <li id="actuator-<?php echo $actuator->getID(); ?>" class="col-lg-3 col-md-4 col-sm-4">
							<form class="nomargin">
								<div class="col-md-5 col-sm-5 vcenter">
									<?php 
										$actuatorImage = ($actuator->getStatus() == "1") ? 'on/' : 'off/';
									?>
									<img class="actuator-img" src="<?php echo URLROOT; ?>/web/images/actuators/<?php echo $actuatorImage . $actuator->getImage(); ?>" />
								</div><!--
								--><div class="col-md-7 col-sm-7 vcenter">
									<h3 style="padding-bottom:3px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;" data-toggle="tooltip" data-placement="bottom" title="<?php echo $actuator->getName(); ?>">
										<?php echo $actuator->getName(); ?>
									</h3><br/>
									<input type="hidden" name="actuatorID" value="<?php echo $actuator->getID(); ?>" />
									<input type="hidden" name="userID" value="<?php echo $this->user->getID(); ?>">
									<input type="hidden" name="permissionID" value="<?php echo $actuator->getPermission(); ?>">
									<div class="switch">
										<input type="checkbox" class="bootstrap-switch actuator-status-btn" data-on-color="success" data-label-width="20" data-handle-width="50" data-size="mini"
										<?php 
											echo $actuator->getStatus() == "1" ? 'checked':''; 
											if( ($actuator->getPermission() == "1") ||
												($actuator->getPermission() == "2" && $actuator->getStatus() == "1") || 
												($actuator->getPermission() == "3" && $actuator->getStatus() == "2") ) {
												echo 'disabled';
											}
										?> />
									</div>
								</div>
							</form>
                        </li>
						
						<?php 
							} 
						?>
						
                      </ul>
                    </div>
                    <!--/Row--> 
                    
                  </div>
                  <!--/Col-md-12--> 
                </div>
                <!--/Row--> 
                
              </div>
            </div>
          </div>
          <!-- End .powerwidget --> 
          
        </div>
        
        
      </div>
      <!-- /Widgets Row End Grid--> 
    </div>
    <!-- / Content Wrapper --> 
  </div>
  <!--/MainWrapper--> 
</div>
<!--/Smooth Scroll--> 

<!-- scroll top -->
<div class="scroll-top-wrapper hidden-xs"> <i class="fa fa-angle-up"></i> </div>
<!-- /scroll top --> 

<!--Modals--> 


<!--Simulation Modal -->
<div id="simulation-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Weather Simulation</h4>
			</div>
			<div class="modal-body">
				<h5>Simulate current weather.</h5>
				<labe>Set to Rain? </label>
				
				<input type="checkbox" id="weather-switch-btn" class="bootstrap-switch" data-on-color="info" data-off-color="warning" data-label-width="20" data-handle-width="80" data-size="large" data-on-text="Rain" data-off-text="Clear" 
				<?php echo ($weather[0]['Rain'] > 0) ? 'checked':''; ?> />
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

<!--SkyCons--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/skycons/skycons.js"></script> 


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
	
	$('.menu-index').addClass('active');
	
	$('[data-toggle="tooltip"]').tooltip();
	
	// weather canvas icon
	if($('canvas').length) {
		var icons = new Skycons({"color": "#fff"}),
			list  = {
				"01d":"clear-day", 
				"01n":"clear-night", 
				"02d":"partly-cloudy-day",
				"02n":"partly-cloudy-night", 
				"03d":"cloudy", 
				"03n":"cloudy", 
				"04d":"cloudy", 
				"04n":"cloudy", 
				"09d":"sleet", 
				"09n":"sleet", 
				"10d":"rain", 
				"10n":"rain", 
				"11d":"rain", 
				"11n":"rain", 
				"13d":"snow", 
				"13n":"snow", 
				"50d":"fog",
				"50n":"fog"
			}

		for(i = 1;i < 8; i++){
			var id = "weather-icon-" + i;
			icons.set(id, list[$('#'+id).text()]);
		}
		icons.play();
	}
	// weather canvas icon
	
	$('#weather-switch-btn').on('switchChange.bootstrapSwitch', function(event, isOnStatus) {
		
		const dateTime = Date.now();
		const timestamp = Math.floor(dateTime / 1000);
		var descript, rain, icon;

		if(isOnStatus) {
			descript = "Rain";
			rain = "10";
			icon = "10d";
		} else {
			descript = "Clear";
			rain = "0";
			icon = "01d";
		}

		$.ajax({
			url: "/FYP/api/weathers/simulations",
			type: "put",
			dataType: "json",
			data: {
				"recordID": "1",
				"description": descript,
				"timeStamp": timestamp,
				"rain": rain,
				"icon": icon,
			},
			success : function(result) {
				if(result.status == true) {
					//checkedRadio.closest('label').removeClass('btn-default');
				} else {
					//preCheckedRadio.closest('label').removeClass('btn-default');
				}
			},
			error : function() {
				alert('Error');
			}
		});
	});
	
});
</script>
<!--/Scripts-->



</body>
</html>