<?php

function confirmSubscription() {
    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-synch';
    $header = "";
    $tx_token = $_GET['tx'];
    $auth_token = "A45W-3O32Tpjckknd.-r4.cnXqxZA3kVlzj0YCxUMeE2v3jVr.-A.05Z";
    $req .= "&tx=$tx_token&at=$auth_token";

    // post back to PayPal system to validate
    $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    $fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
    // If possible, securely post back to paypal using HTTPS
    // Your PHP server will need to be SSL enabled
    // $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

    if (!$fp) {
        // HTTP ERROR
        return 'http error';
    } else {
        fputs ($fp, $header . $req);
        // read the body data
        $res = '';
        $headerdone = false;
        while (!feof($fp)) {
            $line = fgets ($fp, 1024);
            if (strcmp($line, "\r\n") == 0) {
                // read the header
                $headerdone = true;
            }
            else if ($headerdone)
            {
                // header has been read. now read the contents
                $res .= $line;
            }
        }
        // parse the data
        $lines = explode("\n", $res);
        $keyarray = array();

        if (strcmp ($lines[0], "SUCCESS") == 0) {
            for ($i=1; $i<count($lines);$i++){
                list($key,$val) = explode("=", $lines[$i]);
                $keyarray[urldecode($key)] = urldecode($val);
            }
            // check the payment_status is Completed
            // check that txn_id has not been previously processed
            // check that receiver_email is your Primary PayPal email
            // check that payment_amount/payment_currency are correct
            // process payment
            $firstname = $keyarray['first_name'];
            $lastname = $keyarray['last_name'];
            $itemname = $keyarray['item_name'];
            $amount = $keyarray['mc_gross'];

            //echo ("<p><h3>Thank you for your purchase!</h3></p>");
            //echo ("<b>Payment Details</b><br>\n");
            //echo ("<li>Name: $firstname $lastname</li>\n");
            //echo ("<li>Item: $itemname</li>\n");
            //echo ("<li>Amount: $amount</li>\n");
            //echo ("");
            return print_r($keyarray,true);
        }
        else if (strcmp ($lines[0], "FAIL") == 0) {
            // log for manual investigation
            return 'FAIL';
        }
    }
    fclose ($fp);

/*
    Your transaction has been completed, and a receipt for your purchase has been emailed to you.<br>You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.<br>
*/
};
