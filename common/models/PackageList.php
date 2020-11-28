<?php

namespace common\models;

/**
 * This is the model class for table "package_list".
 *
 * @property int $id
 * @property int $package_id 绑定的包裹id
 * @property int $is_print 是否打印 1: 已打印 0;未打印
 */
class PackageList extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id', 'is_print'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_id' => '绑定的包裹id',
            'is_print' => '是否打印 1: 已打印 0;未打印',
        ];
    }
}
