<?php

class Node
{
	public $outerHTML;
	public $innerHTML;
	public $innerText;

	//public $id;
	//public $className;
	public $tagName;
	public $dataset;

	private $attributes;

	public function __construct($html)
	{
		if (preg_match('#^<([a-z]+([\-\d\w]*)?)\s*([\s\S]*?)>([\s\S]*)<\/[a-z][\w\-]*>#', $html, $submatch)) {

		} elseif (preg_match('#<([a-z]+([\-\d\w]*)?)\s*(.*)\/>#', $html, $submatch)) {

		} else {
			throw new ErrorException('Not valid a HTML Node');
		}

		$this->dataset = new NodeStringMap($this);
		$this->tagName = $submatch[1];
		if (isset($submatch[4])) $this->innerHTML = $submatch[4];
		$attributes = $submatch[3];
		if ($attributes) {
			preg_match_all('#\s*([a-z][\w\-]*)\s*\=\s*"([\s\S]*?)"\s*#', $attributes, $submatch);
			foreach ($submatch[1] as $k => $v) {
				$this->setAttribute($v, $submatch[2][$k]);
			}
		}
	}

	public function querySelector($selector)
	{
		preg_match('#<'.$selector.'\s*.*?>[\s\S]*?</'.$selector.'>#', $this->innerHTML, $submatch);
		if ($submatch) return new Node($submatch[0]);
		return null;
	}

	public function querySelectorAll($selector)
	{
		preg_match_all('#<'.$selector.'\s*.*?>[\s\S]*?</'.$selector.'>#', $this->innerHTML, $submatch);
		if ($submatch) {
			$iterator = new Nodelist();
			foreach ($submatch[0] as $html) {
				$iterator->append(new Node($html));
			}
			return $iterator;
		}
		return null;
	}

	public function appendChild(Node $node)
	{
		$node = (string) $node;
		return $this->innerHTML .= $node;
	}

	public function getAttribute($name)
	{
		switch ($name) {
			case 'class':
				return $this->className;
			case 'id':
				return $this->id;
			default:
				return isset($this->attributes[$name]) ? $this->attributes[$name] : null;

		}
		return null;
	}

	public function setAttribute($name, $value)
	{
		switch ($name) {
			case 'id':
				$this->id = $value;
				$this->attributes['id'] = &$this->id;
				break;
			case 'class':
				$this->className = $value;
				$this->attributes['class'] = &$this->className;
				break;
			default:
				$this->attributes[$name] = $value;
				if (preg_match('#^data\-(.+)#', $name, $submatch)) {
					list($_, $shortName) = $submatch;
					$this->dataset->{$shortName} = &$this->attributes[$name];
				}
		}
		return $this;
	}

	public function __set($name, $value)
	{
		switch ($name) {
			case 'className':
				$this->className = $value;
				$this->attributes['class'] = &$this->className;
				break;
			case 'id':
				$this->id = $value;
				$this->attributes['id'] = &$this->id;
				break;
			default:
				$this->{$name} = $value;
		}
		return $this;
	}

	public function __get($name)
	{
		if ($name === 'outerHTML') return $this->__toString();
		if (isset($this->attributes[$name])) return $this->attributes[$name];
		return null;
	}

	public function __toString()
	{
		$attributes = [];
		ksort($this->attributes);
		foreach ($this->attributes as $name => $value) {
			$attributes[] = $name.'="'.$value.'"';
		}
		if ($attributes) $attributes = implode(' ', $attributes);
		return !in_array($this->tagName, [
            'meta',
            'hr',
            'br',
            'link',
            'input',
            'img'
        ]) ? '<'.$this->tagName.
            ($attributes ? ' '.$attributes : '') .'>'.
            $this->innerHTML . '</'.$this->tagName.'>' : '<'.$this->tagName.($attributes ? ' '.$attributes : '').'>';
	}

}