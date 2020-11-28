<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/7/25
 * Time: 3:51 PM
 */

namespace backend\widgets;

use yii\base\Widget;
use yii\bootstrap\BootstrapAsset;

class ImageInput extends Widget
{

    public $name = 'file';
    public $value = '';

    public function run()
    {
        BootstrapAsset::register($this->getView());
        return $this->renderHtml();
    }

    public function renderHtml()
    {
        $html = '<div class="fileinput-box">';
        if ($this->value) {
            $html .= '<img src="' . $this->value . '">';
        }
        $html .= '<div class="fileinput-button">
                    <div class="plus-symbol" ' . ($this->value != '' ? 'style="display:none"' : '') . '>
                        +
                    </div>
                    <input class="fileinput-input" type="file" name="' . $this->name . '" value="">
                </div>
            </div>';
        return $html;
    }
}