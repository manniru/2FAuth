<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception, $request) {
            return response()->json([
                'message' => 'not found'], 404);
        });

        $this->renderable(function (InvalidOtpParameterException $exception, $request) {
            return response()->json([
                'message' => 'invalid OTP parameters',
                'reason' => [$exception->getMessage()]
            ], 400);
        });

        $this->renderable(function (InvalidQrCodeException $exception, $request) {
            return response()->json([
                'message' => 'not a valid QR code'], 400);
        });

        $this->renderable(function (InvalidSecretException $exception, $request) {
            return response()->json([
                'message' => 'not a valid base32 encoded secret'], 400);
        });

        $this->renderable(function (DbEncryptionException $exception, $request) {
            return response()->json([
                'message' => $exception->getMessage()], 400);
        });

        $this->renderable(function (InvalidGoogleAuthMigration $exception, $request) {
            return response()->json([
                'message' => __('errors.invalid_google_auth_migration')], 400);
        });

        $this->renderable(function (UndecipherableException $exception, $request) {
            return response()->json([
                'message' => __('errors.cannot_decipher_secret')], 400);
        });

        $this->renderable(function (UnsupportedOtpTypeException $exception, $request) {
            return response()->json([
                'message' => __('errors.unsupported_otp_type')], 400);
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $exception, $request) {
            if ($exception->guards() === ['reverse-proxy-guard']) {
                return response()->json([
                    'message' => $exception->getMessage()], 407);
            }
            else {
                return response()->json([
                    'message' => $exception->getMessage()], 401);
            }
        });
    }
}