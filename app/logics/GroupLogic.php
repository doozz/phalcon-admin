<?php

class GroupLogic extends LogicBase
{
    public function __construct()
    {
        $this->model = new GroupModel;
    }

    public function getlist($custom = 0)
    {
        return $this->model->getlist($custom);
    }

    public function isExist($gName)
    {
        return $this->model->isExist($gName);
    }

    public function getGroup($gId)
    {
        return $this->model->getGroup($gId);
    }

    public function getGroupUser($gId)
    {
        return $this->model->getGroupUser($gId);
    }

    public function add($gName)
    {
        return $this->model->add($gName);  
    }

    public function edit($gId, $gName)
    {
        return $this->model->edit($gId, $gName);
    }

    public function del($gId)
    {
        return $this->model->del($gId);
    }

    public function setPermi($gId, $config = [])
    {
        $insVal['pg_id'] = $gId;
        $insVal['pg_config'] = json_encode($config, JSON_UNESCAPED_UNICODE);

        return $this->model->setPermi($gId, $insVal);
    }
}