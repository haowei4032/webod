<?php

class HTML
{
    /**
     * @param $tagName
     * @return Node
     * @throws ErrorException
     */
	public static function createElement($tagName)
	{
		return new Node('<'.$tagName.'></'.$tagName.'>');
	}
}