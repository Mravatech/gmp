<?php


namespace App\Http\Controllers\Mentors;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MentorController extends Controller
{

    public function requestMentor(Request $request): JsonResponse
    {
        $user = $request->auth->id;

        $this->validate($request, [
            'reason' => 'required',
            'preference' => 'string'
        ]);


    }
}
