<?php

namespace App\Http\Controllers;

use App\Modalku\Modalku;
use App\Models\Callback;
use App\Models\invoice;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ModalkuController extends Controller
{
    protected $modalku;

    public function __construct()
    {
        $this->modalku = new Modalku;
    }


    public function postModalku(){
        $data = [
            "transaction_id" => "SHP049",
            "amount" => 20000.00,
            "redirect_uri" => "https://stage.importir.com/home",
            "webhook_uri" => "  "
        ];

        invoice::create([
            'no_invoice'     => $data['transaction_id'],
            'amount'         => $data['amount'],
            'status'         => "pending",
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-uat.fundingasiagroup.com/paylater/transactions/partner',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer 5kxA6YFsRfPDMfB9Jh8f36wdII91rJY2',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function modalkuTransaction(){
        $client = new Client();

        $request = $client->request('POST', 'https://partner-uat.fundingasiagroup.com/paylater/transactions/partner', [
            'headers' => [
                "Authorization" => 'Bearer KaArY5sZfPinEy3F8tR0dcij9Gmm9aG6',
                'Content-type' => 'application/json'
            ],

            'json' => [
                "transaction_id" => "SHP043",
                "amount" => 20000.00,
                "redirect_uri" => "https://asus.jprq.io/testmodal",
                "webhook_uri" => "https://hookb.in/E70QW2D9qoFVjY66jOEq"
            ]
        ]);

        return $request->getBody();
    }
    
    public function olahCallback(Request $request)
    {
        $client = new Client();
        $request = $client->request('POST', 'https://hookb.in/E70QW2D9qoFVjY66jOEq', [
            'headers' => [
                'Content-type' => 'application/json',
            ],
        ]);

        return $request->getBody()->getContents();
    }

    public function getCallback(Request $request){
        // $data = [
        //     "transaction_id" => "SHP045",
        //     "uuid" => "fc9c97cb-eaad-4643-ac33-c2321ade80d9",
        //     "status" => "SUCCESS",
        //     "loan_code" => "IPVC-21120019"
        // ];

        $handle = curl_init('https://hookb.in/E70RRqoxRnSVjY66jO1Y');
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($request->all()));
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $result = curl_exec($handle);

        $cb = Callback::create([
            'transaction_id'    => $request->transaction_id,
            'uuid'              => $request->uuid,
            'status'            => $request->status,
            'loan_code'         => $request->loan_code,
        ]);
        return $cb;
    }
    
    // public function modalkucallback(Request $request){
    //     Log::error('Callback Modalku ' . json_encode($request->all()));
    //     try {
    //         if ($request->status == 'SUCCESS') {
    //             $modalkuRepo = new ModalkuRepository();
    //             $modalkuCallback = $modalkuRepo->callback($request);
    //             return response()->json($modalkuCallback);
    //         }
    //         return response()->json(['status' => false, 'reference_number' => $request->reference_number]);
    //     } catch (\Exception $e) {
    //         Log::error('Callback Modalku err exception ' . json_encode($request->all()) . 'error_message : ' . $e->getMessage() . ', error_code : ' . $e->getCode());
    //         return response()->json(['status' => false, 'message' => $e->getMessage()]);
    //     }
    
    // }
}
