<?php

class MarkUpQuotesParser extends MarkUpAbstractParser
{
	public function parse($content)
	{

		$content = $this->preg_imbricked_block("#\n *&gt;&gt; ?(\w+):#isU", "#\n *&gt;&gt; ?(\w+):\n(.+)\n *&lt;&lt;#isU", "fullquotes_parse", $content);
		
		$content = $this->preg_imbricked_block("#\n *&gt;&gt; ?\n#isU", "#\n *&gt;&gt; ?\n(.+)\n *&lt;&lt;#isU", "quotes_parse", $content);
		
		return $content;
	}
	
	protected function quotes_parse($matches)
	{
		return '<span class="formatter-blockquote">' . $this->lang['quotation'] . ':</span><div class="blockquote">' ."\n". $matches[1] . '</div>';
	}
	
	protected function fullquotes_parse($matches)
	{
		return '<span class="formatter-blockquote">' . $matches[1] . ':</span><div class="blockquote">' . "\n" .$matches[2] . '</div>';
	}
}
