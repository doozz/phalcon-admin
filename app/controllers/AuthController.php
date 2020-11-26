<?php
use Phalcon\Security;
use Phalcon\Security\Random;
use Phalcon\Mvc\View;

class AuthController extends ControllerBase
{
    const NORMAL_STATUS = 1; //允许登陆状态

    public function initialize()
    {
        $this->logic = new AdminLogic();
        $this->uId = $this->di->get('session')->get('uInfo')['u_id'];
    }

    public function loginAction()
    {
        if ($this->request->isPost()) {

            if ($this->security->checkToken()) {
                if (!$uName = trim($this->request->getPost('username')))
                    return $this->helper->resRet('请输入您的账号', 500);
    
                if (!$pwd = trim($this->request->getPost('password')))
                    return $this->helper->resRet('请输入您的密码', 500);
    
                if (!$captcha = trim($this->request->getPost('captcha')))
                    return $this->helper->resRet('请输入验证码', 500);
    
                //验证码检查
                if (strtolower($captcha) != strtolower($this->di['session']->get('captcha')))
                    return $this->helper->resRet('验证码输入错误', 500);
    
                if (!$uInfo = $this->logic->getByName($uName))
                    return $this->helper->resRet('用户名或密码错误', 500);
    
                // 验证密码
                if (!password_verify($pwd, $uInfo['u_pass']))
                    return $this->helper->resRet('用户名或密码错误', 500);
    
                if ($uInfo['u_status'] != self::NORMAL_STATUS)
                    return $this->helper->resRet('禁止登录', 500);
    
                //更新信息
                if (!$this->logic->updateLoginInfo($uInfo['u_id']))
                    return $this->helper->resRet('更新信息失败', 500);
                
                $random = new Phalcon\Security\Random();
                $uuid = $random->uuid();
                
                $this->di['cookie']->set('auth', $uName . '_**_' . $_SERVER['REQUEST_TIME'] . '_**_'. $uuid, $_SERVER['REQUEST_TIME'] + 86400);
                $this->di['session']->set('uInfo', ['u_id' => $uInfo['u_id'], 'u_name' => $uInfo['u_name'], 'u_super' => $uInfo['u_super'], 'u_permi' => $uInfo['u_permi']]);
                $this->di['redis']->setex('admin:user:' . $uInfo['u_id'], 86400 , $uuid);
                
                $this->logContent = '用户登录[id]-'.$uInfo['u_id'];
                return $this->helper->resRet(['url' => '/index/index']);
            
            } else {
                return $this->helper->resRet('token失效，重新登录', 500); 
            }
        } else {
            $this->di['view']->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            )->enable();
        }
    }

    public function logoutAction()
    {
        $this->logic->logout();
        header("Location: /auth/login");
    }

    public function captchaAction()
    {
        $this->view->disable();
        // 获取验证码
        $captcha = new \Components\Utils\Captcha();
        $captcha->doimg(115);
        $this->session->set('captcha', $captcha->getCode());
    }

    public function tokenAction()
    {
        return  $this->helper->resRet(['key' => $this->security->getTokenKey(), 'value' => $this->security->getToken()], 200);
    }

}
