/**
 * HorrorWiki Combined Resources JavaScript
 * Integrates skin navigation with extension features
 * File: extensions/HorrorWiki/resources/horror-navigation.js
 */

$(document).ready(function() {
    // Initialize all horror wiki features
    initializeHorrorSkin();
    initializeHorrorFeatures();
    setupIntegratedNavigation();
    setupResponsiveFeatures();
    console.log('🎭 Horror Wiki System Loaded');
});

/**
 * Initialize Horror Skin Features (from skin repo)
 */
function initializeHorrorSkin() {
    // Setup enhanced dropdowns
    setupEnhancedDropdowns();
    
    // Setup search enhancements
    setupSearchEnhancements();
    
    // Setup user menu interactions
    setupUserMenuInteractions();
    
    // Setup responsive navigation
    setupResponsiveNavigation();
}

/**
 * Initialize Horror Extension Features (from extension repo)
 */
function initializeHorrorFeatures() {
    // Only initialize if horror features are enabled
    if (window.horrorWikiConfig && window.horrorWikiConfig.isHorrorPage) {
        setupContentWarnings();
        setupHorrorRatings();
        setupSpoilerSystem();
        setupPageCreation();
        setupAtmosphericEffects();
    }
}

/**
 * Setup Enhanced Dropdowns (Integrated from both repos)
 */
function setupEnhancedDropdowns() {
    // Enhanced dropdown behavior with better touch support
    $('.dropdown-nav, .horror-user-menu').each(function() {
        const $dropdown = $(this);
        const $menu = $dropdown.find('.nav-dropdown, .user-dropdown');
        let timeoutId;
        
        // Mouse events
        $dropdown.on('mouseenter', function() {
            clearTimeout(timeoutId);
            $menu.stop(true, true).slideDown(200);
        });
        
        $dropdown.on('mouseleave', function() {
            timeoutId = setTimeout(function() {
                $menu.stop(true, true).slideUp(200);
            }, 300);
        });
        
        // Touch events for mobile
        $dropdown.on('touchstart', function(e) {
            e.preventDefault();
            if ($menu.is(':visible')) {
                $menu.slideUp(200);
            } else {
                $('.nav-dropdown, .user-dropdown').slideUp(200);
                $menu.slideDown(200);
            }
        });
        
        // Keyboard navigation
        $dropdown.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $menu.is(':visible') ? $menu.slideUp(200) : $menu.slideDown(200);
            }
            if (e.key === 'Escape') {
                $menu.slideUp(200);
            }
        });
    });
    
    // Close dropdowns when clicking outside
    $(document).on('click touchstart', function(e) {
        if (!$(e.target).closest('.dropdown-nav, .horror-user-menu').length) {
            $('.nav-dropdown, .user-dropdown').slideUp(200);
        }
    });
}

/**
 * Setup Search Enhancements (Integrated)
 */
function setupSearchEnhancements() {
    const $searchInput = $('#horror-searchInput, #horror-searchform input[type="search"]');
    
    if ($searchInput.length) {
        // Horror-specific search suggestions
        const horrorTerms = [
            'Horror Movies', 'Horror Films', 'Slasher Movies', 'Psychological Horror',
            'Creepypasta', 'Urban Legends', 'Ghost Stories', 'Vampire Movies',
            'Zombie Horror', 'Supernatural Horror', 'Horror Books', 'Horror Games',
            'Haunted Locations', 'Horror Characters', 'Stephen King', 'H.P. Lovecraft',
            'Classic Horror', 'Modern Horror', 'Horror Directors', 'Scream Queens'
        ];
        
        // Enhanced autocomplete with horror focus
        $searchInput.on('input', function() {
            const value = $(this).val().toLowerCase();
            if (value.length > 2) {
                const suggestions = horrorTerms.filter(term => 
                    term.toLowerCase().includes(value)
                );
                
                // Remove existing suggestions
                $('.horror-search-suggestions').remove();
                
                if (suggestions.length > 0) {
                    const $suggestionsList = $('<div class="horror-search-suggestions"></div>');
                    
                    suggestions.slice(0, 6).forEach(suggestion => {
                        const $item = $(`<div class="suggestion-item">${suggestion}</div>`);
                        $item.on('click', function() {
                            $searchInput.val(suggestion);
                            $('.horror-search-suggestions').remove();
                            // Auto-submit search
                            $searchInput.closest('form').submit();
                        });
                        $suggestionsList.append($item);
                    });
                    
                    $searchInput.parent().append($suggestionsList);
                }
            } else {
                $('.horror-search-suggestions').remove();
            }
        });
        
        // Keyboard navigation for suggestions
        $searchInput.on('keydown', function(e) {
            const $suggestions = $('.suggestion-item');
            const $active = $suggestions.filter('.active');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if ($active.length === 0) {
                    $suggestions.first().addClass('active');
                } else {
                    $active.removeClass('active').next().addClass('active');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if ($active.length > 0) {
                    $active.removeClass('active').prev().addClass('active');
                }
            } else if (e.key === 'Enter' && $active.length > 0) {
                e.preventDefault();
                $active.click();
            } else if (e.key === 'Escape') {
                $('.horror-search-suggestions').remove();
            }
        });
        
        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.horror-search-section').length) {
                $('.horror-search-suggestions').remove();
            }
        });
    }
}

