<?php

namespace backend\models;

use common\models\BaseModel;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property int $id 菜单ID
 * @property string $name 菜单名称
 * @property string $url 菜单文件路径
 * @property string $desc 菜单描述
 * @property int $parent_id 父级菜单ID
 * @property int $type 显示类型(0:导航,1:菜单,2:功能)
 * @property string $icon 菜单icon样式
 * @property int $sort 菜单权重排序号
 * @property int $status 菜单状态 1 有效 0 无效
 * @property int $create_time 创建菜单时间
 * @property int $update_time 修改菜单时间
 */
class Menu extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort', 'status', 'create_time', 'update_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 200],
            [['desc', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'url' => '菜单链接',
            'desc' => '描述',
            'parent_id' => 'Parent ID',
            'icon' => '图标',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }

}
