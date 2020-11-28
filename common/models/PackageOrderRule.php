<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "package_order_rule".
 *
 * @property int $id
 * @property int $type 1：重量 2：体积  3：路程
 * @property string $min 最小值
 * @property string $max 最大值
 * @property string $created_at
 * @property string $updated_at
 */
class PackageOrderRule extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_order_rule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'min', 'max'], 'required'],
            [['type'], 'integer'],
            [['min', 'max'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '1：重量 2：体积  3：路程',
            'min' => '最小值',
            'max' => '最大值',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
