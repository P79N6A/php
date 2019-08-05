<?php
class AppConfigController extends BaseController
{

    public function listAction()
    {
        $page = $this->getRequest('page', 1);
        $tag = $this->getRequest('tag');
        $num = 20;

        $configModel = new ConfigModel();
        list($total, $configs) = $configModel->listConfigs($page, $num, $tag);
        $data = $this->renderList($configs, $total);

        $mutipage = $this->mutipage($data['total'], $page, $num, '', "/AppConfig/list/");
        $this->assign("mutipage", $mutipage);

        $this->assign("data", $data);

        $this->display("include/header2.html", "app/index.html", "include/footer.html");
    }

    public function historyAction()
    {
        $id = $this->getParam('id');

        Interceptor::ensureNotFalse(is_numeric($id), ERROR_PARAM_INVALID_FORMAT, 'id');
        $configModel = new ConfigModel();
        $history = $configModel->history($id);
        $res = array('list' => $history);

        if ($res['list']) {
            $this->success('', $res);
            return;
        } else {
            $this->error('获取历史记录失败');
            return;
        }
    }

    public function nameAction()
    {
        $page = $this->getRequest('page', 1);
        $name = $this->getRequest('name');
        $num = 100;

        $configModel = new ConfigModel();
        list($total, $configs) = $configModel->listByName($region, $name, $page, $num);
        $data = $this->renderList($configs, $total);
        
        $mutipage = $this->mutipage($data['total'], $page, $num, '', "/AppConfig/name/");
        $this->assign("mutipage", $mutipage);

        $this->assign("data", $data);

        $this->display("include/header2.html", "app/index.html", "include/footer.html");
    }

    public function searchAction()
    {
        $page = $this->getRequest('page', 1);
        $keyword = $this->getRequest('keyword');
        $num = 100;

        $configModel = new ConfigModel();
        list($total, $configs) = $configModel->search($keyword, $page, $num);

        $data = $this->renderList($configs, $total);

        $mutipage = $this->mutipage($data['total'], $page, $num, '', "/AppConfig/search/");
        $this->assign("mutipage", $mutipage);

        $this->assign("data", $data);

        $this->display("include/header2.html", "app/index.html", "include/footer.html");
    }

    public function addAction()
    {
        $configModel = new ConfigModel();

        if ($this->isPost()){
            $region = trim($this->getParam('region'));
            $name = trim($this->getParam('name'));
            $value = $this->getParam('value');
            $platform = $this->getParam('platform');
            $version = $this->getParam('version');
            $remark = $this->getParam('remark');
            $expire = $this->getParam('expire');
            $tag = $this->getParam('tag');
            $schema = $this->getParam('schema', '');
            $adduser = $this->getParam('adduser', '');

            $versionmin = $version['min'];
            $versionmax = $version['max'];

            if (ConfigModel::convertVersion($versionmax) && ConfigModel::convertVersion($versionmin) > ConfigModel::convertVersion($versionmax)) {
                $this->error('version 错误');
            }

            if ($configModel->addConfig($region, $name, $value, $expire, $platform, $versionmin, $versionmax, $remark, $tag, $schema, $adduser)) {
                $operate = new Operate();
                $operate->add($this->adminid, 'config_add', 0, 0, "", json_encode(array($region, $name, $value, $expire, $platform, $versionmin, $versionmax, $remark, $tag, $schema, $adduser)), "", 1);

                $this->success('添加成功');

            }
            $this->error('添加失败');
        }
        $this->assign('platforms', $configModel->getAllPlatforms());

        $this->display("include/header2.html", "app/add.html", "include/footer.html");
    }

