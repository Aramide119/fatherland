<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Paypal
{


	public $paypalUrl;
    public $paypalPost;


	//create initializing Link
  public function createCatalog($name,$description)
  {
	$this->paypal=config("constants.paypal.baseurl")."catalogs/product";
	$this->paypalPost=json_encode([
        "name"=> $name,
        "description" => $description,
        "type" => 'SERVICE',
        "category" => 'ENTERTAINMENT_AND_MEDIA',
        "image_url" => "",
        "home_url" => "",
	]);

	$data=$this->paypalCurl();
    

 
	return $data;
	
  }

  
  //verify paystack
    public function verifyPaystackPayment($reference)
    {
    $this->paystackUrl=config("constants.paystack.baseurl")."verify/".$reference;
    
    $data=$this->verifyPaystackCurl();
  
    return $data;
    }


    


  public function paypalCurl()
  {
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $this->paystackUrl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $this->paypalPost,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer sk_test_50e3ec951849f7d27b68b7e5ac2e03c849fd63af',
    'Accept: application/json',
    'Content-Type: application/json',
    
  ),
));

// $response = curl_exec($curl);

// curl_close($curl);
// return json_decode($response);

 $response = curl_exec($curl);


        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
//            $data = [
//                'message' => "cURL Error #: {$err}"
//            ];

            //return response()->json([$data], 503);
            return json_decode("cURL Error #:" . $err);
        } else {
            return json_decode($response);
        }

  }


  //verifypaymentreference



  public function stripeCurl()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.stripe.com/v1/charges', // Stripe charges endpoint
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'amount=2000&currency=usd&source=tok_visa', // Add your payment details here
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer sk_test_4eC39HqLyjWDarjtT1zdp7dc',
            'Content-Type: application/x-www-form-urlencoded',
        ),
    ));

    $response = curl_exec($curl);

    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return json_decode("cURL Error #:" . $err);
    } else {
        return json_decode($response);
    }
}

}

