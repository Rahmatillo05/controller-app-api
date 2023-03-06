<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_history}}`.
 */
class m230306_162618_create_payment_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_history}}', [
            'id' => $this->primaryKey(),
            'debtor_id' => $this->integer(),
            'debt_amount' => $this->integer()->notNull(),
            'paid_amount' => $this->integer()->notNull(),
            'remaining_amount' => $this->integer()
        ]);
        $this->addForeignKey('fk-payment_history-to-debtor', 'payment_history', 'debtor_id', 'debtor', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payment_history-to-debtor', 'payment_history');
        $this->dropTable('{{%payment_history}}');
    }
}
