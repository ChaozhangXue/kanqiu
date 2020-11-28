<?php

namespace common\models;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $customer_id 会员id
 * @property string $name 收件人姓名
 * @property string $phone 手机号码
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $detail_address 详细地址
 * @property int $is_default 是否是默认的 1：默认 2不默认
 * @property string $position 逗号隔开的经纬度， 经度，纬度
 * @property string $created_at
 * @property string $updated_at
 */
class Address extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'is_default'], 'integer'],
            [['detail_address', 'position'], 'required'],
            [['detail_address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'phone'], 'string', 'max' => 50],
            [['province', 'city', 'district', 'position'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => '会员id',
            'name' => '收件人姓名',
            'phone' => '手机号码',
            'province' => '省',
            'city' => '市',
            'district' => '区',
            'detail_address' => '详细地址',
            'is_default' => '是否是默认的 1：默认 2不默认',
            'position' => '逗号隔开的经纬度， 经度，纬度',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
