<?php

class MenuModel extends ModelBase
{
    protected $menutable = 'adm_menus';
    
    public function getInfo($controller, $action)
    {
        $sql = 'SELECT * FROM '.$this->menutable.' WHERE `m_controller` = ? AND `m_action` = ? LIMIT 1';
        $res = $this->query($sql, [$controller,$action]);
        return $this->getRow();
    }

    public function isExist($controller, $action)
    {
        $sql = 'SELECT * FROM '.$this->menutable.' WHERE `m_controller` = ? AND `m_action` = ? LIMIT 1';
        $res = $this->query($sql, [$controller,$action]);
        return $this->getOne();
    }

    public function add($data)
    {
        $this->table = $this->menutable;
        return $this->insert($data);
    }

    public function getList()
    {
        $sql = 'SELECT * FROM '.$this->menutable.' ORDER BY m_id asc';
        $this->query($sql);
        return $this->getAll();
    }

    public function getMenu($mId)
    {
        $sql = 'SELECT * FROM '.$this->menutable.' WHERE m_id = ? LIMIT 1';
        $this->query($sql, [$mId]);
        return $this->getRow();
    }

    public function getSubMenu($pId)
    {
        $sql = 'SELECT * FROM '.$this->menutable.' WHERE `m_parent_id` = ? LIMIT 1';
        $this->query($sql, [$pId]);
        return $this->getRow();
    }

    // public function getpermissionlists($groupId)
    // {
    //     $sql = 'SELECT * FROM adm_permissions WHERE `pg_id` = ? LIMIT 1';
    //     $this->query($sql, [$groupId]);
    //     return $this->getRow();
    // }

    public function getSubMenus($pId = 0)
    {
        $sql = 'SELECT * FROM '.$this->menutable.' WHERE `m_parent_id` = ? AND `m_dis` = 1 ORDER BY `m_id` asc';
        $this->query($sql, [$pId]);
        return $this->getAll();
    }

    public function edit($mId, $data)
    {
        $this->table = $this->menutable;
        return $this->update($data, ['condition' => 'm_id = ? ', 'values' => [$mId]]);
    }

    
    public function del($mId)
    {
        $this->table = $this->menutable;
        return $this->delete(['condition' => 'm_id = ? ', 'values' => [$mId]]);
    }

    // public function getPermiByGid($groupid)
    // {
    //     $this->query('SELECT * FROM adm_permissions WHERE `pg_id` = ? LIMIT 1', [$groupid]);
    //     return $this->getRow();
    // }
}