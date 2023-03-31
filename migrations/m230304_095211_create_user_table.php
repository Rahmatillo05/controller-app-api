<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230304_095211_create_user_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp ()
	{
		$this->createTable('{{%user}}', [
			'id' => $this->primaryKey(),
			'first_name' => $this->string(150)->notNull(),
			'last_name' => $this->string(150)->notNull(),
			'username' => $this->string(150)->unique()->notNull(),
			'password' => $this->string(150)->notNull(),
			'phone_number' => $this->string(150)->notNull(),
			'address' => $this->string(150)->notNull(),
			'auth_key' => $this->string(250),
			'user_role' => $this->smallInteger()->defaultValue(10),
			'status' => $this->smallInteger()->defaultValue(10),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer(),
		]);
		$this->insert('user', [
			'first_name' => 'admin',
			'last_name' => 'admin',
			'username' => 'admin',
			'address' => 'admin',
			'password' => Yii::$app->security->generatePasswordHash('admin'),
			'auth_key' => Yii::$app->security->generateRandomString(15),
			'phone_number' => "+998999999999",
			'created_at' => time(),
			'updated_at' => time(),
		]);
		$this->insert('user', [
			'first_name' => 'admin2',
			'last_name' => 'admin2',
			'username' => 'admin2',
			'address' => 'admin2',
			'password' => Yii::$app->security->generatePasswordHash('admin2'),
			'auth_key' => Yii::$app->security->generateRandomString(15),
			'phone_number' => "+998999999999",
            'user_role' => 10,
			'created_at' => time(),
			'updated_at' => time(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown ()
	{
		$this->dropTable('{{%user}}');
	}
}
