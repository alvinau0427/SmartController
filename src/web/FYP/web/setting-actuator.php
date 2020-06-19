<?php 
	if($this->user == null) {
		return $response->withHeader('Location', URLROOT . '/login');
	}
	if($this->user->getType() != "2") {
		return $response->withHeader('Location', URLROOT );
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

a {
	color: #555;
	text-decoration: none;
}
a:hover,
a:focus {
	color: #FFFFFF;
	text-decoration: underline;
}

a.list-group-item.active:focus {
	background-color: #3b8dbd;
}

.actuator-img{
    width: 64px;
    height: 64px;
}
a:hover .actuator-img{
	background-image: url(<?php echo URLROOT; ?>/web/images/actuators/white/img_light.png);
}

.actuator-time-form{
	margin-bottom:20px;
}
	


<?php 
	foreach($actuatorArr as $actuator) {
		echo '#actuator-img-' . $actuator->getID() . ' {' . PHP_EOL;
		echo 'background-image: url(' . URLROOT . '/web/images/actuators/off/' . $actuator->getImage() . ');' . PHP_EOL;
		echo '}' . PHP_EOL;
		
		echo 'a:hover #actuator-img-' . $actuator->getID() . ', ' . PHP_EOL;
		echo 'a:focus #actuator-img-' . $actuator->getID() . ' {' . PHP_EOL;
		echo 'background-image: url(' . URLROOT . '/web/images/actuators/white/' . $actuator->getImage() . ');' . PHP_EOL;
		echo '}' . PHP_EOL;
		
		echo PHP_EOL;
	}
?>
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
            <li><a href="#">Advanced Settings</a></li>
            <li class="active">Actuators</li>
          </ul>
        </div>
        <!--/Breadcrumb-->
        
        <div class="page-header">
          <h1>Settings<small>Actuators</small></h1>
        </div>
        
        <!-- Widget Row Start grid -->
        <div class="row" id="powerwidgets">
          <div class="col-md-12 bootstrap-grid"> 
            
            <!-- New widget -->
            
            <div class="powerwidget dark-blue" data-widget-editbutton="false" data-widget-deletebutton="false">
              <header>
                <h2>Actuators<small>select one</small></h2>
              </header>
              <div class="inner-spacer">
                <div class="row">
					<?php 
						foreach($actuatorArr as $actuator) {
					?>
					<div class="col-xs-6 col-md-3" style="display: table; height:150px; margin-bottom: 15px;">
						<a href="#" class="thumbnail actuator" style="text-decoration: none; display: table-cell; vertical-align: middle; ">
							<input type="hidden" name="actuatorID" value="<?php echo $actuator->getID(); ?>" />
							<input type="hidden" name="actuatorName" value="<?php echo $actuator->getName(); ?>" />
							<center>
								<div class="actuator-img" id="actuator-img-<?php echo $actuator->getID(); ?>"></div>
								<h3><?php echo $actuator->getName(); ?></h3>
							</center>
						</a>
					</div>
					<?php } ?>
				</div>
              </div>
            </div>
            
            <!-- End .powerwidget --> 
            
			<!-- Mode widget -->
            
            <div id="mode-powerwidget" class="powerwidget cold-grey" data-widget-editbutton="false" data-widget-collapsed="true" data-widget-deletebutton="false">
				<header>
					<h2>Mode</h2>
				</header>
				<div class="inner-spacer">
					<form id="mode-form" class="validate-form" role="form">
						<input type="hidden" name="actuatorID" value="" />
						<div class="btn-group  vcenter" data-toggle="buttons">
							<label class="btn btn-default" style="width:100px">
								<input type="radio" name="mode" value="1" autocomplete="off"> 
								Auto
							</label>
							<label class="btn btn-default" style="width:100px">
								<input type="radio" name="mode" value="2" autocomplete="off"> 
								Notification
							</label>
							<label class="btn btn-default" style="width:100px">
								<input type="radio" name="mode" value="3" autocomplete="off"> 
								On
							</label>
						</div>
					</form>
				</div>
            </div>
            
            <!-- Mode .powerwidget --> 
			
			<!-- Time Range widget -->
            
            <div id="timeRange-powerwidget" class="powerwidget cold-grey" data-widget-editbutton="false" data-widget-collapsed="true" data-widget-deletebutton="false">
				<header>
					<h2>Time Range Setting</h2>
				</header>
				<div class="inner-spacer">
					<div>
						<h4 class="vcenter" style="margin-right:20px; margin-bottom:20px">Time Range</h4>
						<button type="button" class="vcenter btn btn-primary actuator-time-add-btn" disabled="disabled">Add Time Range</button>
					</div>
					<h5>AUTO mode operates only in the following time range.</h5>
					<p>(The actuator can be triggered by sensor(s).)</p>
					
					<hr/>
					
					<div>
						<div id="actuator-timeMessage"><div class="alert alert-info" role="alert">The following are the existing Time Ranges.</div></div>
						
						<div id="timeRange">
							
						</div>
					</div>
				
				</div>
            </div>
            
            <!-- Time Range .powerwidget --> 
			
			<!-- Sensor widget -->
            
            <div id="sensor-powerwidget" class="powerwidget cold-grey" data-widget-editbutton="false" data-widget-collapsed="true" data-widget-deletebutton="false">
				<header>
					<h2>Sensors</h2>
				</header>
				<div class="inner-spacer">
					<button type="button" class="btn btn-lg btn-primary vcenter sensor-add-btn" disabled="disabled" style="width:150px;">
						Add Sensor
					</button>
					
					<form class="sensor-add-form-template hidden form-inline">
						<input type="hidden" name="actuatorID" value="" />
						<div class="form-group">
							<select class="form-control validate-select" name="sensorID">
								
							</select>
						</div>
						<div style="float: right;">
							<button type="button" class="btn btn-primary sensor-add-submit-btn">Add</button>
							&nbsp;<button type="button" class="btn btn-danger sensor-add-cancel-btn">Cancel</button>
						</div>
					</form>
						
					<hr/>
					
					<div>
						<div id="sensor-addMessage">
						</div>
						<ul id="sensor-group" class="list-unstyled" style="max-width: 400px;">
							
						</ul>
					</div>
				
				</div>
            </div>
            
            <!-- Sensor .powerwidget --> 
			
			<!-- Regulation widget -->
            
            <div id="regulation-powerwidget" class="powerwidget cold-grey" data-widget-editbutton="false" data-widget-collapsed="true" data-widget-deletebutton="false">
				<header>
					<h2>Policies</h2>
				</header>
				<div class="inner-spacer">
					<button type="button" class="btn btn-lg btn-primary vcenter regulation-add-btn" disabled="disabled" data-toggle="modal" data-target="#regulation-modal" style="width:150px;">
						Add Policy
					</button>
					<hr/>
					<div class="panel-group regulation-group" id="accordion" role="tablist" aria-multiselectable="true">
						
					</div>
				
				</div>
            </div>
            
            <!-- Regulation .powerwidget --> 
			
          </div>
          <!-- /Inner Row Col-md-12 --> 
        </div>
        <!-- /Widgets Row End Grid--> 
      </div>
      <!-- / Content Wrapper --> 
    </div>
    <!--/MainWrapper--> 
  </div>
<!--/Smooth Scroll--> 


<!-- scroll top -->
<div class="scroll-top-wrapper hidden-xs">
    <i class="fa fa-angle-up"></i>
</div>
<!-- /scroll top -->



<!--Modals-->

<!--Regulation Modal -->
<div id="regulation-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Policy Settings</h4>
			</div>
			<div class="modal-body">
				<div id="regulation-message">
					message
				</div>
				
				<form id="regulation-form" class="form-horizontal validate-form" role="form">
					<fieldset>
						<legend><strong>Policy</strong></legend>
						<input type="hidden" name="regulationID" value="" />
						<input type="hidden" name="actuatorID" value="" />
						<div class="form-group">
							<label for="actuatorName" class="col-sm-2 control-label">Actuator</label>
							<div class="col-sm-10">
								<h5 id="actuatorName"></h5>
							</div>
						</div>
						<div class="form-group">
							<label for="header" class="col-sm-2 control-label">Header</label>
							<div class="col-sm-10">
								<input type="text" class="form-control validate-input" name="header" id="header" placeholder="Policy Header" />
								<p class="text-danger warning-text hidden">Please enter Policy Header</p>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10">
								<textarea class="form-control validate-input" name="description" id="description" placeholder="Description" rows="3"></textarea>
								<p class="text-danger warning-text hidden">Please enter Description</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Status</label>
							<div class="col-sm-10">
								<div class="radio">
									<label>
										<input type="radio" name="status" value="1" checked="checked" />
										Open
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="status" value="2" />
										Close
									</label>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
				<br/>
				<h5>Conditions</h5>
				<hr/>
				<!--hidden-->
				<form class="form-inline hidden condition-temp-form validate-form" role="form">
					<input type="hidden" name="conditionID" value="" />
					<div class="form-group">
						<div class="input-group">
							<select class="form-control validate-select" name="sensorID">
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<select class="form-control validate-select" name="operatorID">
								<option value="">~Select Comparison~</option>
								<option value="1">equal to</option>
								<option value="2">not equal to</option>
								<option value="3">less than</option>
								<option value="4">less than or equal to</option>
								<option value="5">greater than</option>
								<option value="6">greater than or equal to</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group condition-value">
							<select class="form-control validate-select" name="value">
								<option value="">~Select Status~</option>
								<option value="0">Normal</option>
								<option value="1">Detected</option>
							</select>
						</div>
					</div>
					
					
					<div class="btn-group col-md-6 col-md-offset-3" data-toggle="buttons" style="margin-bottom:10px;margin-top:10px;">
						<label class="btn btn-info active">
							<input type="radio" name="logicGate" value="" autocomplete="off" checked="checked" /> 
							None
						</label>
						<label class="btn btn-default">
							<input type="radio" name="logicGate" value="O" autocomplete="off" /> 
							OR
						</label>
						<label class="btn btn-default">
							<input type="radio" name="logicGate" value="N" autocomplete="off" /> 
							AND
						</label>
					</div>
				</form>
				<!--//hidden-->
				
			</div>
			<div class="modal-footer">
				<button type="button" id="regulation-submit-btn" class="btn btn-primary regulation-add-submit-btn">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /.modal -->


<!--Regulation Delete Modal -->
<div id="regulation-remove-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Remove Policy</h4>
			</div>
			<div class="modal-body">
				<h5>Are you sure you want to remove this Policy?</h5>
				<blockquote>
					<p id="regulation-remove-header"></p>
				</blockquote>
				<input type="hidden" name="regulationID" value="" />
			</div>
			<div class="modal-footer">
				<button type="button" id="regulation-remove-submit-btn" class="btn btn-danger">Remove</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- /.Regulation Delete Modal -->


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



<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/web/css/vendors/jquery-timepicker/jquery.timepicker.css" />
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/jquery-timepicker/jquery.timepicker.js"></script>


<link href="<?php echo URLROOT; ?>/web/css/vendors/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet">
<script src="<?php echo URLROOT; ?>/web/js/vendors/bootstrap-switch/bootstrap-switch.min.js"></script>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/web/css/vendors/toastr/toastr.min.css">
<script src="<?php echo URLROOT; ?>/web/js/vendors/toastr/toastr.min.js"></script>

<script src="<?php echo URLROOT; ?>/web/js/vendors/jquery-timeago/jquery.timeago.min.js"></script>

<script src="<?php echo URLROOT; ?>/web/js/vendors/pusher/pusher.min.js"></script>

<script src="<?php echo URLROOT; ?>/web/js/all.php"></script>

<script>
$(document).ready(function() {
	
	$('.menu-setting').addClass('active');
	
	var selectedActuator;
	
	$('a.actuator').on('focus', function(e) {
		if(!selectedActuator) {
			// open powerwidget
			$('#mode-powerwidget .powerwidget-toggle-btn').trigger('click');
			$('#timeRange-powerwidget .powerwidget-toggle-btn').trigger('click');
			$('#sensor-powerwidget .powerwidget-toggle-btn').trigger('click');
			$('#regulation-powerwidget .powerwidget-toggle-btn').trigger('click');
		}
		
		selectedActuator = $(this);
		
		// able the add button
		$('.regulation-add-btn').prop("disabled", false);
		$('.sensor-add-btn').prop("disabled", false);
		$('.actuator-time-add-btn').prop("disabled", false);
		
		reloadEveryThing();
	});
	
	
	function reloadEveryThing() {
		loadActuatorMode();
		loadActuatorRegulation();
		loadActuatorSensors();
		loadTimeRange();
	}
	
	
	// mode
	function loadActuatorMode() {
		var actuatorID = selectedActuator.find('input[name="actuatorID"]').val();
		
		//get this actuator
		$.ajax({
			url: '/FYP/api/actuators/' + actuatorID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var actuator = result.data;
				var actuatorID = actuator.ActuatorID;
				var mode = actuator.ModeID;
				
				modeForm = $('#mode-form');
				
				modeForm.find('input[name="actuatorID"]').val(actuatorID);
				modeForm.find('label').switchClass('btn-info', 'btn-default', 0);
				modeForm.find('input[name="mode"][value="' + mode + '"]').prop('checked', true).closest('label').switchClass('btn-default', 'btn-info', 0);
			}
		});
	}
	
	$(document).on('change', 'input[name="mode"]', function() {
		var selectedMode = $(this);
		var modeForm = $(this).closest('form#mode-form');
		
		console.log(modeForm.serialize())
		$.ajax({
			url: '/FYP/api/actuators/mode',
			type: 'PUT',
			dataType: "json",
			data: modeForm.serialize(),
			success : function(result) {
				if(result.status == true) {
					//change the color
					modeForm.find('label').switchClass('btn-info', 'btn-default', 0);
					selectedMode.closest('label').switchClass('btn-default', 'btn-info', 0);
				} else {
					//change back the color
					selectedMode.closest('label').switchClass('btn-info', 'btn-default', 0);
				}
				
			}
		});
		
		
	});
	// mode
	
	
	
	// timerange
	function loadTimeRange() {
		
		var actuatorID = selectedActuator.find('input[name="actuatorID"]').val();
		
		$('#actuator-timeMessage').empty();
		
		$.ajax({
			url: '/FYP/api/times/actuators/' + actuatorID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				$('#timeRange').empty();
				
				for(i=0;i<result.data.length;i++) {
					var timeRule = result.data[i];
					var timeRangeForm = $('<form class="form-inline actuator-time-form"></form>')
							.append('<input type="hidden" name="timeID" value="'+timeRule.TimeID+'" />'+
									'<div class="form-group">' + 
										'<label for="startTime-'+timeRule.TimeID+'">From&nbsp;</label>'+
										'<input type="text" class="form-control startTime timepicker" id="startTime-'+timeRule.TimeID+'" name="startTime" value="'+timeRule.StartTime+'" size="8"/>'+
									'</div>'+
									'<div class="form-group">'+
										'<label  for="endTime-'+timeRule.TimeID+'">&nbsp;To&nbsp;</label>'+
										'<input type="text" class="form-control endTime timepicker" id="endTime-'+timeRule.TimeID+'" name="endTime" value="'+timeRule.EndTime+'" size="8"/>'+
									'</div>'+
									'&nbsp;<button type="button" class="btn btn-default actuator-time-edit-btn" disabled>Edit</button>'+
									'&nbsp;<button type="button" class="btn btn-danger actuator-time-remove-btn">Remove</button>');
									
					$('#timeRange').append(timeRangeForm);
					
					$('#startTime-' + timeRule.TimeID).timepicker({ 'timeFormat': 'H:i:s' });
					$('#endTime-' + timeRule.TimeID).timepicker({ 'timeFormat': 'H:i:s' });
				}
				
				
				
				
				$('.actuator-time-edit-btn').on('click', function() {
					var form = $(this).closest('form.actuator-time-form');
					var editBtn = $(this);
					
					$.ajax({
						url: "/FYP/api/times",
						type: "PUT",
						dataType: "json",
						data: form.serialize(),
						success : function(result) {
							if(result.status == true) {
								form.effect( "highlight", 1000 );
								editBtn.switchClass( "btn-warning", "btn-default").attr('disabled', true);
								
								var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Update the Time Range.</div>');
							} else {
								var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Time Range has not been updated.</div>');
							}
							$('#actuator-timeMessage').html(message);
						},
						error : function() {
							var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
							$('#actuator-timeMessage').html(message);
						}
					});
				});
				
				$('.actuator-time-remove-btn').on('click', function() {
					var form = $(this).closest('form.actuator-time-form');
					
					$.ajax({
						url: "/FYP/api/times",
						type: "DELETE",
						dataType: "json",
						data: form.serialize(),
						success : function(result) {
							if(result.status == true) {
								form.remove();
								
								var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Remove the Time Range.</div>');
							} else {
								var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Time Range has not been removed.</div>');
							}
							$('#actuator-timeMessage').html(message);
						},
						error : function() {
							var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
							$('#actuator-timeMessage').html(message);
						}
					});
				});
				
				$('.timepicker').on('change', function() {
					timeValidation($(this));
				});
				
			},
			error : function() {
				alert('Error');
			}
		});
		
		$('.actuator-time-add-btn').on('click', function() {
			if($('#actuator-time-add-form').length)
				return;
			
			var timeRangeForm = $('<form id="actuator-time-add-form" class="form-inline actuator-time-form"></form>')
					.append('<input type="hidden" name="actuatorID" value="'+actuatorID+'" />'+
							'<div class="form-group">' + 
								'<label for="startTime">From&nbsp;</label>'+
								'<input type="text" class="form-control startTime timepicker" id="startTime" name="startTime" value="00:00:00" size="8"/>'+
							'</div>'+
							'<div class="form-group">'+
								'<label  for="endTime">&nbsp;To&nbsp;</label>'+
								'<input type="text" class="form-control endTime timepicker" id="endTime" name="endTime" value="23:59:59" size="8"/>'+
							'</div>'+
							'&nbsp;<button type="button" class="btn btn-primary actuator-time-add-submit-btn">Add</button>'+
							'&nbsp;<button type="button" class="btn btn-danger actuator-time-add-cancel-btn">Cancel</button>');
			$('#timeRange').append(timeRangeForm);
			
			$('#startTime').timepicker({ 'timeFormat': 'H:i:s' });
			$('#endTime').timepicker({ 'timeFormat': 'H:i:s' });
			
			$('.actuator-time-add-submit-btn').on('click', function() {
				var form = $(this).closest('form#actuator-time-add-form');
				
				$.ajax({
					url: "/FYP/api/times",
					type: "POST",
					dataType: "json",
					data: form.serialize(),
					success : function(result) {
						if(result.status == true) {
							loadTimeRange();
							
							var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Add the Time Range.</div>');
						} else {
							var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Time Range has not been added.</div>');
						}
						$('#actuator-timeMessage').html(message);
					},
					error : function() {
						var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
						$('#actuator-timeMessage').html(message);
					}
				});
			});
			
			$('.actuator-time-add-cancel-btn').on('click', function() {
				var form = $(this).closest('form');
				
				form.remove();
			});
			
			$('.timepicker').on('change', function() {
				timeValidation($(this));
			});
			
		});
		
		
		function timeValidation(timePicker) {
					
			var form = timePicker.closest('form');
			var startTime = form.find('.startTime').val();
			var endTime = form.find('.endTime').val();
			
			$('#actuator-timeMessage').empty();
			
			if( isTime(startTime) && isTime(endTime) ) {
				
				//check endTime bigger than startTime
				if( endTime > startTime ) {
					if(form.find('.actuator-time-edit-btn').length)
						form.find('.actuator-time-edit-btn').removeAttr("disabled").switchClass( "btn-default", "btn-warning"); 
					else
						form.find('.actuator-time-add-submit-btn').removeAttr("disabled").switchClass( "btn-default", "btn-primary"); 
					
				} else {
					if(form.find('.actuator-time-edit-btn').length)
						form.find('.actuator-time-edit-btn').attr('disabled', true).switchClass( "btn-warning", "btn-default");
					else
						form.find('.actuator-time-add-submit-btn').attr('disabled', true).switchClass( "btn-primary", "btn-default");
					
					var message = $('<div class="alert alert-warning" role="alert"><strong>End Time</strong> must be greater than <strong>Start Time</strong>.</div>');
					$('#actuator-timeMessage').html(message);
				}
			} else {
				if(form.find('.actuator-time-edit-btn').length)
					form.find('.actuator-time-edit-btn').attr('disabled', true).switchClass( "btn-warning", "btn-default");
				else
					form.find('.actuator-time-add-submit-btn').attr('disabled', true).switchClass( "btn-primary", "btn-default");
				
				var message = $('<div class="alert alert-warning" role="alert">Time format must be <strong>hh:mm:ss</strong>.</div>');
				$('#actuator-timeMessage').html(message);
			}
			
			function isTime(time) {
				var valid = (time.search(/^\d{2}:\d{2}:\d{2}$/) != -1) &&
					(time.substr(0,2) >= 0 && time.substr(0,2) <= 23) &&
					(time.substr(3,2) >= 0 && time.substr(3,2) <= 59) &&
					(time.substr(6,2) >= 0 && time.substr(6,2) <= 59);
					
				return valid;
			}
		}
	}
	// timerange
	
	
	
	// sensors
	function loadActuatorSensors() {
		var actuatorID = selectedActuator.find('input[name="actuatorID"]').val();
		var existSensor = [];
		
		var addSensorForm = $('.sensor-add-form-template');
		addSensorForm.find('input[name="actuatorID"]').val(actuatorID);
		
		var selectSensor = addSensorForm.find('select[name="sensorID"]');
		selectSensor.empty();
		selectSensor.append('<option value="">~Select Sensor~</option>');
		
		$('#sensor-addMessage').empty();
		
		//get all related sensor
		$.ajax({
			url: '/FYP/api/sensors/actuators/' + actuatorID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				$('#sensor-group').empty();
							
				for(i=0;i<result.data.length;i++) {
					var sensor = result.data[i];
					
					var sensorLi = $('<li style="margin-bottom:10px"></li>')
							.append('<h4 class="vcenter">'+ sensor.SensorName +'</h4>'+
									'<button type="button" class="btn btn-danger vcenter sensor-remove-btn" style="float: right;">' +
									'Remove<input type="hidden" name="actuatorSensorID" value="'+ sensor.ActuatorSensorID +'" />'+
									'</button>');
					
					
					$('#sensor-group').append(sensorLi);
					
					existSensor.push(sensor.SensorID);
				}
				$.ajax({
					url: '/FYP/api/sensors',
					type: 'GET',
					dataType: "json",
					async: false,
					success : function(sensorResult) {
						
						for(i=0;i<sensorResult.data.length;i++) {
							var sen = sensorResult.data[i];
							if( existSensor.indexOf(sen.SensorID) == -1 ) {
								selectSensor.append('<option value="'+ sen.SensorID +'">'+ sen.SensorName +'</option>');
							}
						}
						
					}
				});
			}
		});
		
		// add button
		$('.sensor-add-btn').on('click', function() {
			if($('.sensor-add-form').length)
				return;
			
			var sensorForm = $('.sensor-add-form-template').clone();
			sensorForm.removeClass('hidden').switchClass('sensor-add-form-template', 'sensor-add-form',0);
			
			
			var sensorLi = $('<li style="margin-bottom:10px"></li>').append(sensorForm);
			$('#sensor-group').append(sensorLi);
		});
		
		$(document).on('click', '.sensor-add-cancel-btn', function() {
			var li = $(this).closest('li');
			
			li.remove();
		});
		
	}
	
	$(document).on('click', '.sensor-add-submit-btn', function() {
		var form = $(this).closest('form.sensor-add-form');
		
		$.ajax({
			url: "/FYP/api/actuators/sensors",
			type: "POST",
			dataType: "json",
			data: form.serialize(),
			success : function(result) {
				if(result.status == true) {
					reloadEveryThing();
					
					var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Add the Sensor.</div>');
				} else {
					var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Sensor has not been added.</div>');
				}
				$('#sensor-addMessage').html(message);
			},
			error : function() {
				var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
				$('#sensor-addMessage').html(message);
			}
		});
	});
	
	// remove sensor
	$(document).on('click', '.sensor-remove-btn', function() {
		var actuatorSensorID = $(this).find('input[name="actuatorSensorID"]').val();
		
		$.ajax({
			url: "/FYP/api/actuators/sensors",
			type: "DELETE",
			dataType: "json",
			data: {"actuatorSensorID":actuatorSensorID},
			success : function(result) {
				if(result.status == true) {
					reloadEveryThing();
					
					var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Remove the Sensor.</div>');
				} else {
					var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Sensor has not been removed.<br/>Please remove the relevant Policy first</div>');
				}
				$('#sensor-addMessage').html(message);
			},
			error : function() {
				var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
				$('#sensor-addMessage').html(message);
			}
		});
	});
	// sensors
	
	
	
	
	// regulation
	function loadActuatorRegulation() {
		var actuatorID = selectedActuator.find('input[name="actuatorID"]').val();
		var actuatorName = selectedActuator.find('input[name="actuatorName"]').val();
		
		//get all regulation with this actuator
		$.ajax({
			url: '/FYP/api/regulations/actuators/' + actuatorID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				
				var regulationGroup = $('.regulation-group');
				regulationGroup.empty();
				
				
				for(i=0;i<result.data.length;i++) {
					var regulation = result.data[i];
					
					var regulationID = regulation.RegulationID;
					
					var priorityGroup = $('<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="width:50px"></div>')
							.append('<button type="button" class="btn btn-xs btn-primary regulation-up-btn" style="border-radius: 0;">' +
										'<span class="glyphicon glyphicon-chevron-up"></span>' +
										'<input type="hidden" name="regulationID" value="' + regulationID + '" />' +
										'<input type="hidden" name="priority" value="' + regulation.Priority + '" />' +
									'</button><br/>' +
									'<button type="button" class="btn btn-xs btn-primary regulation-down-btn" style="border-radius: 0;">' +
										'<span class="glyphicon glyphicon-chevron-down"></span>' +
										'<input type="hidden" name="regulationID" value="' + regulationID + '" />' +
										'<input type="hidden" name="priority" value="' + regulation.Priority + '" />' +
									'</button>');
					
					if(i == 0) {
						priorityGroup.find('.regulation-up-btn').addClass('disabled')
					}
					if(i == result.data.length - 1) {
						priorityGroup.find('.regulation-down-btn').addClass('disabled')
					}
					
					var regForm = $('<div class="col-xs-6 col-sm-7 col-md-8 col-lg-8"></div>')
						.append('<div id="regulation-' + regulationID + '" class="panel panel-primary">' +
									'<div class="panel-heading" role="tab" id="regulation-heading-' + regulationID + '" >' +
										'<h4 class="panel-title">' +
											'<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#regulation-collapse-' + regulationID + '" aria-expanded="false" aria-controls="regulation-collapse-' + regulationID + '">' +
											regulation.Header +
											'</a>' +
										'</h4>' +
									'</div>' +
									'<div id="regulation-collapse-' + regulationID + '" class="regulation-collapse panel-collapse collapse" role="tabpanel" aria-labelledby="regulation-heading-' + regulationID + '">' +
										'<div class="panel-body" style="font-size: 18px;">' +
											'<div class="row">' +
												'<div class="col-md-12"><strong>Description : </strong>' + regulation.RegulationDescription + '</div>' +
												'<div class="col-md-12"><strong>Status : </strong>' + regulation.ActuatorStatusDescription + '</div>' +
												'<div class="col-md-12" style="margin-bottom:20px;"></div>' +
												'<div class="col-md-12">' +
													'<strong>Conditions : </strong>' +
													'<div class="condition"></div>' +
												'</div>' +
											'</div>' +
										'</div>' +
									'</div>' +
								'</div>');
					
					var btnGroup = $('<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"></div>')
							.append('&nbsp;<button type="button" class="btn btn-warning regulation-edit-btn" data-toggle="modal" data-target="#regulation-modal">Edit' +
										'<input type="hidden" name="regulationID" value="' + regulationID + '" />' +
									'</button>' +
									'&nbsp;&nbsp;<button type="button" class="btn btn-danger regulation-remove-btn" data-toggle="modal" data-target="#regulation-remove-modal" >Remove' +
										'<input type="hidden" name="regulationID" value="' + regulationID + '" />' +
									'</button>');
					var rowDiv = $('<div class="row" style="margin-bottom:10px">').append(priorityGroup).append(regForm).append(btnGroup);		
					regulationGroup.append(rowDiv);
					
					$.ajax({
						url: '/FYP/api/conditions/regulations/' + regulationID,
						type: 'GET',
						dataType: "json",
						success : function(result) {
							
							for(i=0;i<result.data.length;i++) {
								
								var condition = result.data[i];
								
								var conList = $('<ul class="list-inline"></ul>')
										.append('<li>' + condition.SensorName + ' Value</li>' + 
												'<li>' + condition.ComparisonOperatorName + '</li>' + 
												'<li>' + condition.Value + '</li>');
										
								$('#regulation-' + condition.RegulationID + ' .condition').append(conList);
							}
							
						},
						error : function() {
							console.log('error')
						}
					});
				}
				
				//config modal part
				var regMod = $('#regulation-modal');
				regMod.find('form input[name="actuatorID"]').val(actuatorID);
				regMod.find('#actuatorName').text(actuatorName);
				
				//get all sensors related with this actuator
				$.ajax({
					url: '/FYP/api/sensors/actuators/' + actuatorID,
					type: 'GET',
					dataType: "json",
					success : function(result) {
						
						var sensorSelect = regMod.find('select[name="sensorID"]')
						sensorSelect.empty();
						sensorSelect.append('<option value="">~Select Sensor~</option>');
						
						for(i=0;i<result.data.length;i++) {
							
							var sensor = result.data[i];
							
							sensorSelect.append('<option value="' + sensor.SensorID + '" data-unit="' + sensor.SensorUnit + '">' + sensor.SensorName + '</option>');
							
						}
						
					},
					error : function() {
						console.log('error')
					}
				});
					
				
			}
		});
		
	}
	
	
	//modal config
	$('#regulation-modal').on('show.bs.modal', function (e) {
		var regForm = $('#regulation-form');
		var conForms = $('.condition-form');
		formRecovery(regForm);
		formRecovery(conForms);
		
		var tempForm = $('.condition-temp-form:first');
		tempForm.nextAll().remove();
		
		var newForm = tempForm.clone();
		newForm.removeClass('hidden').switchClass('condition-temp-form', 'condition-form');
		tempForm.after(newForm);
	});
	
	
	$(document).on('change', 'input[name="logicGate"]', function() {
		var logic = $(this).val();
		var thisForm = $(this).closest('form.condition-form');
		var nextForm = thisForm.next();
		
		
		if(logic && !nextForm.length) {
			//create a new form
			var newForm = $('.condition-temp-form:first').clone();
			newForm.removeClass('hidden').switchClass('condition-temp-form', 'condition-form');
			thisForm.after(newForm);
		} else if(!logic && nextForm.length) {
			//remove all the next form
			thisForm.nextAll().remove();
		}
		
		
		//change the color
		$(this).closest('form').find('label').switchClass('btn-info', 'btn-default', 0);
		$(this).closest('label').switchClass('btn-default', 'btn-info', 0);
	});
	
	
	
	//config sensor value or status
	$(document).on('change', 'select[name="sensorID"]', function() {
		
		var selectedSensor = $(this).find(':selected');
		var unit = selectedSensor.data('unit');
		
		var thisForm = $(this).closest('form');
		var valueDiv = thisForm.find('.condition-value');
		var comparison = thisForm.find('select[name="operatorID"]');
		valueDiv.empty();
		if(unit) { //if it has unit
			valueDiv.append('<input type="text" class="form-control numbersOnly validate-select" name="value" placeholder="Value" size="5" />' + 
								'<span class="input-group-addon">' + unit + '</span>');
								
			
			comparison.prop('disabled', false);
		} else {
			valueDiv.append('<select class="form-control validate-select" name="value">' +
								'<option value="">~Select Status~</option>' +
								'<option value="0">Normal</option>' +
								'<option value="1">Detected</option>' +
							'</select>');
							
			comparison.val(1);
			comparison.prop('disabled', true);
		}
	});
		
	
	// add regulation
	$('.regulation-add-btn').on('click', function() {
		
		var regMod = $('#regulation-modal');
		regMod.find('.modal-title').text('Add Policy');
		regMod.find('#regulation-message').empty();
		regMod.find('#regulation-submit-btn').text('Add').switchClass('btn-warning', 'btn-primary', 0).switchClass('regulation-edit-submit-btn', 'regulation-add-submit-btn', 0);
		
		regMod.find('input[name="header"]').val('');
		regMod.find('textarea[name="description"]').val('');
		regMod.find('input[name="status"][value="1"]').prop('checked',true);
		
	});
	
	$(document).on('click', '.regulation-add-submit-btn', function() {
			
		var regForm = $('#regulation-form');
		var conForms = $('.condition-form');
		
		if(!(formValidation(regForm) & formValidation(conForms)))
			return false;
		
		//console.log(regForm.serialize())
		$.ajax({
			url: '/FYP/api/regulations',
			type: 'POST',
			data: regForm.serialize(),
			dataType: "json",
			async: false,
			success : function(regResult) {
				//alert(JSON.stringify(regResult))
				if(regResult.status) {
					var regulationID = parseInt(regResult.data);
					
					var logicGate = '';
					
					conForms.each(function(i, conForm) {
						//alert(JSON.stringify($(this).serialize()))
						
						//let the disabled operatorID available to serialize
						$('select[name="operatorID"]').prop('disabled',false);
						
						$.ajax({
							url: '/FYP/api/conditions',
							type: 'POST',
							data: $(conForm).serialize() + '&regulationID=' + regulationID,
							dataType: "json",
							async: false,
							success : function(conResult) {
								if(conResult.status) {
									var conditionID = parseInt(conResult.data);
									
									logicGate += ' ' + conditionID + ' ' + $(conForm).find('[name="logicGate"]:checked').val();
									
								} else {
									alert('Error' + JSON.stringify(conResult))
								}
							}
						});
						
						updateLogicGate(regulationID, logicGate);
						$('#regulation-modal').modal('hide');
						
						loadActuatorRegulation();
						
					});
					
				} else {
					alert(JSON.stringify(regResult))
				}
				
			}
		});
		
	});
	// add regulation
	
	
	// edit regulation
	$(document).on('click', '.regulation-edit-btn', function() {
		
		var regulationID = $(this).find('input[name="regulationID"]').val();
		var regMod = $('#regulation-modal');
		
		regMod.find('input[name="regulationID"]').val(regulationID);
		regMod.find('.modal-title').text('Edit Policy');
		regMod.find('#regulation-message').empty();
		regMod.find('#regulation-submit-btn').text('Edit').switchClass('btn-primary', 'btn-warning', 0).switchClass('regulation-add-submit-btn', 'regulation-edit-submit-btn', 0);
		
		//get regulation
		$.ajax({
			url: '/FYP/api/regulations/' + regulationID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var reg = result.data;
				regMod.find('input[name="header"]').val(reg.Header);
				regMod.find('textarea[name="description"]').val(reg.RegulationDescription);
				regMod.find('input[name="status"][value="' + reg.ActuatorStatusID + '"]').prop('checked', true);
				
				var logicGate = reg.LogicGate;
				var logicArr = logicGate.split(" ");
				
				
				//get conditions
				var conForm;
				$.each(logicArr, function(i, logic) {
					if( !isNaN( parseInt(logic) ) )  { // is digit
						$.ajax({
							url: '/FYP/api/conditions/' + logic,
							type: 'GET',
							dataType: "json",
							async: false,
							success : function(result) {
								var condition = result.data;
								
								conForm = $('.condition-form:last');
								conForm.find('input[name="conditionID"]').val(condition.ConditionID);
								conForm.find('select[name="sensorID"]').val(condition.SensorID).trigger("change");
								conForm.find('select[name="operatorID"]').val(condition.ComparisonOperatorID);
								conForm.find('[name="value"]').val(condition.Value);
							}
						});
					} else {
						conForm.find('input[name="logicGate"][value="' + logic + '"]').prop('checked', true).trigger('click');
					}
				});
			}
		});
	});
	
	
	$(document).on('click', '.regulation-edit-submit-btn', function() {
		
		var regForm = $('#regulation-form');
		var conForms = $('.condition-form');
		
		if(!(formValidation(regForm) & formValidation(conForms)))
			return false;
		
		//console.log(regForm.serialize())
		$.ajax({
			url: '/FYP/api/regulations',
			type: 'PUT',
			data: regForm.serialize(),
			dataType: "json",
			async: false,
			success : function(regResult) {
				if(regResult.status) {
					/*message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Edit the Regulation.</div>');
					selectedActuator.trigger("focus");
					$('#regulation-message').empty().append(message);*/
				} 
				
			}
		});
		
		var regulationID = regForm.find('input[name="regulationID"]').val();
		
		//let the disabled operatorID available to serialize
		$('select[name="operatorID"]').prop('disabled',false);
		
		//alert(conForms.length);
		var logicGate = '';
		conForms.each(function(i, conForm) {
			console.log($(conForm).serialize())
			//alert(JSON.stringify($(this).serialize()))
			conForm
			var conditionID = $(conForm).find('input[name="conditionID"]').val();
			var logic = $(conForm).find('[name="logicGate"]:checked').val();
			
			if(conditionID) { // update
					
				$.ajax({
					url: '/FYP/api/conditions',
					type: 'PUT',
					data: $(conForm).serialize(),
					dataType: "json",
					async: false,
					success : function(conResult) {
						
						if(conResult.status) {
							/*message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Edit the Regulation.</div>');
							selectedActuator.trigger("focus");
							$('#regulation-message').empty().append(message);*/
						}
							
						logicGate += ' ' + conditionID + ' ' + logic;
					}
				});
				
			} else { // create
				
				$.ajax({
					url: '/FYP/api/conditions',
					type: 'POST',
					data: $(conForm).serialize() + '&regulationID=' + regulationID,
					dataType: "json",
					async: false,
					success : function(conResult) {
						if(conResult.status) {
							/*message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Edit the Regulation.</div>');
							selectedActuator.trigger("focus");
							$('#regulation-message').empty().append(message);*/
						}
						var conditionID = parseInt(conResult.data);
							
						logicGate += ' ' + conditionID + ' ' + logic;
					}
				});
				
			}
		});
		
		updateLogicGate(regulationID, logicGate);
		$('#regulation-modal').modal('hide');
		
		loadActuatorRegulation();
		
	});
	// edit regulation
	
	
	function updateLogicGate(regulationID, logicGate) {
		var result;
		logicGate = logicGate.trim();
							
		$.ajax({
			url: '/FYP/api/regulations/logicgate',
			type: 'PUT',
			data: {regulationID: regulationID,
					logicGate: logicGate},
			dataType: "json",
			async: false,
			error: function() {
				result = false;
			},
			success : function(updateResult) {
				if(updateResult.status) {
					result = true;
					//var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Add the Regulation.</div>');
				} else {
					result = false;
					//var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong>  The Regulation has not been added.</div>');
				}
				//$('#regulation-message').empty().append(message);
			}
		});
		return result;
	}
	
	
	// delete regulation
	$(document).on('click', '.regulation-remove-btn', function() {
		
		var regulationID = $(this).find('input[name="regulationID"]').val();
		
		$.ajax({
			url: '/FYP/api/regulations/' + regulationID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var reg = result.data;
				
				var regReMod = $('#regulation-remove-modal');
				regReMod.find('#regulation-remove-header').text(reg.Header);
				regReMod.find('input[name="regulationID"]').val(reg.RegulationID);
			}
		});
		
	});
	
	
	$('#regulation-remove-submit-btn').on('click', function() {
		
		var regReMod = $(this).closest('#regulation-remove-modal');
		var regulationID = regReMod.find('input[name="regulationID"]').val();
		
		$.ajax({
			url: '/FYP/api/regulations',
			type: 'DELETE',
			data: {regulationID: regulationID},
			dataType: "json",
			success : function(result) {
				if(result.status) {
					regReMod.modal('hide');
					
					loadActuatorRegulation();
				}
			}
		});
	});
	// delete regulation
	
	
	// update priority
	$(document).on('click', '.regulation-up-btn, .regulation-down-btn', function() {
		
		var regulationID = $(this).find('input[name="regulationID"]').val();
		var priority = $(this).find('input[name="priority"]').val();
		
		if($(this).hasClass('regulation-up-btn')) { // if up priority
			--priority;
		} else {	// if down priority
			++priority;
		}
		
		
		$.ajax({
			url: '/FYP/api/regulations/priority',
			type: 'PUT',
			data: {regulationID: regulationID,
					priority: priority},
			dataType: "json",
			success : function(result) {
				if(result.status) {
					loadActuatorRegulation();
				}
			}
		});
	});
	// update priority
});

</script>

<!--/Scripts-->

</body>
</html>