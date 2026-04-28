<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'products';
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
            [['name', 'price', 'category_id'], 'required'],
            [['description'], 'default', 'value' => null],
            [['price'], 'number'],
            [['category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'category_id' => 'Категория',
            'image' => 'Изображение',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Categorie::class, ['id' => 'category_id']);
    }
}
