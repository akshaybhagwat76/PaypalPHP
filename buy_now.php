<?php
 require_once("./process.php");
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>simplestyle_4</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />-->
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="style/style.css" />
  <link href="css/style.css" rel="stylesheet">
  <style type="text/css">
  .auto-style1 {
	  margin-left: 0;
  }
  </style>
</head>

<body ng-app="formApp">
  <img src="style/loading.gif" class="loading" id="loading">
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1>My Title<span class="logo_colour">Buy Now</span></h1>
          <h2>Help A Charity & Make Internet History</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="index.html">Home</a></li>
          <li class="selected"><a href="buy_now.php">Buy Now</a></li>
          <li><a href="how_it_works.html">How It Works</a></li>
          <li><a href="about_us.html">About Us</a></li>
          <li><a href="contact_us.php">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <div id="content_header"></div>
    <div id="site_content" class="container">
       <h1>Buy Now</h1>
       <p>Complete the following to have your Personalised Image added to the website:</p>
      <div id="content" class="auto-style1 col-12" ng-controller="formController" ng-cloak>
      <div >
      <p>Company Logo Or Personal Message</p>
      <div class="form-group">  
          <div class="radio">
          <label><input type="radio" name="logo" id="rdcompanylogo" checked ng-model="data.logo" value="Comapny Logo" required>Comapny Logo</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="logo" ng-model="data.logo" value="Personal Massage" required>Personal Massage</label>
        </div>
        <div class="col-12">
            <form name="paypal" action=<?php if (!$errors && isset($_POST['submit'])){ echo $paypal_url; } else{ echo $_SERVER['PHP_SELF']; } ?> method="post" enctype="multipart/form-data">
              <input type="hidden" name="cmd" value="_xclick">
          <input type="hidden" name="business" value="ssbahra@gmail.com">
          <input type="hidden" name="item_name" value="Item">
          <input type="hidden" name="item_number" value="009764326u5765">
        <div class="row">
          <div class="col-md-6">
          <div class="form-group" ng-class="{ 'has-error' : paypal.fname.$invalid && !paypal.fname.$pristine }">
            <input type="text" ng-model="data.fname" name="fname" class="form-control" id="fname" placeholder="* First Name" required>
            <div class="help-block" ng-messages="paypal.fname.$error" ng-if="paypal.fname.$dirty">
              <p ng-message="required">Your first name is required.</p>
          </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group" ng-class="{ 'has-error' : paypal.lname.$invalid && !paypal.lname.$pristine }">
            <input type="text" ng-model="data.lname" name="lname" class="form-control" id="lname" placeholder="* Last Name" required>
            <div class="help-block" ng-messages="paypal.lname.$error" ng-if="paypal.lname.$dirty">
              <p ng-message="required">Your last name is required.</p>
          </div>
          </div>
        </div>
        </div>
        <div class="form-group" ng-class="{ 'has-error' : paypal.email.$invalid && !paypal.email.$pristine }">
          <input name="email" type="email" ng-model="data.email" class="form-control" id="email" placeholder="* Email" required>
            <div class="help-block" ng-messages="paypal.email.$error" ng-if="paypal.email.$dirty">
              <p ng-message="required">Your email is required.</p>
          <p ng-message="email">This needs to be a valid email</p>
          </div>
        </div>
        <div class="form-group" ng-class="{ 'has-error' : paypal.coname.$invalid && !paypal.coname.$pristine }">
          <input type="text" ng-model="data.coname" name="coname" class="form-control" id="coname" placeholder="Company Name">
          <div class="help-block" ng-messages="paypal.coname.$error" ng-if="paypal.coname.$dirty">
            <p ng-message="required">Your company name is required.</p>
        </div>
        </div>
        <div class="form-group" ng-class="{ 'has-error' : paypal.coabout.$invalid && !paypal.coabout.$pristine }">
          <textarea ng-model="data.coabout" name="coabout" class="form-control" id="coabout" placeholder="About Company"></textarea>
          <div class="help-block" ng-messages="paypal.coabout.$error" ng-if="paypal.coabout.$dirty">
            <p ng-message="required">Write something about your company here.</p>
        </div>
        </div>
        <div class="form-group" ng-class="{ 'has-error' : paypal.address.$invalid && !paypal.address.$pristine }">
          <input type="url" ng-model="data.address" name="address" class="form-control" id="address" placeholder="Web Address" >
          <div class="help-block" ng-messages="paypal.address.$error" ng-if="paypal.address.$dirty">
            <p ng-message="required">Your web address is required.</p>
            <p ng-message="url">This needs to be a valid URL</p>
        </div>
        </div>
        <?php if ((!isset($_POST['submit']) || !isset($myFile)) || isset($img_error)) : ?>
        <div class="form-group">
          <label>Image</label>
          <div class="radio">
          <label><input type="radio" name="img" ng-model="data.img" value="I will provide an Image" required>I will provide an Image</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="img" ng-model="data.img" value="Create an image for me" required>Create an image for me</label>
        </div>
        <div class="help-block" ng-messages="paypal.img.$error" ng-if="formSubmited && !data.img">
            <p class="text-danger" ng-message="required">Select an option.</p>
        </div>
        </div>
        <div class="form-group has-error" ng-if="data.img == 'I will provide an Image'">
          <label>Upload your image</label>
          <input type="file" id="file" name="image">
          <div class="help-block">
            <?php if(isset($img_error)):
                  echo "<p>".$img_error."</p>";
             endif;?>
          </div>
        </div>
      <?php endif; ?>
        
        <div class="form-group" ng-class="{ 'has-error' : paypal.width.$invalid && !paypal.width.$pristine }">
         <label>Width</label>
          <div class="input-group">
            
            <input type="number" ng-keyup="widthCh()" ng-model="data.width" name="width" class="form-control" id="width" placeholder="* Width" required>
            <div class="input-group-addon">px</div>
            </div>
            <div class="help-block" ng-messages="paypal.width.$error" ng-if="paypal.width.$dirty">
            <p ng-message="required">this field is required.</p>
            </div>
          </div>
          <div class="form-group" ng-class="{ 'has-error' : paypal.height.$invalid && !paypal.height.$pristine }">
          <label>Height</label>
          <div class="input-group">
            
            <input type="number" ng-keyup="heightCh()" ng-model="data.height" name="height" class="form-control" id="height" placeholder="* Height" required>
            <div class="input-group-addon">px</div>
            </div>
            <div class="help-block" ng-messages="paypal.height.$error" ng-if="paypal.height.$dirty">
            <p ng-message="required">this field is required.</p>
        </div>
          </div>
        <!--
        <div class="form-group btn-group">
        <label>Charity</label>
        <div class="radio">
          <label><input type="radio" name="charity" ng-model="data.charity" value="1: Cancer Research" required>1: Cancer Research</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="charity" ng-model="data.charity" value="2: Disabled" required>2: Disabled</label>
        </div>
        <div class="radio">
          <input type="hidden" name="charity" ng-model="data.charity" value="3: Children">
        </div>
        <div class="radio">
          <label><input type="radio" name="charity" ng-model="data.charity" value="4: Elderly" required>4: Elderly</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="charity" ng-model="data.charity" value="5: Animal Welfare" required>5: Animal Welfare</label>
        </div>

        <div class="help-block" ng-messages="paypal.charity.$error" ng-if="formSubmited && !data.charity">
            <p class="text-danger" ng-message="required">Select an option.</p>
        </div>

        </div>  -->
        <div class="form-group" ng-class="{ 'has-error' : (paypal.amount.$invalid && !paypal.amount.$pristine) || paypal.amount.min}">
          <div class="input-group">
            <div class="input-group-addon">$</div>
            <input readonly type="number" ng-model="data.amount" min="50" name="amount" class="form-control" id="amount" placeholder="* Amount" required>
            </div>
            <div class="help-block" ng-messages="paypal.amount.$error" ng-if="paypal.amount.$dirty">
              <p ng-message="required">Enter a height and width value to calculate the amount</p>
            </div>
            <div class="help-block">
              <p ng-if="paypal.amount.min">This field must be at least {{min}}. Please adjust the Height/Width.</p>
            </div>
            
          </div>
        
        
        <?php if(isset($myFile)): ?>
        <input type="hidden" name="custom" value="<?php echo $myFile; ?>">
        <?php endif; ?>
        <input type="hidden" name="no_shipping" value="1">
          <input type="hidden" name="no_note" value="1">
          <input type="hidden" name="currency_code" value="USD">
          <input type="hidden" name="lc" value="US">
          <input type="hidden" name="bn" value="PP-BuyNowBF">
          <input type="hidden" name="notify_url" value="<?php echo $script_root; ?>/ipn.php">
          <input type="hidden" name="return" value="<?php echo $script_root; ?>/thank_you.html">
          <input type="hidden" name="submit" value="1">
          <div class="form-group" ng-class="{ 'has-error' : (paypal.checkbox.$invalid )}">
            <label>
            <input name="checkbox" type="checkbox" required ng-model="data.checkbox"
                   ng-true-value="'on'" ng-false-value="">
                   I accept the Terms & Conditions and Privacy Policy
           </label>
           <div class="help-block">
             <p ng-if="paypal.checkbox.$invalid && formSubmited">please confirm terms & conditions and privacy policy</p>
           </div>
         </div>
        <button type="submit" ng-if="!paypal.$invalid" class="btn btn-default submit-btn">Pay with PayPal</button>
        <button type="button" ng-if="paypal.$invalid" ng-click="validatePayPal()" class="btn btn-default submit-btn">Pay with PayPal</button>
      </form>
        </div>
    </div>
  

    <script src="js/jquery.min.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.0/angular-messages.js"></script>

    
    <script >
        var fileName = "<?php  echo $myFile; ?>";
    </script>
    
    <script src="js/app.js"></script>
    <?php if (isset($_POST['submit']) && isset($myFile) && !$errors): ?>
    <script >
    
      $(document).ready(function() {
        $("#main").hide(1);
        $("#loading").css("display","block");
        
        setTimeout(function(){
          $( ".submit-btn" ).trigger( "click" );
        $("#rdcompanylogo").prop("checked",true);

        }, 2000);
            
        });

    </script>
    <?php endif; ?>


		  <p>&nbsp;</p>
		  <p>&nbsp;</p>
		  <p>&nbsp;</p>
		  <p>&nbsp;</p>
		 
		  
		        </div>
		 
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      <p><a href="terms.html">Terms & Conditions</a> | <a href="privacy.html">Privacy Policy</a></p>
      <p>Copyright &copy; simplestyle_4 design from HTML5webtemplates.co.uk</p>
    </div>
  </div>
</body>
</html>
