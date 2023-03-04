<?php 

namespace app;


class OTP{


    function __construct()
    {
        
    }


    private $API_KEY = '';
    private $SENDER_ID = '';
    private $ROUTE_NO = '';
    private $RESPONSE_TYPE = '';

    public function sendOTP($otp, $number){
        // $isError = 0;
        // $errormessage = true;

        // $message = urlencode('');

        // $data = array(
        //     'authkey' => $this->API_KEY,
        //     'mobile' => $this->number,
        //     'message' => $this->message,
        //     'sender' => $this->SENDER_ID,
        //     'route' => $this->ROUTE_NO,
        //     'response' => $this->RESPONSE_TYPE
        // );

        // $url = "";

        // $crl = curl_init();

        // curl_setopt_array($crl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_POST => true,
        //     CURLOPT_POSTFIELDS => $data
        // ));


        // curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, 0);

        // $output = curl_exec($crl);

        // if (curl_errno($crl)) {
        //     $isError = true;
        //     $errorMessage = curl_error($crl);
        // }
        // curl_close($crl);
        // if($isError){
        //     return array('error' => 1 , 'message' => $errorMessage);
        // }else{
        //     return array('error' => 0 );
        // }

        return array('error' => 0 );
    }
}

?>