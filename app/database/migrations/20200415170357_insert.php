<?php

use Phinx\Migration\AbstractMigration;

class Insert extends AbstractMigration
{
    public function change()
    {
       $this->execute("
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (182, '菜单', '', 0, NULL, 'menu', 'init', 1, 'fa-book');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (183, '菜单列表', '', 182, NULL, 'menu', 'index', 1, 'fa-list');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (184, '添加', '', 183, NULL, 'menu', 'add', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (185, '删除', '', 183, NULL, 'menu', 'del', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (186, '编辑', '', 183, NULL, 'menu', 'edit', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (187, '添加子菜单', '', 183, NULL, 'menu', 'addsub', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (188, '管理员', '', 0, NULL, 'admin', 'init', 1, 'fa-user-secret');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (189, '管理员列表', '', 188, NULL, 'admin', 'index', 1, 'fa-list');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (191, '操作日志', '', 188, NULL, 'admin', 'log', 1, 'fa-pencil');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (195, '添加', '', 189, NULL, 'admin', 'add', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (196, '编辑', '', 189, NULL, 'admin', 'edit', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (197, '删除', '', 189, NULL, 'admin', 'del', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (198, '权限', '', 0, NULL, 'group', 'init', 1, 'fa-unlock-alt');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (199, '分组列表', '', 198, NULL, 'group', 'index', 1, 'fa-list');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (200, '添加', '', 199, NULL, 'group', 'add', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (201, '编辑', '', 199, NULL, 'group', 'edit', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (202, '删除', '', 199, NULL, 'group', 'del', 3, '');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (203, '权限列表', '', 199, NULL, 'group', 'permission', 3, 'fa-list');
       INSERT INTO `news`.`adm_menus`(`m_id`, `m_name`, `m_url`, `m_parent_id`, `m_site`, `m_controller`, `m_action`, `m_dis`, `m_icon`) VALUES (204, '权限设置', '', 199, NULL, 'group', 'addpermi', 3, '');
    ");
    }
}
