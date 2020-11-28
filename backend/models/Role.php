<?php

namespace backend\models;

use common\models\BaseModel;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property int $id 角色ID
 * @property string $name 角色名称
 * @property string $desc 描述
 * @property int $status 角色状态 0 无效 1 有效
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class Role extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'create_time', 'update_time'], 'integer'],
            [['name', 'desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色名称',
            'desc' => '描述',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
