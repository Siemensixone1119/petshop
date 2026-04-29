<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use app\services\CategoryService;

class CategorieController extends BaseController
{
    public function actionIndex()
    {
        Yii::$app->response->statusCode = 200;
        return (new CategoryService())->getCategoryList();
    }

    public function actionView($id)
    {
        $categorie = (new CategoryService())->getCategoryById($id);

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
        $categoryService = new CategoryService();
        $result = $categoryService->createCategory($data);

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

        $categoryService = new CategoryService();
        $result = $categoryService->updateCategory($id, $data);

        if ($result['success']) {
            Yii::$app->response->statusCode = 200;
            return $result;
        }

        Yii::$app->response->statusCode = 404;
        return $result;
    }

    public function actionDelete($id)
    {
        $categoryService = new CategoryService();
        $result = $categoryService->deleteCategory($id);

        if ($result['success']) {
            Yii::$app->response->statusCode = 200;
            return $result;
        }
        Yii::$app->response->statusCode = 404;
        return $result;
    }
}
