<?php
/**
 * HorrorTemplate - Template class for Horror skin with integrated features
 * Combines the original skin template with extension functionality
 * 
 * @file
 * @ingroup Skins
 */

class HorrorTemplate extends BaseTemplate {
    
    /**
     * Main template function - renders the complete horror-themed page
     */
    public function execute() {
        $this->html( 'headelement' );
        ?>
        <script>
            document.body.className += ' horror-theme-active';
            // Initialize horror configuration
            window.horrorWikiConfig = <?php echo json_encode( $this->getSkin()->getConfigVars()['wgHorrorWikiConfig'] ?? [] ); ?>;
        </script>
        
        <div id="horror-wrapper" class="horror-skin-wrapper">
            
            <!-- Horror Header with integrated navigation -->
            <?php $this->renderHorrorHeader(); ?>
            
            <!-- Main Content Area with sidebar -->
            <div id="horror-main" class="horror-main-content">
                
                <!-- Horror Sidebar with categories and tools -->
                <?php $this->renderHorrorSidebar(); ?>
                
                <!-- Content Area with integrated features -->
                <main id="horror-content" class="horror-content">
                    
                    <!-- Page Header with actions -->
                    <?php $this->renderPageHeader(); ?>
                    
                    <!-- Content warnings (if enabled and needed) -->
                    <?php $this->renderContentWarnings(); ?>
                    
                    <!-- Main Page Content -->
                    <div class="horror-page-content">
                        <?php $this->html( 'bodytext' ) ?>
                    </div>
                    
                    <!-- Horror-specific page footer elements -->
                    <?php $this->renderPageFooterElements(); ?>
                    
                    <!-- Category Links -->
                    <?php if ( $this->data['catlinks'] ) : ?>
                        <div class="horror-categories">
                            <?php $this->html( 'catlinks' ) ?>
                        </div>
                    <?php endif; ?>
                    
                </main>
                
            </div>
            
            <!-- Horror Footer -->
            <?php $this->renderHorrorFooter(); ?>
            
        </div>
        
        <!-- Horror-specific JavaScript initialization -->
        <script>
            $(document).ready(function() {
                if (typeof initializeHorrorFeatures === 'function') {
                    initializeHorrorFeatures();
                }
            });
        </script>
        
        </body>
        </html>
        <?php
    }
    
    /**
     * Render the horror-themed header
     */
    private function renderHorrorHeader() {
        ?>
        <header id="horror-header" class="horror-header">
            <div class="horror-header-content">
                
                <!-- Site Logo and Title -->
                <div class="horror-logo-section">
                    <a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" class="horror-logo">
                        <?php if ( isset( $this->data['logopath'] ) && $this->data['logopath'] ) : ?>
                            <img src="<?php $this->text( 'logopath' ) ?>" alt="<?php $this->text( 'sitename' ) ?>" />
                        <?php endif; ?>
                    </a>
                    <div class="horror-site-info">
                        <h1 class="horror-site-title">
                            <a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
                                <?php $this->text( 'sitename' ) ?>
                            </a>
                        </h1>
                        <div class="horror-site-tagline">Your Ultimate Horror Wiki</div>
                    </div>
                </div>
                
                <!-- Search Box -->
                <div class="horror-search-section">
                    <form action="<?php $this->text( 'wgScript' ) ?>" id="horror-searchform">
                        <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
                        <input id="horror-searchInput" name="search" type="search" 
                               placeholder="Search horror content..." 
                               value="<?php echo htmlspecialchars( $this->get( 'search', '' ) ) ?>" />
                        <button type="submit" class="horror-search-btn">
                            <span class="search-icon">🔍</span>
                        </button>
                    </form>
                </div>
                
                <!-- User Menu -->
                <div class="horror-user-section">
                    <?php $this->renderUserMenu(); ?>
                </div>
                
            </div>
            
            <!-- Horror Navigation -->
            <nav class="horror-navigation">
                <div class="horror-nav-content">
                    <?php $this->renderMainNavigation(); ?>
                </div>
            </nav>
            
        </header>
        <?php
    }
    
