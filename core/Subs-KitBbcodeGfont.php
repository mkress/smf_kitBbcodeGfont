<?php
/*******************************************************************************
* BBCode Google Web Fonts Copyright 2012, Markus Kress - Kress.IT						   *
********************************************************************************
* Subs-KitBbcodeGfont.php														   *
********************************************************************************
* License http://creativecommons.org/licenses/by-sa/3.0/deed.de CC BY-SA 	   *
* Support for this software  http://kress.it and							   *
* http://custom.simplemachines.org/mods/index.php?mod=3393					   *
*******************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');


function kit_bbcode_gfont_bbc_codes( &$codes )
{
	global $modSettings, $context;
	
	if ( !empty($modSettings['kitbbcodegfont_guest']) && $context['user']['is_guest'] )
	{
		// hide webfonts for faster page load
		// parse empty bbcode
		$codes[] = array(
				'tag' => 'gfont',
				'type' => 'unparsed_equals',
				'test' => '[A-Za-z0-9_,\-\s]+?\]',
				'before' => '',
				'after' => ''
		);
	}
	else
	{
		// add bbcode	
		$codes[] = array(
			'tag' => 'gfont',
			'type' => 'unparsed_equals',
			'test' => '[A-Za-z0-9_,\-\s]+?\]',
			'before' => '<span style="font-family: $1;" class="bbc_font">',
			'after' => '</span>',
			'validate' => create_function('&$tag, &$data, $disabled', '
				// Access globals
				global $context;
				
				// use as temporary cache
				if ( !isset($context[\'gfont-data\']) )
				{
					$context[\'gfont-data\'] = array();
				}
				// only insert link-tag once per page
				if ( !isset($context[\'gfont-data\'][$data]) )
				{
					$tag[\'before\'] = \'<link href="http://fonts.googleapis.com/css?family=$1" rel="stylesheet" type="text/css" />\'.$tag[\'before\'];
					$context[\'gfont-data\'][$data] = $data;
				}
			')
		);
	}
}

// mod settings
function kit_bbcodegfont_mod_settings(&$config_vars)
{
	global $context, $modSettings, $txt;

	loadLanguage('KitBbcodeGfont');

	$config_vars[] = '';
	$config_vars[] = $txt['kitbbcodegfont_mod'];

	// style
	$config_vars[] = array(
		'select',
		'kitbbcodegfont_guest',
		array(
				0 => $txt['kitbbcodegfont_guest_show'],
				1 => $txt['kitbbcodegfont_guest_hide']
		),
		$txt['kitbbcodegfont_guest']
	);
}