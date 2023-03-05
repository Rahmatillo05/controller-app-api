<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%debtor}}`.
 */
class m230305_073431_create_debtor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%debtor}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'phone_number' => $this->string(),
            'worker_id' => $this->integer(),
            'created_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%debtor}}');
    }
}
