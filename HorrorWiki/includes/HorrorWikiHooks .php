<?php
/**
 * HorrorWikiHooks - Fixed hook handlers for MediaWiki compatibility
 * 
 * @file
 * @ingroup Extensions
 */

class HorrorWikiHooks {
    
    /**
     * Handle BeforePageDisplay - loads styles, scripts, and meta tags
     */
    public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
        // Load horror skin resources
        $out->addModuleStyles( [ 'skins.horror' ] );
        
        // Check if this is a horror content page
        if ( self::isHorrorContentPage( $out->getTitle() ) ) {
            $out->addModules( [ 'ext.horrorwiki.features' ] );
        }
        
        // Add horror theme body classes
        $out->addBodyClasses( 'horror-wiki-theme' );
        
        // Add horror-specific meta tags
        $out->addMeta( 'theme-color', '#0D0D0D' );
        $out->addMeta( 'color-scheme', 'dark' );
        
        // Add structured data for horror content
        self::addHorrorStructuredData( $out );
        
        // Auto-switch to horror skin for horror content (if enabled)
        global $wgHorrorWikiDefaultSkin;
        if ( $wgHorrorWikiDefaultSkin && self::isHorrorContentPage( $out->getTitle() ) ) {
            $user = $out->getUser();
            if ( $user->getOption( 'skin' ) !== 'horror' ) {
                // Temporarily override skin for this page view
                $out->addJsConfigVars( 'wgHorrorWikiAutoSkin', true );
            }
        }
        
