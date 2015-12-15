<?php


class MarkUpContentFormattingExtensionPoint extends AbstractContentFormattingExtensionPoint
{
	public function get_name()
	{
		return 'MarkUp';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_parser()
	{
		return new MarkUpParser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_unparser()
	{
		return new MarkUpUnparser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_second_parser()
	{
		return new ContentSecondParser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_editor()
	{
		$editor = new MarkUpEditor();

		return $editor;
	}
}
?>