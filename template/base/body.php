<?php

defined('__SECTION__') or define('__SECTION__', 1);

$section = getRequest()->getString('section', 'posts');
template($section, 'list');