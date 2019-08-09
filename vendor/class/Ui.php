<?php

class Ui extends Facade
{
    protected function init(...$argv)
    {
        // TODO: Implement init() method.
    }

    /**
     * @param string $label
     * @param string|null $url
     * @param array $attributes
     * @param bool $disabled
     * @return Node
     * @throws ErrorException
     */
	public static function Button($label, $url = null, array $attributes = [], $disabled = false)
	{
		$disabled = boolval($disabled);
		$node = new Node('<a/>');
		$node->innerHTML = $label;
		$node->setAttribute('class', 'button');
		if ($disabled) {
			$node->setAttribute('href', 'javascript:void(0)');
			$attributes['disabled'] = 'disabled';
		} else {
			$node->setAttribute('href', $url);
		}
		foreach ($attributes as $attr => $value) $node->setAttribute($attr, $value);
		return $node;
	}

    /**
     * @param $href
     * @return Node
     * @throws ErrorException
     */
	public static function Css($href)
    {
        $node = new Node('<link/>');
        $node->setAttribute('href', $href);
        $node->setAttribute('type', 'text/css');
        $node->setAttribute('rel', 'stylesheet');
        return $node;
    }
}