<?php

namespace common\models;

/**
 * This is the model class for table "service_station".
 *
 * @property int $id
 * @property string $country 所属乡镇
 * @property string $station_name 服务站名
 * @property string $address 地址
 * @property string $longitude 经度
 * @property string $latitude 纬度
 * @property string $entity 服务主体
 * @property string $in_charge_name 负责人姓名
 * @property string $id_card 经营人身份证号
 * @property string $build_size 建筑面积
 * @property string $code 邮政编码
 * @property int $people_num 站点人数
 * @property string $telephone 联系电话
 * @property string $build_time 成立时间
 * @property string $service_time 服务时间
 * @property string $backup 备注
 * @property int $create_time 创建时间
 * @property int $update_time 修改时间
 */
class ServiceStation extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_station';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country', 'station_name', 'address', 'longitude', 'latitude', 'in_charge_name', 'id_card', 'build_size', 'code', 'telephone'], 'required'],
            [['longitude', 'latitude'], 'number'],
            [['people_num', 'create_time', 'update_time'], 'integer'],
            [['build_time'], 'safe'],
            [['country', 'station_name', 'address', 'entity', 'in_charge_name', 'id_card', 'build_size', 'code', 'telephone', 'service_time', 'backup'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => '所属乡镇',
            'station_name' => '服务站名',
            'address' => '地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'entity' => '服务主体',
            'in_charge_name' => '负责人姓名',
            'id_card' => '经营人身份证号',
            'build_size' => '建筑面积',
            'code' => '邮政编码',
            'people_num' => '站点人数',
            'telephone' => '联系电话',
            'build_time' => '成立时间',
            'service_time' => '服务时间',
            'backup' => '备注',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
        ];
    }
}
