<?php

namespace common\models;

use yii\db\ActiveRecord;


/**
 * base model
 * @property int create_time
 * @property int update_time
 * @property false|string created_at
 * @property false|string updated_at
 */
class BaseModel extends ActiveRecord
{
    public function insert($runValidation = true, $attributes = null)
    {
        if ($this->hasAttribute('create_time')) {
            $this->create_time = time();
        }
        if ($this->hasAttribute('update_time')) {
            $this->update_time = time();
        }

        if ($this->hasAttribute('created_at')) {
            $this->created_at = date("Y-m-d H:i:s",time());
        }
        if ($this->hasAttribute('updated_at')) {
            $this->updated_at = date("Y-m-d H:i:s",time());
        }
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = null)
    {
        if ($this->hasAttribute('update_time')) {
            $this->update_time = time();
        }
        if ($this->hasAttribute('updated_at')) {
            $this->updated_at = date("Y-m-d H:i:s",time());
        }
        return parent::update($runValidation, $attributes);
    }
}
