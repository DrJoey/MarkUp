<?php


class MarkUpExtensionPointProvider extends ExtensionPointProvider
{
   	public function __construct()
    {
        parent::__construct('MarkUp');
    }
    
	public function content_formatting()
	{
		return new MarkUpContentFormattingExtensionPoint();
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('markup.css');
		return $module_css_files;
	}
}
?>