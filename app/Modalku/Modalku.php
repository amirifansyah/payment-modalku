<?php

namespace App\Modalku;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class Modalku{
    private $baseUrl, $clientId, $clientSecret;

    public function __construct()
    {
        $this->baseUrl = env('MODALKU_BASE_URL');
        $this->clientId = env('MODALKU_CLIENT_ID');
        $this->clientSecret = env('MODALKU_CLIENT_SECRET');
    }

    public function generateBase64()
    {
        return base64_encode($this->clientId . ':' . $this->clientSecret);
    }

    public function requestApi($params, $token, $url, $methode = 'GET', $contentType = "application/json"){
        $result = ['status' => false,'message' => ''];
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $methode,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $token,
                "Content-Type: " . $contentType,
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            Log::error('Modalku Curl Err ' . json_encode($err));
            $result['status'] = true;
            $result['message'] = 'Curl Err ' . json_encode($err);
        }

        $response = json_decode($response, true);
        return $response;
    }

    public function requestToken()
    {
        try {
            $result = ['status' => false,'message' => ''];
            $token = "Basic " . $this->generateBase64();
            $url = $this->baseUrl . "oauth2/token";
            $params = "grant_type=client_credentials";
            $getToken = $this->requestApi($params,$token, $url, 'POST', 'application/x-www-form-urlencoded');
            if (!$getToken) {
                $result['status'] = true;
                $result['message'] = 'token is undefined';
            }
            return $getToken;
        } catch (\Exception $e) {
            $result['status'] = true;
            $result['message'] = $e->getMessage();
            return $result;
        }
    }

    public function postRequestPaymentTransaction($transactionId, $amount, $redirectUrl, $callBackUrl = ""){
        dd('mek');
        $requestToken = $this->requestToken();
            if (!$requestToken['status']) {
                return returnCustom($requestToken['message']);
            }
            $token = "Bearer " . $requestToken['message'];
            dd('kon');
    }
}