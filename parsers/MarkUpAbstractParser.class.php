<?php


abstract class MarkUpAbstractParser
{
	public static $parse_tags_to_save = array();
	
	public static $unparse_tags_to_save = array();
	
	protected $lang;
	
	protected $ecarted_tags = array();
	
	final public function __construct()
	{
		global $LANG;
		$this->lang = $LANG;
	}
	
	public function parse_save_tags($content)
	{
		foreach (static::$parse_tags_to_save as $pattern => $name)
		{
			$content = $this->save_tag($content, $pattern, $name);
		}
		return $content;
	}
	
	public function restaure_tags($content)
	{
		foreach ($this->ecarted_tags as $key => $value)
		{
			$content = str_replace('&lt;{' . get_class($this) . '}&gt;' . $key . '&lt;{/' . get_class($this) . '}&gt;', $value, $content);
		}
		return $content;
	}
	
	/**
	 * Permet de parser des blocs imbriqués
	 * 
	 * @param	regex		Première ligne de la regex
	 * @param	regex		Pattern complet de la regex
	 * @param	string		Méthode de callback à lancer
	 * @param	string		Contenu à parser
	 * 
	 * @return	string		Contenu Parsé
	 */
	protected function preg_imbricked_block($block_begin, $pattern, $callback, $content)
	{
		$nbr_match = preg_match_all($block_begin, $content);

		for($i=0;$i<$nbr_match;$i++)
		{
			$content = preg_replace_callback($pattern, array($this, $callback), $content);
		}
		return $content;
	}
	
	
	/**
	 * Permet de sauvegarder du contenu afin qu'il ne soit pas parsé, comme du code
	 * 
	 * @param	string		Contenu à parser
	 * @param	regex		Pattern du contenu à sauvegarder
	 */
	protected function save_tag($content, $pattern, $name)
	{
		if (preg_match_all($pattern, $content, $matches))
		{
			$i = 0;
			foreach ($matches[0] as $occurence)
			{
				$index = $name . ':' . $i;
				$this->ecarted_tags[$index] = $occurence;
				$content = preg_replace($pattern, "\n" . '<{' . get_class($this) . '}>' . $name . ':' . $i . '<{/' . get_class($this) . '}>' . "\n", $content, 1);
				$i++;
			}
		}
		return $content;
	}
	
	

}
