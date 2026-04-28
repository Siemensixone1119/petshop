<?php

namespace app\controllers;

use Yii;
use app\services\CartService;
use app\controllers\BaseController;

class CartController extends BaseController
{
  public function actionIndex()
  {
    $userId = $this->getCurrentUserId();
    $cartService = new CartService();
    $cart = $cartService->viewCart($userId);

    if ($cart['success']) {
      Yii::$app->response->statusCode = 200;
      return $cart;
    }

    Yii::$app->response->statusCode = 404;
    return $cart;
  }

  // Добавить товар в корзину
  public function actionAdd($productId)
  {
    $userId = $this->getCurrentUserId();
    $cartService = new CartService();
    $result = $cartService->addCartItem($productId, $userId);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }

  public function actionUpdate($itemId)
  {
    $userId = $this->getCurrentUserId();
    $quantity = Yii::$app->request->getBodyParam('quantity');
    $cartService = new CartService();
    $result = $cartService->updateItemCart($itemId, $quantity, $userId);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }

  public function actionDelete($itemId)
  {
    $userId = $this->getCurrentUserId();
    $cartService = new CartService();
    $result = $cartService->removeCartItem($itemId, $userId);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }

  public function actionClear()
  {
    $userId = $this->getCurrentUserId();
    $cartService = new CartService();
    $result = $cartService->removeAllCartItem($userId);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }
}
