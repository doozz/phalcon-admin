<?php
class ActorModel extends ModelBase
{
    protected $table = 'cd_actors';
    const NORMAL_SHOW = 1; 
    const UN_SHOW = 3; 

    public function lists($perRows, $perpage)
    { 
        $sql = 'SELECT * FROM '.$this->table. ' WHERE act_status IN (1,3) ORDER BY act_id DESC LIMIT '.$perRows.','. $perpage;
        $this->query($sql);
        return $this->getAll();
    }

    public function nums()
    {
        $sql = 'select count(*) as total from ' . $this->table . ' WHERE act_status = 1';
        $this->query($sql);
        return $this->getRow();
    }

    public function editStatus($aId, $status) 
    {
        $newStatus = $status == self::NORMAL_SHOW ? self::UN_SHOW : self::NORMAL_SHOW;   
        return $this->update(['act_status' => $newStatus], ['condition' => ' act_id = ? AND act_status = ?', 'values' => [$aId, $status]]);
    }

    public function add($condition)
    {
        return $this->insert($condition);  
    }

    public function edit($condition, $tId)
    {
        return $this->update($condition, ['condition' => 'act_id = ?', 'values' => [$tId]]); 
    }

    public function getInfo($aId)
    {
        $sql = 'SELECT * FROM '.$this->table.' WHERE act_id = ? LIMIT 1';
        $this->query($sql, [$aId]);
        return $this->getRow();
    }

    public function del($aId) 
    {
        return $this->update(['act_status' => 5], ['condition' => 'act_id = ?', 'values' => [$aId]]); 
    }

    public function adds($sql)
    {
        $this->query('INSERT INTO '.$this->table. '(act_name, act_type, act_created_time, act_status) VALUES '.$sql);
        return $this->affectRow();
    }

    public function getlists($time, $type)
    { 
        $sql = 'SELECT * FROM '.$this->table. ' WHERE act_created_time = ? AND act_type = ? AND act_status = 1';
        $this->query($sql,[$time, $type]);
        return $this->getAll();
    }
}