<?php


namespace App\Http\Controllers\Jobs;


use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Jobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobsController extends Controller
{

    /**
     * Create Job
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addJob(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'company_description' => 'required',
            'email' => 'required|email',
            'deadline' => 'required|date',
            'gmp' => 'boolean'
        ]);


        $data = [
            'uuid' => Str::uuid(),
            'title' => $request->input('title'),
            'description' =>  $request->input('title'),
            'company' => $request->input('company'),
            'company_description' => $request->input('company_description'),
            'email' =>  $request->input('email'),
            'deadline' =>  $request->input('deadline'),
            'gmp' =>  $request->input('gmp'),
        ];

        if(!Jobs::create($data))
            return response()->json(['status' => 400, 'message' => 'Unable to create Job, Please try later!'], 400);

        return response()->json(['status' => 200, 'message' => 'Job created successful!'], 200);

    }


    /**
     * list all jobs
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $job = Jobs::all();

        return response()->json(['status' => 200, 'data' => $job], 200);
    }


    /**
     * update a job
     * @param Request $request
     * @param $uuid
     * @return JsonResponse
     */
    public function updateJob(Request $request, $uuid): JsonResponse
    {
        if(!Jobs::where('uuid', $uuid)->update($request->all()))
            return response()->json(['status' => 400, 'message' => 'Unable to edit Job, Please try again']);

        return response()->json(['status' => 200, 'message' => 'Job Successfully Updated']);
    }

    /**
     * Apply for a job
     * @param Request $request
     * @param $uuid
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function apply(Request $request, $uuid): JsonResponse
    {
        $this->validate($request, [
            'cv' => 'required|mimes:pdf|max:10000',
            'cover' => 'required|mimes:pdf|max:10000',
            'description' => 'required'
        ]);

        $job = Jobs::where('uuid', $uuid)->first();


        if(!$job)
            return response()->json(['status' => 404, 'message' => 'Job does not exist'], 404);

        if($job->gmp === 0)
            return response()->json(['status' => 404, 'message' => 'You can not apply from the portal'], 404);



        $data = [
            'user_id' => $request->auth->id,
            'uuid' => Str::uuid(),
            'job_id' => $job->id,
            'cv' => Helpers::uploadFile($request->file('cv')),
            'cover' => Helpers::uploadFile($request->file('cover')),
            'description' => $request->input('description')
        ];

        if(!JobApplication::create($data))
            return response()->json(['status' => 400, 'message' => 'Unable to Apply, Please try again '], 400);

        return response()->json(['status' => 200, 'message' => 'Applied successfully, Please try again '], 200);
    }

    /**
     * List Applications
     * @param $uuid
     * @return JsonResponse
     */
    public function application($uuid): JsonResponse
    {
        $job = Jobs::where('uuid', $uuid)->first();

        if(!$job)
            return response()->json(['status' => 404, 'message' => 'Job does not exist'], 404);

        return response()->json(['status' => 200, 'data' => JobApplication::where('job_id', $job->id)->get()], 200);

    }



}
