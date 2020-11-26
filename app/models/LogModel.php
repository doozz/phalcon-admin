<?php

class LogModel extends ModelBase
{
    protected $table = 'adm_action_log';
    
    public function lists($conditions, $perRows, $perpage)
    {
        $where = ' 1 = 1 ';
        foreach($conditions as $key=>$condition)
        {
            if (!empty($condition))
            {
                switch ($key)
                {
                    case 'startTime':
                        $where .=" AND al_logtime >  ".$condition;
                        
                    break;
                    case 'endTime':
                        $where .=" AND al_logtime <  ".$condition;
                        
                    break;
                    case 'nick':
                        $where .= " AND al_u_name = '".$condition."'";
                    break;
                }
            }
        }
        $sql = 'select * FROM '. $this->table . ' WHERE '.$where.' ORDER BY al_id DESC limit '.$perRows .','.$perpage;
        $this->query($sql);
        return $this->getAll();
    }

    public function nums($conditions)
    {
        $where = ' 1 = 1 ';
        foreach($conditions as $key=>$condition)
        {
            if (!empty($condition))
            {
                switch ($key)
                {
                    case 'startTime':
                        $where .=" AND al_logtime >  ".$condition;
                       
                    break;
                    case 'endTime':
                        $where .=" AND al_logtime <  ".$condition;
                     
                    break;
                    case 'nick':
                        $where .= " AND al_u_name = '".$condition."'";
                    break;
                }
            }
        }
        $sql = 'select count(al_id) as total from ' . $this->table . ' WHERE '.$where;
        $this->query($sql);
        return $this->getRow();
    }
    public function addInfo($data)
    {
        return $this->insert($data);
    }

    
   
}