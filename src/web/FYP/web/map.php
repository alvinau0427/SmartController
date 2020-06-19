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
</head>
<style>
.vcenter {
    display: inline-block;
    vertical-align: middle;
    float: none;
}
</style>
<body>

<!--Smooth Scroll-->
<div class="smooth-overflow">
	<?php
		require_once __DIR__ . '/header.php';
	?>
        
        <!--Breadcrumb-->
        <div class="breadcrumb clearfix">
          <ul>
            <li><a href="<?php echo URLROOT; ?>"><i class="fa fa-home"></i></a></li>
            <li class="active">Location</li>
          </ul>
        </div>
        <!--/Breadcrumb-->
        
        <div class="page-header">
          <h1>Location<small>map</small></h1>
        </div>
        
        <!-- Widget Row Start grid -->
        <div class="row" id="powerwidgets">
          <div class="col-md-12 bootstrap-grid"> 
            
            <!-- New widget -->
            
            <div class="powerwidget green-alt" data-widget-editbutton="false" data-widget-deletebutton="false">
              <header>
                <h2>Location<small>all users</small></h2>
              </header>
              <div class="inner-spacer">
                <div class="" id="map" style="height:700px" ></div>
              </div>
            </div>
            
            <!-- End .powerwidget --> 
            
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
	$('.menu-location').addClass('active');
});


var map;
function initMap() {
	var myLatlng = new google.maps.LatLng(22.3705366,114.1171818);
	
	mapOptions = {
		center: myLatlng,
		zoom: 11
	}
  
	var map = new google.maps.Map(document.getElementById("map"), mapOptions);
		
	
	$.ajax({
		url: '/FYP/api/users/locations',
		type: 'GET',
		dataType: "json",
		success : function(result) {
			for(i in result.data) {
				
				var record = result.data[i];
				
				var latlng = new google.maps.LatLng(record.Latitude, record.Longitude);
				
				var icon;
				if(record.UserID == USER.UserID) {
					icon = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
					userName = "You";
				} else {
					icon = 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png';
					userName = record.UserName;
				}
				
				var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					clickable: true,
					icon: icon,
					title: userName
				});
				
				marker.info = new google.maps.InfoWindow({
					content: '<h3>'+userName+'</h3><footer>'+record.DateTime+'</footer>'
				});
				google.maps.event.addListener(marker, 'click', function() {
					this.info.open(marker,this);
				});
			}
			
		}
	});	
}
</script>
<script async defer 
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSoYOnV5pYEpmx0E6cEUn7Uc231YUl4Z4&callback=initMap"></script>

<!--/Scripts-->

</body>
</html>