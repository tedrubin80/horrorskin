/**
 * Horror Wiki Interactive Features
 * Custom JavaScript for enhanced user experience
 */

$(document).ready(function() {
    initializeHorrorFeatures();
    setupHorrorEffects();
    setupSearchEnhancements();
    setupNavigationEffects();
    setupDropdowns();
    setupUserMenu();
});

function setupDropdowns() {
    // Create category dropdown
    createCategoryDropdown();
    
    // Create tools dropdown  
    createToolsDropdown();
    
    // Add hover effects for dropdowns
    $('.horror-nav-item').hover(
        function() {
            const dropdown = $(this).find('.nav-dropdown');
            if (dropdown.length) {
                dropdown.stop(true, true).slideDown(200);
            }
        },
        function() {
            const dropdown = $(this).find('.nav-dropdown');
            if (dropdown.length) {
                dropdown.stop(true, true).slideUp(200);
            }
        }
    );
}

function createCategoryDropdown() {
    // Find the Categories nav item and add dropdown
    const categoriesLink = $('.horror-nav-item').filter(function() {
        return $(this).text().includes('Categories');
    });
    
    if (categoriesLink.length) {
        categoriesLink.css('position', 'relative');
        
        const dropdown = $(`
            <div class="nav-dropdown">
                <a href="/wiki/Category:Horror_Movies">🎬 Horror Movies</a>
                <a href="/wiki/Category:Horror_Books">📚 Horror Books</a>
                <a href="/wiki/Category:Horror_Games">🎮 Horror Games</a>
                <a href="/wiki/Category:Creepypasta">📝 Creepypasta</a>
                <a href="/wiki/Category:Urban_Legends">🏚️ Urban Legends</a>
                <a href="/wiki/Category:Horror_Characters">👹 Horror Characters</a>
            </div>
        `);
        
        categoriesLink.append(dropdown);
    }
}

function createToolsDropdown() {
    // Find tools section and make it a dropdown in navbar
    const toolsList = $('#horror-sidebar .sidebar-list a');
    
    if (toolsList.length) {
        const toolsDropdown = $('<div class="horror-nav-item tools-dropdown" style="position: relative;">🔧 Tools</div>');
        const dropdown = $('<div class="nav-dropdown"></div>');
        
        toolsList.each(function() {
            const link = $(this).clone();
            dropdown.append(link);
        });
        
        toolsDropdown.append(dropdown);
        $('.horror-main-nav').append(toolsDropdown);
    }
}

function setupUserMenu() {
    // Enhanced user dropdown functionality
    $('.horror-user-menu').hover(
        function() {
            $(this).find('.user-dropdown').stop(true, true).slideDown(200);
        },
        function() {
            $(this).find('.user-dropdown').stop(true, true).slideUp(200);
        }
    );
    
    // Add user menu styling if not present
    if ($('.horror-user-menu').length && !$('.user-dropdown').length) {
        createUserDropdown();
    }
}

function createUserDropdown() {
    const userSection = $('.horror-user-section');
    
    // Check if user is logged in by looking for user links
    const userLinks = userSection.find('a');
    
    if (userLinks.length > 0) {
        const userName = userSection.text().trim();
        
        const userMenu = $(`
            <div class="horror-user-menu">
                <span class="user-name">👤 User Menu</span>
                <div class="user-dropdown">
                    <a href="/wiki/Special:Preferences">⚙️ Preferences</a>
                    <a href="/wiki/Special:Watchlist">👁️ Watchlist</a>
                    <a href="/wiki/Special:Contributions">📝 Contributions</a>
                    <a href="/wiki/Special:Userlogout">🚪 Logout</a>
                </div>
            </div>
        `);
        
        userSection.empty().append(userMenu);
    }
}

function initializeHorrorFeatures() {
    console.log('🎃 Horror Wiki System Loaded');
    
    // Add horror-specific body classes
    $('body').addClass('horror-theme-active');
    
    // Detect content type and add appropriate classes
    detectContentType();
    
    // Initialize any interactive elements
    setupInteractiveElements();
}

function detectContentType() {
    const content = $('#horror-content').text().toLowerCase();
    const body = $('body');
    
    // Add classes based on content keywords
    if (content.includes('movie') || content.includes('film')) {
        body.addClass('horror-movie-page');
    }
    if (content.includes('book') || content.includes('novel')) {
        body.addClass('horror-book-page');
    }
    if (content.includes('game')) {
        body.addClass('horror-game-page');
    }
    if (content.includes('urban legend') || content.includes('folklore')) {
        body.addClass('horror-legend-page');
    }
}

