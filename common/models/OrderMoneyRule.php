<?php

namespace common\models;

/**
 * This is the model class for table "order_money_rule".
 *
 * @property int $id
 * @property int $start 开始的值
 * @property int $end 结束的值
 * @property int $type 1:重量 2体积 3距离
 * @property int $money 价格
 */
class OrderMoneyRule extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_money_rule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start', 'end', 'type', 'money'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start' => '开始的值',
            'end' => '结束的值',
            'type' => '1:重量 2体积 3距离 ',
            'money' => '价格',
        ];
    }
}
