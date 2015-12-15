<?php

class MarkUpCodeParser extends MarkUpAbstractParser
{
	public static $tags_to_save = array(
		/**
		 * Code avec type de code :
		 * ``` php;
		 * code php
		 * ```
		 */
		"#\n *``` ?(\w+):\n(.+)\n *```#isU",
		
	);
	
	public function parse($content)
	{
		return $content;
	}
	
	protected function code_parse($matches)
	{
		return '<code>' . $matches[1] . '</code>';
	}
	
	protected function fullcode_parse($matches)
	{
		return "\n" . '[[CODE=' . $matches[1] .']]' . $matches[2] . '[[/CODE]]' . "\n";
	}
	
	public function after_parse($content)
	{
		$content = $this->preg_imbricked_block("#\n *``` ?(\w+):#isU", "#\n *``` ?(\w+):\n(.+)\n *```#isU", "fullcode_parse", $content);

		$content = preg_replace_callback("#`(.+)`#i", array($this, "code_parse"), $content);
		
		return $content ;
		
	}
	
}
