<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Sendchamp
{


    public $sendchampUrl;
    public $sendchampPost;


	//create payment authorization url
  public function sendEmail($email,$name,$message,$subject)
  {
	$this->sendchampUrl=config("constants.sendchamp.baseurl")."/email/send";
	$this->sendchampPost=json_encode([
    "to" => [[
		"email" => $email,
		"name" => $name
	]],
    "from" => [
		"email" => "sales@fatherlandglobal.com",
		"name" => "Fatherland"
	],
    "message_body" => [
		"type" => "text/html",
		"value" => $message
	],
    "subject" => $subject 
	]);


	$data=$this->sendchampCurl();

 
	return $data;
	
  }

  public function sendSms($to,$message)
  {
	$this->sendchampUrl=config("constants.sendchamp.baseurl")."/sms/send";
	$this->sendchampPost=json_encode([
    "to" => $to,
    "message" => $message,
   	"sender_name" => "SAlert",
   	"route" => "dnd"
	]);

	$data=$this->sendchampCurl();

	return $data;
	
  }

  	public function sendchampCurl()
  {

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $this->sendchampUrl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $this->sendchampPost,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . (env('APP_ENV') == 'production' ? config("constants.sendchamp.dev_key") : config("constants.sendchamp.dev_key")),
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return json_decode($response);

  }

}
