<?php

class GroupController extends ControllerBase
{
    public function initialize()
    {
        $this->logic = new GroupLogic();
        $this->menuLogic = new MenuLogic();  
    }

    public function indexAction()
    {
        $this->view->setVars([
            'list' => $this->logic->getlist()
        ]);
    }

    public function addAction()
    {
        if($this->request->isPost())
        {
            if(!$groupName = trim($this->request->getPost('groupName')))
                return $this->showMsg('参数错误', true);

            if($this->logic->isExist($groupName))
                return $this->showMsg('添加失败，管理员组名已存在', true);

            if(!$groupId = $this->logic->add($groupName))
                return $this->showMsg('管理组添加失败', true);
            $this->logContent = '添加管理组[id]-'.$groupId; 
            return $this->showMsg('管理组添加成功', false, 'index');
        } else {
            $this->view->setVars(array(
                'uid' => $this->uId,
               
            ));
        }
    }

    public function editAction()
    {
        if($this->request->isPost())
        {
            if(!$groupId = $this->request->getPost('id'))
                return $this->showMsg('参数错误',true);    

            if(!$groupName = $this->request->getPost('groupName'))
                return $this->showMsg('参数错误',true); 

            if($this->logic->isExist($groupName))
                return $this->showMsg('编辑失败，管理员组名已存在', true);  

            if(!$isEdit = $this->logic->edit($groupId, $groupName))
                $this->showMsg('编辑失败', true);
            $this->logContent = '编辑管理组[id]-'.$groupId; 
            $this->showMsg('编辑成功', false, 'index');
        } else {
            if (!$groupId = intval($this->request->getQuery('id'))) {
                return $this->showMsg('管理员组不存在', true);     
            }

            if (!$info = $this->logic->getGroup($groupId)) {
                return $this->showMsg('管理员组不存在', true, 'index');
            }

            $this->view->setVars(array(
                'info' => $info
            ));
        }
    }

    public function delAction()
    {
        if(!$groupId = intval($this->request->getQuery('id')))
            return $this->showMsg('参数错误', true);

        if($this->logic->getGroupUser($groupId))
            return $this->showMsg('删除失败,请先删除组下用户', true, 'index');

        if(!$this->logic->del($groupId))
            return $this->showMsg('删除失败,', true);

        $this->logContent = '删除管理组[id]-'.$groupId; 
        return $this->showMsg('删除成功', false);
    }

    public function permissionAction()
    {
        if(!$groupId = intval($this->request->getQuery('gid')))
            return $this->showMsg('参数错误', true);
    
        // 获取菜单组
        $treeStr = "<tr>
                        <td class='center'>
                            <label>
                                <input name='cfg[]' value='\$m_id' type='checkbox' id='\$m_id' class='ace ace-checkbox-1 chk-item-1' {\$checked} pid='\$m_parent_id'>
                                <span class='lbl'></span>
                            </label>
                        </td>
                        <td>\$spacer\$m_name</td>
                        <td>\$m_controller</td>
                        <td>\$m_action</td>
                    </tr>";
        $treeMenu = $this->menuLogic->treeMenu($treeStr, 0 ,$groupId);

        $this->view->setVars(array(
            'treeMenu' => $treeMenu,
            'groupid' => $groupId
        ));
    }

    /**
     * 设置管理员组权限
     * @return [type] [description]
     */
    public function addPermiAction()
    {
        $permCfg = !empty($this->request->getPost('cfg')) ? $this->request->getPost('cfg') : array();
        if (!$groupId = intval($this->request->getPost('groupId'))) {
            return $this->showMsg('参数错误', true);
        }

        if ($groupId == $this->uInfo['groupid'])
            return $this->showMsg('无法修改自己分组的权限', true, 'index');

        if(!$this->logic->setPermi($groupId, $permCfg))
            return $this->showMsg('管理组权限设置失败', true, 'index');

        $this->logContent = '管理组权限设置[id]-'.$groupId; 
        return $this->showMsg('管理组权限设置成功', false, 'index');
    }

}