        return true;
    }
    
    /**
     * Handle navigation modifications
     */
    public static function onSkinTemplateNavigation( &$skin, &$links ) {
        global $wgUser;
        
        // Only modify navigation for horror skin or horror content
        if ( $skin->getSkinName() === 'horror' || self::isHorrorContentPage( $skin->getTitle() ) ) {
            
            // Add horror-specific navigation items
            $links['namespaces']['horror-dashboard'] = [
                'class' => 'horror-nav-item',
                'text' => '🎭 Horror Hub',
                'href' => SpecialPage::getTitleFor( 'HorrorDashboard' )->getLocalURL(),
                'context' => 'subject'
            ];
            
            $links['namespaces']['horror-ratings'] = [
                'class' => 'horror-nav-item',
                'text' => '💀 Top Rated',
                'href' => SpecialPage::getTitleFor( 'HorrorRatings' )->getLocalURL(),
                'context' => 'subject'
            ];
            
            if ( $wgUser->isRegistered() ) {
                $links['views']['create-horror-page'] = [
                    'class' => 'horror-create-page',
                    'text' => '✚ Create Horror Page',
                    'href' => '#',
                    'context' => 'subject'
                ];
            }
            
            // Add content warnings link if enabled
            global $wgHorrorWikiEnableWarnings;
            if ( $wgHorrorWikiEnableWarnings ) {
                $links['namespaces']['content-warnings'] = [
                    'class' => 'horror-nav-item',
                    'text' => '⚠️ Warnings',
                    'href' => SpecialPage::getTitleFor( 'ContentWarnings' )->getLocalURL(),
                    'context' => 'subject'
                ];
            }
        }
        
        return true;
    }
    
    /**
     * Register custom parser functions - FIXED VERSION
     */
    public static function onParserFirstCallInit( Parser $parser ) {
        // Register horror-specific parser functions using correct method
        $parser->setHook( 'contentwarning', [ __CLASS__, 'renderContentWarning' ] );
        $parser->setHook( 'horrorrating', [ __CLASS__, 'renderHorrorRating' ] );
        $parser->setHook( 'spoiler', [ __CLASS__, 'renderSpoiler' ] );
        $parser->setHook( 'movieinfobox', [ __CLASS__, 'renderMovieInfobox' ] );
        $parser->setHook( 'bookinfobox', [ __CLASS__, 'renderBookInfobox' ] );
        $parser->setHook( 'gameinfobox', [ __CLASS__, 'renderGameInfobox' ] );
        
        return true;
    }
    
    /**
     * Handle database schema updates
     */
    public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
        $dir = __DIR__ . '/../sql/';
        
        // Only add tables if SQL files exist
        if ( file_exists( $dir . 'horror_ratings.sql' ) ) {
            $updater->addExtensionTable( 'horror_ratings', $dir . 'horror_ratings.sql' );
        }
        if ( file_exists( $dir . 'content_warnings.sql' ) ) {
            $updater->addExtensionTable( 'content_warnings', $dir . 'content_warnings.sql' );
        }
        if ( file_exists( $dir . 'horror_pages.sql' ) ) {
            $updater->addExtensionTable( 'horror_pages', $dir . 'horror_pages.sql' );
        }
        if ( file_exists( $dir . 'horror_categories.sql' ) ) {
            $updater->addExtensionTable( 'horror_categories', $dir . 'horror_categories.sql' );
        }
        if ( file_exists( $dir . 'user_horror_preferences.sql' ) ) {
            $updater->addExtensionTable( 'user_horror_preferences', $dir . 'user_horror_preferences.sql' );
        }
        
        return true;
    }
    
    /**
     * Render content warning widget - FIXED VERSION
     */
    public static function renderContentWarning( $input, array $args, Parser $parser, PPFrame $frame ) {
        $type = $args['type'] ?? '';
        $description = $args['description'] ?? '';
        $severity = $args['severity'] ?? '3';
        
        $warnings = [
            'gore' => [ 'icon' => '🩸', 'text' => 'Graphic Violence & Gore' ],
            'jump' => [ 'icon' => '😱', 'text' => 'Jump Scares' ],
            'psychological' => [ 'icon' => '🧠', 'text' => 'Psychological Horror' ],
            'disturbing' => [ 'icon' => '⚠️', 'text' => 'Disturbing Content' ],
            'suicide' => [ 'icon' => '🚨', 'text' => 'Suicide/Self-Harm References' ],
            'sexual' => [ 'icon' => '🔞', 'text' => 'Sexual Content' ],
            'drug' => [ 'icon' => '💊', 'text' => 'Drug Use' ],
            'occult' => [ 'icon' => '🔮', 'text' => 'Occult/Satanic Themes' ]
        ];
        
        $warningData = $warnings[$type] ?? [ 'icon' => '⚠️', 'text' => 'Content Warning' ];
        $severityClass = 'severity-' . min( 5, max( 1, intval( $severity ) ) );
        
        $html = '<div class="horror-content-warning ' . $severityClass . '" data-warning-type="' . htmlspecialchars( $type ) . '">';
        $html .= '<div class="warning-header">';
        $html .= '<span class="warning-icon">' . $warningData['icon'] . '</span>';
        $html .= '<span class="warning-text">' . $warningData['text'] . '</span>';
        $html .= '</div>';
        
        if ( !empty( $description ) ) {
            $html .= '<div class="warning-description">' . htmlspecialchars( $description ) . '</div>';
        }
        
        $html .= '<div class="warning-actions">';
        $html .= '<button class="warning-accept btn-horror">I Understand, Show Content</button>';
        $html .= '<button class="warning-settings btn-horror-secondary">Warning Settings</button>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Store warning in database if possible
        if ( method_exists( 'HorrorWikiHooks', 'storeContentWarning' ) ) {
            self::storeContentWarning( $parser->getTitle()->getArticleID(), $type, $description, $severity );
        }
        
        return $html;
    }
    
    /**
     * Render horror rating widget - FIXED VERSION
     */
    public static function renderHorrorRating( $input, array $args, Parser $parser, PPFrame $frame ) {
        $gore = intval( $args['gore'] ?? 0 );
        $fear = intval( $args['fear'] ?? 0 );
        $disturbing = intval( $args['disturbing'] ?? 0 );
        $overall = intval( $args['overall'] ?? 0 );
        
        $pageId = $parser->getTitle()->getArticleID();
        $ratings = self::getPageRatings( $pageId );
        
        $html = '<div class="horror-rating-widget" data-page-id="' . $pageId . '">';
        $html .= '<div class="rating-title">💀 Horror Intensity Ratings</div>';
        $html .= '<div class="rating-bars">';
        
        $categories = [
            'Gore Level' => [ 'value' => $gore, 'user_avg' => $ratings['gore'] ?? 0 ],
            'Fear Factor' => [ 'value' => $fear, 'user_avg' => $ratings['fear'] ?? 0 ],
            'Disturbing Content' => [ 'value' => $disturbing, 'user_avg' => $ratings['disturbing'] ?? 0 ]
        ];
        
        if ( $overall > 0 ) {
            $categories['Overall Rating'] = [ 'value' => $overall, 'user_avg' => $ratings['overall'] ?? 0 ];
        }
        
        foreach ( $categories as $label => $data ) {
            $value = max( 0, min( 5, $data['value'] ) );
            $userAvg = round( $data['user_avg'], 1 );
            
            $html .= '<div class="rating-row" data-category="' . strtolower( str_replace( ' ', '_', $label ) ) . '">';
            $html .= '<span class="rating-label">' . $label . '</span>';
            $html .= '<div class="rating-display">';
            $html .= '<div class="rating-bar official" title="Official Rating: ' . $value . '/5">';
            for ( $i = 1; $i <= 5; $i++ ) {
                $active = $i <= $value ? 'active' : '';
                $html .= '<span class="rating-skull official-skull ' . $active . '">💀</span>';
            }
            $html .= '</div>';
            
            if ( $userAvg > 0 ) {
                $html .= '<div class="rating-bar user-avg" title="User Average: ' . $userAvg . '/5">';
                for ( $i = 1; $i <= 5; $i++ ) {
                    $active = $i <= $userAvg ? 'active' : '';
                    $html .= '<span class="rating-skull user-skull ' . $active . '">☠️</span>';
                }
                $html .= '<span class="avg-text">' . $userAvg . '/5</span>';
                $html .= '</div>';
            }
            
            $html .= '</div></div>';
        }
        
        $html .= '</div></div>';
        
        return $html;
    }
    
    /**
     * Render spoiler section - FIXED VERSION
     */
    public static function renderSpoiler( $input, array $args, Parser $parser, PPFrame $frame ) {
        $title = $args['title'] ?? 'Spoiler Alert';
        $content = $input ?? '';
        
        $html = '<div class="horror-spoiler">';
        $html .= '<div class="spoiler-warning" data-title="' . htmlspecialchars( $title ) . '">';
        $html .= '<span class="spoiler-icon">⚠️</span>';
        $html .= '<span class="spoiler-text">' . htmlspecialchars( $title ) . ' - Click to reveal</span>';
        $html .= '</div>';
        $html .= '<div class="spoiler-content">' . $parser->recursiveTagParse( $content, $frame ) . '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render movie infobox - SIMPLIFIED VERSION
     */
    public static function renderMovieInfobox( $input, array $args, Parser $parser, PPFrame $frame ) {
        $title = $args['title'] ?? '';
        $year = $args['year'] ?? '';
        $director = $args['director'] ?? '';
        $genre = $args['genre'] ?? '';
        $runtime = $args['runtime'] ?? '';
        $rating = $args['rating'] ?? '';
        $poster = $args['poster'] ?? '';
        
        $html = '<div class="horror-infobox movie-infobox">';
        $html .= '<div class="infobox-header">🎬 Horror Movie</div>';
        
        if ( !empty( $poster ) ) {
            $html .= '<div class="infobox-image"><img src="' . htmlspecialchars( $poster ) . '" alt="' . htmlspecialchars( $title ) . ' Poster" /></div>';
        }
        
        $fields = [
            'Title' => $title,
            'Year' => $year,
            'Director' => $director,
            'Genre' => $genre,
            'Runtime' => $runtime,
            'MPAA Rating' => $rating
        ];
        
        $html .= '<div class="infobox-content">';
        foreach ( $fields as $label => $value ) {
            if ( !empty( $value ) ) {
                $html .= '<div class="infobox-row">';
                $html .= '<span class="infobox-label">' . $label . ':</span>';
                $html .= '<span class="infobox-value">' . htmlspecialchars( $value ) . '</span>';
                $html .= '</div>';
            }
        }
        $html .= '</div></div>';
        
        return $html;
    }
    
    /**
     * Render book infobox - SIMPLIFIED VERSION
     */
    public static function renderBookInfobox( $input, array $args, Parser $parser, PPFrame $frame ) {
        $title = $args['title'] ?? '';
        $author = $args['author'] ?? '';
        $year = $args['year'] ?? '';
        $genre = $args['genre'] ?? '';
        $pages = $args['pages'] ?? '';
        $isbn = $args['isbn'] ?? '';
        $cover = $args['cover'] ?? '';
        
        $html = '<div class="horror-infobox book-infobox">';
        $html .= '<div class="infobox-header">📚 Horror Book</div>';
        
        if ( !empty( $cover ) ) {
            $html .= '<div class="infobox-image"><img src="' . htmlspecialchars( $cover ) . '" alt="' . htmlspecialchars( $title ) . ' Cover" /></div>';
        }
        
        $fields = [
            'Title' => $title,
            'Author' => $author,
            'Year' => $year,
            'Genre' => $genre,
            'Pages' => $pages,
            'ISBN' => $isbn
        ];
        
        $html .= '<div class="infobox-content">';
        foreach ( $fields as $label => $value ) {
            if ( !empty( $value ) ) {
                $html .= '<div class="infobox-row">';
                $html .= '<span class="infobox-label">' . $label . ':</span>';
                $html .= '<span class="infobox-value">' . htmlspecialchars( $value ) . '</span>';
                $html .= '</div>';
            }
        }
        $html .= '</div></div>';
        
        return $html;
    }
    
    /**
     * Render game infobox - SIMPLIFIED VERSION
     */
    public static function renderGameInfobox( $input, array $args, Parser $parser, PPFrame $frame ) {
        $title = $args['title'] ?? '';
        $developer = $args['developer'] ?? '';
        $year = $args['year'] ?? '';
        $platform = $args['platform'] ?? '';
        $genre = $args['genre'] ?? '';
        $rating = $args['rating'] ?? '';
        $cover = $args['cover'] ?? '';
        
        $html = '<div class="horror-infobox game-infobox">';
        $html .= '<div class="infobox-header">🎮 Horror Game</div>';
        
        if ( !empty( $cover ) ) {
            $html .= '<div class="infobox-image"><img src="' . htmlspecialchars( $cover ) . '" alt="' . htmlspecialchars( $title ) . ' Cover" /></div>';
        }
        
        $fields = [
            'Title' => $title,
            'Developer' => $developer,
            'Year' => $year,
            'Platform' => $platform,
            'Genre' => $genre,
            'Rating' => $rating
        ];
        
        $html .= '<div class="infobox-content">';
        foreach ( $fields as $label => $value ) {
            if ( !empty( $value ) ) {
                $html .= '<div class="infobox-row">';
                $html .= '<span class="infobox-label">' . $label . ':</span>';
                $html .= '<span class="infobox-value">' . htmlspecialchars( $value ) . '</span>';
                $html .= '</div>';
            }
        }
        $html .= '</div></div>';
        
        return $html;
    }
    
    /**
     * Check if current page is horror content
     */
    private static function isHorrorContentPage( $title ) {
        if ( !$title || !$title->exists() ) {
            return false;
        }
        
        // Check categories
        $categories = $title->getParentCategories();
        foreach ( $categories as $category => $sortkey ) {
            $categoryTitle = Title::newFromText( $category );
            if ( $categoryTitle ) {
                $categoryName = strtolower( $categoryTitle->getText() );
                if ( self::isHorrorCategory( $categoryName ) ) {
                    return true;
                }
            }
        }
        
        // Check namespace
        $namespace = $title->getNamespace();
        if ( in_array( $namespace, [ 100, 102, 104 ] ) ) { // Horror namespaces
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if category is horror-related
     */
    private static function isHorrorCategory( $categoryName ) {
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
    private static function addHorrorStructuredData( OutputPage $out ) {
        $title = $out->getTitle();
        
        if ( !$title || !$title->exists() ) {
            return;
        }
        
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title->getText(),
            'url' => $title->getFullURL(),
            'genre' => 'Horror',
            'inLanguage' => 'en-US'
        ];
        
        $out->addScript( '<script type="application/ld+json">' . 
                        json_encode( $structuredData, JSON_UNESCAPED_SLASHES ) . 
                        '</script>' );
    }
    
    /**
     * Store content warning in database - SAFE VERSION
     */
    private static function storeContentWarning( $pageId, $type, $description, $severity ) {
        // Only attempt database operations if tables exist
        try {
            if ( $pageId > 0 ) {
                $dbw = wfGetDB( DB_PRIMARY );
                
                // Check if table exists first
                if ( $dbw->tableExists( 'content_warnings' ) ) {
                    $dbw->replace(
                        'content_warnings',
                        [ [ 'cw_page_id', 'cw_warning_type' ] ],
                        [
                            'cw_page_id' => $pageId,
                            'cw_warning_type' => $type,
                            'cw_description' => $description,
                            'cw_severity' => intval( $severity ),
                            'cw_added_by' => RequestContext::getMain()->getUser()->getId()
                        ],
                        __METHOD__
                    );
                }
            }
        } catch ( Exception $e ) {
            // Silently fail if database operations aren't available
            wfDebugLog( 'HorrorWiki', 'Could not store content warning: ' . $e->getMessage() );
        }
    }
    
    /**
     * Get page ratings from database - SAFE VERSION
     */
    private static function getPageRatings( $pageId ) {
        if ( $pageId <= 0 ) return [];
        
        try {
            $dbr = wfGetDB( DB_REPLICA );
            
            // Check if table exists first
            if ( !$dbr->tableExists( 'horror_ratings' ) ) {
                return [];
            }
            
            $res = $dbr->select(
                'horror_ratings',
                [ 'hr_category', 'AVG(hr_rating) as avg_rating' ],
                [ 'hr_page_id' => $pageId ],
                __METHOD__,
                [ 'GROUP BY' => 'hr_category' ]
            );
            
            $ratings = [];
            foreach ( $res as $row ) {
                $category = strtolower( str_replace( ' ', '_', $row->hr_category ) );
                $ratings[$category] = floatval( $row->avg_rating );
            }
            
            return $ratings;
        } catch ( Exception $e ) {
            // Silently fail if database operations aren't available
            wfDebugLog( 'HorrorWiki', 'Could not get page ratings: ' . $e->getMessage() );
            return [];
        }
    }
}