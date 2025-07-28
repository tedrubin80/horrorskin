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
        
        <div id="horror-wrapper" class="horror-skin-wrapper">
            
            <!-- Horror Header -->
            <header id="horror-header" class="horror-header">
                <div class="horror-header-content">
                    
                    <!-- Site Logo and Title -->
                    <div class="horror-logo-section">
                        <a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" class="horror-logo">
                            <img src="<?php $this->text( 'logopath' ) ?>" alt="<?php $this->text( 'sitename' ) ?>" />
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
                        <?php if ( $this->getSkin()->getUser()->isRegistered() ) : ?>
                            <div class="horror-user-menu">
                                <span class="user-name">👤 <?php echo htmlspecialchars( $this->getSkin()->getUser()->getName() ) ?></span>
                                <div class="user-dropdown">
                                    <a href="<?php echo htmlspecialchars( $this->getSkin()->getUser()->getUserPage()->getLocalURL() ) ?>">User Page</a>
                                    <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Preferences' )->getLocalURL() ) ?>">Preferences</a>
                                    <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Userlogout' )->getLocalURL() ) ?>">Logout</a>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="horror-login-section">
                                <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'Userlogin' )->getLocalURL() ) ?>" class="horror-login-btn">Login</a>
                                <a href="<?php echo htmlspecialchars( SpecialPage::getTitleFor( 'CreateAccount' )->getLocalURL() ) ?>" class="horror-register-btn">Register</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </div>
                
                <!-- Horror Navigation -->
                <nav class="horror-navigation">
                    <div class="horror-nav-content">
                        
                        <!-- Main Navigation -->
                        <div class="horror-main-nav">
                            <a href="/wiki/Special:AllPages" class="horror-nav-item">🎭 Horror Hub</a>
                            <a href="/wiki/Special:PopularPages" class="horror-nav-item">💀 Popular Pages</a>
                            <a href="/wiki/Special:Categories" class="horror-nav-item">🗂️ Categories</a>
                            <a href="/wiki/Special:RecentChanges" class="horror-nav-item">🕐 Recent Changes</a>
                            <a href="/wiki/Special:Random" class="horror-nav-item">🎲 Random Page</a>
                        </div>
                        
                        <!-- Action Navigation -->
                        <div class="horror-action-nav">
                            <?php if ( $this->getSkin()->getUser()->isRegistered() ) : ?>
                                <a href="/wiki/Special:CreatePage" class="horror-create-btn">✚ Create Horror Page</a>
                            <?php endif; ?>
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
                            <li><a href="/wiki/Category:Horror_Movies">🎬 Horror Movies</a></li>
                            <li><a href="/wiki/Category:Horror_Books">📚 Horror Books</a></li>
                            <li><a href="/wiki/Category:Horror_Games">🎮 Horror Games</a></li>
                            <li><a href="/wiki/Category:Creepypasta">📝 Creepypasta</a></li>
                            <li><a href="/wiki/Category:Urban_Legends">🏚️ Urban Legends</a></li>
                            <li><a href="/wiki/Category:Horror_Characters">👹 Horror Characters</a></li>
                        </ul>
                    </div>
                    
                    <!-- MediaWiki Sidebar -->
                    <?php foreach ( $this->getSidebar() as $boxName => $box ) : ?>
                        <?php if ( $boxName !== 'SEARCH' && $boxName !== 'TOOLBOX' ) : ?>
                            <div class="horror-sidebar-section">
                                <h3 class="sidebar-header"><?php echo htmlspecialchars( $box['header'] ?? $boxName ) ?></h3>
                                <?php if ( is_array( $box['content'] ?? null ) ) : ?>
                                    <ul class="sidebar-list">
                                        <?php foreach ( $box['content'] as $key => $item ) : ?>
                                            <li>
                                                <a href="<?php echo htmlspecialchars( $item['href'] ) ?>">
                                                    <?php echo htmlspecialchars( $item['text'] ) ?>
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
        <?php foreach ( $this->getSkin()->getToolbox() as $key => $item ) : ?>
            <li>
                <a href="<?php echo htmlspecialchars( $item['href'] ) ?>">
                    <?php echo htmlspecialchars( $item['text'] ) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
                <!-- Content Area -->
                <main id="horror-content" class="horror-content">
                    
                    <!-- Page Title -->
                    <div class="horror-page-header">
                        <h1 class="horror-page-title"><?php $this->html( 'title' ) ?></h1>
                        
                        <!-- Page Actions -->
                        <div class="horror-page-actions">
                            <?php foreach ( $this->data['content_navigation']['views'] ?? [] as $key => $item ) : ?>
                                <a href="<?php echo htmlspecialchars( $item['href'] ) ?>" 
                                   class="horror-page-action <?php echo $item['class'] ?? '' ?>">
                                    <?php echo htmlspecialchars( $item['text'] ) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Content Warnings (if applicable) -->
                    <div id="horror-content-warnings" class="horror-content-warnings"></div>
                    
                    <!-- Main Page Content -->
                    <div class="horror-page-content">
                        <?php $this->html( 'bodytext' ) ?>
                    </div>
                    
                    <!-- Category Links -->
                    <?php if ( $this->data['catlinks'] ) : ?>
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
                            <li><a href="/wiki/Special:About">About</a></li>
                            <li><a href="/wiki/Special:Contact">Contact</a></li>
                            <li><a href="/wiki/Special:PrivacyPolicy">Privacy</a></li>
                            <li><a href="/wiki/Help:Contents">Help</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h4>📊 Statistics</h4>
                        <ul>
                            <li><a href="/wiki/Special:Statistics">Wiki Stats</a></li>
                            <li><a href="/wiki/Special:RecentChanges">Recent Changes</a></li>
                            <li><a href="/wiki/Special:NewPages">New Pages</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-copyright">
                        <p>&copy; <?php echo date('Y') ?> <?php $this->text( 'sitename' ) ?>. Powered by MediaWiki.</p>
                    </div>
                    
                </div>
            </footer>
            
        </div>
        
        <?php $this->printTrail() ?>
        </body>
        </html>
        <?php
    }
}