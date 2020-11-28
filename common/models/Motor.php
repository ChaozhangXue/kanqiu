<?php

namespace common\models;

/**
 * This is the model class for table "motor".
 *
 * @property int $id
 * @property string $brand 品牌
 * @property string $model 型号
 * @property string $card 车牌号码
 * @property string $color 颜色
 * @property int $site_num 座位数量 1.5座、2.7座、3.7座以上
 * @property int $num 核载人数
 * @property int $car_type 车辆类型: 1.面包车、2.中巴车、3.大巴车、4.商务车
 * @property string $buy_time 购买时间
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Motor extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand', 'model', 'card', 'site_num', 'num'], 'required'],
            [['site_num', 'num', 'car_type'], 'integer'],
            [['buy_time', 'created_at', 'updated_at'], 'safe'],
            [['brand', 'model', 'card', 'color'], 'string', 'max' => 50],
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
            'site_num' => '座位数量 1.5座、2.7座、3.7座以上',
            'num' => '核载人数',
            'car_type' => '车辆类型: 1.面包车、2.中巴车、3.大巴车、4.商务车',
            'buy_time' => '购买时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
