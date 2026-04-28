<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use app\services\CategorieService;

class CategorieController extends BaseController
{
    public function actionIndex()
    {
        Yii::$app->response->statusCode = 200;
        return (new CategorieService())->getCategorieList();
    }

    public function actionView($id)
    {
        $categorie = (new CategorieService())->getCategorieById($id);

        if ($categorie['success']) {
            Yii::$app->response->statusCode = 200;
            return $categorie;
        }

        Yii::$app->response->statusCode = 404;
        return $categorie;
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->getBodyParams();
        $categorieService = new CategorieService();
        $result = $categorieService->createCategorie($data);

        if ($result['success']) {
            Yii::$app->response->statusCode = 201;
            return $result;
        }

        Yii::$app->response->statusCode = 422;
        return $result;
    }

    public function actionUpdate($id)
    {
        $data = Yii::$app->request->getBodyParams();

        $categorieService = new CategorieService();
        $result = $categorieService->updateCategorie($id, $data);

        if ($result['success']) {
            Yii::$app->response->statusCode = 200;
            return $result;
        }

        Yii::$app->response->statusCode = 404;
        return $result;
    }

    public function actionDelete($id)
    {
        $categorieService = new CategorieService();
        $result = $categorieService->deleteCategorie($id);

        if ($result['success']) {
            Yii::$app->response->statusCode = 200;
            return $result;
        }
        Yii::$app->response->statusCode = 404;
        return $result;
    }
}
