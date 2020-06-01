<?php


namespace App\Http\Controllers\Convention;

use GuzzleHttp\Client;
use App\Helpers\Helpers;
use App\Models\Convention;
use App\Models\PaymentLog;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ConventionFees;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ConventionController extends Controller
{

    public function getAllConvention(Request $request)
    {
        $convention = Convention::with(['fees'])->get();

        return response()->json(['status' => 200, 'data' => $convention], 200);
    }


    public function getConventionByYear(Request $request, $year)
    {
        $convention = Convention::with(['fees'])->where('year', $year)->first();

        return response()->json(['status' => 200, 'data' => $convention], 200);
    }


    public function createConvention(Request $request)
    {
        $this->validate($request, [
            'theme' => 'required',
            'name' => 'required',
            'year' => 'required'
        ]);


        $data = [
            'uuid' => Str::uuid(),
            'theme' => $request->input('theme'),
            'name' =>  $request->input('name'),
            'year' =>  $request->input('year')
        ];


        if (!Convention::create($data))
            return response()->json(['status' => 400, 'message' => 'Unable to create, Please try again!'], 400);

        return response()->json(['status' => 200, 'data' => 'Created Successfully!!'], 200);
    }


    public function createFees(Request $request, $uuid)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required'
        ]);

        $conv = Convention::where('uuid', $uuid)->first();
        if (!$conv)
            return response()->json(['status' => 400, 'message' => 'Invalid Payment Category, Please try again!'], 400);



        $data = [
            'uuid' => Str::uuid(),
            'name' =>  $request->input('name'),
            'price' =>  $request->input('price'),
            'convention_id' => $conv->id
        ];

        if (!ConventionFees::create($data))
            return response()->json(['status' => 400, 'message' => 'Unable to create, Please try again!'], 400);

        return response()->json(['status' => 200, 'data' => 'Created Successfully!!'], 200);
    }


    public function startPayment(Request $request, $uuid)
    {
        $fee = ConventionFees::where('uuid', $uuid)->first();
        if (!$fee)
            return response()->json(['status' => 400, 'message' => 'Invalid Payment Category, Please try again!'], 400);

        $user  = $request->auth->id;
        $profile = UserProfile::where('user_id', $user);

        if (!$profile->first())
            return response()->json(['status' => 404, 'message' => 'User does not exist, Please try again!'], 404);




        $data   = [
            "payRequest" => [
                "referenceID" => $refernce = $this->generateReferece(),
                "phoneno" => $profile->first()->phone_number,
                "transtreat" => "1",
                "totAmount" => $fee->price,
                "user" => env('PAYVANTAGE_USER'),
                "password" => env('PAYVANTAGE_PASS'),
                "hash" => env('PAYVANTAGE_HASH')
            ]
        ];

        $log = [
            'uuid' => Str::uuid(),
            'user_id' => $user,
            'amount' => $fee->amount,
            'type' => 500,
            'reference' => $refernce,
        ];

        if (!PaymentLog::create($log))
            return response()->json(['status' => 404, 'message' => 'Unable to proceed!, Please try again!'], 404);


        $client = new Client([
            'base_uri' => env('PAYVANTAGE_URL'),
        ]);

        $request = $client->post('/merchant/payreq', [
            'debug' => TRUE,
            'form_params' => $data,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        $response = $request->getBody()->getContents();

        return response()->json($response);
    }
    public function generateReferece()
    {
        $reference = Helpers::generateShortCode(7);

        if (PaymentLog::where('reference', $reference)->first())
            return $this->generateReferece;

        return $reference;
    }


    public function fromPayvantage(Request $request)
    {
        $this->validate($request, [
            'paymentUpdateRequest' => 'required'
        ]);

        Log::info($request);




        $resp = $request->paymentUpdateRequest;

        $transaction = PaymentLog::where('reference', $resp->referenceID);

        if (!$transaction->first())
            return response()->json(['status' => 404, 'message' => 'Transacation not found!, Please try again!'], 404);

        return response()->json(['status' => 200, 'message' => 'Recieved Successfully!'], 404);
    }
}
