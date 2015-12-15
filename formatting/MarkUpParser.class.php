<?php

class MarkUpParser extends ContentFormattingParser
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
		
		$this->content = str_replace(array("\r\n", "\r"), "\n", $this->content);
		$this->content = "\n" . $this->content . "\n";
		
		$this->content = TextHelper::html_entity_decode($this->content);
		
		foreach (static::$parsers as $parser)
		{
			foreach ($parser::$tags_to_save as $tag)
			{
				$this->content = $parser->save_tags($this->content, $tag);
			}
		}
		
		$this->protect_content();
		
		foreach (static::$parsers as $parser)
		{
			$this->content = $parser->parse($this->content);
		}
		
		$this->parse_paragraphs();
		
		foreach (static::$parsers as $parser)
		{
			$this->content = $parser->restaure_tags($this->content);
		}
		
		foreach (static::$parsers as $parser)
		{
			if (method_exists($parser, "after_parse"))
			{
				$this->content = $parser->after_parse($this->content);
			}
		}
		
		
	
		parent::parse();

	}
	
	public function initParsers()
	{
		static::$parsers = array(
			new MarkUpCodeParser(),
			new MarkUpLinksParser(),
			new MarkUpQuotesParser(),
			new MarkUpListsParser(),
		);
	}
	
	protected function protect_content()
	{
		//Breaking the HTML code
		$this->content = TextHelper::htmlspecialchars($this->content, ENT_NOQUOTES);
		$this->content = strip_tags($this->content);

		//While we aren't in UTF8 encoding, we have to use HTML entities to display some special chars, we accept them.
		$this->content = preg_replace('`&amp;((?:#[0-9]{2,5})|(?:[a-z0-9]{2,8}));`i', "&$1;", $this->content);

		//Treatment of the Word pasted characters
		$array_str = array(
			'?', '?', '?', '?', '?', '?', '?', '?', '?',
			'?', '?', '?', '?', '?', '?', '?', '?', '?',
			'?', '?',  '?', '?', '?', '?', '?', '?', '?'
			);

			$array_str_replace = array(
			'&#8364;', '&#8218;', '&#402;', '&#8222;', '&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;',
			'&#352;', '&#8249;', '&#338;', '&#381;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;',
			'&#8211;', '&#8212;', '&#732;', '&#8482;', '&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
			);
			$this->content = str_replace($array_str, $array_str_replace, $this->content);
	}
	
	protected function parse_paragraphs()
	{
		// Supprimer lignes vides
		$this->content = preg_replace('#\n+#i', "\n", $this->content);
		
		// Ajout saut de ligne pour Regex
		$this->content = "\n" . $this->content . "\n";
		
		// Tout ce qui ne commence pas par une balise est encadré par <p>
		$this->content = preg_replace('#(\n)([^<].*?)(?=\n)#i', '\1<p>\2</p>', $this->content);
	}

}
