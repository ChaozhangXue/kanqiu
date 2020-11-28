<?php

namespace backend\modules\system\services;

use backend\models\RoleMenu;
use backend\models\RoleUser;
use common\services\BaseService;

class RoleService extends BaseService
{
    /**
     * @param $params
     * @throws \Exception
     */
    public function setRoleMenu($params)
    {

        $menuIds = explode(',', $params['menu_ids']);
        $roleMenus = RoleMenu::find()->where(['role_id' => $params['id']])->asArray()->all();
        $roleMenus = array_column($roleMenus, 'id', 'menu_id');
        $addMenuIds = array_diff($menuIds, array_keys($roleMenus));
        $addMenuList = [];
        foreach ($addMenuIds as $key => $menuId) {
            $addMenuList[] = [
                'role_id' => $params['id'],
                'menu_id' => $menuId,
                'create_time' => time()
            ];
        }
        $removeAdminIds = array_diff(array_keys($roleMenus), $menuIds);
        if (count($removeAdminIds)) {
            RoleMenu::deleteAll(['role_id' => $params['id'], 'menu_id' => $removeAdminIds]);
        }
        if (count($addMenuList)) {
            \Yii::$app->db->createCommand()->batchInsert(RoleMenu::tableName(), ['role_id', 'menu_id', 'create_time'], $addMenuList)->execute();
        }
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function setRoleUser($params)
    {
        $userIdList = isset($params['user_id']) ? $params['user_id'] : [];
        $roleMenus = RoleUser::find()->where(['role_id' => $params['id']])->asArray()->all();
        $roleMenus = array_column($roleMenus, 'id', 'user_id');
        $addIds = array_diff($userIdList, array_keys($roleMenus));
        $addList = [];
        foreach ($addIds as $key => $userId) {
            $addList[] = [
                'role_id' => $params['id'],
                'user_id' => $userId,
                'create_time' => time()
            ];
        }
        $removeList = array_diff(array_keys($roleMenus), $userIdList);
        if (count($removeList)) {
            RoleUser::deleteAll(['role_id' => $params['id'], 'user_id' => $removeList]);
        }
        if (count($addList)) {
            \Yii::$app->db->createCommand()->batchInsert(RoleUser::tableName(), ['role_id', 'user_id', 'create_time'], $addList)->execute();
        }
    }
}