# 🎭 Horror Wiki Integration Guide

## 📁 **Unified Directory Structure**

Based on your setup with the skin in a `skin/` folder, here's how to organize the integrated extension:

```
extensions/HorrorWiki/
├── extension.json                    # Modern extension registration
├── HorrorWiki.php                   # Legacy entry point
├── includes/
│   ├── SkinHorror.php              # Integrated skin class
│   ├── HorrorTemplate.php          # Integrated template class
│   ├── HorrorWikiHooks.php         # Unified hook system
│   └── specials/
│       ├── SpecialHorrorDashboard.php
│       ├── SpecialContentWarnings.php
│       └── SpecialHorrorRatings.php
├── resources/                       # Combined CSS & JS
│   ├── horror-skin.css             # Main skin styles (from skin repo)
│   ├── horror-components.css       # Component styles (combined)
│   ├── content-warnings.css        # Warning system styles
│   ├── horror-ratings.css          # Rating system styles
│   ├── horror-navigation.js        # Navigation features (from skin)
│   ├── horror-skin.js              # Skin interactions (from skin)
│   ├── content-warnings.js         # Warning interactions
│   ├── horror-ratings.js           # Rating interactions
│   └── special-pages.js            # Special page functionality
├── sql/
│   ├── horror_ratings.sql          # Rating tables
│   ├── content_warnings.sql        # Warning tables
│   ├── horror_pages.sql            # Page metadata
│   ├── horror_categories.sql       # Categories
│   └── user_horror_preferences.sql # User settings
├── i18n/
│   ├── en.json                     # English messages
│   └── qqq.json                    # Message documentation
└── docs/
    ├── INSTALLATION.md             # Setup guide
    ├── CONFIGURATION.md            # Config options
    └── INTEGRATION.md              # Migration guide
```

## 🔄 **Migration Steps from Two Repos**

### **Step 1: Merge Repository Files**

#### **From Skin Repository (`skin/` folder):**
```bash
# Copy skin files to unified extension
cp skin/includes/skinhorror.php extensions/HorrorWiki/includes/SkinHorror.php
cp skin/includes/horrortemplate.php extensions/HorrorWiki/includes/HorrorTemplate.php
cp skin/resources/horror.css extensions/HorrorWiki/resources/horror-skin.css
cp skin/resources/horror.js extensions/HorrorWiki/resources/horror-navigation.js
cp skin/i18n/en.json extensions/HorrorWiki/i18n/skin-en.json
```

#### **From Extension Repository:**
```bash
# Copy extension files
cp horror_wiki_hooks.php extensions/HorrorWiki/includes/HorrorWikiHooks.php
cp special_horror_dashboard.php extensions/HorrorWiki/includes/specials/SpecialHorrorDashboard.php
cp special_content_warnings.php extensions/HorrorWiki/includes/specials/SpecialContentWarnings.php
cp special_horror_ratings.php extensions/HorrorWiki/includes/specials/SpecialHorrorRatings.php
cp horror_theme_css.css extensions/HorrorWiki/resources/horror-components.css
cp horror_javascript.js extensions/HorrorWiki/resources/horror-skin.js
```

### **Step 2: Create Combined Resources**

#### **Merge CSS Files:**
```css
/* extensions/HorrorWiki/resources/horror-skin.css */
/* Combine content from: */
/* - skin/resources/horror.css (skin base styles) */
/* - horror_theme_css.css (component styles) */
/* - Additional responsive improvements */

/* Import skin variables first */
@import 'mediawiki.skin.variables.less';

/* Base horror theme variables */
:root {
    --blood-red: #8B0000;
    --dark-red: #4A0000;
    --bone-white: #F5F5DC;
    --shadow-black: #0D0D0D;
    --mist-gray: #2D2D2D;
    --accent-orange: #FF4500;
    --warning-yellow: #DAA520;
}

/* Combined skin and component styles... */
```

#### **Merge JavaScript Files:**
```javascript
/* extensions/HorrorWiki/resources/horror-navigation.js */
/* Combine content from: */
/* - skin/resources/horror.js (navigation & dropdowns) */
/* - horror_javascript.js (interactive features) */
/* - Enhanced integration features */

$(document).ready(function() {
    // Initialize both skin and extension features
    initializeHorrorSkin();
    initializeHorrorFeatures();
    setupIntegratedNavigation();
});
```

### **Step 3: Update Configuration**

#### **Create Modern extension.json:**
Use the provided `extension.json` artifact which includes:
- Skin registration
- Resource modules
- Hook handlers
- Configuration options
- Special pages

#### **Backward Compatibility (HorrorWiki.php):**
```php
<?php
// Maintain backward compatibility
if ( function_exists( 'wfLoadExtension' ) ) {
    wfLoadExtension( 'HorrorWiki' );
    return true;
} else {
    die( 'This version of HorrorWiki requires MediaWiki 1.35+' );
}
```

### **Step 4: Database Integration**

