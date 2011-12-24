<?php

namespace tdc\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use tdc\UserBundle\User;
use tdc\UserBundle\Subscription;

class DailyCommand extends ContainerAwareCommand
{
    /** GetTransactionDetails NVP example; last modified 08MAY23.
     *
     *  Get detailed information about a single transaction. 
     */

    private $environment = 'sandbox'; // or 'beta-sandbox' or 'live'

    /**
     * Send HTTP POST Request
     *
     * @param   string  The API method name
     * @param  string  The POST Message fields in &name=value pair format
     * @return    array   Parsed HTTP Response body
     */
    private function PPHttpPost($methodName_, $nvpStr_) {

        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode('seller_1324441092_biz_api1.rudycortes.com');
        $API_Password = urlencode('1324441117');
        $API_Signature = urlencode('A45W-3O32Tpjckknd.-r4.cnXqxZA3kVlzj0YCxUMeE2v3jVr.-A.05Z');
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
            $API_Endpoint = "https://api-3t.$this->environment.paypal.com/nvp";
        }
        $version = urlencode('51.0');

        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if(!$httpResponse) {
            exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        return $httpParsedResponseAr;
    }




    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('tdc:user:daily')
            ->setDescription('Run DAILY maintenance')
            ->setDefinition(array())
            ->setHelp(<<<EOT
The <info>tdc:user:daily</info> runs necessary DAILY commands:

  <info>php app/console tdc:user:daily</info>
EOT
            );
    }

    private function processPending()
    {
        $rep = $this->getContainer()->get('doctrine')->getrepository('tdcUserBundle:Subscription');
        $pending = $rep->findByStatus('pending');
        foreach ($pending as $item) {
            // Set request-specific fields.
            $transactionID = urlencode($item->getTransaction());

            // Add request-specific fields to the request string.
            $nvpStr = "&TRANSACTIONID=$transactionID";

            // Execute the API operation; see the PPHttpPost function above.
            $httpParsedResponseAr = $this->PPHttpPost('GetTransactionDetails', $nvpStr);

            if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                exit('GetTransactionDetails Completed Successfully: '.print_r($httpParsedResponseAr, true));
            } else  {
                exit('GetTransactionDetails failed: ' . print_r($httpParsedResponseAr, true));
            }
        }
    }


    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->processPending();
    /*
        $username = $input->getArgument('username');

        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        $manipulator->activate($username);

        $output->writeln(sprintf('User "%s" has been activated.', $username));
    */
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    /*
        if (!$input->getArgument('username')) {
            $username = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a username:',
                function($username)
                {
                    if (empty($username)) {
                        throw new \Exception('Username can not be empty');
                    }
                    return $username;
                }
            );
            $input->setArgument('username', $username);
        }
    */
    }
}

