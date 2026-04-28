<?php

namespace app\models;

use yii\db\ActiveRecord;

class Categorie extends ActiveRecord
{

    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['description', 'image'], 'default', 'value' => null],
            [['name', 'slug'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 120],
            [['slug'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'description' => 'Описание',
            'image' => 'Изображение',
            'slug' => 'Slug',
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }
}
