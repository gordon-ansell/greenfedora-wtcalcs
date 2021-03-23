<?php

/**
 * This file is part of the GordyAnsell SBS (Static Blogging Software) package.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * SBS configs.
 */
 
use GreenFedora\FileSystem\DirIterFilter\FilterInterface;

return array(
	/**
	 * Default site configs.
	 */	
	'site'	=>	array(
		
		/**
		 * Basics.
		 */ 	
		'timezone'	=>	'UTC',
		'mode'		=>	'prod',
		'prodUrl'	=>	null,
		'assetsUrl'	=>	null,
		'absPath'	=>	null,
		'url'		=>	null,
		
		/**
		 * Min/max limits on fields.
		 */
		'descLen'	=>	array('min' => 70, 'max' => 300),
		
		/**
		 * Display date formatting.
		 */
		'displayDate' => array(
			'date'	=>	'D jS M Y',
			'sep'	=>	' at ',
			'time'	=>	'H:i',	
		),	
		
		/**
		 * Dev runs.
		 */
		'serve'			=>	false,
		'serveOnly'		=>	false,
		'devServer' => array(	
			'address'	=> 	"0.0.0.0",
			'builtIn'	=> 	true,									// Use PHP's built-in web server?
			'port'		=> 	8081,
			'url'		=>	null,
		),
		
		/**
		 * The different sorts of files we have.
		 */
		'articleExts'	=>	array('md', 'html'),
		'markdownExts'	=>	array('md'),
		'scssFiles'		=>	array('main.scss'),	
		
		/**
		 * Categories and tags.
		 */
		'catIconDir'	=>	'/assets/caticons',
		'tagIconDir'	=>	'/assets/tagicons',	
		'catOutputDir'	=>	'/categories',
		'tagOutputDir'	=>	'/tags',		
		
		/**
		 * Template processing.
		 */
		'compileDir'	=>	'/template_c',
		'templateDir'	=>	'/layouts', 	
		
		/**
		 * Important locations, extensions and matching options.
		 */
		'layoutPaths'		=>	array('/_layouts'),
		'layoutExt'			=>	'tpl',
		'sitePath'			=>	'/_site',	
		'blogFileRegex'		=>	"#^([0-9]{4}-[0-9]{2}-[0-9]{2}-).*#",
		'blogFilePrefLen'	=>	11,
		'outputExt'			=>	'html',
		
		/**
		 * Length checks.
		 */
		'lengths'	=>	array(
			'title'			=>	array('min' => 	 4, 'max' => 110, 'type' => 'chars'),	
			'headline'		=>	array('min' =>  16, 'max' => 110, 'type' => 'chars'),	
			'description'	=>	array('min' =>  72, 'max' => 300, 'type' => 'chars'),	
			'excerpt'		=>	array('min' =>   0, 'max' => 300, 'type' => 'chars'),	
			'summary'		=>	array('min' =>  40, 'max' =>  55, 'type' => 'words'),
			'reviewDesc'	=>	array('min' => 100, 'max' => 200, 'type' => 'chars'),
		),	
		
		/**
		 * These are used by the directory loop to ignore certain files and directories.
		 */
		'fileSystemDefs'	=>	array(
			'postPaths'		=>	array('/_posts'),
			'ignorePaths'	=>	array('/2461'),
			'ignoreExts'	=>	array('sh', 'acorn'),
			'rules'			=>	array(
				'underscore' 	=> 	array('type' => FilterInterface::TYPE_BEGINSWITH, 'spec' => '_', 'scope' => FilterInterface::SCOPE_ANY, 'not' => array('postPaths', 'var')),	
				'dot' 		 	=> 	array('type' => FilterInterface::TYPE_BEGINSWITH, 'spec' => '.', 'scope' => FilterInterface::SCOPE_ANY),	
				'ignorePaths'	=>	array('type' => FilterInterface::TYPE_PATH, 'spec' => 'ignorePaths', 'scope' => FilterInterface::SCOPE_PATH),	
				'extensions'	=>	array('type' => FilterInterface::TYPE_EXT, 'spec' => 'ignoreExts', 'scope' => FilterInterface::SCOPE_FILE),
			),
		),	
	),

	/**
	 * Default article configs.
	 */	
	'article' =>	array(
		'layout'	=>	'article',	
		'lateParse'	=>	false,
	),

	/**
	 * Default blog posting configs.
	 */	
	'blogPosting' =>	array(
		'layout'	=>	'blogPosting',	
	),
);
