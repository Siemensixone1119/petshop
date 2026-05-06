<?php

namespace app\services;

use app\models\Cart;
use app\models\Product;
use app\models\CartItem;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class CartService
{
  private function serializeCart(Cart $cart): array
  {
    $items = CartItem::find()
      ->where(['cart_id' => $cart->id])
      ->with('product')
      ->orderBy(['id' => SORT_ASC])
      ->all();

    $totalCount = 0;
    $totalAmount = 0;
    $serializedItems = [];

    foreach ($items as $item) {
      $product = $item->product;
      $price = $product ? (float)$product->price : 0;
      $quantity = (int)$item->quantity;
      $totalCount += $quantity;
      $totalAmount += $price * $quantity;

      $serializedItems[] = [
        'id' => $item->id,
        'cart_id' => $item->cart_id,
        'product_id' => $item->product_id,
        'quantity' => $quantity,
        'product' => $product ? [
          'id' => $product->id,
          'name' => $product->name,
          'description' => $product->description,
          'price' => (float)$product->price,
          'image' => $product->image,
          'category_id' => $product->category_id,
        ] : null,
      ];
    }

    return [
      'id' => $cart->id,
      'user_id' => $cart->user_id,
      'items' => $serializedItems,
      'total_count' => $totalCount,
      'total_amount' => $totalAmount,
    ];
  }

  public function viewCart($userId): array
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

    return ApiResponse::success($this->serializeCart($cart));
  }

  public function addCartItem($productId, $userId): array
  {
    $missingFields = [];

    if (!$userId) {
      $missingFields[] = 'user_id';
    }

    if (!$productId) {
      $missingFields[] = 'product_id';
    }

    if (!empty($missingFields)) {
      return ApiResponse::missingFields($missingFields);
    }

    $cart = Cart::findOne(['user_id' => $userId]);

    if (!$cart) {
      $cart = new Cart();
      $cart->user_id = $userId;

      if (!$cart->save()) {
        return ApiResponse::serverError(
          ApiCode::CART_CREATE_ERROR,
          $cart->getErrors()
        );
      }
    }

    $product = Product::findOne(['id' => $productId]);

    if (!$product) {
      return ApiResponse::error(
        ApiCode::PRODUCT_NOT_FOUND,
        404,
        [
          'product_id' => [ApiCode::PRODUCT_NOT_FOUND]
        ]
      );
    }

    $cartItem = CartItem::findOne([
      'cart_id' => $cart->id,
      'product_id' => $product->id,
    ]);

    if (!$cartItem) {
      $cartItem = new CartItem();

      $cartItem->cart_id = $cart->id;
      $cartItem->product_id = $product->id;
    } else {
      $cartItem->quantity += 1;
    }

    if (!$cartItem->save()) {
      return ApiResponse::validation($cartItem);
    }

    return ApiResponse::success($this->serializeCart($cart));
  }

  public function updateItemCart($itemId, $quantity, $userId): array
  {
    $missingFields = [];

    if (!$itemId) {
      $missingFields[] = 'item_id';
    }

    if (!$userId) {
      $missingFields[] = 'user_id';
    }

    if (!empty($missingFields)) {
      return ApiResponse::missingFields($missingFields);
    }

    if ($quantity === null || $quantity < 1) {
      return ApiResponse::error(
        ApiCode::INVALID_QUANTITY,
        422,
        [
          'quantity' => [ApiCode::INVALID_QUANTITY]
        ]
      );
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

    $cartItem = CartItem::findOne([
      'id' => $itemId,
      'cart_id' => $cart->id
    ]);

    if (!$cartItem) {
      return ApiResponse::error(
        ApiCode::CART_ITEM_NOT_FOUND,
        404,
        [
          'item_id' => [ApiCode::CART_ITEM_NOT_FOUND]
        ]
      );
    }

    $cartItem->quantity = $quantity;

    if (!$cartItem->save()) {
      return ApiResponse::validation($cartItem);
    }

    return ApiResponse::success($this->serializeCart($cart));
  }

  public function removeCartItem($itemId, $userId): array
  {
    if (!$itemId) {
      return ApiResponse::missingFields(['item_id']);
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

    $cartItem = CartItem::findOne([
      'id' => $itemId,
      'cart_id' => $cart->id
    ]);

    if (!$cartItem) {
      return ApiResponse::error(
        ApiCode::CART_ITEM_NOT_FOUND,
        404,
        [
          'item_id' => [ApiCode::CART_ITEM_NOT_FOUND]
        ]
      );
    }

    if (!$cartItem->delete()) {
      return ApiResponse::serverError(
        ApiCode::CART_ITEM_DELETE_ERROR,
        $cartItem->getErrors()
      );
    }

    return ApiResponse::success($this->serializeCart($cart));
  }

  public function removeAllCartItem($userId): array
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

    CartItem::deleteAll(['cart_id' => $cart->id]);

    return ApiResponse::success($this->serializeCart($cart));
  }
}
