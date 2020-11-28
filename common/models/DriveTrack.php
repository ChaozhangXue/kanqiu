<?php

namespace common\models;

/**
 * This is the model class for table "{{%drive_track}}".
 *
 * @property int $id
 * @property int $driver_id 司机id
 * @property string $order_type
 * @property int $order_id 订单id
 * @property string $longitude 经度
 * @property string $latitude 纬度
 * @property string $time 上报时间
 * @property string $created_at
 * @property string $updated_at
 */
class DriveTrack extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%drive_track}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'order_id', 'longitude', 'latitude', 'time'], 'required'],
            [['driver_id', 'order_id'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['time', 'created_at', 'updated_at'], 'safe'],
            [['order_type'], 'string', 'max' => 255],
            [['order_type'], 'default', 'value' => 'package-order'],
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
            'order_type' => 'Order Type',
            'order_id' => 'Order ID',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'time' => 'Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
