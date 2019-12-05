<?php


namespace App\Http\Controllers\Auth;


use App\Models\User;
use Firebase\JWT\JWT;
use App\Models\Education;
use App\Models\Experience;
use App\Models\MemberBank;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Firebase\JWT\ExpiredException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Generate member login JWT
     * @param User $user
     * @return mixed
     */
    protected function createJwt(User $user)
    {
        $payload = [
            'iss' => env('GMP_SECRET'), // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'ut' => 1,
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 3600 * 3600 // Expiration time
        ];

        return JWT::encode($payload, env('GMP_SECRET'));
    }

    /**
     * Member Registration
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:4',
            'first_name' => 'required',
            'last_name' => 'required',
            'other_name' => 'string',
        ]);

        $member_id = $this->generateMemeberID();

        if (!$member_id)
            return response()->json(['status' => 400, 'message' => 'Registration failed, Unable to generate Membership ID!'], 400);


        $data = [
            'uuid' => Str::uuid(),
            'email' => $request->input('email'),
            'password' => password_hash(trim($request->input('password')), PASSWORD_BCRYPT),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'other_name' => $request->input('other_name'),
            'member_id' => $member_id
        ];

        if (!($user = User::create($data))) // If storing of data failed
            return response()->json(['status' => 400, 'message' => 'Registration failed, Please try again!'], 400);

        UserProfile::create([
            'uuid' => Str::uuid(),
            'member_id' => $member_id,
            'user_id' => $user->id
        ]);

        Education::create([
            'uuid' => Str::uuid(),
            'member_id' => $member_id,
        ]);

        Experience::create([
            'uuid' => Str::uuid(),
            'member_id' => $member_id,
        ]);

        return response()->json(['status' => 200, 'message' => 'Registration successful!'], 200);
    }


    /**
     * Generate Membership ID
     * @return bool|string
     */
    public function generateMemeberID()
    {
        $str = 'G';
        $number = 000050;

        $bank = MemberBank::orderBy('id', 'desc')->first();

        if ($bank) {
            $newMemeber = $bank->member + 1;
        } else {
            $newMemeber = $number;
        }

        $data = [
            'uuid' => Str::uuid(),
            'member' => $newMemeber
        ];

        if (!MemberBank::create($data))
            return  false;

        return $str . $newMemeber;
    }


    /**
     * Member login
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = User::where('email', $request->json('email'))->first(); // Find the user by email

        if (!$data) {
            return response()->json([
                'status' => 400,
                'message' => 'User does not exist.'
            ], 400);
        }


        // Verify the password and generate the token
        if (Hash::check($request->json('password'), $data->password)) {

            return response()->json([
                'status' => 200,
                'message' => 'Login Successful',
                'data' => ['token' => $this->createJwt($data), 'user' => $data] // return token
            ], 200);
        }


        return response()->json([
            'status' => 400,
            'message' => 'Login details provided does not exit.'
        ], 400);
    }

    /**
     * Authorize a User
     * @param Request $request
     * @return JsonResponse
     */
    public function authorization(Request $request): JsonResponse
    {
        $token = $token = $request->bearerToken();

        try {
            $credentials = JWT::decode($token, env('GMP_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json(['message' => 'Provided token is expired.'], 400);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An error while decoding token.' . $token], 400);
        }


        $user = User::find($credentials->sub);

        if (!$user)
            return response()->json(['message' => 'Provided token is invalid, Please re-authenticate!'], 401);

        if ($user->status == 1)
            return response()->json(['message' => 'Account suspended, Please contact admin.'], 401);

        // Data
        $data = $user->toArray();

        unset($data['updated_at']); // Remove the update_at

        return response()->json(['status' => 200, 'message' => 'Authorization successful!', 'data' => $data], 200);
    }
}
