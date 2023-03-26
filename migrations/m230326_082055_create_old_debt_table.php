<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%old_debt}}`.
 */
class m230326_082055_create_old_debt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%old_debt}}', [
            'id' => $this->primaryKey(),
            'debtor_id' => $this->integer(),
            'amount' => $this->float(),
            'created_at' => $this->integer(),
        ]);
        $this->addForeignKey('fk-old-debt-to-debtor', 'old_debt', 'debtor_id', 'debtor', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-old-debt-to-debtor', 'old_debt');
        $this->dropTable('{{%old_debt}}');
    }
}
