<?php

namespace backend\models;

use common\models\BaseModel;


/**
 * This is the model class for table "{{%role_user}}".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $role_id 角色ID
 * @property int $create_time 添加时间
 */
class RoleUser extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'create_time'], 'required'],
            [['user_id', 'role_id', 'create_time'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'role_id' => '角色ID',
            'create_time' => '创建时间',
        ];
    }
}
