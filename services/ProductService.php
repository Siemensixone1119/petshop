<?php

namespace app\services;

use app\models\Product;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class ProductService
{
    public function getProductList($categoryId = null, $search, $sort): array
    {
        $search = trim((string)$search);
        $sort = trim((string)$sort);
        $query = Product::find();

        if ($categoryId !== null) {
            $query->andWhere(['category_id' => $categoryId]);
        }

        if ($search !== '') {
            $query->andWhere([
                'or',
                ['ilike', 'name', $search],
                ['ilike', 'description', $search]
            ]);
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy(['price' => SORT_ASC]);
                break;
            case 'price_desc':
                $query->orderBy(['price' => SORT_DESC]);
                break;
            case 'date_asc':
                $query->orderBy(['created_at' => SORT_ASC]);
                break;
            case 'date_desc':
                $query->orderBy(['created_at' => SORT_DESC]);
                break;
            case 'name_asc':
                $query->orderBy(['name' => SORT_ASC]);
                break;
            case 'name_desc':
                $query->orderBy(['name' => SORT_DESC]);
                break;
            default:
                $query->orderBy(['id' => SORT_DESC]);
                break;
        }

        return ApiResponse::success($query->all());
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
