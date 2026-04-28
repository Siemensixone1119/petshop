<?php

namespace app\helpers;

class ApiCode
{
    // Общие ошибки
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const REQUIRED_FIELDS_MISSING = 'REQUIRED_FIELDS_MISSING';
    public const NOT_FOUND = 'NOT_FOUND';
    public const UNAUTHORIZED = 'UNAUTHORIZED';
    public const FORBIDDEN = 'FORBIDDEN';
    public const SERVER_ERROR = 'SERVER_ERROR';

    // Авторизация
    public const INVALID_TOKEN = 'INVALID_TOKEN';
    public const TOKEN_NOT_PROVIDED = 'TOKEN_NOT_PROVIDED';
    public const INVALID_PASSWORD = 'INVALID_PASSWORD';
    public const TOKEN_SAVE_ERROR = 'TOKEN_SAVE_ERROR';
    public const LOGOUT_ERROR = 'LOGOUT_ERROR';

    // Пользователи
    public const USER_NOT_FOUND = 'USER_NOT_FOUND';
    public const USER_ALREADY_EXISTS = 'USER_ALREADY_EXISTS';

    // Категории
    public const CATEGORY_NOT_FOUND = 'CATEGORY_NOT_FOUND';
    public const CATEGORY_DELETE_ERROR = 'CATEGORY_DELETE_ERROR';

    // Товары
    public const PRODUCT_NOT_FOUND = 'PRODUCT_NOT_FOUND';
    public const PRODUCT_DELETE_ERROR = 'PRODUCT_DELETE_ERROR';
    // Корзина
    public const CART_NOT_FOUND = 'CART_NOT_FOUND';
    public const CART_CREATE_ERROR = 'CART_CREATE_ERROR';
    public const CART_ITEM_NOT_FOUND = 'CART_ITEM_NOT_FOUND';
    public const CART_ITEM_ADD_ERROR = 'CART_ITEM_ADD_ERROR';
    public const CART_ITEM_UPDATE_ERROR = 'CART_ITEM_UPDATE_ERROR';
    public const CART_ITEM_DELETE_ERROR = 'CART_ITEM_DELETE_ERROR';
    public const INVALID_QUANTITY = 'INVALID_QUANTITY';
    public const CART_EMPTY = 'CART_EMPTY';

    // Заказы
    public const ORDER_NOT_FOUND = 'ORDER_NOT_FOUND';
    public const ORDER_DELETE_ERROR = 'ORDER_DELETE_ERROR';
    public const ORDER_UPDATE_ERROR = 'ORDER_UPDATE_ERROR';
    public const ORDER_ITEM_CREATE_ERROR = 'ORDER_ITEM_CREATE_ERROR';
}
