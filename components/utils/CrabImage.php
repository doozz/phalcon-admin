<?php

namespace Components\Utils;

use Phalcon\Mvc\User\Component;

class CrabImage extends Component
{
    public function crabImage($imgUrl , $name = '')
    {
        if (empty($imgUrl)) {
            return false;
        }

        //获取图片信息大小
        $imgSize = getImageSize($imgUrl);
        if (!in_array($imgSize['mime'], array('image/jpg', 'image/gif', 'image/png', 'image/jpeg'), true)) {
            return false;
        }

        //获取后缀名
        $_mime = explode('/', $imgSize['mime']);
        $_ext = '.'.end($_mime);
    
        $extName = end(explode('.', $imgUrl));
        $md5Time = substr(md5(uniqid(true)), 8, 16);
        $fileName= $md5Time.'.'.$extName;
        if ($name) {
            $fileName = $name.'.'.$extName;
        }

        //$dir = $this->helper->setDir(time());
        $saveDir = __DIR__.'/../../' .ltrim($this->di['config']['uploadImgPath'], '/');
       
        //开始攫取
        ob_start();
        readfile($imgUrl);
        $imgInfo = ob_get_contents();
        ob_end_clean();

        if (!file_exists($saveDir)) {
            mkdir($saveDir, 0777, true);
        }
        $fp = fopen($saveDir.$fileName, 'a');
        $imgLen = strlen($imgInfo);    //计算图片源码大小
        if ( $imgLen < 1024 * 12) {
            return false;
        }
        $_inx = 1024;   //每次写入1k
        $_time = ceil($imgLen/$_inx);
        if ($imgLen > $this->di['config']['allowedFileSize']) {
            return false;
        }
        for ($i=0; $i<$_time; $i++) {
            fwrite($fp, substr($imgInfo, $i*$_inx, $_inx));
        }
        fclose($fp);

        return 'images/'.$fileName;
    }
}
