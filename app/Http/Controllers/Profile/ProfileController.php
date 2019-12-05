<?php


namespace App\Http\Controllers\Profile;


use App\Helpers\Helpers;
use App\Models\Education;
use App\Models\Experience;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;


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

        if (!$profile->first())
            return response()->json(['status' => 404, 'message' => 'User does not exist, Please try again!'], 404);

        if (!$profile->update($request->all()))
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

        if (!$profile->first())
            return response()->json(['status' => 400, 'message' => 'User does not exist, Please try again!'], 400);

        return response()->json(['status' => 200, 'data' => $profile->first()], 200);
    }

    public function updatePassport(Request $request): JsonResponse
    {
        $user  = $request->auth->id;
        $profile = UserProfile::where('user_id', $user);

        $this->validate($request, [
            'passport' => 'required|max:10000',
        ]);

        if (!$profile->first())
            return response()->json(['status' => 400, 'message' => 'User does not exist, Please try again!'], 400);

        if (!$profile->update(['passport' => Helpers::uploadFile($request->file('passport'))]))
            return response()->json(['status' => 400, 'message' => 'Unable to update user, Please try again!'], 400);

        return response()->json(['status' => 200, 'message' => 'User Passport set successfully Updated!'], 200);
    }


    public  function updateEducation(Request $request)
    {
        $user  = $request->auth->id;
        $profile = Education::where('user_id', $user);

        if (!$profile->first())
            return response()->json(['status' => 404, 'message' => 'User does not exist, Please try again!'], 404);

        if (!$profile->update($request->all()))
            return response()->json(['status' => 400, 'message' => 'Unable to update user, Please try again!'], 400);

        return response()->json(['status' => 200, 'message' => 'User Education Profile successfully Updated!'], 200);
    }


    public  function updateExperience(Request $request)
    {
        $user  = $request->auth->id;
        $profile = Experience::where('user_id', $user);

        if (!$profile->first())
            return response()->json(['status' => 404, 'message' => 'User does not exist, Please try again!'], 404);

        if (!$profile->update($request->all()))
            return response()->json(['status' => 400, 'message' => 'Unable to update user, Please try again!'], 400);

        return response()->json(['status' => 200, 'message' => 'User Education Profile successfully Updated!'], 200);
    }
}
