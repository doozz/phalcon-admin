<?php
class CollectModel extends ModelBase
{
    protected $table = 'cd_collect_config';

    public function lists()
    {
        $sql = 'SELECT * FROM '.$this->table .' WHERE cc_status != 5 ORDER BY cc_id DESC';
        $this->query($sql);
        return $this->getAll();
    }

    public function getInfo($id)
    {
        $sql ='SELECT * FROM '.$this->table .'  WHERE cc_id = ? AND cc_status != 5';
        $this->query($sql,[$id]);
        return $this->getRow();
    }

    public function getSource($title)
    {
        $this->table = 'cd_collect_videos';
        $sql ='SELECT * FROM '.$this->table .'  WHERE c_title LIKE "'.$title.'%"';
        $this->query($sql);
        return $this->getAll();
    }

    public function add($conditions) 
    {
        return $this->insert($conditions);
    }

   public function edit($id, $conditions)
   {
        return $this->update($conditions,['condition' => 'cc_id = ?', 'values' => [$id]]);
   }

   public function addSource($condition)
   {
        $this->table = 'cd_collect_videos';
        return $this->insert($condition);
   }

   public function getSourceById($id)
   {
        $this->table = 'cd_collect_videos';
        $sql ='SELECT * FROM '.$this->table .'  WHERE c_id = ?';
        $this->query($sql,[$id]);
        return $this->getRow();
   }

   public function updateSource($id, $condition) 
   {
    return $this->update($condition,['condition' => 'c_id = ?', 'values' => [$id]]);
   }

}