    /**
     * Render user menu
     */
    private function renderUserMenu() {
        $user = $this->getSkin()->getUser();
        
        if ( $user && $user->isRegistered() ) : ?>
            <div class="horror-user-menu">
                <span class="user-name">👤 <?php echo htmlspecialchars( $user->getName() ) ?></span>
                <div class="user-dropdown">
                    <a href="<?php echo htmlspecialchars( $user->getUserPage()->getLocalURL() ) ?>">👤 User Page</a>
                    <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Preferences' )->getLocalURL() ) ?>">⚙️ Preferences</a>
                    <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Watchlist' )->getLocalURL() ) ?>">👁️ Watchlist</a>
                    <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Contributions', $user->getName() )->getLocalURL() ) ?>">📝 Contributions</a>
                    <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'UserLogout' )->getLocalURL() ) ?>">🚪 Logout</a>
                </div>
            </div>
        <?php else : ?>
            <div class="horror-login-section">
                <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'UserLogin' )->getLocalURL() ) ?>" class="horror-login-btn">Login</a>
                <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'CreateAccount' )->getLocalURL() ) ?>" class="horror-register-btn">Register</a>
            </div>
        <?php endif;
    }
    
    /**
     * Render main navigation
     */
    private function renderMainNavigation() {
        ?>
        <div class="horror-main-nav">
            <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'HorrorDashboard' )->getLocalURL() ) ?>" class="horror-nav-item">🎭 Horror Hub</a>
            
            <!-- Categories dropdown -->
            <div class="horror-nav-item dropdown-nav">
                🗂️ Categories
                <div class="nav-dropdown">
                    <a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Movies' )->getLocalURL() ) ?>">🎬 Horror Movies</a>
                    <a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Books' )->getLocalURL() ) ?>">📚 Horror Books</a>
                    <a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Games' )->getLocalURL() ) ?>">🎮 Horror Games</a>
                    <a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Creepypasta' )->getLocalURL() ) ?>">📝 Creepypasta</a>
                    <a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Urban_Legends' )->getLocalURL() ) ?>">🏚️ Urban Legends</a>
                    <a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Characters' )->getLocalURL() ) ?>">👹 Horror Characters</a>
                </div>
            </div>
            
            <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'HorrorRatings' )->getLocalURL() ) ?>" class="horror-nav-item">💀 Top Rated</a>
            <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'RecentChanges' )->getLocalURL() ) ?>" class="horror-nav-item">🕐 Recent Changes</a>
            <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Random' )->getLocalURL() ) ?>" class="horror-nav-item">🎲 Random Page</a>
            
            <!-- Tools dropdown -->
            <div class="horror-nav-item dropdown-nav">
                🔧 Tools
                <div class="nav-dropdown">
                    <?php $this->renderToolsDropdown(); ?>
                </div>
            </div>
            
            <?php if ( $this->getSkin()->getUser()->isRegistered() ) : ?>
                <a href="#" class="horror-nav-item horror-create-page" id="create-horror-page-btn">✚ Create Horror Page</a>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render tools dropdown
     */
    private function renderToolsDropdown() {
        $toolbox = $this->getToolbox();
        foreach ( $toolbox as $key => $item ) {
            if ( isset( $item['href'] ) && isset( $item['text'] ) ) {
                echo '<a href="' . htmlspecialchars( $item['href'] ) . '">' . htmlspecialchars( $item['text'] ) . '</a>';
            }
        }
    }
    
    /**
     * Render horror sidebar
     */
    private function renderHorrorSidebar() {
        ?>
        <aside id="horror-sidebar" class="horror-sidebar">
            
            <!-- Horror Categories -->
            <div class="horror-sidebar-section">
                <h3 class="sidebar-header">🎭 Horror Categories</h3>
                <ul class="horror-category-list">
                    <li><a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Movies' )->getLocalURL() ) ?>">🎬 Horror Movies</a></li>
                    <li><a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Books' )->getLocalURL() ) ?>">📚 Horror Books</a></li>
                    <li><a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Games' )->getLocalURL() ) ?>">🎮 Horror Games</a></li>
                    <li><a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Creepypasta' )->getLocalURL() ) ?>">📝 Creepypasta</a></li>
                    <li><a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Urban_Legends' )->getLocalURL() ) ?>">🏚️ Urban Legends</a></li>
                    <li><a href="<?php echo htmlspecialchars( Title::makeTitle( NS_CATEGORY, 'Horror_Characters' )->getLocalURL() ) ?>">👹 Horror Characters</a></li>
                </ul>
            </div>
            
            <!-- Recent Activity -->
            <div class="horror-sidebar-section">
                <h3 class="sidebar-header">🕐 Recent Activity</h3>
                <ul class="sidebar-list">
                    <?php $this->renderRecentActivity(); ?>
                </ul>
            </div>
            
            <!-- MediaWiki Sidebar -->
            <?php $this->renderMediaWikiSidebar(); ?>
            
        </aside>
        <?php
    }
    
    /**
     * Render recent activity in sidebar
     */
    private function renderRecentActivity() {
        // Get recent changes (simplified)
        $dbr = wfGetDB( DB_REPLICA );
        $res = $dbr->select(
            'recentchanges',
            [ 'rc_title', 'rc_namespace', 'rc_timestamp' ],
            [],
            __METHOD__,
            [ 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => 5 ]
        );
        
        foreach ( $res as $row ) {
            $title = Title::makeTitle( $row->rc_namespace, $row->rc_title );
            if ( $title && $title->exists() ) {
                echo '<li><a href="' . htmlspecialchars( $title->getLocalURL() ) . '">' . 
                     htmlspecialchars( $title->getText() ) . '</a></li>';
            }
        }
    }
    
    /**
     * Render MediaWiki sidebar sections
     */
    private function renderMediaWikiSidebar() {
        $sidebar = $this->getSidebar();
        foreach ( $sidebar as $boxName => $box ) {
            if ( $boxName !== 'SEARCH' && $boxName !== 'TOOLBOX' && isset( $box['content'] ) ) {
                echo '<div class="horror-sidebar-section">';
                echo '<h3 class="sidebar-header">' . htmlspecialchars( $box['header'] ?? $boxName ) . '</h3>';
                if ( is_array( $box['content'] ) ) {
                    echo '<ul class="sidebar-list">';
                    foreach ( $box['content'] as $item ) {
                        if ( isset( $item['href'] ) && isset( $item['text'] ) ) {
                            echo '<li><a href="' . htmlspecialchars( $item['href'] ) . '">' . 
                                 htmlspecialchars( $item['text'] ) . '</a></li>';
                        }
                    }
                    echo '</ul>';
                }
                echo '</div>';
            }
        }
    }
    
    /**
     * Render page header with title and actions
     */
    private function renderPageHeader() {
        ?>
        <div class="horror-page-header">
            <h1 class="horror-page-title"><?php $this->html( 'title' ) ?></h1>
            
            <!-- Page Actions -->
            <div class="horror-page-actions">
                <?php $this->renderPageActions(); ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render page actions (edit, history, etc.)
     */
    private function renderPageActions() {
        if ( isset( $this->data['content_navigation']['views'] ) ) {
            foreach ( $this->data['content_navigation']['views'] as $key => $item ) {
                if ( isset( $item['href'] ) && isset( $item['text'] ) ) {
                    $class = 'horror-page-action';
                    if ( isset( $item['class'] ) ) {
                        $class .= ' ' . $item['class'];
                    }
                    echo '<a href="' . htmlspecialchars( $item['href'] ) . '" class="' . $class . '">' . 
                         htmlspecialchars( $item['text'] ) . '</a>';
                }
            }
        }
    }
    
    /**
     * Render content warnings if they exist for this page
     */
    private function renderContentWarnings() {
        global $wgHorrorWikiEnableWarnings;
        
        if ( !$wgHorrorWikiEnableWarnings ) {
            return;
        }
        
        $title = $this->getSkin()->getTitle();
        if ( !$title || !$title->exists() ) {
            return;
        }
        
        // Check for content warnings in page content
        $pageContent = $this->getPageContent();
        if ( strpos( $pageContent, '{{contentwarning' ) !== false ) {
            // Content warnings will be rendered by the parser function
            return;
        }
        
        // Auto-detect content warnings based on categories or keywords
        $warnings = $this->detectContentWarnings();
        if ( !empty( $warnings ) ) {
            echo '<div class="auto-detected-warnings">';
            echo '<div class="warning-header">⚠️ Content Warning</div>';
            echo '<div class="warning-description">This page may contain: ' . implode( ', ', $warnings ) . '</div>';
            echo '<button class="warning-accept btn-horror">I Understand</button>';
            echo '</div>';
        }
    }
    
    /**
     * Auto-detect content warnings
     */
    private function detectContentWarnings() {
        $title = $this->getSkin()->getTitle();
        $pageContent = strtolower( $this->getPageContent() );
        $warnings = [];
        
        // Check for warning keywords
        if ( strpos( $pageContent, 'gore' ) !== false || strpos( $pageContent, 'violence' ) !== false ) {
            $warnings[] = 'graphic violence';
        }
        if ( strpos( $pageContent, 'disturbing' ) !== false || strpos( $pageContent, 'unsettling' ) !== false ) {
            $warnings[] = 'disturbing content';
        }
        if ( strpos( $pageContent, 'jump scare' ) !== false ) {
            $warnings[] = 'jump scares';
        }
        
        return $warnings;
    }
    
    /**
     * Render page footer elements (ratings, etc.)
     */
    private function renderPageFooterElements() {
        // This will be handled by parser functions in the page content
    }
    
    /**
     * Render horror footer
     */
    private function renderHorrorFooter() {
        ?>
        <footer id="horror-footer" class="horror-footer">
            <div class="horror-footer-content">
                
                <div class="footer-section">
                    <h4>🎭 <?php $this->text( 'sitename' ) ?></h4>
                    <p>Your ultimate destination for horror movies, books, games, and legends.</p>
                </div>
                
                <div class="footer-section">
                    <h4>🔗 Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Statistics' )->getLocalURL() ) ?>">Wiki Stats</a></li>
                        <li><a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'RecentChanges' )->getLocalURL() ) ?>">Recent Changes</a></li>
                        <li><a href="<?php echo htmlspecialchars( Title::newFromText( 'Help:Contents' )->getLocalURL() ) ?>">Help</a></li>
                    </ul>
                </div>
                
                <div class="footer-copyright">
                    <p>&copy; <?php echo date('Y') ?> <?php $this->text( 'sitename' ) ?>. Powered by MediaWiki &amp; HorrorWiki Extension.</p>
                </div>
                
            </div>
        </footer>
        <?php
    }
    
    /**
     * Get page content for analysis
     */
    private function getPageContent() {
        $title = $this->getSkin()->getTitle();
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
}