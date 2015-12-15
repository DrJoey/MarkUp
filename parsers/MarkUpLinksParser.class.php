<?php


class MarkUpLinksParser extends MarkUpAbstractParser
{
	
	public function parse($content)
	{
		$content = preg_replace_callback("#\[(.+)\]\((.+)\)#i", array($this, "links_parse"), $content);
		
		$content = preg_replace_callback("#[^\"](https?:\/\/|www\.)(\S+)[^\"]#is", array($this, "autolinks_parse"), $content);
		
		return $content;
	}
	
	public function after_parse($content)
	{
		return preg_replace_callback("#\#<a href=\"(.+)\">(.+)<\/a>%#i", array($this, "after_links_parse"), $content);
	}
	
	private function links_parse($matches)
	{
		return '#<a href="' . $matches[2] . '">'. $matches[1] . '</a>%';
	}
	
	private function autolinks_parse($matches)
	{
		return '#<a href="' . trim($matches[0]) . '">'. trim($matches[0]) . '</a>%';
	}
	
	private function after_links_parse($matches)
	{
		return '<a href="' . $matches[1] . '">'. $matches[2] . '</a>';
	}
	
	
}
