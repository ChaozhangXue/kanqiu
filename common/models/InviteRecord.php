<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invite_record".
 *
 * @property int $id
 * @property int $inviter_id 邀请人id
 * @property string $inviter_code 邀请码
 * @property int $invited_id 被邀请人id
 * @property string $create_time
 */
class InviteRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invite_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inviter_id', 'invited_id'], 'integer'],
            [['create_time'], 'safe'],
            [['inviter_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inviter_id' => 'Inviter ID',
            'inviter_code' => 'Inviter Code',
            'invited_id' => 'Invited ID',
            'create_time' => 'Create Time',
        ];
    }
}
