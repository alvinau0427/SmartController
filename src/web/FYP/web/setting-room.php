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

.actuator-img{
    width: 17px;
    height: 17px;
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
            <li><a href="#">Advanced Settings</a></li>
            <li class="active">Rooms</li>
          </ul>
        </div>
        <!--/Breadcrumb-->
        
        <div class="page-header">
          <h1>Settings<small>Rooms</small></h1>
        </div>
        
        <!-- Widget Row Start grid -->
        <div class="row" id="powerwidgets">
          <div class="col-md-12 bootstrap-grid"> 
			
			<!-- Room widget -->
            
            <div id="room-powerwidget" class="powerwidget dark-blue" data-widget-editbutton="false" data-widget-deletebutton="false">
				<header>
					<h2>Rooms</h2>
				</header>
				<div class="inner-spacer">
					<button type="button" class="btn btn-lg btn-primary vcenter room-add-btn" style="width:150px;" data-toggle="modal" data-target="#room-modal">
						New Room
					</button>
						
					<hr/>
					
					<div>
						<div id="actuator-addMessage">
						</div>
						
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<?php
								foreach($roomArr as $room) {
									$roomID = $room->getID();
							?>
							
							<form class="actuator-add-form-template-<?php echo $room->getID(); ?> hidden form-inline">
								<input type="hidden" name="roomID" value="<?php echo $room->getID(); ?>" />
								<div class="form-group">
									<select class="form-control validate-select" name="actuatorID">
										
									</select>
								</div>
								<div style="float: right;">
									<button type="button" class="btn btn-primary actuator-add-submit-btn">Allocate</button>
									&nbsp;<button type="button" class="btn btn-danger actuator-add-cancel-btn">Cancel</button>
								</div>
							</form>
							
							<div class="col-xs-6 col-sm-7 col-md-8 col-lg-8">
								<div id="room-<?php echo $roomID; ?>" class="panel panel-default" style="margin-bottom: 50px">
									<div class="panel-heading" role="tab" id="room-heading-<?php echo $room->getID(); ?>" style="padding: 17px 10px;background-color: #595f66;">
										<h4 class="panel-title">
											<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#room-collapse-<?php echo $roomID; ?>" aria-expanded="false" aria-controls="room-collapse-<?php echo $roomID; ?>">
												<?php echo $room->getName(); ?>
											</a>
										</h4>
									</div>
									<div id="room-collapse-<?php echo $roomID; ?>" class="room-collapse panel-collapse collapse" role="tabpanel" aria-labelledby="room-heading-<?php echo $roomID; ?>">
										<div class="panel-body">
											<div>
												<h4 class="vcenter" style="margin-right:20px;">Actuators :</h4>
												<button type="button" class="btn btn-primary vcenter actuator-add-btn">
													Allocate Actuator
													<input type="hidden" name="roomID" value="<?php echo $roomID; ?>" />
												</button>
												<br/>
												<p>(Allocate an actuator from other rooms to here)</p>
												
											</div>
											<hr/>
											<ul id="actuator-group-<?php echo $roomID; ?>" class="list-unstyled" style="max-width: 400px;">
											<?php
												foreach($room->getActuator() as $actuator) {
											?>
												<!--<li style="margin-bottom:10px">
													<h5 class="vcenter"><php echo $actuator->getName(); ?></h5>
													<button type="button" class="btn btn-danger vcenter actuator-remove-btn" style="float: right;">
													Remove<input type="hidden" name="actuatorID" value="<php echo $actuator->getID(); ?>" />
													</button>
												</li>-->
											<?php
												}
											?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
								<button type="button" class="btn btn-warning room-edit-btn" data-toggle="modal" data-target="#room-modal">Edit
									<input type="hidden" name="roomID" value="<?php echo $roomID; ?>" />
								</button>
								&nbsp;&nbsp;<button type="button" class="btn btn-danger room-remove-btn" data-toggle="modal" data-target="#room-remove-modal" >Remove
									<input type="hidden" name="roomID" value="<?php echo $roomID; ?>" />
								</button>
							</div>
							<?php
								}
							?>
						</div>
					</div>
				
				</div>
            </div>
            
            <!-- Room .powerwidget --> 
			
			
			
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

<!--Room Modal-->
<div id="room-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">New Room</h4>
			</div>
			<div class="modal-body">
				<form id="room-form" class="form-horizontal validate-form" role="form">
				<input type="hidden" name="roomID" value="" />
					<div class="form-group">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control validate-input" name="name" id="name" placeholder="Room Name" >
							<p class="text-danger warning-text hidden">Please enter Room Name</p>
						</div>
					</div>
				</form>
            </div>
            <div class="modal-footer">
				<button type="button" id="room-submit-btn" class="btn btn-primary room-add-submit-btn">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
		</div>
    </div>
</div>
<!--/Room Modal-->

<!--Room Delete Modal -->
<div id="room-remove-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Remove Room</h4>
			</div>
			<div class="modal-body">
				<h5>Are you sure you want to remove this Room?</h5>
				<blockquote>
					<p id="room-remove-header"></p>
				</blockquote>
				<input type="hidden" name="roomID" value="" />
			</div>
			<div class="modal-footer">
				<button type="button" id="room-remove-submit-btn" class="btn btn-danger">Remove</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- /.Room Delete Modal -->


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
	
	$('.menu-setting').addClass('active');
	
	reloadAllRoom();
	
	
	// New Room
	
	//modal config
	$('#room-modal').on('show.bs.modal', function (e) {
		var regForm = $('#room-form');
		formRecovery(regForm);
	});
	
	// new room button click
	$(document).on('click', '.room-add-btn', function() {
		
		var roomMod = $('#room-modal');
		roomMod.find('.modal-title').text('New Room');
		roomMod.find('#room-submit-btn').text('Add').switchClass('btn-warning', 'btn-primary', 0).switchClass('room-edit-submit-btn', 'room-add-submit-btn', 0);
		
		roomMod.find('input[name="name"]').val('');
		roomMod.find('input[name="roomID"]').val('');
	});
	
	// add submit btn
	$(document).on('click', '.room-add-submit-btn', function() {
			
		var roomForm = $('#room-form');
		
		if(!(formValidation(roomForm)))
			return false;
		
		//console.log(roomForm.serialize())
		$.ajax({
			url: '/FYP/api/rooms',
			type: 'POST',
			data: roomForm.serialize(),
			dataType: "json",
			success : function(result) {
				//alert(JSON.stringify(result))
				if(result.status) {
					location.reload();
				} else {
					alert(JSON.stringify(result))
				}
				
			}
		});
		
	});
	// New Room
	
	
	// Edit Room
	$(document).on('click', '.room-edit-btn', function() {
		
		var roomID = $(this).find('input[name="roomID"]').val();
		var roomMod = $('#room-modal');
		
		roomMod.find('input[name="roomID"]').val(roomID);
		roomMod.find('.modal-title').text('Edit Room');
		roomMod.find('#room-submit-btn').text('Edit').switchClass('btn-primary', 'btn-warning', 0).switchClass('room-add-submit-btn', 'room-edit-submit-btn', 0);
		
		//get room
		$.ajax({
			url: '/FYP/api/rooms/' + roomID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var room = result.data;
				console.log(room)
				roomMod.find('input[name="name"]').val(room.RoomName);
			}
		});
	});
	
	
	$(document).on('click', '.room-edit-submit-btn', function() {
		
		var roomForm = $('#room-form');
		
		if(!(formValidation(roomForm)))
			return false;
		
		//console.log(roomForm.serialize())
		$.ajax({
			url: '/FYP/api/rooms',
			type: 'PUT',
			data: roomForm.serialize(),
			dataType: "json",
			success : function(result) {
				if(result.status) {
					location.reload();
				} 
				
			}
		});
	});
	// Edit Room
	
	
	// Delete Room
	$(document).on('click', '.room-remove-btn', function() {
		
		var roomID = $(this).find('input[name="roomID"]').val();
		
		$.ajax({
			url: '/FYP/api/rooms/' + roomID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var room = result.data;
				
				var roomReMod = $('#room-remove-modal');
				roomReMod.find('#room-remove-header').text(room.RoomName);
				roomReMod.find('input[name="roomID"]').val(room.RoomID);
			}
		});
		
	});
	
	
	$(document).on('click', '#room-remove-submit-btn', function() {
		
		var roomReMod = $(this).closest('#room-remove-modal');
		var roomID = roomReMod.find('input[name="roomID"]').val();
		
		$.ajax({
			url: '/FYP/api/rooms',
			type: 'DELETE',
			data: {roomID: roomID},
			dataType: "json",
			success : function(result) {
				if(result.status) {
					location.reload();
				}
			}
		});
	});
	// Delete Room
	
	
	
	
	// Allocate Actuator to Room 
	function reloadAllRoom() {
		$('.room-collapse').each(function() {
			var roomID = $(this).find('input[name="roomID"]').val();
			
			loadActuator(roomID);
		});
	}
	
	
	
	function loadActuator(roomID) {
		var existActuator = [];
		
		$('#actuator-group-'+roomID).empty();
		
		var addActuatorForm = $('.actuator-add-form-template-'+roomID);
		
		var selectActuator = addActuatorForm.find('select[name="actuatorID"]');
		selectActuator.empty();
		selectActuator.append('<option value="">~Select Actuator~</option>');
		
		$.ajax({
			url: '/FYP/api/actuators/rooms/' + roomID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				for(i=0;i<result.data.length;i++) {
					var actuator = result.data[i];
					
					var actuatorLi = $('<li style="margin-bottom:10px"></li>')
								.append('<div class="vcenter" style="margin-right:10px">'+
										'<img class="actuator-img" src="'+URLROOT+'/web/images/actuators/off/' + actuator.ActuatorImage + '" />'+
										'</div>'+
										'<h5 class="vcenter">'+actuator.ActuatorName+'</h5>'+
										'<button type="button" class="btn btn-danger vcenter actuator-remove-btn" style="float: right;">'+
										'Remove<input type="hidden" name="actuatorID" value="'+actuator.ActuatorID+'" />'+
										'</button>');
										
					$('#actuator-group-'+roomID).append(actuatorLi);
					
					existActuator.push(actuator.ActuatorID);
				}
				$.ajax({
					url: '/FYP/api/actuators',
					type: 'GET',
					dataType: "json",
					async: false,
					success : function(actuatorResult) {
						
						for(i=0;i<actuatorResult.data.length;i++) {
							var act = actuatorResult.data[i];
							if( existActuator.indexOf(act.ActuatorID) == -1 ) {
								selectActuator.append('<option value="'+ act.ActuatorID +'">'+ act.ActuatorName +'</option>');
							}
						}
						
					}
				});
			}
		});
		
		// add button click
		$('.actuator-add-btn').on('click', function() {
		
			var roomID = $(this).find('input[name="roomID"]').val();
			
			if($('.actuator-add-form-'+roomID).length)
				return;
			
			var actuatorForm = $('.actuator-add-form-template-'+roomID).clone();
			actuatorForm.removeClass('hidden').switchClass('actuator-add-form-template-'+roomID, 'actuator-add-form-'+roomID,0);
			
			
			var actuatorLi = $('<li style="margin-bottom:10px"></li>').append(actuatorForm);
			$('#actuator-group-'+roomID).append(actuatorLi);
		});
		
		$(document).on('click', '.actuator-add-cancel-btn', function() {
			var li = $(this).closest('li');
			
			li.remove();
		});
	}
	
	// actuator submit add 
	$(document).on('click', '.actuator-add-submit-btn', function() {
		var form = $(this).closest('form');
		
		$.ajax({
			url: "/FYP/api/actuators/room",
			type: "PUT",
			dataType: "json",
			data: form.serialize(),
			success : function(result) {
				if(result.status == true) {
					//reload Every Thing
					reloadAllRoom();
					
					var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Allocate the Actuator.</div>');
				} else {
					var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Actuator has not been allocated.</div>');
				}
				$('#actuator-addMessage').html(message);
			},
			error : function() {
				var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
				$('#actuator-addMessage').html(message);
			}
		});
	});
	
	// remove actuator from room
	$(document).on('click', '.actuator-remove-btn', function() {
		var actuatorID = $(this).find('input[name="actuatorID"]').val();
		
		$.ajax({
			url: "/FYP/api/actuators/room",
			type: "PUT",
			dataType: "json",
			data: {"actuatorID": actuatorID,
					"roomID": "NULL"},
			success : function(result) {
				if(result.status == true) {
					reloadAllRoom();
					
					var message = $('<div class="alert alert-success" role="alert"><strong>Success !</strong> Allocate the Actuator.</div>');
				} else {
					var message = $('<div class="alert alert-warning" role="alert"><strong>Fail !</strong> The Actuator has not been allocated.</div>');
				}
				$('#actuator-addMessage').html(message);
			},
			error : function() {
				var message = $('<div class="alert alert-danger" role="alert"><strong>Fail !</strong> Network connection timed out.</div>');
				$('#actuator-addMessage').html(message);
			}
		});
	});
	// Allocate Actuator to Room 
	
	
});

</script>

<!--/Scripts-->

</body>
</html>