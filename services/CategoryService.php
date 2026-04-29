<?php

namespace app\services;

use app\models\Category;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class CategoryService
{
    public function getCategoryList(): array
    {
        $categoryList = Category::find()->all();

        return ApiResponse::success($categoryList);
    }

    public function getCategoryById($id): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $category = Category::findOne($id);

        if (!$category) {
            return ApiResponse::error(
                ApiCode::CATEGORY_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::CATEGORY_NOT_FOUND]
                ]
            );
        }

        return ApiResponse::success($category);
    }

    public function createCategory($data): array
    {
        if (!$data) {
            return ApiResponse::missingFields(['data']);
        }

        $category = new Category();

        $category->name = isset($data['name']) ? trim($data['name']) : null;
        $category->description = isset($data['description']) ? trim($data['description']) : null;
        $category->image = isset($data['image']) ? trim($data['image']) : null;
        $category->slug = isset($data['slug']) ? trim($data['slug']) : null;
        $category->parent_id = isset($data['parent_id']) ? trim($data['parent_id']) : null;

        if (!$category->save()) {
            return ApiResponse::validation($category);
        }

        return ApiResponse::success($category);
    }

    public function updateCategory($id, $data): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $category = Category::findOne($id);

        if (!$category) {
            return ApiResponse::error(
                ApiCode::CATEGORY_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::CATEGORY_NOT_FOUND]
                ]
            );
        }

        $name = isset($data['name']) ? trim($data['name']) : null;
        $description = isset($data['description']) ? trim($data['description']) : null;
        $image = isset($data['image']) ? trim($data['image']) : null;
        $slug = isset($data['slug']) ? trim($data['slug']) : null;

        if ($name !== null) {
            $category->name = $name;
        }

        if ($description !== null) {
            $category->description = $description;
        }

        if ($image !== null) {
            $category->image = $image;
        }

        if ($slug !== null) {
            $category->slug = $slug;
        }

        if (!$category->save()) {
            return ApiResponse::validation($category);
        }

        return ApiResponse::success($category);
    }

    public function deleteCategory($id): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $category = Category::findOne($id);

        if (!$category) {
            return ApiResponse::error(
                ApiCode::CATEGORY_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::CATEGORY_NOT_FOUND]
                ]
            );
        }

        if (!$category->delete()) {
            return ApiResponse::serverError(
                ApiCode::CATEGORY_DELETE_ERROR,
                $category->getErrors()
            );
        }

        return ApiResponse::success();
    }
}
