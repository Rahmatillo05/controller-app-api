<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistics}}`.
 */
class m230309_172149_create_statistics_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp ()
	{
		$this->createTable('{{%statistics}}', [
			'id' => $this->primaryKey(),
			'total_spent' => $this->money(),
			'total_benefit' => $this->money(),
			'pure_benefit' => $this->money(),
			'created_at' => $this->integer()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown ()
	{
		$this->dropTable('{{%statistics}}');
	}
}
