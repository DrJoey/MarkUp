<?php

class MarkUpUnparser extends ContentFormattingUnparser
{
	/** @var	\MarkUpAbstractParser objects */
	protected static $parsers = null;

	public function __construct()
	{
		if (static::$parsers === null)
		{
			$this->initParsers();
		}
	}
	
	public function parse()
	{
		$this->content = TextHelper::html_entity_decode($this->content);
		
		foreach (static::$parsers as $parser)
		{
			$this->content = $parser->unparse($this->content);
		}
	}
	
	private function initParsers()
	{
		static::$parsers = array(
			new MarkUpCodeParser(),
			new MarkUpLinksParser(),
			new MarkUpQuotesParser(),
			new MarkUpListsParser(),
		);
	}

	
}
?>