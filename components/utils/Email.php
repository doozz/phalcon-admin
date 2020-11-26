<?php
namespace Components\Utils;
use Phalcon\Mvc\User\Component;

interface Observer
{
    // 接收到通知的处理方法
    public function update(Observable $observable);
}

class Email implements Observer
{
    public function update(Observable $observable)
    {
        $state = $observable->getState();
        if ($state) {
            echo '发送邮件：您已经成功下单。';
        } else {
            echo '发送邮件：下单失败，请重试。';
        }
    }
}


class Message implements Observer
{
    public function update(Observable $observable)
    {
        $state = $observable->getState();
        if ($state) {
            echo '短信通知：您已下单成功。';
        } else {
            echo '短信通知：下单失败，请重试。';
        }
    }
}
