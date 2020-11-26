<?php

class MenuController extends ControllerBase
{    
    public function initialize()
    {
        $this->logic = new MenuLogic();
        $this->loglogic = new LogLogic();
        $this->uId = $this->di->get('session')->get('uInfo')['u_id'];
        $this->uName = $this->di->get('session')->get('uInfo')['u_name'];
    }

    public function indexAction()
    {
        $lists = [];
        $treeStr =
        "<tr>
            <td>\$spacer\$m_name</td>
            <td>\$m_controller</td>
            <td>\$m_action</td>
            <td>\$m_dis</td>
            <td>
                <div>
                    <a class='btn btn-success btn-sm checkbox-toggle' href='addsub?pid=\$m_id'><i class='ace-icon fa fa-sitemap bigger-120'></i>添加子菜单</a> &nbsp
                    <a class='btn btn-info btn-sm checkbox-toggle' href='edit?id=\$m_id'><i class='ace-icon fa fa-pencil bigger-120'></i>编辑</a> &nbsp
                    <a class='btn btn-danger btn-sm checkbox-toggle del' href='del?id=\$m_id'><i class='ace-icon fa fa-trash-o bigger-120'></i>删除</a>
                </div>
            </td>
        </tr>";

        $treeMenu = $this->logic->treeMenu($treeStr);

        $this->view->setVars([
            'treeMenu' => $treeMenu
        ]);
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $data['m_name'] = trim($this->request->getPost('name'));
            $data['m_controller'] = trim($this->request->getPost('controller'));
            $data['m_action'] = trim($this->request->getPost('action'));
            
            $data['m_dis'] = trim($this->request->getPost('display'));
            foreach ($data as  $value) {
                if (empty($value)) {
                    return $this->showMsg('参数错误', true);
                }
            }
            $data['m_icon'] = trim($this->request->getPost('icon'));
            // 验证菜单是否已存在
            if ($this->logic->isExist($data['m_controller'], $data['m_action']))
                return $this->showMsg('添加失败，菜单已存在', true);
            
            if (!$menuId = $this->logic->add($data)) 
                return $this->showMsg('添加失败', true);
            
            $this->logContent = '添加菜单[id]-'.$menuId;
            return $this->showMsg('添加成功', false, 'index');
        }
    }

    public function addsubAction()
    {
        if ($this->request->isPost()) {
            $data['m_name'] = trim($this->request->getPost('name'));
            $data['m_controller'] = trim($this->request->getPost('controller'));
            $data['m_action'] = trim($this->request->getPost('action'));
            $data['m_parent_id']  = intval($this->request->getPost('parent_id'));
            $data['m_dis'] = trim($this->request->getPost('display'));
            foreach ($data as  $value) {
                if (empty($value)) {
                    return $this->showMsg('参数错误', true);
                }
            }
            $data['m_icon'] = trim($this->request->getPost('icon'));
            // 验证菜单是否已存在
            if ($this->logic->isExist($data['m_controller'], $data['m_action'])) {
                return $this->showMsg('添加失败，菜单已存在', true);
            }

            $menuId = $this->logic->add($data);
            if (!$menuId) {
                return $this->showMsg('添加失败', true);
            }
            $this->logContent = '添加子菜单[id]-'.$menuId;
            return $this->showMsg('添加成功', false, 'index');
        } else {
            if (!$id = intval($this->request->getQuery('pid'))) {
                return $this->showMsg('参数错误', true);
            }

            if (!$menuInfo = $this->logic->getMenu($id)) {
                return $this->showMsg('菜单不存在', true);
            }

            $this->view->setVars([
                    'menuInfo' => $menuInfo
                ]);
        }
    }

    public function editAction()
    {
        if ($this->request->isPost()) {
            $data['m_name'] = trim($this->request->getPost('name'));
            $data['m_controller'] = trim($this->request->getPost('controller'));
            $data['m_action'] = trim($this->request->getPost('action'));
            $data['m_dis'] = intval($this->request->getPost('display'));
            $m_id = intval($this->request->getPost('id'));
            foreach ($data as  $value) {
                if (empty($value)) {
                    return $this->showMsg('参数错误', true);
                }
            }

            $data['m_icon'] = trim($this->request->getPost('icon'));
            if ($id = $this->logic->isExist($data['m_controller'], $data['m_action'])) {
                if ($id != $m_id) {
                    return $this->showMsg('添加失败，菜单已存在', true);
                }
            }
            
            if (!$this->logic->edit($m_id, $data)) {
                return $this->showMsg('编辑失败', true);
            }
            $this->logContent = '编辑菜单[id]-'.$m_id;
            return $this->showMsg('编辑成功', false, 'index');
        } else {
            $menuId = intval($this->request->getQuery('id'));
            if (!$menuId) {
                $this->showMsg('参数错误', true);
                return false;
            }
            $info = $this->logic->getMenu($menuId);

            if (!$info) {
                $this->showMsg('菜单不存在', true, 'menu');
            }
            $this->view->setVars(array(
                'info'     => $info,
                
            ));
        }
    }

    public function delAction()
    {
        if (!$menuId = intval($this->request->getQuery('id'))) {
            return $this->showMsg('参数错误', true);
        }

        // 获取该菜单所有子菜单信息
        $subMenu = $this->logic->getSubMenu($menuId);
        if ($subMenu) {
            return $this->showMsg('删除失败，请先删除该菜单的子菜单', true, 'index');
        }
            
        if (!$this->logic->del($menuId)) {
            return $this->showMsg('删除失败', true, 'index');
        }
        $this->logContent = '删除菜单[id]-'.$menuId;
        return $this->showMsg('删除成功', false);
    }
}