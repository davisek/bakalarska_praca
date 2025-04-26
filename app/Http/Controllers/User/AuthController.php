<?php

namespace App\Http\Controllers\User;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Data\VerificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\EmailVerificationRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Resources\EnumResources\MetaDataResource;
use App\Http\Resources\User\UserResource;
use App\Mail\PasswordResetEmail;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication and registration"
 * )
 */
class AuthController extends Controller
{
    protected readonly IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user
     *
     * @OA\Post(
     *     path="/auth/register",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Creates a new user account in the system",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Registration successful"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", description="JWT access token")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function register(RegisterRequest $request)
    {
        $registerData = RegisterData::from($request->validated());

        $data = $this->authService->register($registerData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.registration_successful'),
            'user' => UserResource::make($data['user']),
            'token' => $data['access_token'],
        ]);
    }

    /**
     * Login user
     *
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Authenticates a user and returns a JWT token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", description="JWT access token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Login failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Login failed. Invalid credentials.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function login(LoginRequest $request)
    {
        $loginData = LoginData::from($request->validated());
        $token = $this->authService->login($loginData);

        if ($token) {
            return response()->json([
                'type' => 'success',
                'message' => trans('messages.login_successful'),
                'user' => UserResource::make(Auth::user()),
                'token' => $token['access_token'],
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => trans('errors.login_failed'),
        ]);
    }

    /**
     * Logout user
     *
     * @OA\Post(
     *     path="/auth/logout",
     *     operationId="logoutUser",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Logs out a user by invalidating their JWT token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Logout successful")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.logout_successful'),
        ]);
    }

    /**
     * Resend verification code
     *
     * @OA\Post(
     *     path="/auth/resend-code",
     *     operationId="resendVerificationCode",
     *     tags={"Authentication"},
     *     summary="Resend verification code",
     *     description="Resends the email verification code to the user's email",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Verification code resent",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Verification code resent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Already verified",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Email already verified")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function resendVerificationCode()
    {
        if (!is_null(Auth::user()->email_verified_at)) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.already_verified'),
            ]);
        }

        $this->authService->resendVerificationCode();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.verification_code_resent'),
        ]);
    }

    /**
     * Verify email
     *
     * @OA\Post(
     *     path="/auth/verify-email",
     *     operationId="verifyEmail",
     *     tags={"Authentication"},
     *     summary="Verify email",
     *     description="Verifies user's email using the provided verification code",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EmailVerificationRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verification response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Your email has been successfully verified.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(
     *         response=422,
     *         description="Verification failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The code is invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="verification_code",
     *                     type="array",
     *                     @OA\Items(type="string", example="The code is invalid.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $verificationData = VerificationData::from($request->validated());

        $response = $this->authService->verifyEmail($verificationData);

        return response()->json([
            'type' => $response['type'],
            'message' => trans($response['message_code']),
        ]);
    }

    /**
     * Refresh token
     *
     * @OA\Post(
     *     path="/auth/refresh",
     *     operationId="refreshToken",
     *     tags={"Authentication"},
     *     summary="Refresh JWT token",
     *     description="Refreshes the JWT token for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful token refresh",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", description="New JWT access token")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function refresh()
    {
        $token = $this->authService->refresh();

        if ($token) {
            return response()->json([
                'type' => 'success',
                'message' => trans('messages.login_successful'),
                'user' => UserResource::make(Auth::user()),
                'token' => $token['access_token'],
            ]);
        }

        return null;
    }

    /**
     * Request password reset
     *
     * @OA\Post(
     *     path="/auth/forgot-password",
     *     operationId="requestPasswordReset",
     *     tags={"Authentication"},
     *     summary="Request password reset",
     *     description="Sends a password reset code to the user's email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ForgotPasswordRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset email sent",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password reset email has been sent.")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        $result = $this->authService->getUserByLogin($request->validated()['email']);

        if ($result instanceof Response || $result instanceof JsonResponse) {
            return $result;
        }

        $user = $result;
        $this->sentResetCode($user);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_reset_email_sent'),
        ]);
    }

    /**
     * Resend password reset code
     *
     * @OA\Post(
     *     path="/auth/forgot-password/resend",
     *     operationId="resendPasswordResetCode",
     *     tags={"Authentication"},
     *     summary="Resend password reset code",
     *     description="Resends the password reset code to the user's email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ForgotPasswordRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset email sent",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Password reset email sent")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function resend(ForgotPasswordRequest $request)
    {
        $result = $this->authService->getUserByLogin($request->validated()['email']);

        if ($result instanceof Response || $result instanceof JsonResponse) {
            return $result;
        }

        $user = $result;
        $this->sentResetCode($user);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_reset_email_sent'),
        ]);
    }

    /**
     * Reset password
     *
     * @OA\Post(
     *     path="/auth/forgot-password/reset",
     *     operationId="resetPassword",
     *     tags={"Authentication"},
     *     summary="Reset password",
     *     description="Resets the user's password using the verification code",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResetPasswordRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Reset successful"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", description="JWT access token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Verification failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Verification failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="code",
     *                     type="array",
     *                     @OA\Items(type="string", example="Verification failed")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $user = $this->authService->getUserByLogin($data['email']);

        if (!$user) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.verification_failed'),
                'errors' => [
                    'code' => [trans('errors.verification_failed')]
                ]
            ], 422);
        }

        $verifyResult = $this->authService->verify($data, $user);
        if ($verifyResult) {
            return $verifyResult;
        }

        Auth::login($user);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.reset_successful'),
            'user' => UserResource::make($user),
            'token' => $token,
        ]);
    }

    /**
     * Get localization metadata
     *
     * @OA\Get(
     *     path="/auth/locale",
     *     operationId="getLocalizationMetadata",
     *     tags={"Authentication"},
     *     summary="Get localization metadata",
     *     description="Returns available locales for the application",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="locales",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EnumResource")
     *             )
     *         )
     *     )
     * )
     */
    public function metaData()
    {
        return new MetaDataResource(null);
    }

    private function sentResetCode(User $user)
    {
        $code = rand(10000, 99999);

        Cache::put(
            'forgot_password_code_user' . $user->id,
            $code,
            now()->addMinutes(5)
        );

        Mail::to($user->email)->send(new PasswordResetEmail($code, $user->name . ' ' . $user->surname));
    }
}