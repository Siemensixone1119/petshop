<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart_items}}`.
 */
class m260422_132012_create_cart_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cart_items}}', [
            'id' => $this->primaryKey(),
            'cart_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
        ]);

        $this->createIndex(
            'idx-cart_items-cart_id',
            '{{%cart_items}}',
            'cart_id'
        );

        $this->createIndex(
            'idx-cart_items-product_id',
            '{{%cart_items}}',
            'product_id'
        );

        $this->createIndex(
            'uq-cart_items-cart_id-product_id',
            '{{%cart_items}}',
            ['cart_id', 'product_id'],
            true
        );

        $this->addForeignKey(
            'fk-cart_items-cart_id',
            '{{%cart_items}}',
            'cart_id',
            '{{%carts}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-cart_items-product_id',
            '{{%cart_items}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cart_items-cart_id', '{{%cart_items}}');
        $this->dropForeignKey('fk-cart_items-product_id', '{{%cart_items}}');

        $this->dropIndex('uq-cart_items-cart_id-product_id', '{{%cart_items}}');
        $this->dropIndex('idx-cart_items-cart_id', '{{%cart_items}}');
        $this->dropIndex('idx-cart_items-product_id', '{{%cart_items}}');

        $this->dropTable('{{%cart_items}}');
    }
}