<?php

namespace app\services;

use app\models\Order;
use app\models\Cart;
use app\models\CartItem;
use app\models\OrderItem;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class OrderService
{
  public function getUserOrders($userId): array
  {
    if (!$userId) {
      return ApiResponse::missingFields(['user_id']);
    }

    $orders = Order::findAll(['user_id' => $userId]);

    return ApiResponse::success($orders);
  }

  public function getOrderById($orderId): array
  {
    if (!$orderId) {
      return ApiResponse::missingFields(['order_id']);
    }

    $order = Order::findOne(['id' => $orderId]);

    if (!$order) {
      return ApiResponse::error(
        ApiCode::ORDER_NOT_FOUND,
        404,
        [
          'id' => [ApiCode::ORDER_NOT_FOUND]
        ]
      );
    }

    return ApiResponse::success($order);
  }

  public function createOrder($userId): array
  {
    if (!$userId) {
      return ApiResponse::missingFields(['user_id']);
    }

    $cart = Cart::findOne(['user_id' => $userId]);

    if (!$cart) {
      return ApiResponse::error(
        ApiCode::CART_NOT_FOUND,
        404,
        [
          'cart' => [ApiCode::CART_NOT_FOUND]
        ]
      );
    }

    $cartItems = CartItem::find()
      ->where(['cart_id' => $cart->id])
      ->with('product')
      ->all();

    if (!$cartItems) {
      return ApiResponse::error(
        ApiCode::CART_EMPTY,
        404,
        [
          'cart' => [ApiCode::CART_EMPTY]
        ]
      );
    }

    $total_amount = 0;

    foreach ($cartItems as $item) {
      if (!$item->product) {
        return ApiResponse::error(
          ApiCode::PRODUCT_NOT_FOUND,
          404,
          [
            'product' => [ApiCode::PRODUCT_NOT_FOUND]
          ]
        );
      }

      $total_amount += $item->product->price * $item->quantity;
    }

    $order = new Order();

    $order->user_id = $userId;
    $order->total_amount = $total_amount;

    if (!$order->save()) {
      return ApiResponse::validation($order);
    }

    foreach ($cartItems as $item) {
      $orderItem = new OrderItem();

      $orderItem->order_id = $order->id;
      $orderItem->product_id = $item->product_id;
      $orderItem->quantity = $item->quantity;
      $orderItem->price = $item->product->price;

      if (!$orderItem->save()) {
        return ApiResponse::error(
          ApiCode::ORDER_ITEM_CREATE_ERROR,
          422,
          $orderItem->getErrors()
        );
      }
    }

    CartItem::deleteAll(['cart_id' => $cart->id]);

    return ApiResponse::success($order);
  }

  public function removeOrder($userId, $id): array
  {
    $missingFields = [];

    if (!$userId) {
      $missingFields[] = 'user_id';
    }

    if (!$id) {
      $missingFields[] = 'id';
    }

    if (!empty($missingFields)) {
      return ApiResponse::missingFields($missingFields);
    }

    $order = Order::findOne(['user_id' => $userId]);

    if (!$order) {
      return ApiResponse::error(
        ApiCode::ORDER_NOT_FOUND,
        404,
        [
          'id' => [ApiCode::ORDER_NOT_FOUND]
        ]
      );
    }

    if (!$order->delete()) {
      return ApiResponse::serverError(
        ApiCode::ORDER_DELETE_ERROR,
        $order->getErrors()
      );
    }

    return ApiResponse::success($order);
  }

  public function updateOrderStatus($userId, $status, $id): array
  {
    $missingFields = [];

    if (!$userId) {
      $missingFields[] = 'user_id';
    }

    if (!$status) {
      $missingFields[] = 'status';
    }

    if (!$id) {
      $missingFields[] = 'id';
    }

    if (!empty($missingFields)) {
      return ApiResponse::missingFields($missingFields);
    }

    $order = Order::findOne(['id' => $userId]);

    if (!$order) {
      return ApiResponse::error(
        ApiCode::ORDER_NOT_FOUND,
        404,
        [
          'id' => [ApiCode::ORDER_NOT_FOUND]
        ]
      );
    }

    $order->status = $status;

    if (!$order->save()) {
      return ApiResponse::validation($order);
    }

    return ApiResponse::success($order);
  }
}
