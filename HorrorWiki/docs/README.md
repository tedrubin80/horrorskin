# 🎭 Horror Wiki Extension

A complete horror-themed MediaWiki extension with custom skin and advanced features.

## Features

- 🎨 **Custom Horror Skin** - Dark, atmospheric design
- ⚠️ **Content Warnings** - Customizable warning system
- 💀 **Horror Ratings** - Interactive rating system
- 🎯 **Special Pages** - Dashboard, ratings, warnings management
- 📱 **Responsive Design** - Works on all devices

## Installation

1. Extract to `extensions/HorrorWiki/`
2. Add `wfLoadExtension( 'HorrorWiki' );` to LocalSettings.php
3. Run `php maintenance/update.php`
4. Import SQL files from `sql/` directory

## Configuration

```php
$wgHorrorWikiDefaultSkin = true;       // Auto-apply to horror content
$wgHorrorWikiEnableWarnings = true;    // Enable warning system
$wgHorrorWikiEnableRatings = true;     // Enable rating system
```

## Generated on: 2025-07-28 22:44:22
