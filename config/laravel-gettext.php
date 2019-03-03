<?php

return [

	/**
	 * Default locale: this will be the default for your application.
	 * Is to be supposed that all strings are written in this language.
	 */
	'locale' => 'hu_HU',

	/**
	 * Supported locales: An array containing all allowed languages
	 */
	'supported-locales' => [
        'hu_HU',
		'en_GB',
    ],

	/**
	 * Default charset encoding.
	 */
	'encoding' => 'UTF-8',

	/**
	 * -----------------------------------------------------------------------
	 * All standard configuration ends here. The following values
	 * are only for special cases.
	 * -----------------------------------------------------------------------
	 **/

    /**
     * Base translation directory path (don't use trailing slash)
     */
    'translations-path' => '../resources/lang',

	/**
	 * Fallback locale: When default locale is not available
	 */
	'fallback-locale' => 'en_GB',

	/**
	 * Default domain used for translations: It is the file name for .po and .mo files
	 */
	'domain' => 'default',

	/**
	 * Project name: is used on .po header files
	 */
	'project' => 'OnlineSolutions',

	/**
	 * Translator contact data (used on .po headers too)
	 */
	'translator' => 'Kőrösi Krisztián <info@korosikrisztian.hu>',

	/**
	 * Paths where PoEdit will search recursively for strings to translate.
	 * All paths are relative to app/ (don't use trailing slash).
	 *
	 * Remember to call artisan gettext:update after change this.
	 */
	'source-paths' => [
        'default' => [
			'Http/Controllers',
			'../resources/views/page/templates/default',
			'Commands'
        ],
		'2014newbrand' => [
			'../resources/views/page/templates/2014newbrand'
        ]
    ],

	/**
	 * Sync laravel: A flag that determines if the laravel built-in locale must
	 * be changed when you call LaravelGettext::setLocale.
	 */
	'sync-laravel' => true,

];
