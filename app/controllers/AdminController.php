<?php
use Phalcon\Security;
use Phalcon\Security\Random;
use Phalcon\Mvc\View;
class AdminController extends ControllerBase
{
    const NUM_PER_PAGE = 20;//每页显示数

    public function initialize()
    {
        $this->logic = new AdminLogic();
        $this->menuLogic = new MenuLogic();
        $this->loglogic = new LogLogic();
        $this->groupLogic = new GroupLogic();
        $this->uId = $this->di->get('session')->get('uInfo')['u_id'];
    }

    public function indexAction()
    {
        $admList = $this->logic->getList(); 
        $this->view->setVars(array(
            'adminLists' => $admList,
        ));
    }

    /**
     * 添加管理员
     */
    public function addAction()
    {
        if($this->request->isPost())
        {   
            $data['u_name'] = trim($this->request->getPost('username'));   
            $data['u_pass'] = trim($this->request->getPost('password'));
            $data['u_permi'] = trim($this->request->getPost('per'));
            foreach ($data as $value) {
                if(empty($value)) {
                    return $this->showMsg('参数错误', true);
                }
            }
                
            // 检测管理员是否已存在
            $adminExists = $this->logic->getByName($data['u_name']);
            if ($adminExists)
                return $this->showMsg('添加失败，该管理员已存在', true);
            
            // 添加用户并绑定管理员权限
            if($data['u_permi'] == 'x') {
                $permCfg = !empty($this->request->getPost('cfg')) ? $this->request->getPost('cfg') : [];
                if (!$data['u_permi'] = $this->groupLogic->setPermi($data['u_permi'], $permCfg)) {
                    $this->logContent = ('添加管理员失败[id]-'.$id); 
                    return $this->showMsg('添加管理员失败', true, 'index');
                }
            } 

            if (!$this->logic->add($data)) {
                $this->logContent = '添加管理员[id]-'.$id.'绑定管理员组失败[id]-'.$groupId;
                return $this->showMsg('添加管理员失败', true, 'index');
            }
            $this->logContent = '添加管理员[id]-'.$id.'绑定管理员组[id]-'.$groupId; 
            return $this->showMsg('添加成功', false, 'index');
        } else {
            $lists = [];
            $treeStr = "
                    <tr>
                        <td class='center'>
                            <label>
                                <input name='cfg[]' value='\$m_id' type='checkbox' id='\$m_id' class='ace ace-checkbox-1 chk-item-1'  pid='\$m_parent_id'>
                                <span class='lbl'></span>
                            </label>
                        </td>
                        <td>\$spacer\$m_name</td>
                        <td>\$m_controller</td>
                        <td>\$m_action</td>
                    </tr>";

            $treeMenu = $this->menuLogic->treeMenu($treeStr);
            $groupList = $this->groupLogic->getlist();
            $this->view->setVars([
                'groupList' => $groupList,
                'treeMenu' => $treeMenu
            ]);
        }
    }

    /**
     * 编辑管理员
     * @return [type] [description]
     */
    public function editAction()
    {
        if($this->request->isPost())
        {
            if (!$adminId = $this->request->getPost('id'))
                return $this->showMsg('参数错误', true);
           
            if (!$info = $this->logic->getById($adminId)) {
                return $this->showMsg('管理员不存在', true); 
            }
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $groupId = $this->request->getPost('groupid');

            // 检测用户是否已存在
            if (!$this->logic->getByName($username)) {
                return $this->showMsg('用户名已存在', true);   
            }
           
            if ($this->uInfo['u_super'] != 1) {
                if($info['u_super'] == 1)
                    return $this->showMsg('编辑失败，禁止编辑主管理员', true);
            } 

            // 编辑用户
            if ($groupId == 'x') {
                $permCfg = !empty($this->request->getPost('cfg')) ? $this->request->getPost('cfg') : [];
                if (!$groupId = $this->groupLogic->setPermi($groupId, $permCfg)) {
                    $this->logContent = ('编辑管理员[id]-'.$id); 
                    return $this->showMsg('编辑管理员失败', true);
                } 
            }

            if(!$isEdit = $this->logic->edit($adminId, $username, $password, $groupId))
                return $this->showMsg('编辑失败', true); 
            $this->logContent = '编辑管理员[id]-'.$adminId; 
            return $this->showMsg('编辑成功', false,'index');
        } else {
            $adminId = $this->request->getQuery('id')  ? intval($this->request->getQuery('id')) : '';
            if (!$info = $this->logic->getById($adminId)) 
                return $this->showMsg('管理员不存在', true);
            
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
            $treeMenu = $this->menuLogic->treeMenu($treeStr, 0, $info['u_permi']);
            $groupList = $this->groupLogic->getlist();
            $permi = $this->groupLogic->getGroup($info['u_permi']);
            $this->view->setVars([
                'info' => $info,
                'treeMenu' => $treeMenu,
                'groupList' => $groupList,
                'permi' => $permi
            ]);
        }
    }

