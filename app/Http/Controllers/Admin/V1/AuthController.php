<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin auth operations"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/admin/v1/register",
     *     summary="Register",
     *     description="Admin Register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Register",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Example"),
     *             @OA\Property(property="email", type="string", format="email", example="example@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password", minLength=8),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت نام موفقیت‌آمیز",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="1|abcdef123456..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="message", type="string", example="حساب کاربری با موفقیت ایجاد شد."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email has already been taken."}}),
     *         ),
     *     ),
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'حساب کاربری با موفقیت ایجاد شد.'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/login",
     *     summary="Admin Login",
     *     description="Admin Login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User information for login",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="example@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ورود موفقیت‌آمیز",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="1|abcdef123456..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="message", type="string", example="با موفقیت به حساب کاربری خود وارد شدید."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="خطای احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ایمیل یا رمز وارد شده اشتباه است."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}}),
     *         ),
     *     ),
     * )
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'ایمیل یا رمز وارد شده اشتباه است.'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'حساب کاربری شما فعال نیست و اجازه ورود ندارید.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'با موفقیت به حساب کاربری خود وارد شدید.'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/logout",
     *     summary="Admin Logout",
     *     description="Admin can logout from here",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="خروج موفقیت‌آمیز",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="با موفقیت از حساب خود خارج شدید."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         ),
     *     ),
     * )
     **/
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'با موفقیت از حساب خود خارج شدید.'
        ]);
    }
}
