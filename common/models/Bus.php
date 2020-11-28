<?php

namespace common\models;

/**
 * This is the model class for table "bus".
 *
 * @property int $id
 * @property string $brand 品牌
 * @property string $model 型号
 * @property string $card 车牌号码
 * @property string $color 颜色
 * @property int $num 核载人数
 * @property int $car_type 车辆类型: 1:小巴士、2:中巴士、3:大巴士
 * @property string $buy_time 购买时间
 * @property string $dept 部门
 * @property string $created_at
 * @property string $updated_at
 */
class Bus extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand', 'model', 'card', 'num', 'car_type'], 'required'],
            [['num', 'car_type'], 'integer'],
            [['buy_time', 'created_at', 'updated_at'], 'safe'],
            [['brand', 'model', 'card', 'color', 'dept'], 'string', 'max' => 50],
            [['card'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand' => '品牌',
            'model' => '型号',
            'card' => '车牌号码',
            'color' => '颜色',
            'num' => '核载人数',
            'car_type' => '车辆类型: 1:小巴士、2:中巴士、3:大巴士',
            'buy_time' => '购买时间',
            'dept' => '部门',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
