<?php

namespace app\models;

use yii\db\ActiveRecord;

class CartItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'cart_items';
    }

    public function rules()
    {
        return [
            [['quantity'], 'default', 'value' => 1],
            [['cart_id', 'product_id', 'quantity'], 'required'],
            [['cart_id', 'product_id', 'quantity'], 'integer'],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['cart_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cart_id' => 'ID корзины',
            'product_id' => 'ID продукта',
            'quantity' => 'Количество',
        ];
    }
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}