/**
 * Setup User Menu Interactions (Enhanced)
 */
function setupUserMenuInteractions() {
    // Enhanced user menu with preference shortcuts
    $('.user-dropdown a').on('click', function(e) {
        const href = $(this).attr('href');
        
        // Add loading state for preference pages
        if (href && href.includes('Preferences')) {
            $(this).append(' <span class="loading-spinner">⟳</span>');
        }
    });
    
    // Add user activity indicators
    updateUserActivityIndicators();
}

/**
 * Setup Content Warnings (From extension repo)
 */
function setupContentWarnings() {
    $('.horror-content-warning').each(function() {
        const $warning = $(this);
        const $content = $warning.nextAll().not('.horror-content-warning');
        const warningType = $warning.data('warning-type');
        
        // Initially hide content after warning
        $content.hide();
        
        // Check user preferences
        const userPrefs = getUserWarningPreferences();
        if (userPrefs[warningType] && userPrefs[warningType].autoAccept) {
            $warning.hide();
            $content.show();
            return;
        }
        
        // Warning accept button
        $warning.find('.warning-accept').on('click', function() {
            $warning.addClass('accepting');
            
            setTimeout(function() {
                $warning.slideUp(300, function() {
                    $content.fadeIn(500);
                    addBloodDrip($warning);
                });
            }, 500);
            
            // Store user preference
            if (warningType) {
                storeWarningPreference(warningType, 'accepted');
            }
        });
        
        // Warning settings button
        $warning.find('.warning-settings').on('click', function() {
            showWarningSettingsModal(warningType);
        });
    });
}

/**
 * Setup Horror Ratings (From extension repo)
 */
