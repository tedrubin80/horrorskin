<?php
/**
 * HorrorTemplate - Template class for Horror skin
 * 
 * @file
 * @ingroup Skins
 */

class HorrorTemplate extends BaseTemplate {
    
    /**
     * Main template function
     */
    public function execute() {
        $this->html( 'headelement' );
        ?>
        <script>document.body.className += ' horror-theme-active';</script>
        <style>
        body { background: #0D0D0D !important; color: #F5F5DC !important; }
        #horror-wrapper { background: #0D0D0D; color: #F5F5DC; }
        .horror-nav-item { background: #8B0000 !important; color: #F5F5DC !important; padding: 8px 16px !important; }
        </style>
        
        <div id="horror-wrapper" class="horror-skin-wrapper">
            
            <!-- Horror Header -->
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
                        <?php if ( $this->getSkin()->getUser() && $this->getSkin()->getUser()->isRegistered() ) : ?>
                            <div class="horror-user-menu">
                                <span class="user-name">👤 <?php echo htmlspecialchars( $this->getSkin()->getUser()->getName() ) ?></span>
                                <div class="user-dropdown">
                                    <a href="<?php echo htmlspecialchars( $this->getSkin()->getUser()->getUserPage()->getLocalURL() ) ?>">User Page</a>
                                    <a href="<?php echo htmlspecialchars( Title::newFromText( 'Preferences', NS_SPECIAL )->getLocalURL() ) ?>">Preferences</a>
                                    <a href="<?php echo htmlspecialchars( Title::newFromText( 'Userlogout', NS_SPECIAL )->getLocalURL() ) ?>">Logout</a>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="horror-login-section">
                                <a href="<?php echo htmlspecialchars( Title::newFromText( 'Userlogin', NS_SPECIAL )->getLocalURL() ) ?>" class="horror-login-btn">Login</a>
                                <a href="<?php echo htmlspecialchars( Title::newFromText( 'CreateAccount', NS_SPECIAL )->getLocalURL() ) ?>" class="horror-register-btn">Register</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>
                
                <!-- Horror Navigation -->
                <nav class="horror-navigation">
                    <div class="horror-nav-content">
                        
                        <!-- Main Navigation -->
                        <div class="horror-main-nav">
                            <a href="<?php echo htmlspecialchars( Title::newFromText( 'AllPages', NS_SPECIAL )->getLocalURL() ) ?>" class="horror-nav-item">🎭 Horror Hub</a>
                            <a href="<?php echo htmlspecialchars( Title::newFromText( 'Categories', NS_SPECIAL )->getLocalURL() ) ?>" class="horror-nav-item">🗂️ Categories</a>
                            <a href="<?php echo htmlspecialchars( Title::newFromText( 'RecentChanges', NS_SPECIAL )->getLocalURL() ) ?>" class="horror-nav-item">🕐 Recent Changes</a>
                            <a href="<?php echo htmlspecialchars( Title::newFromText( 'Random', NS_SPECIAL )->getLocalURL() ) ?>" class="horror-nav-item">🎲 Random Page</a>
                        </div>
                        
                    </div>
                </nav>
                
            </header>
            
            <!-- Main Content Area -->
            <div id="horror-main" class="horror-main-content">
                
                <!-- Sidebar -->
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
                    
                    <!-- MediaWiki Sidebar -->
                    <?php 
                    $sidebar = $this->getSidebar();
                    foreach ( $sidebar as $boxName => $box ) : ?>
                        <?php if ( $boxName !== 'SEARCH' && $boxName !== 'TOOLBOX' ) : ?>
                            <div class="horror-sidebar-section">
                                <h3 class="sidebar-header"><?php echo htmlspecialchars( $box['header'] ?? $boxName ) ?></h3>
                                <?php if ( is_array( $box['content'] ?? null ) ) : ?>
                                    <ul class="sidebar-list">
                                        <?php foreach ( $box['content'] as $key => $item ) : ?>
                                            <li>
                                                <a href="<?php echo htmlspecialchars( $item['href'] ?? '' ) ?>">
                                                    <?php echo htmlspecialchars( $item['text'] ?? '' ) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <!-- Tools -->
                    <div class="horror-sidebar-section">
                        <h3 class="sidebar-header">🔧 Tools</h3>
                        <ul class="sidebar-list">
                            <?php 
                            if ( isset( $sidebar['TOOLBOX']['content'] ) && is_array( $sidebar['TOOLBOX']['content'] ) ) :
                                foreach ( $sidebar['TOOLBOX']['content'] as $key => $item ) : ?>
                                    <li>
                                        <a href="<?php echo htmlspecialchars( $item['href'] ?? '' ) ?>">
                                            <?php echo htmlspecialchars( $item['text'] ?? '' ) ?>
                                        </a>
                                    </li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </div>
                    
                </aside>
                
                <!-- Content Area -->
                <main id="horror-content" class="horror-content">
                    
                    <!-- Page Title -->
                    <div class="horror-page-header">
                        <h1 class="horror-page-title"><?php $this->html( 'title' ) ?></h1>
                        
                        <!-- Page Actions -->
                        <div class="horror-page-actions">
                            <?php if ( isset( $this->data['content_navigation']['views'] ) ) : ?>
                                <?php foreach ( $this->data['content_navigation']['views'] as $key => $item ) : ?>
                                    <a href="<?php echo htmlspecialchars( $item['href'] ?? '' ) ?>" 
                                       class="horror-page-action <?php echo htmlspecialchars( $item['class'] ?? '' ) ?>">
                                        <?php echo htmlspecialchars( $item['text'] ?? '' ) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Main Page Content -->
                    <div class="horror-page-content">
                        <?php $this->html( 'bodytext' ) ?>
                    </div>
                    
                    <!-- Category Links -->
                    <?php if ( isset( $this->data['catlinks'] ) && $this->data['catlinks'] ) : ?>
                        <div class="horror-categories">
                            <?php $this->html( 'catlinks' ) ?>
                        </div>
                    <?php endif; ?>
                    
                </main>
                
            </div>
            
            <!-- Horror Footer -->
            <footer id="horror-footer" class="horror-footer">
                <div class="horror-footer-content">
                    
                    <div class="footer-section">
                        <h4>🎭 <?php $this->text( 'sitename' ) ?></h4>
                        <p>Your ultimate destination for horror movies, books, games, and legends.</p>
                    </div>
                    
                    <div class="footer-section">
                        <h4>🔗 Quick Links</h4>
                        <ul>
                            <li><a href="<?php echo htmlspecialchars( Title::newFromText( 'Statistics', NS_SPECIAL )->getLocalURL() ) ?>">Wiki Stats</a></li>
                            <li><a href="<?php echo htmlspecialchars( Title::newFromText( 'RecentChanges', NS_SPECIAL )->getLocalURL() ) ?>">Recent Changes</a></li>
                            <li><a href="<?php echo htmlspecialchars( Title::newFromText( 'Help:Contents' )->getLocalURL() ) ?>">Help</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-copyright">
                        <p>&copy; <?php echo date('Y') ?> <?php $this->text( 'sitename' ) ?>. Powered by MediaWiki.</p>
                    </div>
                    
                </div>
            </footer>
            
        </div>
        
        </body>
        </html>
        <?php
    }
}