    /**
     * 删除管理员
     * @return [type] [description]
     */
    public function delAction()
    {
        if (!$adminId = intval($this->request->getQuery('id')))
            return $this->showMsg('参数错误', true);
        if ($adminId == $this->uInfo['u_id'])
            return $this->showMsg('删除失败，禁止删除当前登录用户', true);
       
        if($this->logic->getById($adminId)['u_super'] == 1)
            return $this->showMsg('删除失败，禁止删除主管理员', true); 

        if (!$isDel = $this->logic->del($adminId))
            return $this->showMsg('删除失败', true, 'index');

        $this->logContent = '删除管理员[id]-'.$adminId; 
        return $this->showMsg('删除成功', false, 'index');
    }

    public function logAction()
    {
        $page = !empty($this->request->getQuery('page')) ? abs(intval($this->request->getQuery('page'))) : 1;
        $conditions['nick'] = !empty($this->request->get('nick')) ? $this->request->get('nick') : '';
        $conditions['startTime'] = !empty($this->request->getQuery('start')) ? strtotime($this->request->getQuery('start')) : '';
        $conditions['endTime'] = !empty($this->request->getQuery('end')) ? strtotime($this->request->getQuery('end')) : '';
        
        if ($conditions['starttime'] > $conditions['endtime']) {
            $tmp = $conditions['starttime'];
            $conditions['starttime'] = $conditions['endtime'];
            $conditions['endtime'] = $tmp;
        }

        $res = $this->loglogic->detail($conditions, $page, self::NUM_PER_PAGE);
      
        $page = new \Components\Utils\Pagination($res['total'], self::NUM_PER_PAGE, $page);
       
        $this->view->setVars([
            'res' => $res,
            'page' => $page->createLink(),
        ]);   
    }
    
    public function pwdAction()
    {
        if($this->request->isPost())
        {
            if(!$pwd = trim($this->request->getPost('pwd')))
                return $this->showMsg('请输入您的密码', 500);  
            if(!$rpwd = trim($this->request->getPost('new_pwd')))
                return $this->showMsg('请输入您的密码', 500);  
            $newpwd = trim($this->request->getPost('repeat_pwd'));
            if ($rpwd != $newpwd)
                return $this->showMsg('参数错误', 500);

            if(!$pass = $this->logic->getById($this->uInfo['u_id']))
                return $this->showMsg('参数错误', 500);
            if (!password_verify($pwd, $pass['u_pass']))
                return $this->showMsg('原密码错误', 500);

            if (!$this->logic->updataPass($this->uInfo['u_id'], password_hash($rpwd, PASSWORD_DEFAULT)))
                return $this->showMsg('修改密码失败', 500);

            $this->logContent = '用户修改密码[id]-'.$this->uInfo['u_id'];

            return $this->showMsg('修改成功，请重新登录', false, '/auth/logout');
        }
    }

    public function bindingAction()
    {
        $secret = $this->redis->get("googleAuth:".$this->uId);
        if ($secret) {
            $this->view->setVars([
                'bind' => 1,
            ]);  
            return;
        }

        if ($this->request->getQuery('secret')) {
            $secret = $this->redis->set("googleAuth:".$this->uId, $this->request->getQuery('secret'));
            return $this->showMsg('绑定成功', false);
        }

        $ga =  $captcha = new \Components\Utils\GoogleAuth();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('FansPower', $secret); //第一个
        $this->view->setVars([
            'qrcode' => $qrCodeUrl,
            'secret' => $secret,
            'bind' => 0,
        ]);  
    }
}
