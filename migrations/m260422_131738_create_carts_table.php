<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%carts}}`.
 */
class m260422_131738_create_carts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%carts}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at'=> $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-carts-user_id',
            '{{%carts}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-carts-user_id','{{%carts}}');
        $this->dropTable('{{%carts}}');
    }
}
