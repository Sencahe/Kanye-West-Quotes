<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Http\Controllers\Exception;

use \stdClass;

/**
  * @OA\Post(
 *      path="/api/auth/token",
 *      operationId="getBearerToken",
 *      tags={"Auth"},
 *      summary="Get the Bearer Token",
 *      description="Return bearer token",
 *      @OA\Response(
 *          response=200,
 *          description="successful token generated",
 *          @OA\JsonContent()
 *      ),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Request body is required",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  type="object",
 *                  example={"email": "test@test.com","password":"password"}
 *              )
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="Content-Type",
 *          in="header",
 *          required=true,
 *          @OA\Schema(type="string"),
 *          example="application/json",
 *          description="Content Type"
 *      )
 * )
 */


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $isRestRequest = strpos($request->path(), "/api") === 0 || strpos($request->path(), "api") === 0;
        //Validate required fields for user creation
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:250",
            "last_name" => "required|string|max:250",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8",
            "confirm_password" => "required|string|min:8|same:password"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        //Attempt to create the user
        try {
            $user = User::create([
                "name" => $request->name,
                "last_name"=> $request->last_name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            //Handle response to create the user
            if ($isRestRequest) {
                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json(["data" => $user, "access_token" => $token, "token_type" => "Bearer"], 200);
            } else {

                return response()->json(["message" => "User successfully created!"], 200);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }


    public function login(Request $request)
    {
        $isRestRequest = strpos($request->path(), "/api") === 0 || strpos($request->path(), "api") === 0;

        try {
            $credentials = $request->only('email', 'password');
            // Attempt to login
            if (!Auth::attempt($credentials)) {

                return response()->json(["message" => "Email and/or Password are incorrect!"], 401);
            }
            // Get the user
            $user = User::where("email", $request["email"])->firstOrFail();

            // Handle response
            if ($isRestRequest) {

                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json([
                    "message" => "You have successfully generated access token!",
                    "access_token" => $token,
                    "token_type" => "Bearer",
                    "user" => $user
                ]);

            } else {
                $request->session()->regenerate();
                return response()->json(["user" => $user, "message" => "You have successfully logged in!"], 200);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $isRestRequest = strpos($request->path(), "/api") === 0 || strpos($request->path(), "api") === 0;

        try {
            if ($isRestRequest) {
                auth()->user()->tokens()->delete();
            } else {
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            return response()->json(["message" => "You have successfully logged out!"]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }
}
