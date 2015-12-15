<?php

class MarkUpCodeParser extends MarkUpAbstractParser
{
	/**
	* Code avec type de code :
	* ``` php:
	* code php
	* ```
	*/
	const FULLTYPECODE_REGEX = "#\n *``` ?(\w+):\n(.+)\n *```#isU";
	
	/**
	* Code sans type de code :
	* ```
	* code
	* ```
	*/
	const FULLCODE_REGEX = "#\n *``` ?\n(.+)\n *```#isU";
	
	public static $parse_tags_to_save = array(
		self::FULLTYPECODE_REGEX => 'fulltypecode',
		self::FULLCODE_REGEX => 'fullcode',
	);
	
	public function parse($content)
	{
		return $content;
	}
	
	public function after_parse($content)
	{
		$content = $this->preg_imbricked_block("#\n *``` ?(\w+):#isU", self::FULLTYPECODE_REGEX, "fulltypecode_parse", $content);

		$content = $this->preg_imbricked_block("#\n *``` ?#isU", self::FULLCODE_REGEX, "fullcode_parse", $content);
		
		$content = preg_replace_callback("#`(.+)`#i", array($this, "code_parse"), $content);
		
		return $content ;
		
	}
	
	protected function code_parse($matches)
	{
		return '<code>' . $matches[1] . '</code>';
	}
	
	protected function fulltypecode_parse($matches)
	{
		return "\n" . '[[CODE=' . $matches[1] .']]' . $matches[2] . '[[/CODE]]' . "\n";
	}
	
	protected function fullcode_parse($matches)
	{
		return "\n" . '[[CODE]]' . $matches[1] . '[[/CODE]]' . "\n";
	}
}
