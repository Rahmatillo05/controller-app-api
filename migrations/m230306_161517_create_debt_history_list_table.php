<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%debt_history_list}}`.
 */
class m230306_161517_create_debt_history_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%debt_history_list}}', [
            'id' => $this->primaryKey(),
            'selling_id' => $this->integer(),
            'history_id' => $this->integer()
        ]);
        $this->addForeignKey('fk-debt_history_list-to-debt_history', 'debt_history_list', 'history_id', 'debt_history', 'id', 'CASCADE');
        $this->addForeignKey('fk-selling-to-debt_history', 'debt_history_list', 'selling_id', 'selling', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-debt_history_list-to-debt_history', 'debt_history_list');
        $this->dropForeignKey('fk-selling-to-debt_history', 'debt_history_list');
        $this->dropTable('{{%debt_history_list}}');
    }
}