    public function updateAction()
    {
        $ids = $this->getParam('id');
        $regions = $this->getParam('region');
        $name = $this->getParam('name');
        $value = $this->getParam('value');
        $platforms = $this->getParam('platform');
        $versions = $this->getParam('version');
        $remark = $this->getParam('remark');
        $expire = $this->getParam('expire');
        $tag = $this->getParam('tag');
        $schema = $this->getParam('schema', '');
        $adduser = $this->getParam('adduser', '');

        if (count($platforms) !== count(array_unique($platforms))) {
            $this->render(array(), 1, '平台不允许相同');
        }

        foreach ($ids as $id) {
            $region = $regions[$id];
            $platform = $platforms[$id];
            $versionsmin = $versions[$id]['min'];
            $versionsmax = $versions[$id]['max'];

            if (ConfigModel::convertVersion($versionsmax) && ConfigModel::convertVersion($versionsmin) > ConfigModel::convertVersion($versionsmax)) {
                $this->error('version 错误');
            }

            $configModel = new ConfigModel();

            if (!$configModel->updateConfig($id, $region, $name, $value, $expire, $platform, $versionsmin, $versionsmax, $remark, $tag, $schema, $adduser, $this->adminid)) {
                $this->error('修改失败');
            }

            $operate = new Operate();
            $operate->add($this->adminid, 'config_edit', 0, $id, "", json_encode(array($id, $region, $name, $value, $expire, $platform, $versionsmin, $versionsmax, $remark, $tag, $schema, $adduser)), "", 1);
        }

        $this->success('修改成功');
    }

    public function deleteAction()
    {
        $ids = $this->getParam('id');
        Interceptor::ensureNotEmpty($ids, ERROR_PARAM_NOT_EXIST, 'id');

        foreach ($ids as $id){
            $configModel = new ConfigModel();
            $configModel->delConfig($id);
        }

        $operate = new Operate();
        $operate->add($this->adminid, 'config_del', 0, $id, "", json_encode($configModel->getRecord($id)), "", 1);
        $this->render(array(), 0, '刪除成功');
    }

    private function renderList($configs, $total)
    {
        // 检查是否有相同的name，存在相同平台的badcase，这种不能合并展示
        $test = array();
        $badcase = array();
        foreach ($configs as $k => $v) {
            $key = $v['name'] . '_' . md5($v['value'] . $v['remark'] . $v['tag']);
            if (++$test[$key][$v['platform']] > 1) {
                $badcase[$key] = true;
            }

        }

        $combined = array();
        $i = 0;
        foreach ($configs as $k => $v) {
            $key = $v['name'] . '_' . md5($v['value'] . $v['remark'] . $v['tag']);
            if (isset($badcase[$key])) {
                $key = $v['name'] . '_' . $i++;
            }
            if (isset($combined[$key])) {
                $original = $combined[$key];
                if ($original['value'] == $v['value'] && $original['remark'] == $v['remark']) {
                    if (in_array($v['platform'], $combined[$key]['platforms'])) {
                        $badcase[$v['name']] = true;
                    } else {
                        array_push($combined[$key]['ids'], $v['id']);
                        array_push($combined[$key]['regions'], $v['region']);
                        array_push($combined[$key]['platforms'], $v['platform']);
                        array_push($combined[$key]['minversions'], $v['minversion']);
                        array_push($combined[$key]['maxversions'], $v['maxversion']);
                    }
                }
            } else {
                $combined[$key] = $v;
                $combined[$key]['ids'] = array($v['id']);
                $combined[$key]['regions'] = array($v['region']);
                $combined[$key]['platforms'] = array($v['platform']);
                $combined[$key]['minversions'] = array($v['minversion']);
                $combined[$key]['maxversions'] = array($v['maxversion']);
            }
        }

        $list = array();
        $list['combined'] = $combined;

        $configModel = new ConfigModel();
        $tags = $configModel->getAllTags();
        $regions = $configModel->getAllRegions();
        $platforms = $configModel->getAllPlatforms();

        $list['regions'] = $regions;
        $list['platforms'] = $platforms;
        $list['currentTag'] = '';
        $list['tags'] = $tags;
        $list['total'] = $total;
        $list['list'] = $configs;

        return $list;
    }

    public function fixOnlineIosAction()
    {
        $this->display("include/header2.html", "app/fix_online.html", "include/footer.html");
    }

    public function fixOnlineAction()
    {
        $config_list = array(
            '384'=>'china',
            '379'=>'abroad',
            '378'=>'abroad',
            '376'=>'china',
            '375'=>'china',
            '377'=>'abroad',
            '374'=>'china',
            '393'=>'china',
            '395'=>'abroad',
            '733'=>'china',
        );
        $versions = $this->getParam('version');

        $config_model = new ConfigModel();
        foreach($config_list as $id=>$region){
            $info = $config_model->getConfig($id, $region);
            $config_model->updateConfig($id, $region, $info['name'], $info['value'], $info['expire'], $info['platform'], $versions, $info['maxversion'], $info['remark'], $info['tag'], $info['jsonschema'],$this->adminid,$this->adminid);
        }

        $this->success('修改成功');
    }
}
