<?php
/*******************************************************************************
* BBCode Google Web Fonts Copyright 2012, Markus Kress - Kress.IT						   *
********************************************************************************
* Subs-KitBbcodeGfont.php														   *
********************************************************************************
* License http://creativecommons.org/licenses/by-sa/3.0/deed.de CC BY-SA 	   *
* Support for this software  http://kress.it and							   *
* http://custom.simplemachines.org/mods/index.php?mod=3478					   *
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
				global $context, $modSettings;
				
				// use as temporary cache
				if ( !isset($context[\'gfont-data\']) )
				{
					$context[\'gfont-data\'] = array();
				}
				// only insert link-tag once per page
				if ( !isset($context[\'gfont-data\'][$data]) )
				{
					// build charsets
					$charsets = array(\'latin\');
					if ( !empty($modSettings[\'kitbbcodegfont_charset_latin_ext\']) )
					{
						$charsets[] = \'latin-ext\';
					}
					if ( !empty($modSettings[\'kitbbcodegfont_charset_greek\']) )
					{
						$charsets[] = \'greek\';
						$charsets[] = \'greek-ext\';
					}
					if ( !empty($modSettings[\'kitbbcodegfont_charset_cyrillic\']) )
					{
						$charsets[] = \'cyrillic\';
						$charsets[] = \'cyrillic-ext\';
					}
					
					$tag[\'before\'] = \'<script type="text/javascript">
					WebFontConfig = {
						google: { families: [ "\' . urlencode($data) . \'::\' . implode(\',\', $charsets) . \'"] }
					};
					(function() {
						var wf = document.createElement("script");
						wf.src = ("https:" == document.location.protocol ? "https" : "http") +
							"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
						wf.type = "text/javascript";
						wf.async = "true";
						var s = document.getElementsByTagName("script")[0];
						s.parentNode.insertBefore(wf, s);
					})(); 
					</script>\'.$tag[\'before\'];
					$context[\'gfont-data\'][$data] = $data;
				}
			')
		);
	}
}

// bbc button
function kit_bbcode_gfont_bbc_buttons(&$bbcTags)
{
	loadLanguage('KitBbcodeGfont');
	
	$newCode = array(array(
			'image' => 'gfont',
			'code' => 'gfont',
			'before' => '[gfont=Finger Paint]',
			'after' => '[/gfont]',
			'description' => $txt['kitbbcodegfont_bbc_gfont']
	));
	
	array_splice( $bbcTags[0], 4, 0, $newCode );
}

// mod settings
function kit_bbcode_gfont_mod_settings(&$config_vars)
{
	global $context, $modSettings, $txt;

	loadLanguage('KitBbcodeGfont');

	$config_vars[] = '&nbsp;';
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
	
	$config_vars[] = $txt['kitbbcodegfont_mod_charsets'];
	//charsets
	$config_vars[] = array(
			'check',
			'kitbbcodegfont_charset_latin_ext',
			'',
			$txt['kitbbcodegfont_charset_latin_ext']
	);
	$config_vars[] = array(
			'check',
			'kitbbcodegfont_charset_greek',
			'',
			$txt['kitbbcodegfont_charset_greek']
	);
	$config_vars[] = array(
			'check',
			'kitbbcodegfont_charset_cyrillic',
			'',
			$txt['kitbbcodegfont_charset_cyrillic']
	);
}