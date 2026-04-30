<?php

namespace app\services;

use app\models\Product;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class ProductService
{
    public function getProductList($categoryId = null, array $params = []): array
    {
        $query = Product::find();

        if ($categoryId !== null) {
            $query->andWhere(['category_id' => $categoryId]);
        }

        $search = trim($params['search'] ?? '');

        if ($search !== '') {
            $query->andFilterWhere([
                'or',
                ['ilike', 'name', $search],
                ['ilike', 'description', $search],
            ]);
        }

        $sort = $params['sort'] ?? 'default';

        switch ($sort) {
            case 'price-asc':
                $query->orderBy(['price' => SORT_ASC]);
                break;

            case 'price-desc':
                $query->orderBy(['price' => SORT_DESC]);
                break;

            case 'name-asc':
                $query->orderBy(['name' => SORT_ASC]);
                break;

            case 'name-desc':
                $query->orderBy(['name' => SORT_DESC]);
                break;

            default:
                $query->orderBy(['id' => SORT_DESC]);
                break;
        }

        $page = (int)($params['page'] ?? 1);
        $perPage = (int)($params['per-page'] ?? 8);

        if ($page < 1) {
            $page = 1;
        }

        if ($perPage < 1) {
            $perPage = 8;
        }

        if ($perPage > 50) {
            $perPage = 50;
        }

        $total = (clone $query)->count();

        $products = $query
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->all();

        return ApiResponse::success([
            'items' => $products,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => (int)$total,
                'total_pages' => (int)ceil($total / $perPage),
            ],
        ]);
    }

    public function getProductById($id): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $product = Product::findOne($id);

        if (!$product) {
            return ApiResponse::error(
                ApiCode::PRODUCT_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::PRODUCT_NOT_FOUND]
                ]
            );
        }

        return ApiResponse::success($product);
    }

    public function createProduct($categoryId, $data): array
    {
        $missingFields = [];

        if (!$data) {
            $missingFields[] = 'data';
        }

        if (!$categoryId) {
            $missingFields[] = 'category_id';
        }

        if (!empty($missingFields)) {
            return ApiResponse::missingFields($missingFields);
        }

        $product = new Product();

        $product->name = isset($data['name']) ? trim($data['name']) : null;
        $product->description = isset($data['description']) ? trim($data['description']) : null;
        $product->price = isset($data['price']) ? trim($data['price']) : null;
        $product->stock = isset($data['stock']) ? trim($data['stock']) : null;
        $product->image = isset($data['image']) ? trim($data['image']) : null;
        $product->category_id = $categoryId;

        if (!$product->save()) {
            return ApiResponse::validation($product);
        }

        return ApiResponse::success($product);
    }

    public function updateProduct($categoryId, $id, $data): array
    {
        $missingFields = [];

        if (!$data) {
            $missingFields[] = 'data';
        }

        if (!$id) {
            $missingFields[] = 'id';
        }

        if (!$categoryId) {
            $missingFields[] = 'category_id';
        }

        if (!empty($missingFields)) {
            return ApiResponse::missingFields($missingFields);
        }

        $product = Product::findOne([
            'id' => $id,
            'category_id' => $categoryId
        ]);

        if (!$product) {
            return ApiResponse::error(
                ApiCode::PRODUCT_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::PRODUCT_NOT_FOUND]
                ]
            );
        }

        $name = isset($data['name']) ? trim($data['name']) : null;
        $description = isset($data['description']) ? trim($data['description']) : null;
        $price = isset($data['price']) ? trim($data['price']) : null;
        $stock = isset($data['stock']) ? trim($data['stock']) : null;
        $image = isset($data['image']) ? trim($data['image']) : null;

        if ($name !== null) {
            $product->name = $name;
        }

        if ($description !== null) {
            $product->description = $description;
        }

        if ($price !== null) {
            $product->price = $price;
        }

        if ($stock !== null) {
            $product->stock = $stock;
        }

        if ($image !== null) {
            $product->image = $image;
        }

        if ($categoryId !== null) {
            $product->category_id = $categoryId;
        }

        if (!$product->save()) {
            return ApiResponse::validation($product);
        }

        return ApiResponse::success($product);
    }

    public function deleteProduct($id, $categoryId): array
    {
        $missingFields = [];

        if (!$id) {
            $missingFields[] = 'id';
        }

        if (!$categoryId) {
            $missingFields[] = 'category_id';
        }

        if (!empty($missingFields)) {
            return ApiResponse::missingFields($missingFields);
        }

        $product = Product::findOne([
            'id' => $id,
            'category_id' => $categoryId
        ]);

        if (!$product) {
            return ApiResponse::error(
                ApiCode::PRODUCT_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::PRODUCT_NOT_FOUND]
                ]
            );
        }

        if (!$product->delete()) {
            return ApiResponse::serverError(
                ApiCode::PRODUCT_DELETE_ERROR,
                $product->getErrors()
            );
        }

        return ApiResponse::success($product);
    }
}
