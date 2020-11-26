<?php
use Phalcon\Mvc\View;

class IndexController extends ControllerBase
{
    const NUM_PER_PAGE = 20;//每页显示数
    
    public function initialize()
    {
        $this->uId = $this->di->get('session')->get('uInfo')['u_id'];
        $this->uName = $this->di->get('session')->get('uInfo')['u_name'];
    }

    public function indexAction()
    {
        
    }

    public function checkLoginAction()
    {
        return $this->helper->resRet('ok', 200); 
    }
    

}