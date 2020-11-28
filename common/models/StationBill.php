<?php

namespace common\models;

/**
 * This is the model class for table "station_bill".
 *
 * @property int $id
 * @property int $order_id 订单编号
 * @property int $station_id 站点id
 * @property string $station_name 站点名称
 * @property string $owner 负责人
 * @property int $order_type 订单类型 1：取货订单 2：送货订单
 * @property string $time 订单时间
 * @property string $money 订单总额
 * @property string $yongjin 佣金数额
 * @property int $package_num 包裹数量
 * @property string $verify_time 审核时间
 * @property int $status 结算状态 0未结算 1已结算
 * @property string $bill_time 账单时间
 */
class StationBill extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'station_bill';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'station_id', 'order_type'], 'required'],
            [['order_id', 'station_id', 'order_type', 'package_num', 'status'], 'integer'],
            [['time', 'verify_time', 'bill_time'], 'safe'],
            [['money', 'yongjin'], 'number'],
            [['station_name', 'owner'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'station_id' => '站点id',
            'station_name' => '站点名称',
            'owner' => '负责人',
            'order_type' => '订单类型 1：取货订单 2：送货订单',
            'time' => '订单时间',
            'money' => '订单总额',
            'yongjin' => '佣金数额',
            'package_num' => '包裹数量',
            'verify_time' => '审核时间',
            'status' => '结算状态 0未结算 1已结算',
            'bill_time' => '账单时间',
        ];
    }
}
