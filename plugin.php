<?php
/*
Plugin Name: Yourls EE Edition Logger
Plugin URI: https://github.com/p-arnaud/yourls-ee-edition-logger
Description: Log every link edition
Version: 1.0
Author: Pierre Arnaud
Author URI: https://github.com/p-arnaud
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
global $EE_EDITIONLOGGER_LOGPATH;

require_once 'config.php';
require 'vendor/autoload.php';


function ee_editionlogger_insert_link ( $args ) {
	global $EE_EDITIONLOGGER_LOGPATH;
	$insert  = $args[0];
	$url     = $args[1];
	$keyword = $args[2];

	if ( $insert ) {
		$logger = new Katzgrau\KLogger\Logger($EE_EDITIONLOGGER_LOGPATH);
		$logger->info("[".YOURLS_USER."] Link inserted: ( $keyword, $url )");
	}
}


function ee_editionlogger_delete_link ( $args ) {
	global $EE_EDITIONLOGGER_LOGPATH;
	$keyword = $args[0];

	$logger = new Katzgrau\KLogger\Logger($EE_EDITIONLOGGER_LOGPATH);
	$logger->info("[".YOURLS_USER."] Link deleted: ( $keyword )");
}


function ee_editionlogger_edit_link ( $args ) {
	global $EE_EDITIONLOGGER_LOGPATH;
	$url                   = $args[0];
	$keyword               = $args[1];
	$newkeyword            = $args[2];
	$new_url_already_there = $args[3];
	$keyword_is_ok         = $args[4];

	// same check as in the source
	if ( ( !$new_url_already_there || yourls_allow_duplicate_longurls() ) && $keyword_is_ok ) {
		$logger = new Katzgrau\KLogger\Logger($EE_EDITIONLOGGER_LOGPATH);
		$logger->info( "[".YOURLS_USER."] Link edited: $keyword -> ( $newkeyword, $url )" );
	}
}


yourls_add_action( 'insert_link',   'ee_editionlogger_insert_link' );

yourls_add_action( 'delete_link',   'ee_editionlogger_delete_link' );

yourls_add_action( 'pre_edit_link', 'ee_editionlogger_edit_link' );