#### **Combine SQL Files:**
```sql
-- extensions/HorrorWiki/sql/tables.sql
-- Combined from both repositories

-- Create all tables from both systems
SOURCE horror_ratings.sql;
SOURCE content_warnings.sql;
SOURCE horror_pages.sql;
SOURCE horror_categories.sql;
SOURCE user_horror_preferences.sql;

-- Add indexes for integrated features
CREATE INDEX idx_integrated_horror_content ON horror_pages(hp_content_type, hp_year);
CREATE INDEX idx_user_skin_preferences ON user_horror_preferences(uhp_user_id, uhp_warning_type);
```

## 🎨 **Enhanced Integration Features**

### **Smart Skin Detection:**
The integrated system automatically:
- ✅ Detects horror content pages
- ✅ Applies horror skin automatically (if enabled)
- ✅ Loads appropriate CSS/JS resources
- ✅ Shows relevant navigation items

### **Unified User Experience:**
- 🎭 Single extension with both skin and features
- ⚙️ Unified preferences page
- 🔄 Seamless switching between regular and horror content
- 📱 Consistent mobile experience

### **Advanced Content Detection:**
```php
// Automatic horror content detection
private function isHorrorContentPage() {
    // Check categories, keywords, namespaces
    // Auto-apply horror styling and features
}
```

## 🚀 **Installation Process**

### **Option A: Fresh Installation**
```bash
# Clone the integrated repository
git clone https://github.com/user/horror-wiki-complete.git extensions/HorrorWiki

# Import database
mysql -u username -p database_name < extensions/HorrorWiki/sql/tables.sql

# Add to LocalSettings.php
echo "wfLoadExtension( 'HorrorWiki' );" >> LocalSettings.php

# Run database updates
php maintenance/update.php
```

### **Option B: Migration from Separate Repos**
```bash
# Backup existing installations
cp -r extensions/HorrorWiki extensions/HorrorWiki.backup
cp -r skins/horror skins/horror.backup

# Remove old installations
rm -rf extensions/HorrorWiki
rm -rf skins/horror

# Install integrated version
git clone https://github.com/user/horror-wiki-complete.git extensions/HorrorWiki

# Update LocalSettings.php
# Remove: wfLoadSkin( 'horror' );
# Replace with: wfLoadExtension( 'HorrorWiki' );

# Run updates
php maintenance/update.php
```

## ⚙️ **Configuration Options**

### **LocalSettings.php Configuration:**
```php
# Load the unified extension
wfLoadExtension( 'HorrorWiki' );

# Configuration options
$wgHorrorWikiDefaultSkin = true;        // Auto-apply to horror content
$wgHorrorWikiEnableWarnings = true;     // Enable warning system
$wgHorrorWikiEnableRatings = true;      // Enable rating system
$wgHorrorWikiAutoDetectContent = true;  // Smart content detection

# Set as default skin (optional)
$wgDefaultSkin = 'horror';

# Enable for all users (optional)
$wgSkipSkins = [ 'vector', 'monobook' ]; // Hide other skins
```

### **Advanced Configuration:**
```php
# Custom horror namespaces
$wgExtraNamespaces[100] = "HorrorMovie";
$wgExtraNamespaces[102] = "HorrorBook";
$wgExtraNamespaces[104] = "Creepypasta";

# Horror-specific permissions
$wgGroupPermissions['horroreditor']['horrorwiki-manage'] = true;
$wgGroupPermissions['horroreditor']['horrorwiki-rate'] = true;

# Content warning defaults
$wgHorrorWikiWarningDefaults = [
    'gore' => 3,
    'fear' => 4,
    'disturbing' => 2
];
```

## 🔧 **Feature Integration Benefits**

### **Unified System:**
- 📦 **Single Extension** - One package, easy maintenance
- 🎨 **Consistent Theming** - Skin and features match perfectly
- ⚡ **Optimized Loading** - Smart resource management
- 🔧 **Simplified Config** - One extension to configure

### **Enhanced Functionality:**
- 🔍 **Smart Detection** - Auto-applies to horror content
- 📊 **Integrated Analytics** - Combined skin and content stats
- 👥 **User Preferences** - Unified settings for skin and features
- 🎯 **SEO Optimized** - Structured data integration

### **Developer Benefits:**
- 🔄 **Modern Standards** - Uses extension.json format
- 🧪 **Easy Testing** - Single codebase to test
- 📝 **Better Documentation** - Unified docs and examples
- 🚀 **Future-Proof** - Ready for MediaWiki updates

## 📋 **Testing Checklist**

After integration, verify:

- [ ] ✅ Horror skin loads correctly
- [ ] ✅ Navigation dropdowns work
- [ ] ✅ Content warnings display
- [ ] ✅ Rating system functions
- [ ] ✅ Special pages accessible
- [ ] ✅ Mobile responsive design
- [ ] ✅ Auto-detection works
- [ ] ✅ User preferences save
- [ ] ✅ Database tables created
- [ ] ✅ Parser functions work

## 🎯 **Next Steps**

1. **Merge Repositories** - Combine both repos into unified structure
2. **Test Integration** - Verify all features work together
3. **Update Documentation** - Create unified installation guide
4. **Release Version** - Tag as v2.0.0 with integrated features
5. **Community Feedback** - Get user testing and feedback

The integrated Horror Wiki extension will provide a seamless, professional horror wiki experience with both custom theming and advanced functionality in a single, maintainable package! 🎭👹