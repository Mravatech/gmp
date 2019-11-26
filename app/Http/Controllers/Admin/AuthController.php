<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;


class AuthController extends Controller
{

    /**
     * Admin Login
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = Admin::where('email', $request->json('email'))->first(); // Find the user by email

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
     * Generate  login JWT
     * @param Admin $user
     * @return mixed
     */
    protected function createJwt(Admin $user)
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
     * Admin Registration
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);


        $data = [
            'uuid' => Str::uuid(),
            'email' => $request->input('email'),
            'password' => password_hash(trim($request->input('password')), PASSWORD_BCRYPT),
        ];

        if (!($user = Admin::create($data))) // If storing of data failed
            return response()->json(['status' => 400, 'message' => 'Registration failed, Please try again!'], 400);


        return response()->json(['status' => 200, 'message' => 'Registration successful!'], 200);
    }


    /**
     * Authorize an Admin
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
        } catch (Throwable $e) {
            return response()->json(['message' => 'An error while decoding token.' . $token], 400);
        }


        $user = Admin::find($credentials->sub);

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
