<?php

namespace backend\widgets;


class ActiveField extends \yii\widgets\ActiveField
{

    public $template="{label}\n<div class=\"col-sm-10\">\n{input}\n{hint}\n{error}\n</div>";
    public $labelOptions = ['class' => 'col-sm-2 control-label'];
}