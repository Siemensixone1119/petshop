<?php

namespace app\services;

use app\models\Categorie;
use app\helpers\ApiResponse;
use app\helpers\ApiCode;

class CategorieService
{
    public function getCategorieList(): array
    {
        $categorieList = Categorie::find()->all();

        return ApiResponse::success($categorieList);
    }

    public function getCategorieById($id): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $categorie = Categorie::findOne($id);

        if (!$categorie) {
            return ApiResponse::error(
                ApiCode::CATEGORY_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::CATEGORY_NOT_FOUND]
                ]
            );
        }

        return ApiResponse::success($categorie);
    }

    public function createCategorie($data): array
    {
        if (!$data) {
            return ApiResponse::missingFields(['data']);
        }

        $categorie = new Categorie();

        $categorie->name = isset($data['name']) ? trim($data['name']) : null;
        $categorie->description = isset($data['description']) ? trim($data['description']) : null;
        $categorie->image = isset($data['image']) ? trim($data['image']) : null;
        $categorie->slug = isset($data['slug']) ? trim($data['slug']) : null;

        if (!$categorie->save()) {
            return ApiResponse::validation($categorie);
        }

        return ApiResponse::success($categorie);
    }

    public function updateCategorie($id, $data): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $categorie = Categorie::findOne($id);

        if (!$categorie) {
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
            $categorie->name = $name;
        }

        if ($description !== null) {
            $categorie->description = $description;
        }

        if ($image !== null) {
            $categorie->image = $image;
        }

        if ($slug !== null) {
            $categorie->slug = $slug;
        }

        if (!$categorie->save()) {
            return ApiResponse::validation($categorie);
        }

        return ApiResponse::success($categorie);
    }

    public function deleteCategorie($id): array
    {
        if (!$id) {
            return ApiResponse::missingFields(['id']);
        }

        $categorie = Categorie::findOne($id);

        if (!$categorie) {
            return ApiResponse::error(
                ApiCode::CATEGORY_NOT_FOUND,
                404,
                [
                    'id' => [ApiCode::CATEGORY_NOT_FOUND]
                ]
            );
        }

        if (!$categorie->delete()) {
            return ApiResponse::serverError(
                ApiCode::CATEGORY_DELETE_ERROR,
                $categorie->getErrors()
            );
        }

        return ApiResponse::success();
    }
}
