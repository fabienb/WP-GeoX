# WP-GeoX

Display conditional content based on visitor's location. Works with both Gutenberg and Classic Editor.

![Latest Version](https://img.shields.io/badge/release-v1.1.1-orange)
[![WordPress Version](https://img.shields.io/badge/wordpress-%3E%3D6.5-00749c)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0.html)

## Description

GeoX allows you to display content conditionally based on the visitor's geographic location. It works seamlessly with both the Gutenberg block editor and the Classic Editor.
It's a simple and effective alternative to the expensive plugins out there.

**I am happy to release this plugin for FREE. But if this is helpful to you in any way, please consider donating via [Paypal](https://paypal.me/fabienbutazzi) or use the Sponsor links in the sidebar to support this work and future enhancements.**

### Features

- Conditional content display based on city/country/continent
- Shortcodes to display visitor's location information
- Compatible with Gutenberg and Classic Editor
- Uses Google Maps API for accurate geolocation (with fallback)

## Installation

1. Upload the plugin files to the `/wp-content/plugins/geox` directory, or install the plugin through the WordPress plugins screen directly (not yet available)
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure the plugin in Settings -> GeoX and add your Google Maps API key

## Setting up Google Geolocation API

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Geolocation API:
   - In the sidebar, click on "APIs & Services" > "Library"
   - Search for "Geolocation API"
   - Click on "Enable"
4. Create credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "API Key"
   - Copy your new API key
5. (Recommended) Restrict the API key:
   - Click on the newly created API key
   - Under "Application restrictions", select "HTTP referrers"
   - Add your website's domain
   - Under "API restrictions", select "Geolocation API"
   - Click "Save"

_Please make sure you read the API documentation to understand if there is any cost involved with your usage_

## Usage

### Conditional Content Block (Gutenberg)

Use the GeoX Conditional Container block to wrap content that should only be displayed for specific locations. You can specify both include and exclude criteria in the block settings.
This works as a container block or wrapper, meaning you can add any other Gutenberg block (or multiple ones) inside GeoX.

### Conditional Content Shortcode

Here is an example:

`[geoX include="UK, Tokyo, EU" exclude="FR, Berlin"]
This content will be visible to visitors from the UK, Tokyo, or anywhere in Europe, except those from France or Berlin.
[/geoX]
`

You can also use the shortcode with only include or exclude criteria:

`[geoX exclude="US, CA"]
This content will be visible to visitors from everywhere except the US and Canada.
[/geoX]
`

### Location Information Shortcodes

If you need to display your visitor's information within the content, you can use these shortcodes:

- `[geoX_country]` - Displays the visitor's country name
- `[geoX_country_code]` - Displays the visitor's country code (2 letters)
- `[geoX_region]` - Displays the visitor's region or state
- `[geoX_city]` - Displays the visitor's city
- `[geoX_continent]` - Displays the visitor's continent name
- `[geoX_continent_code]` - Displays the visitor's continent code (2 letters)

## Changelog

### 1.1.1
- Improved error handling for both WP_Error cases and non-200 HTTP response codes

### 1.1.0
- Added a more comprehensive list of 3-letter ISO country codes

### 1.0.9
- Improved continent detection using internal country-to-continent mapping
- Enhanced reliability and efficiency of location data retrieval

[View full changelog](CHANGELOG.md)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPLv2 or later - see the [LICENSE](LICENSE) file for details.

## Support

If you find a bug or have a feature request, please [open an issue](https://github.com/fabienb/WP-GeoX/issues).
