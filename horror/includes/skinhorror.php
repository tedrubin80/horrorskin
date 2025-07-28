<?php
/**
 * SkinHorror - Main Horror skin class for MediaWiki
 * 
 * @file
 * @ingroup Skins
 */

use MediaWiki\MediaWikiServices;

class SkinHorror extends SkinMustache {
    
    /**
     * @var string
     */
    public $skinname = 'horror';
    
    /**
     * @var string
     */
    public $stylename = 'Horror';
    
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
    
    /**
     * Add horror-specific body classes
     */
    public function addToBodyAttributes( $out, &$bodyAttrs ) {
        parent::addToBodyAttributes( $out, $bodyAttrs );
        
        // Add horror theme class
        $bodyAttrs['class'] .= ' horror-skin-theme';
        
        // Add page-specific classes
        $title = $this->getTitle();
        if ( $title ) {
            $namespace = $title->getNamespace();
            $bodyAttrs['class'] .= ' ns-' . $namespace;
            
            // Add horror-specific classes based on page content
            if ( $this->isHorrorContent() ) {
                $bodyAttrs['class'] .= ' horror-content-page';
            }
        }
    }
    
    /**
     * Get template data for the skin
     */
    public function getTemplateData() {
        $data = parent::getTemplateData();
        
        // Add horror-specific data
        $data['horror-navigation'] = $this->buildHorrorNavigation();
        $data['horror-sidebar'] = $this->buildHorrorSidebar();
        $data['horror-header'] = $this->buildHorrorHeader();
        $data['horror-footer'] = $this->buildHorrorFooter();
        
        // Add content warnings if needed
        if ( $this->shouldShowContentWarnings() ) {
            $data['content-warnings'] = $this->getContentWarnings();
        }
        
        return $data;
    }
    
    /**
     * Build horror-specific navigation
     */
    private function buildHorrorNavigation() {
        $navigation = [];
        
        // Horror-specific navigation items
        $navigation['horror-hub'] = [
            'text' => '🎭 Horror Hub',
            'href' => SpecialPage::getTitleFor( 'HorrorDashboard' )->getLocalURL(),
            'class' => 'horror-nav-item'
        ];
        
        $navigation['top-rated'] = [
            'text' => '💀 Top Rated',
            'href' => SpecialPage::getTitleFor( 'HorrorRatings' )->getLocalURL(),
            'class' => 'horror-nav-item'
        ];
        
        $navigation['browse-categories'] = [
            'text' => '🗂️ Categories',
            'href' => SpecialPage::getTitleFor( 'Categories' )->getLocalURL(),
            'class' => 'horror-nav-item'
        ];
        
        // Add create page link if user is logged in
        if ( $this->getUser()->isRegistered() ) {
            $navigation['create-horror'] = [
                'text' => '✚ Create Horror Page',
                'href' => '#',
                'class' => 'horror-create-page-btn',
                'id' => 'horror-create-page'
            ];
        }
        
        return $navigation;
    }
    
    /**
     * Build horror-specific sidebar
     */
    private function buildHorrorSidebar() {
        $sidebar = [];
        
        // Horror categories
        $sidebar['horror-categories'] = [
            'header' => '🎭 Horror Categories',
            'items' => [
                [ 'text' => '🎬 Horror Movies', 'href' => '/wiki/Category:Horror_Movies' ],
                [ 'text' => '📚 Horror Books', 'href' => '/wiki/Category:Horror_Books' ],
                [ 'text' => '🎮 Horror Games', 'href' => '/wiki/Category:Horror_Games' ],
                [ 'text' => '📝 Creepypasta', 'href' => '/wiki/Category:Creepypasta' ],
                [ 'text' => '🏚️ Urban Legends', 'href' => '/wiki/Category:Urban_Legends' ],
                [ 'text' => '👹 Horror Characters', 'href' => '/wiki/Category:Horror_Characters' ]
            ]
        ];
        
        // Recent horror content
        $sidebar['recent-horror'] = [
            'header' => '🕐 Recent Horror',
            'items' => $this->getRecentHorrorPages()
        ];
        
        return $sidebar;
    }
    
    /**
     * Build horror header
     */
    private function buildHorrorHeader() {
        return [
            'site-title' => $this->getConfig()->get( 'Sitename' ),
            'site-tagline' => 'Your Ultimate Horror Wiki',
            'search-placeholder' => 'Search horror content...',
            'user-menu' => $this->buildUserMenu()
        ];
    }
    
    /**
     * Build horror footer
     */
    private function buildHorrorFooter() {
        return [
            'copyright' => '© ' . date('Y') . ' ' . $this->getConfig()->get( 'Sitename' ),
            'tagline' => 'Powered by MediaWiki & Horror Community',
            'links' => [
                [ 'text' => 'About', 'href' => '/wiki/About' ],
                [ 'text' => 'Contact', 'href' => '/wiki/Contact' ],
                [ 'text' => 'Privacy', 'href' => '/wiki/Privacy_Policy' ],
                [ 'text' => 'Terms', 'href' => '/wiki/Terms_of_Service' ]
            ]
        ];
    }
    
    /**
     * Check if current page contains horror content
     */
    private function isHorrorContent() {
        $title = $this->getTitle();
        if ( !$title ) return false;
        
        // Check if page is in horror-related categories
        $horrorCategories = [
            'Horror_Movies', 'Horror_Books', 'Horror_Games',
            'Creepypasta', 'Urban_Legends', 'Horror_Characters'
        ];
        
        foreach ( $horrorCategories as $category ) {
            if ( $title->getNamespace() === NS_CATEGORY && 
                 strpos( $title->getText(), str_replace('_', ' ', $category) ) !== false ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if content warnings should be shown
     */
    private function shouldShowContentWarnings() {
        return $this->getConfig()->get( 'HorrorSkinEnableContentWarnings' ) && 
               $this->isHorrorContent();
    }
    
    /**
     * Get content warnings for current page
     */
    private function getContentWarnings() {
        // This would integrate with the database if available
        return [
            'gore' => '🩸 Contains graphic violence',
            'jump' => '😱 Contains jump scares',
            'psychological' => '🧠 Psychological horror content'
        ];
    }
    
    /**
     * Get recent horror pages
     */
    private function getRecentHorrorPages() {
        // Simple implementation - can be enhanced with database queries
        return [
            [ 'text' => 'The Exorcist', 'href' => '/wiki/The_Exorcist' ],
            [ 'text' => 'Halloween (1978)', 'href' => '/wiki/Halloween_(1978)' ],
            [ 'text' => 'Stephen King', 'href' => '/wiki/Stephen_King' ],
            [ 'text' => 'Silent Hill', 'href' => '/wiki/Silent_Hill' ],
            [ 'text' => 'The Shining', 'href' => '/wiki/The_Shining' ]
        ];
    }
    
    /**
     * Build user menu
     */
    private function buildUserMenu() {
        $user = $this->getUser();
        
        if ( $user->isRegistered() ) {
            return [
                'username' => $user->getName(),
                'user_page' => $user->getUserPage()->getLocalURL(),
                'logout' => SpecialPage::getTitleFor( 'Userlogout' )->getLocalURL()
            ];
        } else {
            return [
                'login' => SpecialPage::getTitleFor( 'Userlogin' )->getLocalURL(),
                'register' => SpecialPage::getTitleFor( 'CreateAccount' )->getLocalURL()
            ];
        }
    }
}