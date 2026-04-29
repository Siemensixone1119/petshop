<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
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
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 120],
            [['slug'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::class, 'targetAttribute' => ['parent_id' => 'id']],
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

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }
}