function setupHorrorRatings() {
    $('.horror-rating-widget').each(function() {
        const $widget = $(this);
        const pageId = $widget.data('page-id');
        
        // Interactive rating skulls
        $widget.find('.rating-skull').hover(
            function() {
                $(this).css('transform', 'scale(1.2) rotate(10deg)');
            },
            function() {
                $(this).css('transform', 'scale(1) rotate(0deg)');
            }
        );
        
        // User rating functionality
        $widget.find('.user-rating-controls .rating-skull').on('click', function() {
            const $skull = $(this);
            const $row = $skull.closest('.user-rating-row');
            const category = $row.data('category');
            const rating = $skull.data('rating');
            
            // Update visual rating
            $row.find('.rating-skull').each(function(index) {
                if (index < rating) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
            
            // Store temporary rating
            $row.data('user-rating', rating);
        });
        
        // Submit ratings
        $widget.find('.submit-rating').on('click', function() {
            const ratings = {};
            
            $widget.find('.user-rating-row').each(function() {
                const category = $(this).data('category');
                const rating = $(this).data('user-rating');
                if (rating) {
                    ratings[category] = rating;
                }
            });
            
            if (Object.keys(ratings).length > 0) {
                submitUserRatings(pageId, ratings);
            } else {
                showNotification('Please rate at least one category', 'warning');
            }
        });
    });
}

/**
 * Setup Spoiler System (From extension repo)
 */
function setupSpoilerSystem() {
    $('.horror-spoiler').each(function() {
        const $spoiler = $(this);
        const $warning = $spoiler.find('.spoiler-warning');
        const $content = $spoiler.find('.spoiler-content');
        
        $warning.on('click', function() {
            if ($content.hasClass('revealed')) {
                $content.removeClass('revealed').slideUp();
                $warning.find('.spoiler-text').text($warning.data('title') + ' - Click to reveal');
            } else {
                $content.addClass('revealed').slideDown();
                $warning.find('.spoiler-text').text('Click to hide spoiler');
                
                // Add blood drip effect
                addBloodDrip($spoiler);
            }
        });
    });
}

/**
 * Setup Page Creation (Enhanced from extension repo)
 */
function setupPageCreation() {
    $('#create-horror-page-btn, .horror-create-page').on('click', function(e) {
        e.preventDefault();
        showCreatePageModal();
    });
}

/**
 * Setup Integrated Navigation (New integration feature)
 */
function setupIntegratedNavigation() {
    // Add horror-specific navigation enhancements
    $('.horror-nav-item').each(function(index) {
        const $item = $(this);
        
        // Staggered animation delay
        $item.css('animation-delay', (index * 100) + 'ms');
        
        // Enhanced hover effects
        $item.hover(
            function() {
                $(this).addClass('nav-hover');
            },
            function() {
                $(this).removeClass('nav-hover');
            }
        );
        
        // Click tracking
        $item.on('click', function() {
            const pageName = $(this).text().trim();
            trackNavigation(pageName);
            
            // Add click effect
            $(this).addClass('nav-clicked');
            setTimeout(() => {
                $(this).removeClass('nav-clicked');
            }, 200);
        });
    });
    
    // Add breadcrumb navigation for horror content
    addHorrorBreadcrumbs();
}

/**
 * Setup Responsive Features
 */
function setupResponsiveFeatures() {
    // Mobile menu toggle
    if (window.innerWidth <= 768) {
        setupMobileNavigation();
    }
    
    // Handle window resize
    $(window).on('resize', function() {
        if (window.innerWidth <= 768) {
            setupMobileNavigation();
        } else {
            cleanupMobileNavigation();
        }
    });
}

/**
 * Setup Mobile Navigation
 */
function setupMobileNavigation() {
    // Add mobile menu toggle if not exists
    if (!$('.mobile-nav-toggle').length) {
        const $toggle = $('<button class="mobile-nav-toggle">☰</button>');
        $('.horror-header-content').prepend($toggle);
        
        $toggle.on('click', function() {
            $('.horror-main-nav').toggleClass('mobile-open');
            $(this).toggleClass('active');
        });
    }
}

/**
 * Cleanup Mobile Navigation
 */
function cleanupMobileNavigation() {
    $('.mobile-nav-toggle').remove();
    $('.horror-main-nav').removeClass('mobile-open');
}

/**
 * Setup Atmospheric Effects (From extension repo)
 */
function setupAtmosphericEffects() {
    // Add subtle horror effects
    addRandomGlitchEffect();
    setupScrollEffects();
    addAmbientParticles();
}

/**
 * Add Random Glitch Effect
 */
function addRandomGlitchEffect() {
    setInterval(() => {
        if (Math.random() < 0.002) { // 0.2% chance every second
            $('body').addClass('glitch-effect');
            setTimeout(() => {
                $('body').removeClass('glitch-effect');
            }, 150);
        }
    }, 1000);
}

/**
 * Setup Scroll Effects
 */
function setupScrollEffects() {
    let ticking = false;
    
    $(window).on('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                const scrolled = $(window).scrollTop();
                
                // Parallax effect for header
                $('#horror-header').css('transform', `translateY(${scrolled * 0.1}px)`);
                
                // Fade in elements as they come into view
                $('.horror-sidebar-section, .horror-rating-widget, .horror-content-warning').each(function() {
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

/**
 * Add Ambient Particles
 */
function addAmbientParticles() {
    for (let i = 0; i < 8; i++) {
        const $particle = $('<div class="ambient-particle"></div>');
        $particle.css({
            left: Math.random() * 100 + '%',
            animationDelay: Math.random() * 10 + 's',
            animationDuration: (Math.random() * 15 + 15) + 's'
        });
        $('body').append($particle);
    }
}

/**
 * Show Create Page Modal (Enhanced)
 */
function showCreatePageModal() {
    const modalHTML = `
        <div id="horror-create-modal" class="horror-modal">
            <div class="horror-modal-content">
                <span class="horror-close">&times;</span>
                <h2>🎭 Create Horror Page</h2>
                <form id="horror-page-form">
                    <div class="form-group">
                        <label>Page Title:</label>
                        <input type="text" id="page-title" required placeholder="Enter page title...">
                    </div>
                    <div class="form-group">
                        <label>Content Type:</label>
                        <select id="content-type">
                            <option value="movie">🎬 Horror Movie</option>
                            <option value="book">📚 Horror Book</option>
                            <option value="game">🎮 Horror Game</option>
                            <option value="legend">🏚️ Urban Legend</option>
                            <option value="creepypasta">📝 Creepypasta</option>
                            <option value="character">👹 Horror Character</option>
                            <option value="location">🏰 Haunted Location</option>
                            <option value="other">📄 Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Content Warnings:</label>
                        <div class="warning-checkboxes">
                            <label><input type="checkbox" value="gore"> 🩸 Gore/Violence</label>
                            <label><input type="checkbox" value="jump"> 😱 Jump Scares</label>
                            <label><input type="checkbox" value="psychological"> 🧠 Psychological</label>
                            <label><input type="checkbox" value="disturbing"> ⚠️ Disturbing Content</label>
                            <label><input type="checkbox" value="suicide"> 🚨 Suicide/Self-Harm</label>
                            <label><input type="checkbox" value="sexual"> 🔞 Sexual Content</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" id="add-to-nav"> Add to Navigation Menu</label>
                    </div>
                    <button type="submit" class="horror-btn">Create Page</button>
                </form>
            </div>
        </div>
    `;
    
    $('body').append(modalHTML);
    $('#horror-create-modal').fadeIn();
    
    // Modal interactions
    $('.horror-close').on('click', function() {
        $('#horror-create-modal').fadeOut(function() {
            $(this).remove();
        });
    });
    
    $('#horror-page-form').on('submit', function(e) {
        e.preventDefault();
        createHorrorPage();
    });
    
    // Close modal on outside click
    $('#horror-create-modal').on('click', function(e) {
        if (e.target === this) {
            $(this).fadeOut(function() {
                $(this).remove();
            });
        }
    });
}

/**
 * Create Horror Page (Enhanced)
 */
function createHorrorPage() {
    const title = $('#page-title').val();
    const contentType = $('#content-type').val();
    const warnings = [];
    const addToNav = $('#add-to-nav').is(':checked');
    
    $('.warning-checkboxes input:checked').each(function() {
        warnings.push($(this).val());
    });
    
    if (!title.trim()) {
        showNotification('Please enter a page title', 'error');
        return;
    }
    
    // Generate page URL
    const pageUrl = mw.config.get('wgScript') + '?title=' + encodeURIComponent(title) + '&action=edit&preloadtitle=' + encodeURIComponent(title);
    
    // Add to navigation if requested
    if (addToNav) {
        addToCustomNavigation(title, pageUrl);
    }
    
    // Store page creation data
    storePageCreationData(title, contentType, warnings);
    
    // Close modal and redirect
    $('#horror-create-modal').fadeOut(function() {
        $(this).remove();
        window.location.href = pageUrl;
    });
}

/**
 * Utility Functions
 */

function addBloodDrip(element) {
    if (Math.random() < 0.3) { // 30% chance
        const $drip = $('<div class="blood-drip">🩸</div>');
        const rect = element[0].getBoundingClientRect();
        
        $drip.css({
            position: 'fixed',
            top: rect.top + rect.height,
            left: rect.left + Math.random() * rect.width,
            zIndex: 1000,
            fontSize: '20px',
            pointerEvents: 'none'
        });
        
        $('body').append($drip);
        
        $drip.animate({
            top: '+=' + (Math.random() * 100 + 50),
            opacity: 0
        }, 1500, function() {
            $(this).remove();
        });
    }
}

function showNotification(message, type = 'info') {
    const $notification = $(`
        <div class="horror-notification ${type}">
            <span class="notification-icon">${getNotificationIcon(type)}</span>
            <span class="notification-text">${message}</span>
        </div>
    `);
    
    $('body').append($notification);
    
    $notification.fadeIn(300).delay(3000).fadeOut(300, function() {
        $(this).remove();
    });
}

function getNotificationIcon(type) {
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    return icons[type] || icons.info;
}

function getUserWarningPreferences() {
    try {
        return JSON.parse(localStorage.getItem('horror_warning_prefs') || '{}');
    } catch (e) {
        return {};
    }
}

function storeWarningPreference(type, status) {
    try {
        const prefs = getUserWarningPreferences();
        prefs[type] = { autoAccept: status === 'accepted', timestamp: Date.now() };
        localStorage.setItem('horror_warning_prefs', JSON.stringify(prefs));
    } catch (e) {
        console.log('Could not store warning preference');
    }
}

function trackNavigation(pageName) {
    try {
        let navHistory = JSON.parse(localStorage.getItem('horror_nav_history') || '[]');
        navHistory.unshift({ page: pageName, timestamp: Date.now() });
        navHistory = navHistory.slice(0, 10); // Keep last 10
        localStorage.setItem('horror_nav_history', JSON.stringify(navHistory));
    } catch (e) {
        console.log('Could not track navigation');
    }
}

function addToCustomNavigation(title, url) {
    try {
        let customNav = JSON.parse(localStorage.getItem('horror_custom_nav') || '[]');
        customNav.push({ title: title, url: url, timestamp: Date.now() });
        localStorage.setItem('horror_custom_nav', JSON.stringify(customNav));
        
        // Add to current page navigation
        const $navItem = $(`<a href="${url}" class="horror-nav-item custom-nav">${title}</a>`);
        $('.horror-main-nav').append($navItem);
    } catch (e) {
        console.log('Could not add to custom navigation');
    }
}

function storePageCreationData(title, contentType, warnings) {
    try {
        const data = {
            title: title,
            contentType: contentType,
            warnings: warnings,
            timestamp: Date.now(),
            user: mw.config.get('wgUserName') || 'Anonymous'
        };
        
        let creationHistory = JSON.parse(localStorage.getItem('horror_page_creation') || '[]');
        creationHistory.unshift(data);
        creationHistory = creationHistory.slice(0, 20); // Keep last 20
        localStorage.setItem('horror_page_creation', JSON.stringify(creationHistory));
    } catch (e) {
        console.log('Could not store page creation data');
    }
}

function addHorrorBreadcrumbs() {
    const currentTitle = mw.config.get('wgPageName');
    if (!currentTitle) return;
    
    // Simple breadcrumb for horror content
    const breadcrumbs = ['Main Page'];
    
    // Add category-based breadcrumb
    if (currentTitle.includes('Horror_Movie')) {
        breadcrumbs.push('Horror Movies');
    } else if (currentTitle.includes('Horror_Book')) {
        breadcrumbs.push('Horror Books');
    } else if (currentTitle.includes('Creepypasta')) {
        breadcrumbs.push('Creepypasta');
    }
    
    breadcrumbs.push(currentTitle.replace(/_/g, ' '));
    
    if (breadcrumbs.length > 2) {
        const $breadcrumbHtml = $('<div class="horror-breadcrumbs"></div>');
        breadcrumbs.forEach((crumb, index) => {
            if (index > 0) $breadcrumbHtml.append(' > ');
            if (index === breadcrumbs.length - 1) {
                $breadcrumbHtml.append(`<span class="current-page">${crumb}</span>`);
            } else {
                $breadcrumbHtml.append(`<a href="/wiki/${crumb.replace(/ /g, '_')}">${crumb}</a>`);
            }
        });
        
        $('.horror-page-header').prepend($breadcrumbHtml);
    }
}

function updateUserActivityIndicators() {
    // Add activity indicators for logged-in users
    if (mw.config.get('wgUserName')) {
        // Check for unread notifications, etc.
        // This would integrate with MediaWiki's notification system
    }
}

function submitUserRatings(pageId, ratings) {
    if (!mw.config.get('wgUserName')) {
        showNotification('Please log in to submit ratings', 'warning');
        return;
    }
    
    // AJAX submission would go here
    // For now, just show success message
    showNotification('Ratings submitted successfully!', 'success');
    
    // Refresh the rating display
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function showWarningSettingsModal(warningType) {
    // This would show a modal for warning settings
    showNotification('Warning settings feature coming soon!', 'info');
}