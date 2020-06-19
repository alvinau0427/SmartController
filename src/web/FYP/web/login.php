<!DOCTYPE html>

<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo TITLE; ?></title>
<link href="<?php echo URLROOT; ?>/web/css/styles.css" rel="stylesheet" type="text/css">

<link rel="shortcut icon" type="image/x-icon" href="<?php echo URLROOT; ?>/web/digitalhome_logo.ico" />
</head>

<body>
<div class="colorful-page-wrapper">
  <div class="center-block">
    <div class="login-block">
      <form action="/FYP/login/authentication" method="post" id="login-form" class="orb-form">
        <header>
          <div class="image-block"><img src="<?php echo URLROOT; ?>/web/images/logo.png" alt="User" /></div>
          Login to <?php echo TITLE; ?> </header>
        <fieldset>
		  <section>
            <div class="row">
              <div class="col col-12">
                <label class="text-danger"><center><?php echo $message; ?></center></label>
              </div>
            </div>
          </section>
          <section>
            <div class="row">
              <label class="label col col-4">Account</label>
              <div class="col col-8">
                <label class="input"> <i class="icon-append fa fa-user"></i>
                  <input type="text" name="account">
                </label>
              </div>
            </div>
          </section>
          <section>
            <div class="row">
              <label class="label col col-4">Password</label>
              <div class="col col-8">
                <label class="input"> <i class="icon-append fa fa-lock"></i>
                  <input type="password" name="password">
                </label>
              </div>
            </div>
          </section>
         <!-- <section>
            <div class="row">
              <div class="col col-4"></div>
              <div class="col col-8">
                <label class="checkbox">
                  <input type="checkbox" name="remember" checked>
                  <i></i>Keep me logged in</label>
              </div>
            </div>
          </section>-->
        </fieldset>
        <footer>
          <button type="submit" class="btn btn-default">Log in</button>
        </footer>
      </form>
    </div>
    
    
</div>

<!--Scripts--> 
<!--JQuery--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/jquery/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/jquery/jquery-ui.min.js"></script> 

<!--Forms--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/forms/jquery.form.min.js"></script> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/forms/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/forms/jquery.maskedinput.min.js"></script> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/jquery-steps/jquery.steps.min.js"></script> 

<!--NanoScroller--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/vendors/nanoscroller/jquery.nanoscroller.min.js"></script> 

<!--Main App--> 
<script type="text/javascript" src="<?php echo URLROOT; ?>/web/js/scripts.js"></script>

<script>

// Login Form Validation
if ($('#login-form').length) {
	$("#login-form").validate({
		// Rules for form validation
		rules: {
			account: {
				required: true,
				minlength: 1,
				maxlength: 20
			},
			password: {
				required: true,
				minlength: 1,
				maxlength: 20
			}
		},

		// Messages for form validation
		messages: {
			account: {
				required: 'Please enter your Account'
			},
			password: {
				required: 'Please enter your Password'
			}
		},

		errorPlacement: function (error, element) {
			error.insertAfter(element.parent());
		}
	});

}
	

</script>

<!--/Scripts-->

</body>
</html>