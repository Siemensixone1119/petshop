<?php

use yii\db\Migration;

class m260423_060934_add_auth_token_to_users_table extends Migration
{
public function safeUp()
    {
        $this->addColumn('{{%users}}', 'auth_token', $this->string(255)->null()->unique());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'auth_token');
    }
}
