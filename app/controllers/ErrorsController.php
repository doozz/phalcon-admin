<?php
use Components\Utils\Helper;
use Phalcon\Mvc\View;

class ErrorsController extends ControllerBase
{

    public function show404Action()
    {
        if($this->request->isAjax())
            return $this->di['helper']->resRet('Not Found', 404);
        else 
            $this->showMsg('页面不存在', true, '/index/index');
    }

    public function show500Action($msg = NULL, $code = NULL)
    {
        if($this->request->isAjax())
            return $this->di['helper']->resRet($msg, 500);
        else
            $this->showMsg('Server Error', true, '/index/index');exit;
    }
}
