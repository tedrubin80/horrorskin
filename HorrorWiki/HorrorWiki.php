<?php
/**
 * HorrorWiki Extension - Legacy Entry Point
 * Unified horror wiki system with custom skin and features
 */

if ( function_exists( 'wfLoadExtension' ) ) {
    wfLoadExtension( 'HorrorWiki' );
    return true;
} else {
    die( 'This version of HorrorWiki requires MediaWiki 1.35+' );
}
