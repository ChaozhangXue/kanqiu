<?php

namespace common\models;

/**
 * This is the model class for table "bus_station".
 *
 * @property int $id
 * @property string $station_name 站点名称
 * @property string $up_point 上行的经纬度 逗号隔开
 * @property string $down_point 下行的经纬度 逗号隔开
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class BusStation extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bus_station';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['station_name'], 'string', 'max' => 50],
            [['up_point', 'down_point'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_name' => '站点名称',
            'up_point' => '上行的经纬度 逗号隔开',
            'down_point' => '下行的经纬度 逗号隔开',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
