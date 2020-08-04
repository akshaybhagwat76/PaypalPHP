<?php

// Set to 0 once you're ready to go live
define("USE_SANDBOX", 1);

// Set this to 0 once you go live or don't require logging.
define("DEBUG", 1);

// Set Email sender
define("FROM", 'test@holisticfeeling.com');
define("FROM_NAME", 'Paypal Form');

// here you can set your email that you want to send mails to
define("EMAIL", 'ssbahra@gmail.com');
// Email name
define("EMAIL_NAME","Sukhy");


/*Email system configuration*/
define("SMTP", 'mailout.one.com');
define("PORT", 25);
define("USERNAME", 'test@holisticfeeling.com');
define("PASSWORD", 'merc190e');

if(USE_SANDBOX == true) {
    $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
    $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

?>
