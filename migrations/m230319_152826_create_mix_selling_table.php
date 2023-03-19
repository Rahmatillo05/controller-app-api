<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mix_selling}}`.
 */
class m230319_152826_create_mix_selling_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mix_selling}}', [
            'id' => $this->primaryKey(),
            'selling_ids'=> $this->string()->notNull(),
            'on_cash' => $this->integer()->notNull(),
            'on_plastic' => $this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mix_selling}}');
    }
}
