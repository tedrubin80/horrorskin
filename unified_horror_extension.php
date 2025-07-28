{
	"name": "HorrorWiki",
	"version": "2.0.0",
	"author": [
		"Horror Wiki Development Team"
	],
	"url": "https://github.com/user/horror-wiki-complete",
	"description": "Complete horror wiki system with custom skin, content warnings, ratings, and special pages",
	"descriptionmsg": "horrorwiki-desc",
	"license-name": "MIT",
	"type": "other",
	"requires": {
		"MediaWiki": ">= 1.35.0"
	},
	"ValidSkinNames": {
		"horror": {
			"class": "SkinHorror",
			"args": [
				{
					"name": "horror",
					"template": "HorrorTemplate"
				}
			]
		}
	},
	"MessagesDirs": {
		"HorrorWiki": [
			"i18n"
		]
	},
	"AutoloadClasses": {
		"SkinHorror": "includes/SkinHorror.php",
		"HorrorTemplate": "includes/HorrorTemplate.php",
		"HorrorWikiHooks": "includes/HorrorWikiHooks.php",
		"SpecialHorrorDashboard": "includes/specials/SpecialHorrorDashboard.php",
		"SpecialContentWarnings": "includes/specials/SpecialContentWarnings.php",
		"SpecialHorrorRatings": "includes/specials/SpecialHorrorRatings.php"
	},
	"SpecialPages": {
		"HorrorDashboard": "SpecialHorrorDashboard",
		"ContentWarnings": "SpecialContentWarnings",
		"HorrorRatings": "SpecialHorrorRatings"
	},
	"Hooks": {
		"BeforePageDisplay": "HorrorWikiHooks::onBeforePageDisplay",
		"SkinTemplateNavigation::Universal": "HorrorWikiHooks::onSkinTemplateNavigation",
		"ParserFirstCallInit": "HorrorWikiHooks::onParserFirstCallInit",
		"LoadExtensionSchemaUpdates": "HorrorWikiHooks::onLoadExtensionSchemaUpdates"
	},
	"ResourceModules": {
		"skins.horror": {
			"styles": [
				"resources/horror-skin.css",
				"resources/horror-components.css"
			],
			"targets": [
				"desktop",
				"mobile"
			]
		},
		"skins.horror.js": {
			"scripts": [
				"resources/horror-navigation.js",
				"resources/horror-skin.js"
			],
			"dependencies": [
				"jquery"
			],
			"targets": [
				"desktop",
				"mobile"
			]
		},
		"ext.horrorwiki.features": {
			"scripts": [
				"resources/content-warnings.js",
				"resources/horror-ratings.js",
				"resources/special-pages.js"
			],
			"styles": [
				"resources/content-warnings.css",
				"resources/horror-ratings.css"
			],
			"dependencies": [
				"jquery",
				"skins.horror.js"
			],
			"targets": [
				"desktop",
				"mobile"
			]
		}
	},
	"ResourceFileModulePaths": {
		"localBasePath": "",
		"remoteExtPath": "HorrorWiki"
	},
	"config": {
		"HorrorWikiDefaultSkin": {
			"value": true,
			"description": "Automatically set horror skin for horror content pages"
		},
		"HorrorWikiEnableWarnings": {
			"value": true,
			"description": "Enable content warning system"
		},
		"HorrorWikiEnableRatings": {
			"value": true,
			"description": "Enable horror rating system"
		},
		"HorrorWikiAutoDetectContent": {
			"value": true,
			"description": "Auto-detect horror content and apply appropriate styling"
		}
	},
	"manifest_version": 2
}