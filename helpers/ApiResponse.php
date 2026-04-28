<?php

namespace app\helpers;

use Yii;
use yii\base\Model;

class ApiResponse
{
    public static function success($data = null, int $statusCode = 200, array $extra = []): array
    {
        Yii::$app->response->statusCode = $statusCode;

        $response = [
            'success' => true,
            'status' => $statusCode,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return array_merge($response, $extra);
    }

    public static function created($data = null, array $extra = []): array
    {
        return self::success($data, 201, $extra);
    }

    public static function error(
        string $code,
        int $statusCode = 400,
        array $errors = [],
        array $extra = []
    ): array {
        Yii::$app->response->statusCode = $statusCode;

        $response = [
            'success' => false,
            'status' => $statusCode,
            'code' => $code,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return array_merge($response, $extra);
    }

    public static function badRequest(string $code = ApiCode::BAD_REQUEST, array $errors = []): array
    {
        return self::error($code, 400, $errors);
    }

    public static function validation($modelOrErrors): array
    {
        $errors = $modelOrErrors instanceof Model
            ? $modelOrErrors->getErrors()
            : $modelOrErrors;

        return self::error(
            ApiCode::VALIDATION_ERROR,
            422,
            $errors
        );
    }

    public static function missingFields(array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            $errors[$field][] = 'FIELD_REQUIRED';
        }

        return self::error(
            ApiCode::REQUIRED_FIELDS_MISSING,
            422,
            $errors
        );
    }

    public static function missingRequired(array $fields): ?array
    {
        $missingFields = [];

        foreach ($fields as $field => $value) {
            if ($value === null || trim((string)$value) === '') {
                $missingFields[] = $field;
            }
        }

        if (empty($missingFields)) {
            return null;
        }

        return self::missingFields($missingFields);
    }

    public static function notFound(string $code = ApiCode::NOT_FOUND): array
    {
        return self::error($code, 404);
    }

    public static function unauthorized(string $code = ApiCode::UNAUTHORIZED, array $errors = []): array
    {
        return self::error($code, 401, $errors);
    }

    public static function forbidden(string $code = ApiCode::FORBIDDEN, array $errors = []): array
    {
        return self::error($code, 403, $errors);
    }

    public static function conflict(string $code, array $errors = []): array
    {
        return self::error($code, 409, $errors);
    }

    public static function serverError(string $code = ApiCode::SERVER_ERROR, array $errors = []): array
    {
        return self::error($code, 500, $errors);
    }
}
