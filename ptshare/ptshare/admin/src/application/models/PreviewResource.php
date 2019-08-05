<?php

class PreviewResource extends  Table
{
	public function __construct($table = 'preview_resource', $primary = 'id')
	{
		$this->setDBConf("MYSQL_CONF_MALL");
		$this->setTableName($table);
		$this->setPrimary($primary);
	}

	public function create($type, $cover)
	{
		$info = [
				'type' 		=> $type,
				'url'		=> $cover,
				'addtime'	=> date("Y-m-d H:i:s"),
				'is_cover'	=> 1,
				'status'	=> 200,
				'sellid'	=> 0,
		];
		return $this->insert($this->getTableName(), $info);
	}

	public function relation($resource_id, $packageid)
	{
		$info = [
				'package_id' 	=> $packageid,
				'preview_id'	=> $resource_id,
		];

		return $this->insert('package_previews', $info);
	}
}