function setupInteractiveElements() {
    // Add hover effects to navigation items
    $('.horror-nav-item').hover(
        function() {
            $(this).css('transform', 'translateY(-2px) scale(1.05)');
        },
        function() {
            $(this).css('transform', 'translateY(0) scale(1)');
        }
    );
    
    // Add click effects to buttons
    $('.horror-search-btn, .horror-login-btn, .horror-register-btn').click(function() {
        $(this).addClass('clicked');
        setTimeout(() => {
            $(this).removeClass('clicked');
        }, 200);
    });
    
    // Smooth scrolling for internal links
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 20
            }, 500);
        }
    });
}

function setupSearchEnhancements() {
    const searchInput = $('#horror-searchInput');
    
    if (searchInput.length) {
        // Add search suggestions for horror content
        const horrorTerms = [
            'Horror Movies', 'Creepypasta', 'Urban Legends', 'Haunted Locations',
            'Horror Games', 'Stephen King', 'H.P. Lovecraft', 'Zombie', 'Vampire',
            'Ghost Stories', 'Slasher Films', 'Psychological Horror'
        ];
        
        // Simple autocomplete functionality
        searchInput.on('input', function() {
            const value = $(this).val().toLowerCase();
            if (value.length > 2) {
                const suggestions = horrorTerms.filter(term => 
                    term.toLowerCase().includes(value)
                );
                
                // Remove existing suggestions
                $('.horror-search-suggestions').remove();
                
                if (suggestions.length > 0) {
                    const suggestionsList = $('<div class="horror-search-suggestions"></div>');
                    suggestions.slice(0, 5).forEach(suggestion => {
                        const item = $(`<div class="suggestion-item">${suggestion}</div>`);
                        item.click(function() {
                            searchInput.val(suggestion);
                            $('.horror-search-suggestions').remove();
                        });
                        suggestionsList.append(item);
                    });
                    searchInput.parent().append(suggestionsList);
                }
            } else {
                $('.horror-search-suggestions').remove();
            }
        });
        
        // Hide suggestions when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.horror-search-section').length) {
                $('.horror-search-suggestions').remove();
            }
        });
    }
}

function setupHorrorEffects() {
    // Add subtle atmospheric effects
    setupScrollEffects();
    addRandomGlitchEffect();
    
    // Add blood drip effect on certain interactions
    $('.horror-nav-item').click(function() {
        addBloodDrip($(this));
    });
}

function setupScrollEffects() {
    let ticking = false;
    
    $(window).scroll(function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                const scrolled = $(window).scrollTop();
                
                // Parallax effect for header
                $('#horror-header').css('transform', `translateY(${scrolled * 0.1}px)`);
                
                // Fade in sidebar sections as they come into view
                $('.horror-sidebar-section').each(function() {
                    const elementTop = $(this).offset().top;
                    const elementBottom = elementTop + $(this).outerHeight();
                    const viewportTop = $(window).scrollTop();
                    const viewportBottom = viewportTop + $(window).height();
                    
                    if (elementBottom > viewportTop && elementTop < viewportBottom) {
                        $(this).addClass('in-view');
                    }
                });
                
                ticking = false;
            });
            ticking = true;
        }
    });
}

function setupNavigationEffects() {
    // Add pulsing effect to important navigation items
    $('.horror-nav-item').each(function(index) {
        const delay = index * 200;
        $(this).css('animation-delay', delay + 'ms');
    });
    
    // Add click tracking for popular pages
    $('.horror-nav-item, .sidebar-list a').click(function() {
        const pageName = $(this).text().trim();
        updateRecentlyViewed(pageName);
    });
}

function addRandomGlitchEffect() {
    // Add occasional glitch effect (very subtle)
    setInterval(() => {
        if (Math.random() < 0.005) { // 0.5% chance every second
            $('body').addClass('glitch-effect');
            setTimeout(() => {
                $('body').removeClass('glitch-effect');
            }, 150);
        }
    }, 1000);
}

function addBloodDrip(element) {
    if (Math.random() < 0.3) { // 30% chance
        const drip = $('<div class="blood-drip">🩸</div>');
        const rect = element[0].getBoundingClientRect();
        
        drip.css({
            position: 'fixed',
            top: rect.top + rect.height,
            left: rect.left + Math.random() * rect.width,
            zIndex: 1000,
            fontSize: '20px',
            pointerEvents: 'none'
        });
        
        $('body').append(drip);
        
        drip.animate({
            top: '+=' + (Math.random() * 100 + 50),
            opacity: 0
        }, 1500, function() {
            $(this).remove();
        });
    }
}

function updateRecentlyViewed(pageName) {
    try {
        let recentPages = JSON.parse(localStorage.getItem('horror_recent_pages') || '[]');
        
        // Remove if already exists and add to front
        recentPages = recentPages.filter(page => page !== pageName);
        recentPages.unshift(pageName);
        
        // Keep only 5 recent pages
        recentPages = recentPages.slice(0, 5);
        
        localStorage.setItem('horror_recent_pages', JSON.stringify(recentPages));
    } catch (e) {
        console.log('Could not update recently viewed pages');
    }
}

