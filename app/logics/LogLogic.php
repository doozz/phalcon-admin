<?php

class LogLogic extends LogicBase
{
    public function __construct()
    {
        $this->logModel = new LogModel;
    }

    /**
     * 分页获取列表
     * @param  integer $currentPage [description]
     * @param  integer $perRows   [description]
     * @return [type]           [description]
     */
     public function detail($conditions,$page, $perpage)
    {
        $perRows = ($page - 1) * $perpage;
        $res['list'] = $this->logModel->lists($conditions, $perRows, $perpage);
        $res['total'] = $this->logModel->nums($conditions)['total'];
        return $res;
    }

    /**
     * 添加日志信息
     * @param [type] $adminId      [description]
     * @param [type] $siteId     [description]
     * @param [type] $controller [description]
     * @param [type] $action     [description]
     * @param [type] $content    [description]
     * @param [type] $logip      [description]
     */
    public function addLog($adminId,$uname, $controller, $action, $logip, $content = '')
    {
        $data['al_adm_id'] = $adminId;
        $data['al_controller'] = $controller;
        $data['al_action'] = $action;
        $data['al_content'] = $content;
        $data['al_logtime'] = time();
        $data['al_logip'] = $logip;
        $data['al_u_name'] = $uname;
        return $this->logModel->addInfo($data);
    }

    /**
     * 获取日志记录总数
     * @return [type] [description]
     */
    public function getTotal($siteId = false, $uName = false, $startTime = 0, $endTime = 0)
    {
        return $this->logModel->getTotalNum($siteId, $uName, $startTime, $endTime)['total'];
    }

    /**
     * 删除日志
     * @param  [type] $adminId [description]
     * @return [type]          [description]
     */
    // public function delLog($logIds)
    // {
    //     return $this->logModel->delInfo($logIds);
    // }

}