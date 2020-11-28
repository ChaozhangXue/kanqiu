<?php

namespace common\models;

/**
 * This is the model class for table "station_allocation".
 *
 * @property int $id
 * @property int $bus_id 公交站点id
 * @property int $line_id 公交线路id
 * @property string $line_name 公交线路名称
 * @property int $service_id 服务站点id
 * @property string $service_name 服务站点名
 * @property string $in_charge_name 负责人姓名
 * @property string $telphone 联系电话
 * @property string $build_time 创建时间
 * @property string $create_people 创建人
 * @property int $create_time
 * @property int $update_time
 */
class StationAllocation extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'station_allocation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bus_id', 'line_id', 'service_id', 'create_time', 'update_time'], 'integer'],
            [['build_time', 'create_people'], 'required'],
            [['build_time'], 'safe'],
            [['line_name', 'service_name', 'in_charge_name', 'telphone', 'create_people'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bus_id' => '公交站点id',
            'line_id' => '公交线路id',
            'line_name' => '公交线路名称',
            'service_id' => '服务站点id',
            'service_name' => '服务站点名',
            'in_charge_name' => '负责人姓名',
            'telphone' => '联系电话',
            'build_time' => '创建时间',
            'create_people' => '创建人',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
