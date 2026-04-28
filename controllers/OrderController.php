<?php

namespace app\controllers;

use Yii;
use app\services\OrderService;
use app\controllers\BaseController;

class OrderController extends BaseController
{

  public function actionIndex()
  {
    $userId = $this->getCurrentUserId();
    $orderService = new OrderService();
    $orders = $orderService->getUserOrders($userId);

    if ($orders['success']) {
      Yii::$app->response->statusCode = 200;
      return $orders;
    }

    Yii::$app->response->statusCode = 404;
    return $orders;
  }

  public function actionView($id)
  {
    $orderService = new OrderService();
    $order = $orderService->getOrderById($id);

    if ($order['success']) {
      Yii::$app->response->statusCode = 200;
      return $order;
    }

    Yii::$app->response->statusCode = 404;
    return $order;
  }

  public function actionCreate()
  {
    $userId = $this->getCurrentUserId();
    $orderService = new OrderService();
    $order = $orderService->createOrder($userId);

    if ($order['success']) {
      Yii::$app->response->statusCode = 200;
      return $order;
    }

    Yii::$app->response->statusCode = 404;
    return $order;
  }

  public function actionCancel($id)
  {
    $userId = $this->getCurrentUserId();
    $orderSercice = new OrderService();
    $result = $orderSercice->removeOrder($userId, $id);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }

  public function actionUpdateStatus($id)
  {
    $userId = $this->getCurrentUserId();
    $status = Yii::$app->request->getBodyParam('status');
    $orderService = new OrderService();
    $result = $orderService->updateOrderStatus($userId, $status, $id);

    if ($result['success']) {
      Yii::$app->response->statusCode = 200;
      return $result;
    }

    Yii::$app->response->statusCode = 500;
    return $result;
  }
}
