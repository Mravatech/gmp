<?php


namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Models\PaymentTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    /**
     * Add PaymentT Type
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addTypes(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' => 'required',
            'amount' => 'required'
        ]);

        $data = [
            'uuid' => Str::uuid(),
            'name' => $request->input('name'),
            'amount' => $request->input('amount')
        ];

        if(!PaymentTypes::create($data))
            return response()->json(['status' => 400, 'message' => 'Unable to add Payment type, Please try again']);

        return response()->json(['status' => 200, 'message' => 'Payment Type Successfully Added']);
    }

    /**
     * Edit Payment Types
     * @param Request $request
     * @param $uuid
     * @return JsonResponse
     */
    public function editTypes(Request $request, $uuid): JsonResponse
    {
        if(!PaymentTypes::where('uuid', $uuid)->update($request->all()))
            return response()->json(['status' => 400, 'message' => 'Unable to edit Payment type, Please try again']);

        return response()->json(['status' => 200, 'message' => 'Payment Type Successfully Updated']);
    }


    /**
     * Delete Payment Types
     * @param Request $request
     * @param $uuid
     * @return JsonResponse
     */
    public function deleteTypes(Request $request, $uuid): JsonResponse
    {
        if(!PaymentTypes::where('uuid', $uuid)->delete())
            return response()->json(['status' => 400, 'message' => 'Unable to delete Payment type, Please try again']);

        return response()->json(['status' => 200, 'message' => 'Payment Type Successfully Deleted']);
    }





}
