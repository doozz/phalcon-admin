<?php

class AdminModel extends ModelBase
{
    protected $table = 'adm_users';
   
    public function __construct()
    {
        parent::__construct();
    }

    public function getByName($uName)
    {
        $this->query('SELECT * FROM '.$this->table.' WHERE `u_name` = ? and u_status = 1 LIMIT 1 ', [$uName]);

        return $this->getRow();
    }

    public function getById($uId)
    {
        $this->query('SELECT * FROM '.$this->table.' WHERE `u_id` = ? and u_status = 1 LIMIT 1 ', [$uId]);
        return $this->getRow();
    }

    public function updataPass($uId,$pass)
    {
        return $this->update(['u_pass'=>$pass],['condition' => 'u_id = ?','values'=>[$uId]]);
    }

    public function updateLoginInfo($adminId, $upData)
    {

        return $this->update($upData, ['condition' => 'u_id = ?', 'values' => [$adminId]]);
    }

    public function getList()
    {
        $sql = 'SELECT u.*,g.pg_name  FROM `adm_users` as u LEFT JOIN `adm_perm_groups` as g ON u.u_permi = g.pg_id ORDER BY `u_id` DESC';
        $this->query($sql);
        return $this->getAll();
    }

    public function addAdmin($data)
    {
        return $this->insert($data);
    }

    public function edit($data, $adminId)
    {
        $this->table = 'adm_users';
        $this->update($data,['condition' => 'u_id = ?', 'values' => [$adminId]]);
        return true;
    }

    public function delAdmin($adminId)
    {
        if(!$this->delete(['condition' => 'u_id = ? ', 'values' => [$adminId]]))
            return false;

        return true;
    }
}
