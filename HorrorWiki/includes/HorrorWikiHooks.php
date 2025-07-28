<?php
/**
 * HorrorWikiHooks - Unified hook handlers combining skin and extension functionality
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
        $out->addModules( [ 'skins.horror.js' ] );
        
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
     * Register custom parser functions
     */
    public static function onParserFirstCallInit( Parser $parser ) {
        // Register horror-specific parser functions
        $parser->setFunctionHook( 'contentwarning', [ __CLASS__, 'renderContentWarning' ] );
        $parser->setFunctionHook( 'horrorrating', [ __CLASS__, 'renderHorrorRating' ] );
        $parser->setFunctionHook( 'spoiler', [ __CLASS__, 'renderSpoiler' ] );
        $parser->setFunctionHook( 'movieinfobox', [ __CLASS__, 'renderMovieInfobox' ] );
        $parser->setFunctionHook( 'bookinfobox', [ __CLASS__, 'renderBookInfobox' ] );
        $parser->setFunctionHook( 'gameinfobox', [ __CLASS__, 'renderGameInfobox' ] );
        
        return true;
    }
    
    /**
     * Handle database schema updates
     */
    public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
        $dir = __DIR__ . '/../sql/';
        $updater->addExtensionTable( 'horror_ratings', $dir . 'horror_ratings.sql' );
        $updater->addExtensionTable( 'content_warnings', $dir . 'content_warnings.sql' );
        $updater->addExtensionTable( 'horror_pages', $dir . 'horror_pages.sql' );
        $updater->addExtensionTable( 'horror_categories', $dir . 'horror_categories.sql' );
        $updater->addExtensionTable( 'user_horror_preferences', $dir . 'user_horror_preferences.sql' );
        
        return true;
    }
    
    /**
     * Render content warning widget
     */
    public static function renderContentWarning( $parser, $type = '', $description = '', $severity = '3' ) {
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
        
        // Store warning in database
        self::storeContentWarning( $parser->getTitle()->getArticleID(), $type, $description, $severity );
        
        return [ $html, 'noparse' => true, 'isHTML' => true ];
    }
    
    /**
     * Render horror rating widget
     */
    public static function renderHorrorRating( $parser, $gore = 0, $fear = 0, $disturbing = 0, $overall = 0 ) {
        $pageId = $parser->getTitle()->getArticleID();
        $ratings = self::getPageRatings( $pageId );
        
        $html = '<div class="horror-rating-widget" data-page-id="' . $pageId . '">';
        $html .= '<div class="rating-title">💀 Horror Intensity Ratings</div>';
        $html .= '<div class="rating-bars">';
        
        $categories = [
            'Gore Level' => [ 'value' => intval( $gore ), 'user_avg' => $ratings['gore'] ?? 0 ],
            'Fear Factor' => [ 'value' => intval( $fear ), 'user_avg' => $ratings['fear'] ?? 0 ],
            'Disturbing Content' => [ 'value' => intval( $disturbing ), 'user_avg' => $ratings['disturbing'] ?? 0 ]
        ];
        
        if ( intval( $overall ) > 0 ) {
            $categories['Overall Rating'] = [ 'value' => intval( $overall ), 'user_avg' => $ratings['overall'] ?? 0 ];
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
        
        // Add user rating interface for logged-in users
        global $wgUser;
        if ( !$wgUser->isAnon() ) {
            $html .= '<div class="user-rating-section">';
            $html .= '<div class="rating-prompt">Rate this content:</div>';
            $html .= '<div class="user-rating-controls">';
            foreach ( array_keys( $categories ) as $category ) {
                $catKey = strtolower( str_replace( ' ', '_', $category ) );
                $html .= '<div class="user-rating-row" data-category="' . $catKey . '">';
                $html .= '<span class="rating-label">' . $category . '</span>';
                $html .= '<div class="rating-input">';
                for ( $i = 1; $i <= 5; $i++ ) {
                    $html .= '<span class="rating-skull clickable" data-rating="' . $i . '">💀</span>';
                }
                $html .= '</div></div>';
            }
            $html .= '<button class="submit-rating btn-horror">Submit Ratings</button>';
            $html .= '</div></div>';
        }
        
        $html .= '</div>';
        
        return [ $html, 'noparse' => true, 'isHTML' => true ];
    }
    
    /**
     * Render spoiler section
     */
    public static function renderSpoiler( $parser, $content = '', $title = 'Spoiler Alert' ) {
        $html = '<div class="horror-spoiler">';
        $html .= '<div class="spoiler-warning" data-title="' . htmlspecialchars( $title ) . '">';
        $html .= '<span class="spoiler-icon">⚠️</span>';
        $html .= '<span class="spoiler-text">' . htmlspecialchars( $title ) . ' - Click to reveal</span>';
        $html .= '</div>';
        $html .= '<div class="spoiler-content">' . $parser->recursiveTagParse( $content ) . '</div>';
        $html .= '</div>';
        
        return [ $html, 'noparse' => true, 'isHTML' => true ];
    }
    
    /**
     * Render movie infobox
     */
    public static function renderMovieInfobox( $parser, $title = '', $year = '', $director = '', $genre = '', 
                                              $runtime = '', $rating = '', $poster = '', $trailer = '' ) {
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
        
        if ( !empty( $trailer ) ) {
            $html .= '<div class="infobox-row">';
            $html .= '<a href="' . htmlspecialchars( $trailer ) . '" class="trailer-link btn-horror" target="_blank">🎥 Watch Trailer</a>';
            $html .= '</div>';
        }
        
        $html .= '</div></div>';
        
        // Store movie data
        self::storeHorrorPageData( $parser->getTitle()->getArticleID(), 'movie', [
            'genre' => $genre,
            'year' => intval( $year ),
            'director' => $director,
            'runtime' => intval( $runtime ),
            'rating_mpaa' => $rating,
            'poster_url' => $poster,
            'trailer_url' => $trailer
        ]);
        
        return [ $html, 'noparse' => true, 'isHTML' => true ];
    }
    
    /**
     * Render book infobox
     */
    public static function renderBookInfobox( $parser, $title = '', $author = '', $year = '', $genre = '', 
                                             $pages = '', $isbn = '', $cover = '' ) {
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
        
        // Store book data
        self::storeHorrorPageData( $parser->getTitle()->getArticleID(), 'book', [
            'author' => $author,
            'genre' => $genre,
            'year' => intval( $year ),
            'pages' => intval( $pages ),
            'isbn' => $isbn,
            'cover_url' => $cover
        ]);
        
        return [ $html, 'noparse' => true, 'isHTML' => true ];
    }
    
    /**
     * Render game infobox
     */
    public static function renderGameInfobox( $parser, $title = '', $developer = '', $year = '', $platform = '', 
                                             $genre = '', $rating = '', $cover = '' ) {
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
        
        // Store game data
        self::storeHorrorPageData( $parser->getTitle()->getArticleID(), 'game', [
            'developer' => $developer,
            'genre' => $genre,
            'year' => intval( $year ),
            'platform' => $platform,
            'rating_esrb' => $rating,
            'cover_url' => $cover
        ]);
        
        return [ $html, 'noparse' => true, 'isHTML' => true ];
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
     * Store content warning in database
     */
    private static function storeContentWarning( $pageId, $type, $description, $severity ) {
        if ( $pageId > 0 ) {
            $dbw = wfGetDB( DB_MASTER );
            
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
    
    /**
     * Store horror page metadata
     */
    private static function storeHorrorPageData( $pageId, $contentType, $data ) {
        if ( $pageId > 0 ) {
            $dbw = wfGetDB( DB_MASTER );
            
            $insertData = [
                'hp_page_id' => $pageId,
                'hp_content_type' => $contentType
            ];
            
            // Add data fields with hp_ prefix
            foreach ( $data as $key => $value ) {
                $insertData['hp_' . $key] = $value;
            }
            
            $dbw->replace( 'horror_pages', [ 'hp_page_id' ], $insertData, __METHOD__ );
        }
    }
    
    /**
     * Get page ratings from database
     */
    private static function getPageRatings( $pageId ) {
        if ( $pageId <= 0 ) return [];
        
        $dbr = wfGetDB( DB_REPLICA );
        
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
    }
}