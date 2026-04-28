<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_items}}`.
 */
class m260422_132514_create_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'price' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->createIndex(
            'idx-order_items-order_id',
            '{{%order_items}}',
            'order_id'
        );

        $this->createIndex(
            'idx-order_items-product_id',
            '{{%order_items}}',
            'product_id'
        );

        $this->createIndex(
            'uq-order_items-order_id-product_id',
            '{{%order_items}}',
            ['order_id', 'product_id'],
            true
        );

        $this->addForeignKey(
            'fk-order_items-order_id',
            '{{%order_items}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_items-product_id',
            '{{%order_items}}',
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
        $this->dropForeignKey(
            'fk-order_items-order_id',
            '{{%order_items}}'
        );

        $this->dropForeignKey(
            'fk-order_items-product_id',
            '{{%order_items}}'
        );

        $this->dropIndex(
            'uq-order_items-order_id-product_id',
            '{{%order_items}}'
        );

        $this->dropIndex(
            'idx-order_items-order_id',
            '{{%order_items}}'
        );

        $this->dropIndex(
            'idx-order_items-product_id',
            '{{%order_items}}'
        );

        $this->dropTable('{{%order_items}}');
    }
}