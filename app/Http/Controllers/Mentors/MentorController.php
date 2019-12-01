<?php


namespace App\Http\Controllers\Mentors;


use App\Http\Controllers\Controller;
use App\Models\MentorRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MentorController extends Controller
{

    public function requestMentor(Request $request): JsonResponse
    {
        $user = $request->auth->id;

        $this->validate($request, [
            'reason' => 'required',
            'preference' => 'string'
        ]);

        $data = [
            'reason' => $request->input('reason'),
            'preference' => $request->input('preference'),
            'user_id' => $user,
            'uuid' => Str::uuid()
        ];


        if (!(MentorRequest::create($data))) // If storing of data failed
            return response()->json(['status' => 400, 'message' => 'Request failed, Please try again!'], 400);

        return response()->json(['status' => 200, 'message' => 'Request successful!'], 200);
    }


    /**
     * assign mentor to request
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function assignMentor(Request $request, $uuid): JsonResponse
    {
        $this->validate($request, [
            'assigned_user' => 'required|exist:users'
        ]);

        $mentor_request = MentorRequest::where('uuid', $uuid);

        if (!$mentor_request->first())
            return response()->json(['status' => 400, 'message' => 'Invalid Request, Please try again!'], 400);


        if (!$mentor_request->update(['assigned_user' => $request->input('assigned_user')]))
            return response()->json(['status' => 400, 'message' => 'Unable to assign, Please try again!'], 400);

        return response()->json(['status' => 200, 'message' => 'Mentor Assigned Successful!'], 200);
    }


    /**
     * GEt my Request
     * @param Request $request
     * @return JsonResponse
     */
    public function myRequest(Request $request): JsonResponse
    {
        return response()->json(['status' => 200, 'data' => MentorRequest::where('user_id', $request->auth->id)->get()], 400);
    }


    /**
     * get all request
     * @param Request $request
     * @return JsonResponse
     */
    public function allRequest(Request $request): JsonResponse
    {
        return response()->json(['status' => 200, 'data' => MentorRequest::where('user_id', $request->auth->id)->all()], 400);
    }


    public function submitReport(Request $request)
    { }
}
