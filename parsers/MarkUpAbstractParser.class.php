<?php


abstract class MarkUpAbstractParser
{
	public static $tags_to_save = array();
	
	protected $lang;
	
	protected $ecarted_tags = array();
	
	final public function __construct()
	{
		global $LANG;
		$this->lang = $LANG;
	}
	
	/**
	 * Permet de parser des blocs imbriqu�s
	 * 
	 * @param	regex		Premi�re ligne de la regex
	 * @param	regex		Pattern complet de la regex
	 * @param	string		M�thode de callback � lancer
	 * @param	string		Contenu � parser
	 * @return	string		Contenu Pars�
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
	 * Permet de sauvegarder du contenu afin qu'il ne soit pas pars�, comme du code
	 * 
	 * @param	string		Contenu � parser
	 * @param	regex		Pattern du contenu � sauvegarder
	 */
	public function save_tags($content, $pattern)
	{
		if (preg_match_all($pattern, $content, $matches))
		{
			
			$i = 0;
			foreach ($matches[0] as $occurence)
			{
				$this->ecarted_tags[$i] = $occurence;
				$content = preg_replace($pattern, "\n" . '<{' . get_class($this) . '}>' . $i . '<{/' . get_class($this) . '}>' . "\n", $content, 1);
				$i++;
			}
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

}
