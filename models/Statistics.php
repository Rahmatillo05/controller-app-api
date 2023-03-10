<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "statistics".
 *
 * @property int $id
 * @property float|null $total_spent
 * @property float|null $total_benefit
 * @property float|null $pure_benefit
 * @property int|null $created_at
 */
class Statistics extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName ()
	{
		return 'statistics';
	}

	public function behaviors ()
	{
		return [
			[
				'class' => TimestampBehavior::class,
				'updatedAtAttribute' => false
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules ()
	{
		return [
			[ [ 'total_spent', 'total_benefit', 'pure_benefit' ], 'number' ],
			[ [ 'created_at' ], 'integer' ],
		];
	}

	public function saved ()
	{
		$this->total_spent = $this->calculateSpent();
		$this->total_benefit = $this->calculateBenefit();
		$this->pure_benefit = $this->calculatePureBenefit();
		return $this->save() ? $this : $this->errors;
	}

	private function calculateSpent ()
	{

	}

	private function calculateBenefit ()
	{

	}

	private function calculatePureBenefit ()
	{

	}

}
