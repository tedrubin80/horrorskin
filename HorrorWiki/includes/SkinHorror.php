<?php
/**
 * SkinHorror - Main Horror skin class for MediaWiki
 * 
 * @file
 * @ingroup Skins
 */

class SkinHorror extends SkinTemplate {
    
    /**
     * @var string
     */
    public $skinname = 'horror';
    
    /**
     * @var string
     */
    public $stylename = 'horror';
    
    /**
     * @var string
     */
    public $template = 'HorrorTemplate';
    
    /**
     * Add skin-specific modules
     */
    public function setupSkinUserCss( OutputPage $out ) {
        parent::setupSkinUserCss( $out );
        
        // Add our horror-specific CSS
        $out->addModuleStyles( [
            'skins.horror'
        ] );
        
        // Add dark theme meta tag
        $out->addMeta( 'theme-color', '#0D0D0D' );
        $out->addMeta( 'color-scheme', 'dark' );
    }
    
    /**
     * Add skin-specific JavaScript
     */
    public function getDefaultModules() {
        $modules = parent::getDefaultModules();
        
        // Add our horror-specific JavaScript
        $modules['horror'] = [
            'skins.horror.js'
        ];
        
        return $modules;
    }
}