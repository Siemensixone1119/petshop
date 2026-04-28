<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use app\models\User;
use app\services\AuthService;

class AuthController extends BaseController
{
    public function actionRegister()
    {
        $authService = new AuthService();
        $data = Yii::$app->request->getBodyParams();


        $login = $data["login"] ?? null;
        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;

        $result = $authService->register($login, $email, $password);

        if ($result['success']) {
            Yii::$app->response->statusCode = 201;
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'auth_token',
                'value' =>  $result['auth_token'],
                'httpOnly' => true,
                'secure' => false,
                'sameSite' => \yii\web\Cookie::SAME_SITE_LAX,
                'path' => '/',
            ]));
            unset($result['auth_token']);
            return $result;
        }

        Yii::$app->response->statusCode = 401;
        return $result;
    }

    public function actionLogin()
    {
        $authService = new AuthService();
        $data = Yii::$app->request->getBodyParams();

        $email = $data["email"] ?? null;
        $password = $data["password"] ?? null;

        $result = $authService->login($email, $password);

        if ($result["success"]) {
            Yii::$app->response->statusCode = 200;
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'auth_token',
                'value' =>  $result['auth_token'],
                'httpOnly' => true,
                'secure' => false,
                'sameSite' => \yii\web\Cookie::SAME_SITE_LAX,
                'path' => '/',
            ]));
            unset($result['auth_token']);
            return $result;
        }
        Yii::$app->response->statusCode = 401;
        return $result;
    }

    public function actionLogout()
    {
        $authService = new AuthService();
        $currentToken = Yii::$app->request->cookies->getValue('auth_token');
        $result = $authService->logout($currentToken);

        Yii::$app->response->cookies->remove('auth_token');

        if ($result['success']) {
            Yii::$app->response->statusCode = 200;
            return [
                'success' => true,
                'message' => 'Пользователь успешно вышел'
            ];
        }

        Yii::$app->response->statusCode = 401;
        return $result;
    }
}
