<?php

class MarkUpListsParser extends MarkUpAbstractParser
{
	public function parse($content)
	{
		$content = $this->preg_imbricked_block("#\n *\-\- ?\n#is", "#\n *\-\- ?\n(.+)\n *\-\-#is", "ulists_parse", $content);
		
		$content = $this->preg_imbricked_block("#\n *\+\+ ?\n#is", "#\n *\+\+ ?\n(.+)\n *\-\-#is", "olists_parse", $content);

		return $content;
	}
	
	protected function ulists_parse($matches)
	{
		$lines = explode("\n", $matches[1]);
		
		$content = "<ul>\n";
		$content .= $this->parse_content_list($lines);
		
		return $content . "</ul>\n";
	}
	
	protected function olists_parse($matches)
	{
		$lines = explode("\n", $matches[1]);
		
		$content = "<ol>\n";
		$content .= $this->parse_content_list($lines);
		
		return $content . "</ol>\n";
	}
	
	protected function parse_content_list($lines)
	{
		$content = "";
		
		foreach ($lines as $key => $line)
		{
			if (preg_match("# *\* ?(.*)#i", $line))
			{
				$line = preg_replace("# *\* ?(.*)#i", "<li>$1</li>\n", $line);
			}
			else
			{
				if (substr($content, -6) === "</li>\n")
				{
					$content = substr($content,0,-6) . "\n";
				}
				$line = $line . "\n";
			}			
			$content .= $line;
		}
		return $content;
	}
	
}
