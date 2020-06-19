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
<body>
    <?php
        require_once __DIR__ . '/header.php';
    ?>
        
        <!--Breadcrumb-->
        <div class="breadcrumb clearfix">
          <ul>
            <li><a href="<?php echo URLROOT; ?>"><i class="fa fa-home"></i></a></li>
            <li class="active">Profile</li>
          </ul>
        </div>
        <!--/Breadcrumb-->
        
        <div class="page-header">
          <h1>Profile<small><?= $this->user->getName() ?></small></h1>
        </div>
        
        <!-- Widget Row Start grid -->
        <div class="row" id="powerwidgets">
          <div class="col-md-12 bootstrap-grid"> 
            
            <!-- New widget -->
            
            <div class="powerwidget cold-grey" id="profile" data-widget-editbutton="false" data-widget-sortable="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false">
              <header>
                <h2>Profile Page<small>Basic View</small></h2>
              </header>
              <div class="inner-spacer"> 
                
                <!--Profile-->
                <div class="user-profile">
                  <div class="main-info">
                    <div class="user-img"><img src="<?php echo URLROOT; ?>/web/images/users/<?= $this->user->getImage() ?>" alt="User Picture" /></div>
                    <h1><?= $this->user->getName() ?></h1>
                    ID: <?= $this->user->getID() ?> | Type: <?= $this->user->getTypeDescription() ?> </div>
                    
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                          <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                          <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                          <!-- <li data-target="#carousel-example-generic" data-slide-to="2"></li> -->
                        </ol>
                        <div class="carousel-inner">
                          <div class="item item1 active"> </div>
                          <div class="item item2"></div>
                          <!-- <div class="item item3"></div> -->
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> </div>
                    
                      <div class="user-profile-info">
                        <div class="tabs-white">
                          <ul id="myTab" class="nav nav-tabs nav-justified">
                            <!-- <li class="active"><a href="#home" data-toggle="tab">About</a></li> -->
                            <?php if($this->user->getType() == 2) { ?>
                            <li class="active"><a href="#users" data-toggle="tab">Users</a></li>
                            <?php } ?>
                          </ul>
                          
                          <div id="myTabContent" class="tab-content">
                            
                            <?php if($this->user->getType() == 2) { ?>
                            <div class="tab-pane in active" id="users">
                              <div class="profile-header"> Users <span class="badge"><?= sizeof($users)-1 ?></span>
                                <div class="btn-group btn-group-xs pull-right">
									
                                  <button type="button" id="user-add-btn" class="btn btn-success" data-toggle="modal" data-target="#user-modal">
									+ Add
								  </button>
								  
                                </div>
                              </div>
                              <div class="row">

                                <?php 
									foreach($users as $user) { 
										if($user['UserTypeID'] == 1)
											continue;
								?>
								
                                <div class="col-md-4 col-sm-6">
                                  <div class="tiny-user-block clearfix">
                                    <div class="user-img"> <img src="<?php echo URLROOT; ?>/web/images/users/<?= $user['Image'] ?>" alt="User"/> </div>
                                    <h3><?= $user['UserName'] ?></h3>
                                    <ul>
                                      <li>ID: <strong><?= $user['UserID'] ?></strong></li>
                                    </ul>
                                    <button class="btn btn-sm btn-success user-edit-btn" data-toggle="modal" data-target="#user-modal">
										<input type="hidden" name="userID" value="<?php echo $user['UserID']; ?>" />
										Edit
									</button>
                                    <button class="btn btn-sm btn-warning user-remove-btn" data-toggle="modal" data-target="#user-remove-modal">
										<input type="hidden" name="userID" value="<?php echo $user['UserID']; ?>" />
										Delete
									</button>
                                  </div>
                                </div>
                                
                                <?php } ?>

                              </div>
                            </div>
                            <?php } ?>
                            
                            
                            
                          </div>
                          
                        </div>
                      
                    
                </div>
                <!--/Profile--> 

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


<!--Profile Modal-->
<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Add User</h4>
			</div>
			<div class="modal-body">
				<form id="user-form" class="form-horizontal validate-form" role="form">
					<input type="hidden" name="userID" value="" />
					<div class="form-group">
						<label for="name" class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control validate-input" name="name" id="name" placeholder="User Name" >
							<p class="text-danger warning-text hidden">Please enter User Name</p>
						</div>
					</div>
					<div class="form-group">
						<label for="loginAccount" class="col-sm-2 control-label">Account</label>
						<div class="col-sm-10">
							<input type="text" class="form-control validate-input" name="loginAccount" id="loginAccount" placeholder="Login Account">
							<p class="text-danger warning-text hidden">Please enter Login Account</p>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10">
							<div class="input-group">
								<span id="change-password" class="input-group-addon hidden">
									Change Password &nbsp;
									<input type="checkbox" name="changePassword">
								</span>
								<input type="password" class="form-control validate-input" name="password" id="password" placeholder="Password">
							</div>
							<p class="text-danger warning-text hidden">Please enter Password</p>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" name="email" id="email" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label for="type" class="col-sm-2 control-label">Type</label>
						<div class="col-sm-10">
							<div class="radio">
								<label>
									<input type="radio" name="type" value="3" checked="checked" />
									Normal User
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="2" />
									Root User
								</label>
							</div>
						</div>
					</div>
				</form>
            </div>
            <div class="modal-footer">
				<button type="button" id="user-submit-btn" class="btn btn-primary user-add-submit-btn">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
		</div>
    </div>
