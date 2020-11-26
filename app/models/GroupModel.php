<?php

class GroupModel extends ModelBase
{
    protected $table = 'adm_perm_groups';
    protected $userTable = 'adm_users';
   
    public function __construct()
    {
        parent::__construct();
    }

    public function get($gId)
    {
        $this->query('SELECT * FROM '.$this->table.' WHERE `pg_id` = ? LIMIT 1', [$gId]);
        return $this->getRow();
    }

    public function getlist($custom = 0)
    {
        $sql = 'SELECT * FROM '. $this->table.' WHERE pg_custom = ? ORDER BY pg_id DESC';
        $this->query($sql, [$custom]);
        return $this->getAll(); 
    }

    public function isExist($gName)
    {
        $this->query('SELECT pg_id FROM '. $this->table .' WHERE pg_name = ? LIMIT 1', [$gName]);
        return $this->getOne();
    }

    public function getGroup($gId)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE pg_id = ? LIMIT 1';
        $this->query($sql,[$gId]);
        return $this->getRow();
    }

    public function getGroupUser($gId) {
        $sql = 'SELECT * FROM '.$this->userTable.' WHERE permi_id = ? and  u_status = 1 LIMIT 1';
        $this->query($sql, [$gId]);
        return $this->getOne();    
    }

    public function add($gName)
    {
        return $this->insert(['pg_name'=>$gName]);  
    }

    public function edit($gId, $gName)
    {
        return $this->update(['pg_name' => $gName],['condition' => 'pg_id = ? ', 'values' => [$gId]]); 
    }

    public function del($gId)
    {
        return $this->delete(['condition' => 'pg_id = ? ', 'values' => [$gId]]);  
    }

    public function setPermi($gId, $config=[])
    {
        if ($gId == 'x') {
            return $this->insert(['pg_name'=>'自定义','pg_custom' => 1, 'pg_config' => $config['pg_config']]); 
        } else {
            return $this->update($config, ['condition' => 'pg_id = ? ', 'values' => [$gId]]);
        }
    }
}
