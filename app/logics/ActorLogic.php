<?php 

class ActorLogic extends LogicBase
{
    public function __construct()
    {
        $this->model = new ActorModel;
    }

    /**
     * 获取列表
     * @return [type]           [description]
     */
    public function lists($page, $perpage)
    {
        $perRows = ($page - 1) * $perpage;
        $res['list'] = $this->model->lists($perRows, $perpage);
        $res['total'] = $this->model->nums()['total'];
        return $res;
    }

    public function editStatus($aId, $status)
    {
        return $this->model->editStatus($aId, $status);
    }
    
    /**
     * 添加
     * @return [type]           [description]
     */
    public function add($condition)
    {
        $condition['act_created_time'] = time();
        $condition['act_status'] = 1;
        return $this->model->add($condition);
    }

    /**
     * 编辑
     * @return [type]           [description]
     */
    public function edit($condition, $aId) 
    {
        return $this->model->edit($condition, $aId);
    }

    public function getInfo($aId)
    {
        return $this->model->getInfo($aId);
    }

    /**
     * 删除
     * @return [type]           [description]
     */
    public function del($aId) 
    {
        return $this->model->del($aId);
    }
}