<?php

namespace backend\modules\system\services;

use backend\models\Menu;
use backend\models\RoleMenu;
use backend\models\RoleUser;
use common\services\BaseService;

class MenuService extends BaseService
{

    public function getParent($cmenu, $menuList)
    {
        $menuList[$cmenu['id']] = $cmenu;
        if ($cmenu['parent_id'] == 0) {
            return $menuList;
        }
        $menu = Menu::find()->where(['id' => $cmenu['parent_id']])->one();
        return $this->getParent($menu, $menuList);
    }

    public function getCurrentMenu()
    {
        $module = \Yii::$app->controller->module->id;
        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;
        $url = '/' . $module . '/' . $controller . '/' . $action;
        if ($module == \Yii::$app->id) {
            $url = '/' . $controller . '/' . $action;
        }
        return Menu::find()->where(['url' => $url])->one();
    }

    public function getActiveMenu()
    {
        $menu = $this->getCurrentMenu();
        $activeList = [];
        if ($menu) {
            $activeList = $this->getParent($menu, []);
        }
        return $activeList;
    }

    public function getChildMenus($parent_id = 0, $i = 0)
    {
        $types = [];
        if ($parent_id == 0) {
            $types[] = ['id' => $parent_id, 'name' => '顶级目录'];
        }
        $rows = Menu::find()
            ->where(['parent_id' => $parent_id, 'status' => 1])
            ->orderBy('sort desc,create_time asc')
            ->all();
        $i++;
        foreach ($rows as $v) {
            $name = str_pad($v['name'], (strlen($v['name']) + $i * 2), '--', STR_PAD_LEFT);
            $types[] = ['id' => $v['id'], 'name' => $name];
            $childTypes = $this->getChildMenus($v['id'], $i);
            $types = array_merge($types, $childTypes);
        }
        return $types;
    }

    public function getTopList()
    {
        $roleMenus = RoleUser::find()->alias('a')
            ->leftJoin(RoleMenu::tableName() . ' as b', 'b.role_id = a.role_id')
            ->select(['b.menu_id'])
            ->where(['user_id' => \Yii::$app->user->id])->asArray()->all();
        $roleMenus = array_column($roleMenus, 'menu_id');
        $rows = Menu::find()
            ->where(['status' => 1, 'id' => $roleMenus])
            ->orderBy('sort desc,create_time asc')
            ->asArray()
            ->all();
        $topList = [];
        foreach ($rows as $v) {
            if ($v['parent_id'] == 0) {
                ini_set('display_errors', 'On');
                error_reporting(E_ALL);
                $childList = $this->getChild($rows, $v['id']);
                foreach ($childList as $child) {
                    if ($child['url'] != '') {
                        $v['url'] = $child['url'];
                        $topList[] = $v;
                        break;
                    }
                }
            }
        }
        return $topList;
    }

    public function getChild($rows, $id)
    {
        $childList = [];
        foreach ($rows as $v) {
            if ($v['parent_id'] == $id) {
                $childList[] = $v;
                $arr = $this->getChild($rows, $v['id']);
                $childList = array_merge($childList, $arr);
            }
        }
        return $childList;
    }


    public function getAllMethodList($menu_id = 0)
    {
        //配置白名单
        $whites = \Yii::$app->params['action_white_list'];
        $whiteList = [];
        foreach ($whites as $controller => $actions) {
            foreach ($actions as $action) {
                $whiteList[] = strtolower($controller . '/' . $action);
            }
        }
        $existMethodList = Menu::find()->where('url != ""')->asArray()->all();
        $existMethodList = array_column($existMethodList, 'url', 'id');
        $currentMethod = isset($existMethodList[$menu_id]) ? $existMethodList[$menu_id] : '';
        $uriList = [];
        $modules = \Yii::$app->getModules();
        foreach ($modules as $key => $v) {
            if (in_array($key, ['debug', 'gii'])) {
                continue;
            }
            $moduleList[] = $key;
        }
        foreach ($moduleList as $module) {
            $controllerActionList = $this->getControllerActions(\Yii::$app->getModule($module)->getControllerPath());
            foreach ($controllerActionList as $controller => $actionList) {
                foreach ($actionList as $action) {
                    $uri = '/' . $this->urlParse($module) . '/' . $this->urlParse($controller) . '/' . $this->urlParse($action);
                    if (($uri == $currentMethod || !in_array($uri, $existMethodList)) && !in_array($uri, $whiteList)) {
                        $uriList[] = $uri;
                    }
                }
            }
        }
        return $uriList;
    }


    protected function getControllerActions($controllerPath)
    {
        $controllerList = [];
        if ($handle = opendir($controllerPath)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerList[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerList);
        $fullList = [];
        foreach ($controllerList as $controller) {
            $handle = fopen($controllerPath . '/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fullList[substr($controller, 0, -14)][] = $display[1];
                        endif;
                    endif;
                }
            }
            fclose($handle);
        }
        return $fullList;
    }

    public function urlParse($str)
    {
        return trim(strtolower(preg_replace('/([A-Z])/', '-$1', lcfirst($str))),'-');
    }
}