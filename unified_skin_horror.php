<?php
/**
 * SkinHorror - Integrated Horror skin class for MediaWiki
 * Combines custom horror theming with extension functionality
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
     * Add skin-specific modules and meta tags
     */
    public function setupSkinUserCss( OutputPage $out ) {
        parent::setupSkinUserCss( $out );
        
        // Load horror skin styles
        $out->addModuleStyles( [
            'skins.horror'
        ] );
        
        // Load extension features if enabled
        if ( $this->isHorrorContentPage() ) {
            $out->addModuleStyles( [
                'ext.horrorwiki.features'
            ] );
        }
        
        // Add horror-specific meta tags
        $out->addMeta( 'theme-color', '#0D0D0D' );
        $out->addMeta( 'color-scheme', 'dark' );
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );
        
        // Add structured data for horror content
        $this->addHorrorStructuredData( $out );
    }
    
    /**
     * Add skin-specific JavaScript
     */
    public function getDefaultModules() {
        $modules = parent::getDefaultModules();
        
        // Add horror skin JavaScript
        $modules['horror'] = [
            'skins.horror.js'
        ];
        
        // Add extension features for horror content
        if ( $this->isHorrorContentPage() ) {
            $modules['horrorfeatures'] = [
                'ext.horrorwiki.features'
            ];
        }
        
        return $modules;
    }
    
    /**
     * Check if current page is horror-related content
     */
    private function isHorrorContentPage() {
        global $wgHorrorWikiAutoDetectContent;
        
        if ( !$wgHorrorWikiAutoDetectContent ) {
            return true; // Always load if auto-detection is disabled
        }
        
        $title = $this->getTitle();
        $pageText = '';
        
        // Get page content for analysis
        if ( $title && $title->exists() ) {
            $page = WikiPage::factory( $title );
            $content = $page->getContent();
            if ( $content ) {
                $pageText = strtolower( ContentHandler::getContentText( $content ) );
            }
        }
        
        // Check for horror-related categories
        $categories = $title->getParentCategories();
        foreach ( $categories as $category => $sortkey ) {
            $categoryTitle = Title::newFromText( $category );
            if ( $categoryTitle ) {
                $categoryName = strtolower( $categoryTitle->getText() );
                if ( $this->isHorrorCategory( $categoryName ) ) {
                    return true;
                }
            }
        }
        
        // Check for horror-related keywords in content
        $horrorKeywords = [
            'horror', 'scary', 'creepy', 'supernatural', 'ghost', 'demon',
            'zombie', 'vampire', 'werewolf', 'haunted', 'paranormal',
            'slasher', 'thriller', 'fear', 'terror', 'nightmare',
            'creepypasta', 'urban legend'
        ];
        
        foreach ( $horrorKeywords as $keyword ) {
            if ( strpos( $pageText, $keyword ) !== false ) {
                return true;
            }
        }
        
        // Check namespace for horror content
        $namespace = $title->getNamespace();
        if ( in_array( $namespace, [ 100, 102, 104 ] ) ) { // Horror namespaces
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if category is horror-related
     */
    private function isHorrorCategory( $categoryName ) {
        $horrorCategories = [
            'horror movies', 'horror films', 'horror books', 'horror games',
            'creepypasta', 'urban legends', 'haunted locations', 'horror characters',
            'slasher films', 'supernatural horror', 'psychological horror',
            'zombie horror', 'vampire horror', 'ghost stories'
        ];
        
        foreach ( $horrorCategories as $horrorCat ) {
            if ( strpos( $categoryName, $horrorCat ) !== false ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Add structured data for horror content
     */
    private function addHorrorStructuredData( OutputPage $out ) {
        $title = $this->getTitle();
        
        if ( !$title || !$title->exists() ) {
            return;
        }
        
        // Basic article structured data
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title->getText(),
            'url' => $title->getFullURL(),
            'genre' => 'Horror',
            'inLanguage' => 'en-US',
            'author' => [
                '@type' => 'Organization',
                'name' => $this->getConfig()->get( 'Sitename' )
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->getConfig()->get( 'Sitename' ),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $this->getConfig()->get( 'Server' ) . '/logo.png'
                ]
            ]
        ];
        
        // Check for specific content types
        if ( $this->isHorrorContentPage() ) {
            $pageContent = $this->getPageContent();
            
            // Enhance structured data for movies
            if ( strpos( $pageContent, '{{movieinfobox' ) !== false ) {
                $structuredData['@type'] = 'Movie';
                $structuredData['genre'] = 'Horror';
            }
            
            // Enhance structured data for books
            if ( strpos( $pageContent, '{{bookinfobox' ) !== false ) {
                $structuredData['@type'] = 'Book';
                $structuredData['genre'] = 'Horror';
            }
            
            // Enhance structured data for games
            if ( strpos( $pageContent, '{{gameinfobox' ) !== false ) {
                $structuredData['@type'] = 'VideoGame';
                $structuredData['genre'] = 'Horror';
            }
        }
        
        $out->addScript( '<script type="application/ld+json">' . 
                        json_encode( $structuredData, JSON_UNESCAPED_SLASHES ) . 
                        '</script>' );
    }
    
    /**
     * Get page content for analysis
     */
    private function getPageContent() {
        $title = $this->getTitle();
        if ( !$title || !$title->exists() ) {
            return '';
        }
        
        $page = WikiPage::factory( $title );
        $content = $page->getContent();
        if ( $content ) {
            return ContentHandler::getContentText( $content );
        }
        
        return '';
    }
    
    /**
     * Override to add horror-specific body classes
     */
    public function addToBodyAttributes( $out, &$bodyAttrs ) {
        parent::addToBodyAttributes( $out, $bodyAttrs );
        
        // Add horror theme class
        if ( isset( $bodyAttrs['class'] ) ) {
            $bodyAttrs['class'] .= ' horror-wiki-theme';
        } else {
            $bodyAttrs['class'] = 'horror-wiki-theme';
        }
        
        // Add content-specific classes
        if ( $this->isHorrorContentPage() ) {
            $bodyAttrs['class'] .= ' horror-content-page';
            
            // Add specific content type classes
            $pageContent = strtolower( $this->getPageContent() );
            if ( strpos( $pageContent, 'movie' ) !== false || strpos( $pageContent, 'film' ) !== false ) {
                $bodyAttrs['class'] .= ' horror-movie-page';
            }
            if ( strpos( $pageContent, 'book' ) !== false || strpos( $pageContent, 'novel' ) !== false ) {
                $bodyAttrs['class'] .= ' horror-book-page';
            }
            if ( strpos( $pageContent, 'game' ) !== false ) {
                $bodyAttrs['class'] .= ' horror-game-page';
            }
            if ( strpos( $pageContent, 'creepypasta' ) !== false ) {
                $bodyAttrs['class'] .= ' horror-creepypasta-page';
            }
        }
    }
    
    /**
     * Override to customize edit section links for horror theme
     */
    public function doEditSectionLink( Title $nt, $section, $tooltip = null, $lang = false ) {
        // Use horror-themed edit links
        $editlink = parent::doEditSectionLink( $nt, $section, $tooltip, $lang );
        
        // Add horror styling to edit links
        $editlink = str_replace( 'class="', 'class="horror-edit-link ', $editlink );
        
        return $editlink;
    }
    
    /**
     * Add horror-specific configuration
     */
    public function getConfigVars() {
        $vars = parent::getConfigVars();
        
        // Add horror-specific JavaScript configuration
        $vars['wgHorrorWikiConfig'] = [
            'enableWarnings' => $this->getConfig()->get( 'HorrorWikiEnableWarnings' ),
            'enableRatings' => $this->getConfig()->get( 'HorrorWikiEnableRatings' ),
            'autoDetectContent' => $this->getConfig()->get( 'HorrorWikiAutoDetectContent' ),
            'isHorrorPage' => $this->isHorrorContentPage()
        ];
        
        return $vars;
    }
}