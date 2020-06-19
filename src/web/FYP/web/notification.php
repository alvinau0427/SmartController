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

.notification-left-col {
	margin-right:15px;
}

.notification-icon {
	height:40px;
}

#notification-form > .form-group{
	margin: 10px;
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
            <li class="active">Notifications</li>
          </ul>
        </div>
        <!--/Breadcrumb-->
        
        <div class="page-header">
          <h1>Notifications<small>records</small></h1>
        </div>
        
		<!-- Widget Row Start grid -->
        <div class="row" id="powerwidgets">
			<div class="col-md-12 bootstrap-grid">
			
				<div class="powerwidget blue" data-widget-editbutton="false" data-widget-deletebutton="false">
					<header role="heading">
						<h2>Filter<small>Records</small></h2>
					</header>
					<div class="inner-spacer" role="content">
						<form id="notification-form" class="form-inline" role="form">
							<div class="form-group">
								<label for="userID">User&nbsp;</label>
								<select class="form-control" name="userID" id="userID">
								<?php
									if($_SESSION['user']['UserTypeID'] != "2") {
										echo '<option value="'.$_SESSION['user']['UserID'].'">'.$_SESSION['user']['UserName'].'</option>';
									} else {
										echo '<option value="">All</option>';
										
										foreach($userArr as $user) {
											echo '<option value="'.$user->getID().'">'.$user->getName().'</option>';
										}
									}
								?>
								</select>
							</div>
							<div class="form-group">
								<label for="actuatorID">Actuator&nbsp;</label>
								<select class="form-control" name="actuatorID" id="actuatorID">
								<?php
									echo '<option value="">All</option>';
									
									foreach($actuatorArr as $actuator) {
										echo '<option value="'.$actuator->getID().'">'.$actuator->getName().'</option>';
									}
									
								?>
								</select>
							</div>
							<div class="form-group">
								<label for="status">Action&nbsp;</label>
								<select class="form-control" name="status" id="status">
									<option value="">All</option>
									<option value="1">Open</option>
									<option value="2">Close</option>
								</select>
							</div>
							<div class="form-group">
								<label for="startTime">From&nbsp;</label>
								<input type="datetime-local" class="form-control" id="startTime" name="startTime" value="2017-01-01T00:00" size="8"/>
							
								<label for="endTime">&nbsp;To&nbsp;</label>
								<input type="datetime-local" class="form-control" id="endTime" name="endTime" value="<?php echo date('Y-m-d').'T'.date('H:i');?>" size="8"/>
							</div>
						</form>
						<br/>
						<br/>
						<table class="table table-hover">
							<tbody id="notification-record">
								
							</tbody>
						</table>
					</div>
				</div>
				
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


<script type="text/javascript">
$(document).ready(function() {
	
	$('.menu-notification').addClass('active');
	
	//console.log($('#notification-form').serialize());
	var notiForm = $('#notification-form');
	var notiRecord = $('#notification-record');
	
	var isPreviousEventComplete = true, isDataAvailable = true;
	var startIndex = 0;
	var numItems = 10;
	
	loadRecord(startIndex, numItems);
	
	function loadRecord(startIndex, numItems) {
		
		isPreviousEventComplete = false;
		
		var actuatorID = notiForm.find('select[name="actuatorID"]').val();
		var url = "";
		
		if(actuatorID == null || actuatorID == '') 
			url = '/FYP/api/actuators/records';
		else
			url = '/FYP/api/actuators/records/' + actuatorID;
		
		var data = notiForm.serializeArray();
		data.push({name: 'startIndex', value: startIndex});
		data.push({name: 'numItems', value: numItems});
		
		$.ajax({
			url: url,
			type: 'GET',
			dataType: "json",
			data: data,
			success : function(result) {
				//console.log(result);
				if(result.data.length < 1)
					isDataAvailable = false;
				for(i=0;i<result.data.length;i++) {
					
					var record = result.data[i];
					
					var imgColor = (record.ActuatorStatusID == 1)?'on':'off';
					
					var tr = $('<tr></tr>');
						
					tr.html('<td>' +
								'<div class="vcenter notification-left-col">' +
									'<img class="notification-icon" src="'+URLROOT+'/web/images/actuators/' + imgColor + '/' + record.ActuatorImage + '">' +
								'</div>' +
								'<div class="vcenter">' +
									'<p><strong>' + record.UserName + '</strong> ' +
									'<span>' + record.ActuatorStatusDescription + ' ' + record.RoomName + ' ' + record.ActuatorName + '</span></p>'+
									'<span class="label label-default"><i class="entypo-clock"></i>&nbsp;'+ record.DateTime +'</span>' +
								'</div>' +
							'</td>');
					
					notiRecord.append(tr);
				}
				
				isPreviousEventComplete = true;
			}
		});
	}
	
	$('form#notification-form').on('change', function() {
		startIndex = 0;
		numItems = 10;
		isPreviousEventComplete = true;
		isDataAvailable = true;
		
		notiRecord.empty();
		loadRecord(startIndex, numItems);
	});
	
	
	
	$(window).on('scroll', function () {
		
		if ($(document).height() - 50 <= $(window).scrollTop() + $(window).height()) {
			if (isPreviousEventComplete && isDataAvailable) {
				
				startIndex += numItems;
				loadRecord(startIndex, numItems);

			}
			
		}
        
   });
	
});

</script>

<!--/Scripts-->

</body>
</html>