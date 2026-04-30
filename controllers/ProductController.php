<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use app\services\ProductService;

class ProductController extends BaseController
{
  public function actionIndex($categoryId = null)
  {
    $params = Yii::$app->request->get();

    $productService = new ProductService();
    $productList = $productService->getProductList($categoryId, $params);

    if ($productList['success']) {
      Yii::$app->response->statusCode = 200;
      return $productList;
    }

    Yii::$app->response->statusCode = 404;
    return $productList;
  }

  public function actionView($id)
  {
    $productService = new ProductService();
    $product = $productService->getProductById($id);

    if ($product['success']) {
      Yii::$app->response->statusCode = 200;
      return $product;
    }

    Yii::$app->response->statusCode = 404;
    return $product;
  }

  public function actionCreate($categoryId)
  {
    $data = Yii::$app->request->getBodyParams();
    $productService = new ProductService();
    $result = $productService->createProduct($categoryId, $data);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 422;
    return $result;
  }

  public function actionUpdate($categoryId, $id)
  {
    $data = Yii::$app->request->getBodyParams();
    $productService = new ProductService();
    $result = $productService->updateProduct($categoryId, $id, $data);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 422;
    return $result;
  }

  public function actionDelete($categoryId, $id)
  {
    $productService = new ProductService();
    $result = $productService->deleteProduct($id, $categoryId);

    if ($result['success']) {
      Yii::$app->response->statusCode = 204;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }
}
