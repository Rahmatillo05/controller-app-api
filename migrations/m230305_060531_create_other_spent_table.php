<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%other_spent}}`.
 */
class m230305_060531_create_other_spent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%other_spent}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'sum' => $this->integer()->notNull(),
            'created_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%other_spent}}');
    }
}
