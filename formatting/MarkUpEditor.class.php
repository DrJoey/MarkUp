<?php

class MarkUpEditor extends ContentEditor
{

	public function __construct()
	{
		parent::__construct();
		$this->template = new FileTemplate('MarkUp/editor.tpl');
	}
	public function display()
	{
		
		$this->template->put_all(array(
			'PAGE_PATH' => $_SERVER['PHP_SELF'],
			'FIELD' => $this->identifier,
			));
		return $this->template->render();
	}
}
