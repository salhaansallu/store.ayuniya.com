<?php

namespace app;

use Twilio\Exceptions\HttpException;
use Twilio\Rest\Client;

class OTP{


    function __construct()
    {

    }


    public function sendOTP($otp, $number){
        // $accountSid = env('TWILIO_ACCOUNT_SID');
        // $authToken  = env('TWILIO_AUTH_TOKEN');
        // $appSid     = env('TWILIO_APP_SID');
        // $client = new Client($accountSid, $authToken);
        // try
        // {
        //     // Use the client to do fun stuff like send text messages!
        //     $client->messages->create(
        //     // the number you'd like to send the message to
        //     $number,
        //     array(
        //          // A Twilio phone number you purchased at twilio.com/console
        //          "messagingServiceSid" => "MGe49b522f33ce60b99b257f6cdd009146",
        //          // the body of the text message you'd like to send
        //          'body' => $otp." is your OTP for Ayuniya.com. Please do not share this message with anyone",
        //     )
        //     );

        //     return array(
        //         "error" => "0",
        //     );
        // }
        // catch (HttpException $e)
        // {
        //     return array(
        //         "error" => "1",
        //         "message" => $e->getMessage(),
        //     );
        // }

        return array(
                    "error" => "0",
                    "message" => "OTP sent!",
                );
    }
}

?>
