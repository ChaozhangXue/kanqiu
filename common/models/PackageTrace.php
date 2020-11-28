<?php

namespace common\models;

/**
 * This is the model class for table "package_trace".
 *
 * @property int $id
 * @property int $package_id 包裹id
 * @property string $detail 内容
 * @property string $created_at
 * @property string $updated_at
 */
class PackageTrace extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_trace';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id', 'detail', 'created_at', 'updated_at'], 'required'],
            [['package_id'], 'integer'],
            [['detail'], 'string'],
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
            'package_id' => '包裹id',
            'detail' => '内容',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
