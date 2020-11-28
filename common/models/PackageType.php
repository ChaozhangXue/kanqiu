<?php

namespace common\models;

/**
 * This is the model class for table "package_type".
 *
 * @property int $id
 * @property string $type_name 物品类型
 */
class PackageType extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_name'], 'required'],
            [['type_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_name' => '物品类型',
        ];
    }
}
