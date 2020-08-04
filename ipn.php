<?php
// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
require_once("./config.php");
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 0);
// Set to 0 once you're ready to go live
define("LOG_FILE", "./ipn.log");

// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
    $get_magic_quotes_exists = true;
}
$req_values = array();
foreach ($myPost as $key => $value) {
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
    $req_values[$key] = $value;

}
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data
$ch = curl_init($paypal_url);

if ($ch == FALSE) {

    return FALSE;
}
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
if(DEBUG == true) {
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}
// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);
$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
    {
    if(DEBUG == true) { 
        error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
    }
    curl_close($ch);
    exit;
} else {
        // Log the entire HTTP response if debug is switched on.
        if(DEBUG == true) {
            error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
            error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
        }
        curl_close($ch);
}
// Inspect IPN validation result and act accordingly
// Split response headers and payload, a better way for strcmp
$tokens = explode("\r\n\r\n", trim($res));

$res = trim(end($tokens));

// die($res);
if (strcmp ($res, "VERIFIED") == 0) {
    
    // check whether the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment and mark item as paid.
    // assign posted variables to local variables
    //$item_name = $_POST['item_name'];
    //$item_number = $_POST['item_number'];
    //$payment_status = $_POST['payment_status'];
    //$payment_amount = $_POST['mc_gross'];
    //$payment_currency = $_POST['mc_currency'];
    //$txn_id = $_POST['txn_id'];
    //$receiver_email = $_POST['receiver_email'];
    //$payer_email = $_POST['payer_email'];
     
    $info_file_name = urldecode($req_values["custom"]);
    $jsondata = file_get_contents($info_file_name);
    $info_file_array = json_decode($jsondata, true);
    error_log($jsondata, 3, LOG_FILE);
    $msg_body = "";
    foreach ($info_file_array as $key => $value) {
        switch ($key) {
            case 'fname';
                $msg_body .= "<p><strong>First Name:</strong> " .$value ."</p>";
            break;
            case 'lname';
                $msg_body .= "<p><strong>Last Name:</strong> " .$value ."</p>";
            break;
            case 'email';
                $msg_body .= "<p><strong>Email:</strong> " .$value ."</p>";
            break;
            case 'coname';
                $msg_body .= "<p><strong>Company Name:</strong> " .$value ."</p>";
            break;
            case 'coabout';
                $msg_body .= "<p><strong>About Company:</strong> </p><p>" .$value ."</p>";
            break;
            case 'address';
                $msg_body .= "<p><strong>Web Address:</strong> " .$value ."</p>";
            break;
            case 'img';
                $msg_body .= "<p><strong>Image?:</strong> " .$value ."</p>";
            break;
            case 'width';
                $msg_body .= "<p><strong>Width:</strong> " .$value ."px</p>";
            break;
            case 'height';
                $msg_body .= "<p><strong>Height:</strong> " .$value ."px</p>";
            break;
            //case 'charity';
            //    $msg_body .= "<p><strong>Charity:</strong> " .$value ."</p>";
            //break;
            case 'amount';
                $msg_body .= "<p><strong>Amount:</strong> $" .$value ."</p>";
            break;

            
        }
    }
    
    require_once 'lib/swift_required.php';

    // Create the Transport
    $transport = (new Swift_SmtpTransport(SMTP, PORT,null));
    $transport->setUsername(USERNAME);
    $transport->setPassword(PASSWORD);
    // Create the Mailer using your created Transport
    $mailer = Swift_Mailer::newInstance($transport);

    // Create the message
    $message = Swift_Message::newInstance()

      // Give the message a subject
      ->setSubject('PayPal Form')

      // Set the From address with an associative array
      ->setFrom(array(FROM => FROM_NAME))

      // Set the To addresses with an associative array
      ->setTo(array(EMAIL => EMAIL_NAME))

      // Give it a body
      ->setBody(
        '<html>' .
        ' <head></head>' .
        ' <body>' .
        '<p>You received payment from ' .
        $info_file_array["fname"] . " " . $info_file_array["lname"] ."</p>" .
         "<p>Here are the details:</p>" .
         $msg_body .
        
         ' </body>' .
        '</html>'
        ,'text/html'
        );

      // Optionally add any attachments
      if(isset($info_file_array["image_url"])){
        $message->attach(Swift_Attachment::fromPath($info_file_array["image_url"]));
      }
      

    $result = $mailer->send($message);
    unlink('uploads/' .$info_file_array["image_name"]);
    unlink($info_file_name);

    if(DEBUG == true) {
        error_log(date('[Y-m-d H:i e] '). "Verified IPN: $info_file_array ". PHP_EOL, 3, LOG_FILE);
    }
} else if (strcmp ($res, "INVALID") == 0) {
    
    // log for manual investigation
    // Add business logic here which deals with invalid IPN messages
    if(DEBUG == true) {
        error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
    }
}
?>
