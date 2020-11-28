<?php

namespace common\models;

/**
 * This is the model class for table "{{%bus_money_rule}}".
 *
 * @property int $id
 * @property int $car_type 1小巴 2中巴 3大巴
 * @property string $start 开始距离
 * @property string $end 结束距离
 * @property string $money 价格
 */
class BusMoneyRule extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bus_money_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_type'], 'integer'],
            [['start', 'end', 'money'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_type' => 'Car Type',
            'start' => 'Start',
            'end' => 'End',
            'money' => 'Money',
        ];
    }
}
