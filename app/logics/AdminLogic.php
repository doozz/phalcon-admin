<?php

class AdminLogic extends LogicBase
{
    public function __construct()
    {
        $this->model = new AdminModel;
    }

    public function login($uName, $pwd)
    {
        if (!$user = $this->model->getInfoByName($uName, 'u_id, u_name, u_pass, u_status,u_super '))
            return false;

        if ($user['au_status'] != 1)
            return false;

        // 验证密码
        if (!password_verify($pwd, $user['au_pass']))
            return false;

        return $user;
    }

    public function getByName($uName)
    {
        return $this->model->getByName($uName);
    }

    public function getById($uId)
    {
        return $this->model->getById($uId);
    }

    public function updateLoginInfo($uId)
    {
        $upData = array(
            'u_lastlogintime' => $_SERVER['REQUEST_TIME'],
            'u_lastloginip' => Components\Utils\Helper::getIP()
        );
        return $this->model->updateLoginInfo($uId, $upData);
    }

    public function updataPass($uId,$pass)
    {
        return $this->model->updataPass($uId,$pass);
    }

    public function logout()
    {
        $this->di['cookie']->get('auth')->delete();
        $this->di['redis']->delete('user:' . $this->di['session']->get('uInfo')['u_id']);
        $this->di['session']->remove('uInfo');
    } 

    public function getList()
    {
        return $this->model->getList();
    }

    public function add($data)
    {
        $data['u_pass'] =  password_hash($data['u_pass'],PASSWORD_DEFAULT);
        $data['u_addtime'] = time();  // 注册时间
        $data['u_status'] = 1;
        $data['u_super'] = 0;
        return $this->model->addAdmin($data);
    }

    public function edit($adminId, $username, $password = '', $groupId)
    {
        if ($password)
            $data['u_pass'] = password_hash($password,PASSWORD_DEFAULT);
        
        if ($username)
            $data['u_name'] = $username;

        if ($groupId)
            $data['u_permi'] = $groupId;
        return $this->model->edit($data, $adminId);
    }

    public function del($adminId)
    {
        return $this->model->delAdmin($adminId);
    }

       
}