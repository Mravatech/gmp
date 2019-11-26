<?php


namespace App\Http\Controllers\Profile;


use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    /**
     * add user Bio-data
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $user  = $request->auth->id;
        $profile = UserProfile::where('user_id', $user);

        if(!$profile->first())
            return response()->json(['status' => 404, 'message' => 'User does not exist, Please try again!'], 404);

        if(!$profile->update($request->all()))
            return response()->json(['status' => 400, 'message' => 'Unable to update user, Please try again!'], 400);

        return response()->json(['status' => 200, 'message' => 'User Profile successfully Updated!'], 200);

    }

    /**
     * get User Profile
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        $user  = $request->auth->id;
        $profile = UserProfile::where('user_id', $user);

        if(!$profile->first())
            return response()->json(['status' => 400, 'message' => 'User does not exist, Please try again!'], 400);

        return response()->json(['status' => 200, 'data' => $profile->first()], 200);

    }
}
