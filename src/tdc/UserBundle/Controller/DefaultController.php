<?php
namespace tdc\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use tdc\UserBundle\Entity\Subscription;

#require_once(dirname(__file__)."/../Lib/confirm.php");

class DefaultController extends Controller
{
    public function subscribeAction()
    {
        $userObj = $this->container->get('security.context')
                    ->getToken()
                    ->getUser();
             
        return $this->render('tdcUserBundle:Default:subscribe.html.twig',
                            array("user"=>$userObj));
    }

    public function subscribeConfirmAction()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];

        $retval  ="Transaction: ".$txn_id."\n";
        $retval .="Payer Email: ".$payer_email."\n";
        $retval .="Status : ".$payment_status."\n";
        $retval .="Item Name: ".$item_name."\n";
        $retval .="Item Number: ".$item_number."\n";
        $retval .= "Reuest: ".$req."\n";

        $expireTable = array("m1"=>"+1 month","m3"=>"+3 months","m6"=>"+6 months","m12"=>"+1 year");

        $reason = "";
        if (!$fp) {
            // HTTP ERROR
            $reason = "Failed to connect to paypal for payment confirmation\n";
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                if (strcmp ($res, "VERIFIED") == 0) {
                    try {
                            $em =  $this->getDoctrine()->getEntityManager();
                            // check that txn_id has not been previously processed
                            $subscr = $this->getdoctrine()->getrepository('tdcUserBundle:Subscription')
                                        ->findByTransaction($txn_id);
                            if ($subscr) {
                                $reason = "Exists already\n";
                            } else {
                                $userObj = $this->container->get('security.context')
                                        ->getToken()
                                        ->getUser();
                                $subscr = new Subscription();
                                $now   = new \DateTime('now');
                                $subscr->setCreated($now);
                                $expire = new \DateTime('now');
                                $expire->modify($expireTable[$item_number]);
                                $subscr->setExpires($expire);
                                $subscr->setDuration($item_number);
                                $subscr->setTransaction($txn_id);
                                $subscr->setUserSubscription($userObj);
                                $subscr->setStatus(strtolower($payment_status));

                                $em->persist($subscr);
                                $em->flush();

                            }
                        } catch (Exception $e) {
                            $reason ='Caught exception: '.$e->getMessage()."\n";
                        }
                        // check that receiver_email is your Primary PayPal email
                        // check that payment_amount/payment_currency are correct
                        // process payment
                }
                else if (strcmp ($res, "INVALID") == 0) {
                    // log for manual investigation
                    $reason = "Invalid transaction\n";
                }
            }
            fclose ($fp);
        }

        if ($reason != "") {
            $fp = fopen(getcwd().'/../app/logs/'.$txn_id.'.txt', 'w');
            fwrite($fp, $reason.$retval);
            fclose($fp);
        }

        return new Response($reason.$retval);

    }
}
