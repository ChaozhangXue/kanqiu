<?php

namespace common\models;

/**
 * This is the model class for table "station_account".
 *
 * @property int $id
 * @property int $station_id 站点id
 * @property string $balance 结余金额
 * @property string $total_income 历史总额
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class StationAccount extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'station_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['station_id', 'balance'], 'required'],
            [['station_id', 'create_time', 'update_time'], 'integer'],
            [['balance', 'total_income'], 'number'],
            [['station_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_id' => '站点id',
            'balance' => '结余金额',
            'total_income' => '历史总额',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
