<?php 
	header("Content-type: application/javascript"); 
	session_start();
?>
//all page
const URLROOT = "<?php echo $_SESSION['URLROOT']; ?>";
const USER = <?php echo json_encode($_SESSION['user']); ?>;

function formValidation(validateForm) {
	var valid = true;
	
	var inputs = validateForm.find('.validate-input:enabled');
	inputs.each(function(i, input) {
		if($.trim($(input).val()) == "") {
			var warnText = $(input).data('warning-text');
			$(input).closest('.form-group').addClass('has-error');
			$(input).next('p.warning-text').removeClass('hidden');
			
			valid = false;
			
		} else {
			$(input).closest('.form-group').removeClass('has-error');
			$(input).next('p.warning-text').addClass('hidden');
		}
	});
	
	var selects = validateForm.find('.validate-select:enabled');
	selects.each(function(i, select) {
		if($.trim($(select).val()) == "") {
			$(select).closest('.form-group').addClass('has-error');
			
			valid = false;
			
		} else {
			$(select).closest('.form-group').removeClass('has-error');
		}
	});
	
	return valid;
};

function formRecovery(recoverForm) {
	
	var inputs = recoverForm.find('.validate-input');
	inputs.each(function(i, input) {
		$(input).closest('.form-group').removeClass('has-error');
		$(input).next('p.warning-text').addClass('hidden');
	});
	
	var selects = recoverForm.find('.validate-select');
	selects.each(function(i, select) {
		$(select).closest('.form-group').removeClass('has-error');
	});
}





