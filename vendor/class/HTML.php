<?php

class HTML
{
	public static function createElement($tagName)
	{
		return new Node('<'.$tagName.'></'.$tagName.'>');
	}
}