<?php

namespace common\models;

/**
 * This is the model class for table "{{%driver_account}}".
 *
 * @property int $id
 * @property int $driver_id 司机ID
 * @property string $balance 结余金额
 * @property string $total_income 历史总额
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class DriverAccount extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%driver_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'balance'], 'required'],
            [['driver_id', 'create_time', 'update_time'], 'integer'],
            [['balance', 'total_income'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'driver_id' => 'Driver ID',
            'balance' => 'Balance',
            'total_income' => 'Total Income',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
