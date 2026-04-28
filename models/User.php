<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'users';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['login', 'email', 'password_hash', 'role'], 'required'],
            [['created_at'], 'integer'],
            [['login'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['password_hash'], 'string', 'max' => 255],
            [['auth_token'], 'string', 'max' => 255],
            [['role'], 'string', 'max' => 20],
            [['email'], 'unique'],
            [['login'], 'unique'],
            [['auth_token'], 'unique'],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'email' => 'Email',
            'password_hash' => 'Хэш пароля',
            'role' => 'Роль',
            'created_at' => 'Дата создания',
            'auth_token' => 'Auth Token',
        ];
    }

    public function getCart()
    {
        return $this->hasOne(Cart::class, ['user_id' => 'id']);
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, ['user_id' => 'id']);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function generateAuthToken()
    {
        $this->auth_token = Yii::$app->security->generateRandomString();
    }
}
