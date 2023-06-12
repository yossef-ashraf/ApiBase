<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

    use ApiResponseTrait;

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function register(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;
        return $this->apiResponse(200, 'Successfully', null,$token);
        // return response()->json(['token' => $token], 201);
    }

    /**
     * Log in the user and return a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // if (Auth::guard('api')->attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

        // if (Auth::attempt($credentials)) {
             $token = $user->createToken('API Token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        // } else {
        //     return response()->json(['message' => 'Invalid credentials'], 401);
        // }
    }

    /**
     * Log out the user (Revoke the token).
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Get the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

     /**
     * Send password reset link to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email'], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function resetPassword(Request $request)
    {
        dd(Auth::user());
        $validations = Validator::make($request->all(), [
            'password' => ['required', 'min:8'],
            'new_password' => ['required', 'min:8'],
            'confirm_password' => "required|same:new_password",
        ]);

        if ($validations->fails()) {
            return $this->apiResponse(400, 'validation error', $validations->errors());
        }

        if (User::where(['email' => $request->user()->email])->first() && Hash::check($request->password, $request->user()->password)) {
            // if ($credentials) {
            # code...
            $user = User::where('id', auth()->user()->id)->first();
            $user->update(['password' => Hash::make($request->confirm_password)]);
            return $this->apiResponse(1, 'Successfully', ['user' => $user]);
        }
        return $this->apiResponse(200, 'Successfully');
    }

        /**
     * Refresh the user's access token.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required',
        ]);

        $token = $request->user()->refreshToken();

        return response()->json(['token' => $token], 200);
    }

}
