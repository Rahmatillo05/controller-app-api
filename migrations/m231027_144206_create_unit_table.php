<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit}}`.
 */
class m231027_144206_create_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'short_name' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
        $this->batchInsert('unit', ['name', 'short_name'], [
            ['DONA', 'DN'],
            ['KLOGRAM', 'KG'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unit}}');
    }
}
