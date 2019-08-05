alter table `Mapiactivity`.`starling_user` add `city_name` varchar(20) not null default '' after `human_id`;
alter table `Mapiactivity`.`starling_task` add `city_name` varchar(20) not null default '' after `status`,add `channel` varchar(20) not null default '' after `city_name`;
alter table `Mapiactivity`.`starling_apply` add `salt` char(4) not null default '' after `total`,add `secret` varchar(32) not null default '' after `salt`,ADD INDEX `secret`(`secret`);
ALTER TABLE `starling_apply` ADD INDEX `secret`(`secret`);


select * from Mapi.user_collect_75 where user_id = 401975 and cancel = 0 order by updated_at desc;

select status from Laputa.rooms where id in (767,24172,121391);


刷新详情页es数据步骤

1、打开http://172.18.130.7:81//#/homeIndex
2、选择《前台研发业务》，选择第31项《laputa_room_detail》，禁用
3、点击《配置》，选择《room》点击查看配置，啥也不改，拉到最底部，点击《保存配置》点击《保存配置》注意这里点两次，每次都要等待提示你保存成功。。！！！注意注意
4、返回到列表，选择《前台研发业务》，选择第31项《laputa_room_detail》启用，然后点击《全量同步》，在列表里找到时间最近的一项，点《同步》
完成

假如我们有30万张图片，我要掉你接口把30万张图片原来的bucket名字传给你， 完了你生成新的 通知给我我要保存起来是吗？

select a.*, b.* from Mapi.subscribe_relate as a left join Mapi.subscribe as b on a.subscribe_id = b.id where a.city_id = 1 and a.uid = 401975 and a.deleted = 'N' order by a.modtime desc limit 10
