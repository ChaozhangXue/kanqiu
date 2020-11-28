<?php

namespace common\models;

/**
 * This is the model class for table "service_station_account".
 *
 * @property int $id
 * @property string $order_num 订单编号
 * @property string $station_name 站点名称
 * @property string $in_charge_name 负责人
 * @property string $order_time 订单时间
 * @property double $total_account 订单总额
 * @property double $yongjin 佣金数额
 * @property int $package_num 包裹数量
 * @property string $verify_time 审核时间
 * @property int $status 结算状态 1:未审核 2已审核
 * @property string $bill_time 账单时间
 * @property int $create_time
 * @property int $update_time
 */
class ServiceStationAccount extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_station_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_time', 'verify_time', 'bill_time'], 'safe'],
            [['total_account', 'yongjin'], 'number'],
            [['package_num', 'status', 'create_time', 'update_time'], 'integer'],
            [['order_num', 'station_name', 'in_charge_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_num' => '订单编号',
            'station_name' => '站点名称',
            'in_charge_name' => '负责人',
            'order_time' => '订单时间',
            'total_account' => '订单总额',
            'yongjin' => '佣金数额',
            'package_num' => '包裹数量',
            'verify_time' => '审核时间',
            'status' => '结算状态 1:未审核 2已审核',
            'bill_time' => '账单时间',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
