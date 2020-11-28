<?php

namespace backend\widgets;

use backend\models\RoleMenu;
use backend\models\RoleUser;
use backend\modules\system\services\MenuService;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\Widget;

class Menu extends Widget
{
    public function run()
    {
        BootstrapAsset::register($this->getView());
        return $this->renderItem();
    }

    public function renderItem()
    {
        $module = \Yii::$app->controller->module->id;
        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;
        $url = '/' . $module . '/' . $controller . '/' . $action;
        if ($module == \Yii::$app->id) {
            $url = '/' . $controller . '/' . $action;
        }
        /** @var \backend\models\Menu $menu */
        $menu = \backend\models\Menu::find()->where(['url' => $url])->one();
        if (!$menu) {
            return false;
        }
        $userId = \Yii::$app->user->id;
        $roleMenus = RoleUser::find()->alias('a')
            ->leftJoin(RoleMenu::tableName() . ' as b', 'b.role_id = a.role_id')
            ->select(['b.menu_id'])
            ->where(['user_id' => $userId])->asArray()->all();
        $roleMenus = array_column($roleMenus, 'menu_id');
        $menuService = new MenuService();
        $activeList = $menuService->getParent($menu, []);
        $menuList = \backend\models\Menu::find()->where(['status' => 1, 'id' => $roleMenus])->orderBy('sort desc')->all();
        $topList = [];
        $leftList = [];
        foreach ($menuList as $v) {
            if ($v['parent_id'] == 0) {
                if (isset($activeList[$v['id']])) {
                    $top = $v;
                }
                $topList[] = $v;
            }
        }
        foreach ($menuList as $v) {
            if ($v['parent_id'] == $top['id']) {
                $leftList[$v['id']]['item'] = $v;
                $leftList[$v['id']]['list'] = [];
            }
        }
        foreach ($menuList as $v) {
            if (isset($leftList[$v['parent_id']])) {
                $leftList[$v['parent_id']]['list'][] = $v;
            }
        }

        $html = '<ul class="list-group">';
        foreach ($leftList as $v) {
            $html .= '<li class="list-group-item main-item ' . (isset($activeList[$v['item']['id']]) ? 'active' : '') . '"><div><i class="' . $v['item']['icon'] . '"></i> ' . $v['item']['name'] . '</div></li>';
            if (count($v['list'])) {
                $html .= '<li class="sub-item collapse ' . (isset($activeList[$v['item']['id']]) ? 'in' : '') . '"><ul class="list-sub-item">';
                foreach ($v['list'] as $child) {
                    $html .= '<li class="list-group-item ' . (isset($activeList[$child['id']]) ? 'active' : '') . '" data-url="' . \Yii::$app->urlManager->createUrl($child['url']) . '">' . $child['name'] . '</li>';
                }
                $html .= '</ul></li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

}
