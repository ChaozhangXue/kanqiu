<?php

namespace common\models;

/**
 * This is the model class for table "{{%driver}}".
 *
 * @property int $id
 * @property int $type 司机类型 1公交司机 2客运司机
 * @property string $realname 真实姓名
 * @property int $gender 性别 1男 2女
 * @property string $birth_date 出生日期
 * @property string $dept 部门
 * @property string $job_position 岗位
 * @property string $employment_time 从业时长
 * @property string $license 驾照等级
 * @property string $mobile 手机号码
 * @property string $idcard 身份证号
 * @property int $entry_time 入职时间
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class Driver extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%driver}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'create_time', 'update_time', 'type','status'], 'integer'],
            [['gender', 'create_time', 'update_time', 'type', 'realname', 'dept', 'job_position', 'employment_time', 'license', 'mobile', 'idcard', 'birth_date', 'entry_time'], 'required'],
            [['realname', 'dept', 'job_position', 'employment_time'], 'string', 'max' => 255],
            [['license', 'mobile', 'idcard'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'realname' => '姓名',
            'gender' => '性别',
            'birth_date' => '出生日期',
            'dept' => '部门',
            'job_position' => '岗位',
            'employment_time' => '从业时长',
            'license' => '驾照',
            'mobile' => '联系电话',
            'idcard' => '身份证',
            'entry_time' => '入职时间',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
