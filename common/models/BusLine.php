<?php

namespace common\models;

/**
 * This is the model class for table "bus_line".
 *
 * @property int $id
 * @property string $station_name 线路名称
 * @property int $station_num 站点数量
 * @property string $start_time 首班时间
 * @property string $end_time 末班时间
 * @property string $start_point 起点
 * @property string $end_point 终点
 * @property string $create_people 创建人
 * @property string $area 所属地区
 * @property string $station_list 站点列表（逗号隔开）
 * @property int $type 线路类型：1上行 2下行
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class BusLine extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bus_line';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['station_name'], 'required'],
            [['station_num', 'type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['station_name', 'start_time', 'end_time', 'start_point', 'end_point', 'create_people', 'area', 'station_list'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_name' => '线路名称',
            'station_num' => '站点数量',
            'start_time' => '首班时间',
            'end_time' => '末班时间',
            'start_point' => '起点',
            'end_point' => '终点',
            'create_people' => '创建人',
            'area' => '所属地区',
            'station_list' => '站点列表（逗号隔开）',
            'type' => '线路类型：1上行 2下行',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    public function extraFields()
    {
        return [
            "station" => function () {
                return BusStation::find()
                    ->where(['in', 'id', explode(',', $this->station_list)])
                    ->asArray()
                    ->all();
            },
        ];
    }
}
