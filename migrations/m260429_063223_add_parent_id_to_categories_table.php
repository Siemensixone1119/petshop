<?php

use yii\db\Migration;

class m260429_063223_add_parent_id_to_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'parent_id', $this->integer()->null());

        $this->createIndex(
            'idx-categories-parent_id',
            '{{%categories}}',
            'parent_id'
        );

        $this->addForeignKey(
            'fk-categories-parent_id',
            '{{%categories}}',
            'parent_id',
            '{{%categories}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-categories-parent_id',
            '{{%categories}}'
        );

        $this->dropIndex(
            'idx-categories-parent_id',
            '{{%categories}}'
        );

        $this->dropColumn('{{%categories}}', 'parent_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260429_063223_add_parent_id_to_categories_table cannot be reverted.\n";

        return false;
    }
    */
}
