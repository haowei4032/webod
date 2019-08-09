<?php

class NodeStringMap implements ArrayAccess
{
	
	public function __construct(Node &$node)
	{
		return $this->getNode($node);
	}

	public function getNode(Node &$node = null)
	{
		static $ref = null;
		if (!$node) {
			if ($ref) return $ref;
		}else {
			$ref = $node;
		}
		return $ref;
	}

	public function offsetExists ($offset ) {
		return isset($this->{$offset});
	}

	public function offsetGet ($offset ) {
		return isset($this->{$offset}) ? $this->{$offset} : null;
	}

	public function offsetSet ($offset , $value )
	{
		$this->getNode()->setAttribute('data-'.$offset, $value);
	}

	public function offsetUnset ($offset )
	{
		unset($this->{$offset});
	}

	public function __set($name, $value)
	{
		$this->getNode()->setAttribute('data-'.$name, $value);
	}

}