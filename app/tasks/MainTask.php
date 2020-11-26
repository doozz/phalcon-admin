<?php

class MainTask extends TaskBase
{
    public function initGroupAction()
    {
        $init = new StarModel();
        $init->initGroup();
        exit;
    }

    public function initRankAction()
    {
        $init = new StarModel();
        $init->initRank();
        exit;
    }

    public function editGroupAction()
    {
        $init = new StarModel();
        $init->editGroup();
        exit;
    }

    public function initDailyStatisticsAction()
    {
        $init = new StarModel();
        $init->initStatistics();
        exit;
    }
}
