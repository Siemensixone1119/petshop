<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\services\AuthService;

class BaseController extends Controller
{
    public $enableCsrfValidation = false;
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $publicRoutes = [
            'auth/login',
            'auth/register',

            'categorie/index',
            'categorie/view',

            'product/index',
            'product/view',
        ];

        $currentRoute = Yii::$app->controller->id . '/' . $action->id;

        if (in_array($currentRoute, $publicRoutes, true)) {
            return parent::beforeAction($action);
        }

        $currentToken = Yii::$app->request->cookies->getValue('auth_token');

        $result = (new AuthService())->validateToken($currentToken);

        if (!$result['success']) {
            Yii::$app->response->statusCode = 401;
            Yii::$app->response->data = $result;
            return false;
        }

        return true;
    }

    protected function getCurrentUserId()
    {
        $token = Yii::$app->request->cookies->getValue('auth_token');

        $result = (new AuthService())->validateToken($token);

        if (!$result['success']) {
            return null;
        }

        return $result['data']->id;
    }
}
