<?php

use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    public function change()
    {
       $this->execute("
      SET NAMES utf8mb4;
      SET FOREIGN_KEY_CHECKS = 0;
      
      -- ----------------------------
      -- Table structure for adm_action_log
      -- ----------------------------
      DROP TABLE IF EXISTS `adm_action_log`;
      CREATE TABLE `adm_action_log` (
        `al_id` int(11) NOT NULL AUTO_INCREMENT,
        `al_adm_id` int(11) NOT NULL,
        `al_site_id` smallint(6) NOT NULL,
        `al_controller` varchar(20) NOT NULL,
        `al_action` varchar(20) NOT NULL,
        `al_content` varchar(255) NOT NULL,
        `al_logtime` int(11) NOT NULL,
        `al_logip` varchar(18) NOT NULL,
        `al_u_name` varchar(20) NOT NULL,
        `al_pg_id` int(11) NOT NULL,
        PRIMARY KEY (`al_id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COMMENT='后台操作日志-adm_action_log';
      
      -- ----------------------------
      -- Table structure for adm_menus
      -- ----------------------------
      DROP TABLE IF EXISTS `adm_menus`;
      CREATE TABLE `adm_menus` (
        `m_id` int(11) NOT NULL AUTO_INCREMENT,
        `m_name` varchar(20) NOT NULL,
        `m_url` varchar(125) NOT NULL COMMENT '如果为#则为主菜单',
        `m_parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '如果该记录为子菜单，则该字段必须有值',
        `m_site` tinyint(2) DEFAULT NULL,
        `m_controller` varchar(20) DEFAULT NULL,
        `m_action` varchar(20) DEFAULT NULL,
        `m_dis` tinyint(1) DEFAULT '1' COMMENT '1-显示   0-不显示',
        `m_icon` varchar(125) NOT NULL,
        PRIMARY KEY (`m_id`) USING BTREE
      ) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8 COMMENT='后台菜单-adm_menu';
      
      -- ----------------------------
      -- Table structure for adm_perm_groups
      -- ----------------------------
      DROP TABLE IF EXISTS `adm_perm_groups`;
      CREATE TABLE `adm_perm_groups` (
        `pg_id` int(11) NOT NULL AUTO_INCREMENT,
        `pg_name` varchar(20) NOT NULL,
        `pg_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-不可用 1-正常',
        `pg_custom` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-管理员设置  1-自定义',
        `pg_config` text CHARACTER SET utf8 NOT NULL,
        PRIMARY KEY (`pg_id`) USING BTREE
      ) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COMMENT='权限组-adm_perm_groups';
          
      SET FOREIGN_KEY_CHECKS = 1;
      
    ");
    }
}