function showNotification(message, type = 'info') {
    const notification = $(`
        <div class="horror-notification ${type}">
            ${message}
        </div>
    `);
    
    $('body').append(notification);
    
    notification.fadeIn(300).delay(3000).fadeOut(300, function() {
        $(this).remove();
    });
}

// CSS for effects and dropdowns (injected dynamically)
const effectsCSS = `
.clicked {
    transform: scale(0.95) !important;
    transition: transform 0.1s ease;
}

.glitch-effect {
    animation: glitch 0.1s;
}

@keyframes glitch {
    0% { transform: translate(0); }
    20% { transform: translate(-1px, 1px); }
    40% { transform: translate(-1px, -1px); }
    60% { transform: translate(1px, 1px); }
    80% { transform: translate(1px, -1px); }
    100% { transform: translate(0); }
}

/* Navigation Dropdowns */
.nav-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 200px;
    background: linear-gradient(to bottom, var(--shadow-black), var(--mist-gray));
    border: 2px solid var(--blood-red);
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.nav-dropdown a {
    display: block !important;
    padding: 10px 15px !important;
    color: var(--bone-white) !important;
    text-decoration: none !important;
    border-bottom: 1px solid rgba(139, 0, 0, 0.3) !important;
    transition: all 0.3s ease !important;
    font-size: 0.9em !important;
}

.nav-dropdown a:hover {
    background: var(--blood-red) !important;
    color: var(--bone-white) !important;
    padding-left: 25px !important;
    text-shadow: 0 0 5px var(--bone-white) !important;
}

.nav-dropdown a:last-child {
    border-bottom: none !important;
    border-radius: 0 0 6px 6px;
}

/* User Dropdown */
.horror-user-menu {
    position: relative;
    cursor: pointer;
}

.user-name {
    color: var(--bone-white);
    font-weight: bold;
    padding: 8px 12px;
    background: linear-gradient(to bottom, var(--mist-gray), var(--shadow-black));
    border: 1px solid var(--blood-red);
    border-radius: 5px;
    display: inline-block;
}

.user-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 180px;
    background: linear-gradient(to bottom, var(--shadow-black), var(--mist-gray));
    border: 2px solid var(--blood-red);
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.user-dropdown a {
    display: block !important;
    padding: 8px 15px !important;
    color: var(--bone-white) !important;
    text-decoration: none !important;
    border-bottom: 1px solid rgba(139, 0, 0, 0.3) !important;
    transition: all 0.3s ease !important;
    font-size: 0.85em !important;
}

.user-dropdown a:hover {
    background: var(--blood-red) !important;
    color: var(--bone-white) !important;
    padding-left: 25px !important;
}

/* Tools Dropdown in Navbar */
.tools-dropdown {
    cursor: pointer;
}

.tools-dropdown .nav-dropdown {
    min-width: 160px;
}

/* Enhanced Navigation Hover Effects */
.horror-nav-item {
    position: relative;
    transition: all 0.3s ease;
}

.horror-nav-item:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(139, 0, 0, 0.4) !important;
}

/* Search Suggestions */
.horror-search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--shadow-black);
    border: 2px solid var(--blood-red);
    border-top: none;
    border-radius: 0 0 5px 5px;
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.suggestion-item {
    padding: 8px 12px;
    color: var(--bone-white);
    cursor: pointer;
    border-bottom: 1px solid rgba(139, 0, 0, 0.3);
    transition: background 0.2s ease;
}

.suggestion-item:hover {
    background: var(--blood-red);
}

.suggestion-item:last-child {
    border-bottom: none;
}

.horror-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--blood-red);
    color: var(--bone-white);
    padding: 12px 20px;
    border-radius: 5px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: none;
    font-weight: bold;
}

.horror-notification.success {
    background: #4CAF50;
}

.horror-notification.error {
    background: #f44336;
}

.horror-notification.info {
    background: var(--accent-orange);
}

.in-view {
    animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.horror-search-section {
    position: relative;
}

/* Mobile Responsive Dropdowns */
@media (max-width: 768px) {
    .nav-dropdown {
        position: fixed;
        top: auto;
        left: 10px;
        right: 10px;
        width: auto;
        min-width: auto;
    }
    
    .user-dropdown {
        position: fixed;
        top: auto;
        right: 10px;
        left: auto;
        width: auto;
        min-width: 200px;
    }
}
`;

// Inject the CSS
$('<style>').prop('type', 'text/css').html(effectsCSS).appendTo('head');