$(document).ready(function() {
	
	$('.bootstrap-switch').bootstrapSwitch();
	
	var toastrOptions1 = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": true,
		"progressBar": true,
		"positionClass": "toast-bottom-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": 10000,
		"extendedTimeOut": 5000,
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut",
		"tapToDismiss": false
	};
	
	var toastrOptions2 = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": true,
		"progressBar": false,
		"positionClass": "toast-bottom-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": 0,
		"extendedTimeOut": 0,
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut",
		"tapToDismiss": false
	};
	//pusher

	// Enable pusher logging - don't include this in production
	Pusher.logToConsole = true;
	
	var pusher = new Pusher('1bc88481522c1ebbf138', {
		encrypted: true
	});


	var actuatorChannel = pusher.subscribe('actuator');
	//系統自動改status
	actuatorChannel.bind('autoUpdateStatus', function(data) {
		
		$('#notification-new').addClass('new');
		
		//if not receive
		if(USER.ReceiveNotification != 1)
			return;
		
		for(i in data.devices) {
			var actuator = data.devices[i];
			var color = "success";
			toastr.options = toastrOptions1;
			
			toastr[color]('<b>Policy Activated : </b>"<i>' + actuator.regulationHeader + '</i>"<br/>' +
						'Success to <b>' + actuator.toStatusDescription + ' the ' + actuator.actuatorName + '</b>', 'Auto Update').css("width","400px");
			
			var form = $('#actuator-' + actuator.actuatorID).find('form');
			
			if(form.length) {

				var btn = form.find('.actuator-status-btn');
			
				if(btn.bootstrapSwitch('state') == actuator.toStatus) {
					return;
				} else {
					var permission = form.find('input[name="permissionID"]').val();
					
					if(actuator.toStatus == 1) {
						checkPermission(btn, true, permission);
						
						btn.bootstrapSwitch('state', true, true);
						switchActuatorImage(form, true);
					} else {
						checkPermission(btn, false, permission);
						
						btn.bootstrapSwitch('state', false, true);
						switchActuatorImage(form, false);
					}
				}
				
			}
		}
	});
	
	//問你改唔改status
	actuatorChannel.bind('notificationUpdateStatus', function(data) {
		
		//if not receive
		if(USER.ReceiveNotification != 1)
			return;
		
		for(i in data.devices) {
			var actuator = data.devices[i];
			var color = "info";
			var form = '<form>'+
							'<input type="hidden" name="actuatorID" value="' + actuator.actuatorID + '">'+
							'<input type="hidden" name="userID" value="' + USER.UserID + '">'+
							'<input type="hidden" name="status" value="' + actuator.toStatus + '">'+
							'<button type="button" class="actuator-notificationUpdateStatus-btn btn btn-warning">' + actuator.toStatusDescription + '</button> &nbsp'+
							'<button type="button" class="clear btn btn-default">Cancel</button>'+
						'</form>';
			
			toastr.options = toastrOptions2;
			var $toast = toastr[color]('<b>' + actuator.regulationHeader + '</b><br/>' +
										"Do you want to <b>" + actuator.toStatusDescription + " " + actuator.actuatorName + 
										"</b> ?<br/><br/>" + form, 'Policy Activated').css("width","400px");
			
			if ($toast.find('.clear').length) {
                $toast.delegate('.clear', 'click', function () {
                    toastr.clear($toast, { force: true });
                });
            }
			
			
			$toast.delegate('.actuator-notificationUpdateStatus-btn', 'click', function() {
					
				var form = $(this).closest('form');
				
				$.ajax({
					url: "/FYP/api/actuators",
					type: "put",
					dataType: "json",
					data: form.serialize(),
					success : function(result) {
						//alert(JSON.stringify(result));
						
						toastr.clear($toast, { force: true });
						toastr.options = toastrOptions1;
							
						if(result.status == true) {
							var $success = toastr["success"]('Success to <b>' + actuator.toStatusDescription + ' the ' + actuator.actuatorName + '</b>', 'Success').css("width","400px");
						} else {
							var $success = toastr["error"]('The ' + actuator.actuatorName + ' already ' + actuator.toStatusDescription, 'Fail');
						}
					},
					error : function() {
						location.reload();
					}
				});
			});
			
		}
	});
	
	//人地改status
	actuatorChannel.bind('updateStatus', function(data) {

		//alert(JSON.stringify(data));
		
		$('#notification-new').addClass('new');
		
		for(i in data.devices) {
			var actuator = data.devices[i];
			
			var form = $('#actuator-' + actuator.actuatorID).find('form');
			
			if(form.length) {
			
				var btn = form.find('.actuator-status-btn');
				
				if(btn.bootstrapSwitch('state') == actuator.status) {
					return;
				} else {
					var permission = form.find('input[name="permissionID"]').val();

					if(actuator.status == 1) {
						checkPermission(btn, true, permission);
						
						btn.bootstrapSwitch('state', true, true);
						switchActuatorImage(form, true);
						
					} else {
						checkPermission(btn, false, permission);
						
						btn.bootstrapSwitch('state', false, true);
						switchActuatorImage(form, false);
					}
				}
				
			}
		}
	});
	
	
	var weatherChannel = pusher.subscribe('weather');
	weatherChannel.bind('update', function(data) {

		//if not receive
		if(USER.ReceiveNotification != 1)
			return;
		
		for(i in data.devices) {
			
			var actuator = data.devices[i];
			
			var toStatusDescription = (actuator.toStatus == 1) ? "Open" : "Close";
			var color = "info";
			var form = '<form>'+
							'<input type="hidden" name="actuatorID" value="' + actuator.actuatorID + '">'+
							'<input type="hidden" name="userID" value="' + USER.UserID + '">'+
							'<input type="hidden" name="status" value="2">'+
							'<button type="button" class="weather-updateModule-btn btn btn-warning">' + toStatusDescription + '</button> &nbsp'+
							'<button type="button" class="clear btn btn-default">Cancel</button>'+
						'</form>';
						
			toastr.options = toastrOptions2;
			
			var $toast = toastr[color]("It is highly probable that it will rain today.<br/>" +
										"Do you want to <b>" + toStatusDescription + " " + actuator.actuatorName + 
										"</b> ?<br/><br/>" + form, 'Weather warning [HK Observatory]').css("width","400px");
			
			if ($toast.find('.clear').length) {
                $toast.delegate('.clear', 'click', function () {
                    toastr.clear($toast, { force: true });
                });
            }
			
			
			$toast.delegate('.weather-updateModule-btn', 'click', function() {
				
				var form = $(this).closest('form');
				
				$.ajax({
					url: "/FYP/api/actuators",
					type: "put",
					dataType: "json",
					data: form.serialize(),
					success : function(result) {
						
						toastr.clear($toast, { force: true });
						toastr.options = toastrOptions1;
						
						if(result.status == true) {
							var $success = toastr["success"]('Success to <b>' + toStatusDescription + ' the ' + actuator.actuatorName + '</b>', 'Success').css("width","400px");
						} else {
							var $success = toastr["error"]('The ' + actuator.actuatorName + ' already ' + toStatusDescription , 'Fail').css("width","400px");
							
						}
					},
					error : function() {
						location.reload();
					}
				});
			});
		}
		
	});
	//pusher
	
	//number only
	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	
	//number only
	
	
	
	
	$('#notification-button').on('click', function() {
		
		$.ajax({
			url: '/FYP/api/actuators/records/?startIndex=0&numItems=10',
			type: 'GET',
			dataType: "json",
			success : function(result) {
				
				$('ul.notification-dropdown').empty();
				
				$('#notification-new').removeClass('new');
				
				var i = 0;
				for(;i < result.data.length;i++) {
					if(i >= 10)
						break;
					
					var data = result.data[i];
					var color = (data.UserID == 1)?"info":"success";
					var imgColor = (data.ActuatorStatusID == 1)?'on':'off';
					var date = new Date(data.DateTime);
					
					
					var li = $('<li class="bg-' + color + '"></li>');
					
					li.html('<a href="#">' +
							'<img class="notification-icon" src="'+URLROOT+'/web/images/actuators/' + imgColor + '/' + data.ActuatorImage + '"></img>' +
							'<p style="font-size: 1em;"><strong>' + data.UserName + '</strong> ' + 
							'<span style="font-weight: initial;">' + data.ActuatorStatusDescription + ' ' + data.RoomName + ' ' + data.ActuatorName + '</span></p>' +
							'<span class="label label-default"><i class="entypo-clock"></i>&nbsp;' +
							'<time class="timeago" datetime="' + date.toISOString() + '"></time></span>' +
							'</a>');
					
					$('ul.notification-dropdown').append(li);
					
				}
				
				$("time.timeago").timeago();
				
				$("#notification-num").html(i);
			}
		});
	});
	
	//switch actuator
	function switchActuatorImage(form, checked) {
		var actuatorImg = form.find('img.actuator-img');
						
		var imgSrc = actuatorImg.attr('src');
		var imgStr = (checked) ? imgSrc.replace("/off/","/on/") : imgSrc.replace("/on/","/off/");
		
		if(imgSrc == imgStr)
			return;
		
		//change the image with animation
		actuatorImg.stop().fadeOut('slow', function () {
			$(this).attr('src', imgStr);
			$(this).fadeIn('slow');
		});
	}
	
	function checkPermission(btn, isChecked, permission) {
		if( (permission == 1) ||
			(permission == 2 && isChecked) || 
			(permission == 3 && !isChecked) ) {
			
			btn.bootstrapSwitch('disabled', true, true);
		} else {
			btn.bootstrapSwitch('disabled', false, true);
		}
	}
	
	function switchBack(btn, isChecked) {
		alert('switchback')
		if(isChecked) {
			btn.bootstrapSwitch('state', false, true);
		} else {
			btn.bootstrapSwitch('state', true, true);
		}
	}
	
	$('.actuator-status-btn').on('switchChange.bootstrapSwitch', function(event, isOnStatus) {
		
		var form = $(this).closest('form');
		
		//alert(form.serializeArray());
		var status;
		var btn = $(this);
		//if it is On status
		if(isOnStatus) {
			status = 1;
		} else {	//if it is Off status
			status = 2;
		}
		//switchActuatorImage(form, isOnStatus);
		$.ajax({
			url: "/FYP/api/actuators",
			type: "put",
			dataType: "json",
			data: form.serialize() + "&status=" + status,
			success : function(result) {
				//alert(JSON.stringify(result));
				if(result.status == true) {
					switchActuatorImage(form, isOnStatus);
					var permission = form.find('input[name="permissionID"]').val();
					checkPermission(btn, isOnStatus, permission);
				} else {
					switchBack(btn, isOnStatus);
				}
			},
			error : function() {
				switchBack(btn, isOnStatus);
			}
			
		});
		
	});
	
	//switch actuator
	
	
	//update location
	if (navigator.geolocation) {
		
		if(USER.LocationDisplay == 1) {
			navigator.geolocation.getCurrentPosition(setPosition);
		}
		
		function setPosition(position) {
			
			var userID = $('input#userID').val();
			
			$.ajax({
				url: '/FYP/api/users/locations',
				type: 'PUT',
				dataType: "json",
				data: {
					userID: userID,
					latitude: position.coords.latitude,
					longitude: position.coords.longitude
				},
				success : function(result) {},
				error : function() {
					console.log("can not update location");
				}
			});	
			
			
		}
	} else {
		console.log("Geolocation is not supported by this browser.");
	}
	//update location
	
	
	// update user setting
	$('#user-receiveNotification-btn, #user-receiveEmail-btn, #user-locationDisplay-btn').on('switchChange.bootstrapSwitch', function(event, isOnStatus) {
		
		var status;
		var btn = $(this);
		//if it is On status
		if(isOnStatus) {
			status = 1;
		} else {	//if it is Off status
			status = 0;
		}
		var url;
		if(btn.attr('id') == "user-receiveNotification-btn") {
			url = "/FYP/api/users/receiveNotification";
		} else if(btn.attr('id') == "user-receiveEmail-btn") {
			url = "/FYP/api/users/receiveEmail";
		} else if(btn.attr('id') == "user-locationDisplay-btn") {
			url = "/FYP/api/users/locationDisplay";
		}
		
		
		$.ajax({
			url: url,
			type: "put",
			dataType: "json",
			data: {
				userID: USER.UserID,
				status: status
			},
			success : function(result) {
				//alert(JSON.stringify(result));
				if(result.status == true) {
					
				} else {
					switchBack(btn, isOnStatus);
				}
			},
			error : function() {
				switchBack(btn, isOnStatus);
			}
			
		});
		
	});
	
	
	// update user setting
});