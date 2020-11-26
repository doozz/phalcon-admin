<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ControllerBase extends Controller
{
    protected $logContent = '';  //
    protected $ctrlName;    // 当前访问控制器名
    protected $actName;    // 当前访问方法名
    protected $uInfo = array(); // 用户信息
    protected $menuLogic;
 
    /**
     * set before execute route
     */
    public function beforeExecuteRoute($dispatcher)
    {
        if ($this->request->isAjax())
            $this->di['view']->disable();
        
        $this->ctrlName = $this->dispatcher->getControllerName();
        $this->actName = $this->dispatcher->getActionName();

        if (!$this->_whiteList($this->ctrlName,$this->actName )) {
            $this->menuLogic = new MenuLogic();
            $this->uInfo = $this->di['session']->get('uInfo');
           
            if ($this->ctrlName != 'auth') {
                if (!$this->_checkLogin()) {
                    $this->di['cookie']->get('auth')->delete();
                    $this->di['session']->remove('uInfo');
                    if ($this->request->isAjax()) {
                        return $this->di['helper']->resRet('', 401);
                    } else {
                        return $this->showMsg('登陆已过期，请重新登陆', true, '/auth/login');
                    }
                }

                if ($this->ctrlName != 'index') {
                    if (!$this->_checkPerms()) {
                        if ($this->request->isAjax()) {
                            return $this->di['helper']->resRet('没有权限访问', 500);
                            exit;
                        }
                        return $this->showMsg('没有权限访问', true, '/index/index');
                    }
                }
            }

                $this->di['view']->setVars([
                'uName' => $this->uInfo['u_name'],
                'controller' => $this->ctrlName,
                'action' => $this->actName,
                '_menuInfo' => $this->_getMenu($this->ctrlName, $this->actName),
                'menus' => $this->_getPermMenu()
            ]);
        }
    }

    /**
     * set after execute route
     *
     *
     */
    public function afterExecuteRoute($dispatcher)
    {
        if ($this->logContent)
        {
            $logLogic = new LogLogic();
            $adminId = $this->di['session']->get('uInfo')['u_id'];
            $uname = $this->di['session']->get('uInfo')['u_name'];
            $controller = $this->ctrlName;
            $action = $this->actName;
            $ip = Components\Utils\Helper::getIP();
            return $logLogic->addLog($adminId, $uname, $controller, $action, $ip, $this->logContent);
        }
    }

    /**
     * 跳转提示页
     * @param  [type]  $msg      [description]
     * @param  boolean $error    [description]
     * @param  string  $back_url [description]
     * @param  string  $sec      [description]
     * @return [type]            [description]
     */
    protected function showMsg($msg, $error =false, $back_url='', $sec = '2')
    {
        $this->view->setVars(array(
            'back_url' => $back_url ?: (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'),
            'msg' => $msg,
            'error'=>$error,
            'sec' => $sec
          ));
        $this->view->pick("common/showMsg");
        return true;
    }

    /**
     * 检测用户登陆状态
     * @return boolean            [description]
     */

    protected function _checkLogin()
    {
        if (!$this->uInfo) return false;

        if (!$cookie = $this->di['cookie']->get('auth')) return false;

        if (!$aInfo = explode('_**_', trim($cookie->getValue()))) return false;

        if (count($aInfo) != 3) return false;

        if ($aInfo[2] != $this->di['redis']->get('admin:user:' . $this->uInfo['u_id'])) return false;

        return true;      
    }

    protected function _checkPerms()
    {
        if ($this->uInfo['u_super'] == 1) {
            return true;
        }

        $groupLogic = new GroupLogic();
          //获取对定的权限组id
        if(!$group = $groupLogic->getGroup($this->uInfo['u_permi']))
            return false;
        // 验证访问模块权限
        $visiMenu = $this->menuLogic->isExist(strtolower($this->ctrlName), strtolower($this->actName));
        $perms = json_decode($group['pg_config'], true);
        if (!in_array($visiMenu, $perms))
            return false;
        
        return true;
    }

    protected function _getPermMenu()
    {
        $menu = [];
        if ($this->uInfo['u_super'] == 1)
            $menu = $this->menuLogic->getMenus();
        else{
            $menu = $this->menuLogic->getMenuByGid($this->uInfo['u_permi']);
        }
        return $menu;
    }

    protected function _getMenu($c, $a)
    {
        return $this->menuLogic->getInfo($c, $a);   
    }

    protected function _whiteList($c, $a) 
    {
        // $list = ['system' => ['callback']];
        // if ($list[$c]) {
        //     if (in_array($a, $list[$c])) {
        //         return true;
        //     }
        //     return false;
        // } 
        return false;
    }
}

