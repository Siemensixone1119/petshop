<?php

namespace app\services;

use app\models\User;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class AuthService
{
    public function validateToken($currentToken): array
    {
        if (!$currentToken) {
            return ApiResponse::unauthorized(
                ApiCode::TOKEN_NOT_PROVIDED,
                [
                    'token' => ['FIELD_REQUIRED']
                ]
            );
        }

        $user = User::findOne(['auth_token' => $currentToken]);

        if (!$user) {
            return ApiResponse::unauthorized(
                ApiCode::INVALID_TOKEN,
                [
                    'token' => [ApiCode::INVALID_TOKEN]
                ]
            );
        }

        return ApiResponse::success([
            'id' => $user->id,
            'login' => $user->login,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function register($username, $email, $password): array
    {
        $missingResponse = ApiResponse::missingRequired([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        if ($missingResponse !== null) {
            return $missingResponse;
        }

        $user = new User();
        $user->login = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->role = 'user';
        $user->generateAuthToken();

        if (!$user->save()) {
            return ApiResponse::validation($user);
        }

        return ApiResponse::created(
            [
                'login' => $user->login,
                'email' => $user->email,
            ],
            [
                'auth_token' => $user->auth_token,
            ]
        );
    }

    public function login($email, $password): array
    {
        $missingResponse = ApiResponse::missingRequired([
            'email' => $email,
            'password' => $password,
        ]);

        if ($missingResponse !== null) {
            return $missingResponse;
        }

        $user = User::findOne(['email' => $email]);

        if (!$user) {
            return ApiResponse::error(
                ApiCode::USER_NOT_FOUND,
                404,
                [
                    'email' => [ApiCode::USER_NOT_FOUND]
                ]
            );
        }

        if (!$user->validatePassword($password)) {
            return ApiResponse::error(
                ApiCode::INVALID_PASSWORD,
                401,
                [
                    'password' => [ApiCode::INVALID_PASSWORD]
                ]
            );
        }

        $user->generateAuthToken();

        if (!$user->save(false, ['auth_token'])) {
            return ApiResponse::serverError(
                ApiCode::TOKEN_SAVE_ERROR,
                $user->getErrors()
            );
        }

        return ApiResponse::success(
            [
                'login' => $user->login,
                'email' => $user->email,
            ],
            200,
            [
                'auth_token' => $user->auth_token,
            ]
        );
    }

    public function logout($token): array
    {
        if (!$token) {
            return ApiResponse::unauthorized(
                ApiCode::TOKEN_NOT_PROVIDED,
                [
                    'token' => ['FIELD_REQUIRED']
                ]
            );
        }

        $user = User::findOne(['auth_token' => $token]);

        if (!$user) {
            return ApiResponse::unauthorized(
                ApiCode::INVALID_TOKEN,
                [
                    'token' => [ApiCode::INVALID_TOKEN]
                ]
            );
        }

        $user->auth_token = null;

        if (!$user->save(false, ['auth_token'])) {
            return ApiResponse::serverError(
                ApiCode::LOGOUT_ERROR,
                $user->getErrors()
            );
        }

        return ApiResponse::success();
    }
}
