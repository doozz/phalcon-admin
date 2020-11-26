<?php
namespace Components\Utils;

use Phalcon\Mvc\User\Component;

class Pagination extends Component
{
    public $page; //当前页码；
    public $firstRow; // 起始行数；
    public $total; //数据总数
    public $listRows; //列表每页显示行数
    public $stringSegment; //页码标示字段
    public $url; //页面地址
    public $lastPage; //最后页，也是总页数
    public $numLinks; //当前页码

    public function __construct($total, $listRows = 30, $numLinks = '', $stringSegment = 'page', $url = '')
    {
        $lastPage = ceil($total/$listRows);
        $this->lastPage = $lastPage;
        $this->page = $numLinks == false ? 1 : min((int)$numLinks,$lastPage);
        $this->total = $total;
        $this->listRows = $listRows;
        $this->stringSegment = $stringSegment;
        $this->url = $url == '' ? $_SERVER['REQUEST_URI'] : $url;
        $this->firstRow = ($this->page-1) * $listRows;
    }

    /**
     *url网址处理函数:URL后加页码查询信息待赋值
     * @return [type] [description]
     */
    protected function getNewUrl()
    {
        $url = $this->url;
        $parseUrl = parse_url($url);
        if(!empty($parseUrl['query']))
        {
            $urlQuery = $parseUrl['query']; //单独取出URL的查询字串
            //因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息
            $urlQuery = preg_replace('/(^|&)'.$this->stringSegment.'=[0-9]+/','',$urlQuery);

            //将处理后的URL的查询字串替换原来的URL的查询字串：
            $newUrl = str_replace($parseUrl['query'],$urlQuery,$url);

            //在URL后加page查询信息，但待赋值
            if($urlQuery)
            {
                 $newUrl .= '&'.$this->stringSegment;
            }
            else
            {
                $newUrl .= $this->stringSegment;
            }
         }
         else
         {
            $newUrl = $url.'?'.$this->stringSegment;
         }

        return $newUrl;
     }

    /**
     * [createLink 输出分页导航条代码]
     * @return [type] [description]
     */
    public function createLink()
    {
        $pagenav = '';
        if($this->lastPage > 1)
        {
            $url = $this->getNewUrl();
            $pagenav .= '<div class="text-right"><ul class="pagination pagination-sm no-margin "><li class=""><a href=' .$url. '=1>&lt;&lt;</a></li>';
            $prepg = $this->page-1; //上一页
            $nextpg = $this->page == $this->lastPage ? 0 : $this->page+1; //下一页
            //上一页
            if($prepg)
            {
                $pagenav .= "<li><a class='prev' href='$url=$prepg'>&lt;</a></li>";
            }
            else
            {
                $pagenav .= '';
            }

            //计算显示的页码
            if($this->page <= 2)
            {
                $startNum = 5;
                $endNum = 1;
            }
            else
            {
                $startNum = $this->page+2;
                $endNum = $this->page-2;
            }

            //计算要显示页码
            if($this->lastPage < $startNum)
            {
                $num = $this->lastPage;
            }
            else
            {
                $num = $startNum;
            }
            $page = !empty($this->request->getQuery($this->stringSegment)) ? ($this->request->getQuery($this->stringSegment)) : 1;
            //显示页面
            for ($i = $endNum; $i <= $num; $i++)
            {
                if ($i == $page)
                    $style = "style='background-color : #ECF0F5'";
                else
                    $style = '';
                if($this->page == $i)
                {
                    $pagenav .= "<li><a  href='$url=$i' $style>$i</a></li>";
                }
                else
                {
                    $pagenav .= "<li class=''><a  href='$url=$i' $style>$i</a></li>";
                }
            }

            //下一页
            if($nextpg)
            {
                $pagenav .= "<li class='next'><a href='$url=$nextpg'>&gt;</a></li>";
            }
            else
            {
                $pagenav .= '';
            }
            $pagenav .= "<li class=''><a href='$url=$this->lastPage'>&gt;&gt;</a></li></ul>";
            $pagenav .= "<div class='count-nums'>共".$this->total."条</div></div>";

            return $pagenav;
        }
     }
}