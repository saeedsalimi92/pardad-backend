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
     *     path="/v1/admin/register",
     *     summary="register",
     *     description="register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات مورد نیاز برای ثبت نام",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", minLength=8),
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
     *     path="/v1/admin/login",
     *     summary="ورود کاربر",
     *     description="با استفاده از این endpoint کاربر می‌تواند با ایمیل و رمز عبور وارد حساب خود شود و یک توکن احراز هویت دریافت کند.",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات مورد نیاز برای ورود",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'با موفقیت به حساب کاربری خود وارد شدید.'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/v1/admin/logout",
     *     summary="خروج کاربر",
     *     description="با استفاده از این endpoint کاربر می‌تواند از حساب خود خارج شود و توکن‌های احراز هویت او حذف می‌شوند.",
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
