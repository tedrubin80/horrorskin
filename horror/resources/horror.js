/**
 * Horror Wiki Interactive Features
 * Custom JavaScript for enhanced user experience
 */

$(document).ready(function() {
    initializeHorrorFeatures();
    setupContentWarnings();
    setupSpoilerSystem();
    setupDynamicNavigation();
    setupHorrorEffects();
    setupUserPageCreation();
});

function initializeHorrorFeatures() {
    console.log('🎃 Horror Wiki System Loaded');
    
    // Add horror-specific body classes based on page content
    detectContentType();
    
    // Initialize rating interactions
    setupRatingInteractions();
    
    // Add atmospheric sound effects (optional)
    setupAtmosphericEffects();
}

function detectContentType() {
    const content = $('#mw-content-text').text().toLowerCase();
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

function setupContentWarnings() {
    $('.horror-content-warning').each(function() {
        const $warning = $(this);
        const $content = $warning.nextAll();
        
        // Initially hide content after warning
        $content.hide();
        
        $warning.find('.warning-accept').click(function() {
            $warning.slideUp(300, function() {
                $content.fadeIn(500);
            });
            
            // Store user preference
            const warningType = $warning.data('warning-type');
            localStorage.setItem('horror_warning_' + warningType, 'accepted');
        });
        
        // Check if user has already accepted this type of warning
        const warningType = $warning.data('warning-type');
        if (localStorage.getItem('horror_warning_' + warningType) === 'accepted') {
            $warning.hide();
            $content.show();
        }
    });
}

function setupSpoilerSystem() {
    $('.horror-spoiler').each(function() {
        const $spoiler = $(this);
        const $warning = $spoiler.find('.spoiler-warning');
        const $content = $spoiler.find('.spoiler-content');
        
        $warning.click(function() {
            if ($content.hasClass('revealed')) {
                $content.removeClass('revealed').slideUp();
                $warning.text('Click to reveal spoiler');
            } else {
                $content.addClass('revealed').slideDown();
                $warning.text('Click to hide spoiler');
                
                // Add blood drip effect
                addBloodDrip($spoiler);
            }
        });
    });
}

function setupRatingInteractions() {
    $('.rating-skull').hover(
        function() {
            $(this).css('transform', 'scale(1.2) rotate(10deg)');
        },
        function() {
            $(this).css('transform', 'scale(1) rotate(0deg)');
        }
    );
    
    // Interactive rating system
    $('.horror-rating-widget').each(function() {
        const $widget = $(this);
        
        // Add click functionality to skulls for user ratings
        $widget.find('.rating-skull').click(function() {
            const $skull = $(this);
            const $row = $skull.closest('.rating-row');
            const rating = $skull.index() + 1;
            
            // Update visual rating
            $row.find('.rating-skull').each(function(index) {
                if (index < rating) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
            
            // Save user rating (if logged in)
            saveUserRating($row.find('.rating-label').text(), rating);
        });
    });
}

function setupDynamicNavigation() {
    // Create horror page functionality
    $('.horror-create-page').click(function(e) {
        e.preventDefault();
        showCreatePageModal();
    });
    
    // Add recently viewed pages to navigation
    updateRecentlyViewed();
    
    // Add horror-specific search suggestions
    enhanceSearchBox();
}

function showCreatePageModal() {
    const modalHTML = `
        <div id="horror-create-modal" class="horror-modal">
            <div class="horror-modal-content">
                <span class="horror-close">&times;</span>
                <h2>🎭 Create Horror Page</h2>
                <form id="horror-page-form">
                    <div class="form-group">
                        <label>Page Title:</label>
                        <input type="text" id="page-title" required>
                    </div>
                    <div class="form-group">
                        <label>Content Type:</label>
                        <select id="content-type">
                            <option value="movie">Horror Movie</option>
                            <option value="book">Horror Book</option>
                            <option value="game">Horror Game</option>
                            <option value="legend">Urban Legend</option>
                            <option value="creepypasta">Creepypasta</option>
                            <option value="character">Horror Character</option>
                            <option value="location">Haunted Location</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Content Warnings:</label>
                        <div class="warning-checkboxes">
                            <label><input type="checkbox" value="gore"> Gore/Violence</label>
                            <label><input type="checkbox" value="jump"> Jump Scares</label>
                            <label><input type="checkbox" value="psychological"> Psychological</label>
                            <label><input type="checkbox" value="disturbing"> Disturbing Content</label>
                            <label><input type="checkbox" value="suicide"> Suicide/Self-Harm</label>
                            <label><input type="checkbox" value="sexual"> Sexual Content</label>
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
    
    // Modal interactions
    $('#horror-create-modal').fadeIn();
    
    $('.horror-close').click(function() {
        $('#horror-create-modal').fadeOut(function() {
            $(this).remove();
        });
    });
    
    $('#horror-page-form').submit(function(e) {
        e.preventDefault();
        createHorrorPage();
    });
}

function createHorrorPage() {
    const title = $('#page-title').val();
    const contentType = $('#content-type').val();
    const warnings = [];
    const addToNav = $('#add-to-nav').is(':checked');
    
    $('.warning-checkboxes input:checked').each(function() {
        warnings.push($(this).val());
    });
    
    // Generate page template based on content type
    const pageTemplate = generatePageTemplate(contentType, warnings);
    
    // Create the page URL
    const pageUrl = mw.config.get('wgScript') + '?title=' + encodeURIComponent(title) + '&action=edit&preload=Template:' + contentType;
    
    // Add to navigation if requested
    if (addToNav) {
        addToCustomNavigation(title, pageUrl);
    }
    
    // Redirect to edit page with template
    window.location.href = pageUrl;
}

function generatePageTemplate(contentType, warnings) {
    let template = '';
    
    // Add content warnings
    if (warnings.length > 0) {
        warnings.forEach(warning => {
            template += `{{contentwarning|${warning}|Please be aware this content contains ${warning} elements}}\n\n`;
        });
    }
    
    // Add content-specific templates
    switch(contentType) {
        case 'movie':
            template += `{{horrorrating|gore=3|fear=4|disturbing=2}}\n\n`;
            template += `== Plot Summary ==\n\n== Cast ==\n\n== Production ==\n\n== Reception ==\n\n== Legacy ==\n\n`;
            break;
        case 'book':
            template += `{{horrorrating|gore=2|fear=4|disturbing=3}}\n\n`;
            template += `== Plot ==\n\n== Characters ==\n\n== Themes ==\n\n== Publication History ==\n\n== Adaptations ==\n\n`;
            break;
        case 'game':
            template += `{{horrorrating|gore=3|fear=5|disturbing=3}}\n\n`;
            template += `== Gameplay ==\n\n== Story ==\n\n== Development ==\n\n== Reception ==\n\n== Sequels ==\n\n`;
            break;
        default:
            template += `{{horrorrating|gore=1|fear=2|disturbing=1}}\n\n`;
            template += `== Description ==\n\n== History ==\n\n== Cultural Impact ==\n\n`;
    }
    
    return template;
}

function addToCustomNavigation(title, url) {
    // Store in localStorage for persistent navigation
    let customNav = JSON.parse(localStorage.getItem('horror_custom_nav') || '[]');
    customNav.push({title: title, url: url});
    localStorage.setItem('horror_custom_nav', JSON.stringify(customNav));
    
    // Add to current page navigation
    const navItem = `<li><a href="${url}" class="custom-horror-nav">${title}</a></li>`;
    $('#p-navigation ul').append(navItem);
}

function updateRecentlyViewed() {
    const currentPage = mw.config.get('wgPageName');
    let recentPages = JSON.parse(localStorage.getItem('horror_recent_pages') || '[]');
    
    // Add current page to recent list
    if (currentPage && !recentPages.includes(currentPage)) {
        recentPages.unshift(currentPage);
        recentPages = recentPages.slice(0, 5); // Keep only 5 recent pages
        localStorage.setItem('horror_recent_pages', JSON.stringify(recentPages));
    }
    
    // Add recent pages to navigation
    if (recentPages.length > 0) {
        const recentHTML = '<div class="portlet" id="p-recent"><h3>Recently Viewed</h3><div class="body"><ul>';
        recentPages.forEach(page => {
            recentHTML += `<li><a href="/wiki/${page}">${page.replace(/_/g, ' ')}</a></li>`;
        });
        recentHTML += '</ul></div></div>';
        $('#mw-panel').append(recentHTML);
    }
}

function enhanceSearchBox() {
    const searchBox = $('#searchInput');
    
    // Add horror-specific search suggestions
    const horrorTerms = [
        'Horror Movies', 'Creepypasta', 'Urban Legends', 'Haunted Locations',
        'Horror Games', 'Stephen King', 'Lovecraft', 'Zombie', 'Vampire',
        'Ghost Stories', 'Slasher Films', 'Psychological Horror'
    ];
    
    searchBox.autocomplete({
        source: horrorTerms,
        classes: {
            "ui-autocomplete": "horror-autocomplete"
        }
    });
}

function setupAtmosphericEffects() {
    // Add subtle horror effects
    addRandomGlitchEffect();
    setupScrollEffects();
    addAmbientParticles();
}

function addRandomGlitchEffect() {
    setInterval(() => {
        if (Math.random() < 0.01) { // 1% chance every second
            $('body').addClass('glitch-effect');
            setTimeout(() => {
                $('body').removeClass('glitch-effect');
            }, 100);
        }
    }, 1000);
}

function setupScrollEffects() {
    $(window).scroll(function() {
        const scrolled = $(window).scrollTop();
        const parallax = scrolled * 0.5;
        
        // Parallax effect for background
        $('body::before').css('transform', `translateY(${parallax}px)`);
        
        // Fade in elements as they come into view
        $('.horror-rating-widget, .horror-content-warning').each(function() {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('in-view');
            }
        });
    });
}

function addAmbientParticles() {
    // Create floating particles for atmosphere
    for (let i = 0; i < 10; i++) {
        const particle = $('<div class="ambient-particle"></div>');
        particle.css({
            left: Math.random() * 100 + '%',
            animationDelay: Math.random() * 10 + 's',
            animationDuration: (Math.random() * 10 + 10) + 's'
        });
        $('body').append(particle);
    }
}

function addBloodDrip(element) {
    const drip = $('<div class="blood-drip">🩸</div>');
    drip.css({
        position: 'absolute',
        top: element.offset().top,
        left: element.offset().left + Math.random() * element.width(),
        zIndex: 1000
    });
    
    $('body').append(drip);
    
    drip.animate({
        top: '+=' + (Math.random() * 200 + 100),
        opacity: 0
    }, 2000, function() {
        $(this).remove();
    });
}

function saveUserRating(category, rating) {
    if (mw.user.isAnon()) return;
    
    const pageId = mw.config.get('wgArticleId');
    const userId = mw.user.getId();
    
    // AJAX call to save rating
    $.ajax({
        url: mw.util.wikiScript('api'),
        type: 'POST',
        data: {
            action: 'horror-save-rating',
            page_id: pageId,
            user_id: userId,
            category: category,
            rating: rating,
            format: 'json'
        },
        success: function(data) {
            console.log('Rating saved successfully');
            showNotification('Rating saved!', 'success');
        },
        error: function() {
            showNotification('Failed to save rating', 'error');
        }
    });
}

function showNotification(message, type) {
    const notification = $(`<div class="horror-notification ${type}">${message}</div>`);
    $('body').append(notification);
    
    notification.fadeIn().delay(3000).fadeOut(function() {
        $(this).remove();
    });
}

// CSS for additional effects
const additionalCSS = `
.glitch-effect {
    animation: glitch 0.1s;
}

@keyframes glitch {
    0% { transform: translate(0); }
    20% { transform: translate(-2px, 2px); }
    40% { transform: translate(-2px, -2px); }
    60% { transform: translate(2px, 2px); }
    80% { transform: translate(2px, -2px); }
    100% { transform: translate(0); }
}

.ambient-particle {
    position: fixed;
    width: 4px;
    height: 4px;
    background: rgba(139, 0, 0, 0.3);
    border-radius: 50%;
    animation: float-particle linear infinite;
    pointer-events: none;
    z-index: -1;
}

@keyframes float-particle {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
}

.horror-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
}

.horror-modal-content {
    background: linear-gradient(135deg, #0D0D0D, #1A0A0A);
    margin: 5% auto;
    padding: 20px;
    border: 3px solid var(--blood-red);
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
    color: var(--bone-white);
    box-shadow: 0 0 30px rgba(139, 0, 0, 0.5);
}

.horror-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    z-index: 10000;
    display: none;
}

.horror-notification.success {
    background: #4CAF50;
}

.horror-notification.error {
    background: #f44336;
}

.in-view {
    animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
`;

// Inject additional CSS
$('<style>').prop('type', 'text/css').html(additionalCSS).appendTo('head');