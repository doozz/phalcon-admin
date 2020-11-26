<?php
use Components\Utils\Tree;

class MenuLogic extends LogicBase
{
    public function __construct()
    {
        $this->model = new MenuModel;
        $this->groupModel = new GroupModel;
    }

    public function getInfo($controller, $action)
    {

        return $this->model->getInfo($controller, $action);
    }

    public function isExist($controller, $action)
    {
        return $this->model->isExist($controller, $action);
    }

    public function treeMenu($showHtml = '', $siteId = 0, $gId = 0)
    {
        $group = $this->groupModel->get($gId);
        $conf = json_decode($group['pg_config'], true);

        $tree = new Tree();
        $tree->id = 'm_id';
        $tree->parentid = 'm_parent_id';
        $tree->name = 'm_name';

        $treeMenu = array();
        
        $menuData = $this->model->getList();

        array_walk($menuData, function (&$value) {
            $value['m_dis'] = $value['m_dis'] == 1 ? '显示' : '不显示';
        });

        $tree->init($menuData);
        $treeMenu = $tree->get_tree_multi(0, $showHtml, 0, '', $conf);
      
        return $treeMenu;
    }

    public function getMenu($mId)
    {
        return $this->model->getMenu($mId);
    }

    public function getMenus()
    {
        $menus = [];
        $subMenu = [];
        $menus = $this->model->getSubMenus();
        foreach ($menus as $key => $pMenu) {
            $subMenu = $this->model->getSubMenus($pMenu['m_id']);
            $menus[$key]['sub'] = $subMenu;
        }
        return $menus;
    }

    public function getSubMenu($pId)
    {
        return $this->model->getSubMenu($pId);
    }

    public function getMenuByGid($gId)
    {
        if (!$group = $this->groupModel->get($gId)) {
            return [];
        }

        $permConf = json_decode($group['pg_config'], true);
        $allMenus = $this->getMenus();

        foreach ($allMenus as $key => $menus) {
            if (!in_array($menus['m_id'], $permConf)) {
                unset($allMenus[$key]);
            }

            if (!empty($allMenus[$key])) {
                foreach ($allMenus[$key]['sub'] as $skey => $value) {
                    if (!in_array($value['m_id'], $permConf)) {
                        unset($allMenus[$key]['sub'][$skey]);
                    }
                }
            }
        }
        return $allMenus;
    }

    public function edit($mid, $data)
    {
        return $this->model->edit($mid, $data);
    }

    public function add($data)
    {
        return $this->model->add($data);
    }

    public function del($mId)
    {
        return $this->model->del($mId);
    }

    
    // public function getPermByGidSid($groupId)
    // {
    //     return $this->model->getPermByGid($groupId);
    // }
}