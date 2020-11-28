<?php

namespace backend\models;

use common\models\BaseModel;

/**
 * This is the model class for table "{{%role_menu}}".
 *
 * @property string $id
 * @property int $role_id 角色ID
 * @property int $menu_id 菜单ID
 * @property int $create_time
 */
class RoleMenu extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'menu_id'], 'required'],
            [['role_id', 'menu_id', 'create_time'], 'integer'],
            [['role_id', 'menu_id'], 'unique', 'targetAttribute' => ['role_id', 'menu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => '角色ID',
            'menu_id' => '菜单ID',
            'create_time' => '创建时间',
        ];
    }
}
