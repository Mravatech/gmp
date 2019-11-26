<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\PaymentTypes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * List all member
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return response()->json(['status' => 200, 'data' => User::with(['details'])->get()], 200);

    }


    /**
     * Deactivate a user
     * @param Request $request
     * @param $uuid
     * @return JsonResponse
     */
    public function deactivateMember(Request $request, $uuid): JsonResponse
    {
        if(!User::where('uuid', $uuid)->update($request->all()))
            return response()->json(['status' => 400, 'message' => 'Unable to edit Payment type, Please try again']);

        return response()->json(['status' => 200, 'message' => 'Payment Type Successfully Updated']);
    }
}