</div>
<!--/Profile Modal-->

<!--Profile Delete Modal -->
<div id="user-remove-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Delete User</h4>
			</div>
			<div class="modal-body">
				<h5>Are you sure you want to delete this User?</h5>
				<blockquote>
					<p id="user-remove-header"></p>
				</blockquote>
				<input type="hidden" name="userID" value="" />
			</div>
			<div class="modal-footer">
				<button type="button" id="user-remove-submit-btn" class="btn btn-danger">Delete</button>
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- /.Profile Delete Modal -->


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
    
	//modal config
	$('#user-modal').on('show.bs.modal', function (e) {
		var userForm = $('#user-form');
		formRecovery(userForm);
	});
	
	
	$(document).on('change', 'input[name="changePassword"]', function() {
		var userMod = $('#user-modal');
		
		if($(this).is(':checked')) {
			userMod.find('input[name="password"]').prop("disabled", false);
		} else {
			userMod.find('input[name="password"]').prop("disabled", true);
		}
	});
	
	// add user
	$('#user-add-btn').on('click', function() {
		
		var userMod = $('#user-modal');
		userMod.find('.modal-title').text('Add User');
		userMod.find('#user-submit-btn').text('Add').switchClass('btn-warning', 'btn-primary', 0).switchClass('user-edit-submit-btn', 'user-add-submit-btn', 0);
		
		userMod.find('input[name="name"]').val('');
		userMod.find('input[name="loginAccount"]').val('').prop("disabled", false);
		userMod.find('input[name="password"]').val('');
		userMod.find('input[name="email"]').val('');
		userMod.find('input[name="type"][value="3"]').prop('checked', true);
		
		userMod.find('input[name="changePassword"]').prop('checked', true).trigger('change');
		userMod.find('#change-password').addClass('hidden', 0);
	});
	
    $(document).on('click', '.user-add-submit-btn', function() {
		
        var userForm = $('#user-form');
		//console.log(userForm.serializeArray());
		if(!(formValidation(userForm)))
			return false;
		
        $.ajax({
            url: '/FYP/api/users',
            type: 'POST',
            data: userForm.serializeArray(),
			dataType: "json",
            success : function(result) {
                location.reload();
            },
            error : function(result) {
                console.log(result);
                alert('Registration Fail!');
            }
        });

    });
	
	
	
	
	// edit user
	$(document).on('click', '.user-edit-btn', function() {
		
		var userID = $(this).find('input[name="userID"]').val();
		var userMod = $('#user-modal');
		
		userMod.find('input[name="userID"]').val(userID);
		userMod.find('.modal-title').text('Edit User');
		userMod.find('#user-submit-btn').text('Edit').switchClass('btn-primary', 'btn-warning', 0).switchClass('user-add-submit-btn', 'user-edit-submit-btn', 0);
		
		userMod.find('#change-password').removeClass('hidden', 0);
		userMod.find('input[name="changePassword"]').prop('checked', false).trigger('change');
		
		//get user
		$.ajax({
			url: '/FYP/api/users/' + userID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var user = result.data;
				userMod.find('input[name="name"]').val(user.UserName);
				userMod.find('input[name="loginAccount"]').val(user.LoginAccount).prop("disabled", true);
				userMod.find('input[name="password"]').val('');
				userMod.find('input[name="email"]').val(user.Email);
				userMod.find('input[name="type"][value="' + user.UserTypeID + '"]').prop('checked', true);
				
			}
		});
	});
	
	$(document).on('click', '.user-edit-submit-btn', function() {
		
        var userForm = $('#user-form');
		//console.log(userForm.serializeArray());
		if(!(formValidation(userForm)))
			return false;
		
        $.ajax({
            url: '/FYP/api/users',
            type: 'PUT',
            data: userForm.serializeArray(),
			dataType: "json",
            success : function(result) {
                location.reload();
            },
            error : function(result) {
                console.log(result);
                alert('Registration Fail!');
            }
        });

    });
	
	
	
	// delete user
	$(document).on('click', '.user-remove-btn', function() {
		
		var userID = $(this).find('input[name="userID"]').val();
		
		$.ajax({
			url: '/FYP/api/users/' + userID,
			type: 'GET',
			dataType: "json",
			success : function(result) {
				var user = result.data;
				
				var userMod = $('#user-remove-modal');
				userMod.find('input[name="userID"]').val(user.UserID);
				userMod.find('#user-remove-header').text(user.UserName);
			}
		});
		
	});
	
	$(document).on('click', '#user-remove-submit-btn', function() {
		
		var userMod = $(this).closest('#user-remove-modal');
		var userID = userMod.find('input[name="userID"]').val();
		
        $.ajax({
            url: '/FYP/api/users',
			type: 'DELETE',
			data: {userID: userID},
			dataType: "json",
            success : function(result) {
                location.reload();
            },
            error : function(result) {
                console.log(result);
                alert('Delete Fail!');
            }
        });

    });
</script>

<!--/Scripts-->

</body>
</html>