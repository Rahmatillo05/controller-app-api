<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_history_list}}`.
 */
class m230306_163121_create_payment_history_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_history_list}}', [
            'id' => $this->primaryKey(),
            'debtor_id' => $this->integer(),
            'pay_amount' => $this->integer(),
            'created_at' => $this->integer(),
        ]);
        $this->addForeignKey('fk-payment_history_list-to-debtor', 'payment_history_list', 'debtor_id', 'debtor', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payment_history_list-to-payment_history', 'payment_history_list');
        $this->dropTable('{{%payment_history_list}}');
    }
}
