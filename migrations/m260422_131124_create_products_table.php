<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m260422_131124_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull(),
            'description' => $this->text()->null(),
            'price' => $this->decimal(10, 2)->notNull(),
            'stock' => $this->integer()->notNull()->defaultValue(0),
            'image' => $this->string(255)->null(),
            'category_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-products-category_id',
            '{{%products}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-products-category_id',
            '{{%products}}',
            'category_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-products-category_id','{{%products}}');
        $this->dropIndex('idx-products-category_id','{{%products}}');
        $this->dropTable('{{%products}}');
    }
}
