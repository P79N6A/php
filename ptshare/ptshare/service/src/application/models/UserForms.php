<?php

class UserForms
{
	static public function add($userid, $formid)
	{
		$DAOUserForms = new DAOUserForms();
		return $DAOUserForms->addForm($userid, $formid);
	}
	
}