<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%debt_history}}`.
 */
class m230306_110105_create_debt_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%debt_history}}', [
            'id' => $this->primaryKey(),
            'worker_id' => $this->integer(),
            'debtor_id' => $this->integer(),
            'debt_amount' => $this->integer()->notNull(),
            'pay_amount' => $this->integer(),
            'type_pay' => $this->integer(),
            'created_at' => $this->integer()
        ]);
        $this->addForeignKey('fk-from-backlog-to-worker', 'debt_history', 'worker_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-from-backlog-to-debtor', 'debt_history', 'debtor_id', 'debtor', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-from-backlog-to-worker', 'debt_history');
        $this->dropForeignKey('fk-from-backlog-to-debtor', 'debt_history');
        $this->dropTable('{{%debt_history}}');
    }
}
