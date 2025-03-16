<?php
/**
 * GeoX Block Class
 *
 * Handles the registration and rendering of the GeoX conditional content block.
 *
 * @package GeoX
 * @since 1.0.0
 */

/**
 * Class GeoX_Block
 *
 * Manages the Gutenberg block for conditional content display based on geolocation.
 */
class GeoX_Block {
    /**
     * User location data
     *
     * @var array|null
     */
    private $user_location;

    /**
     * Cache duration in seconds
     *
     * @var int
     */
    private const CACHE_DURATION = 3600; // 1 hour

    /**
     * Continent codes and names
     *
     * @var array
     */
    private $continents = [
        'AF' => 'Africa',
        'AN' => 'Antarctica',
        'AS' => 'Asia',
        'EU' => 'Europe',
        'NA' => 'North America',
        'OC' => 'Oceania',
        'SA' => 'South America'
    ];

    /**
     * Mapping of country codes to continent codes
     *
     * @var array
     */
    private $country_to_continent = [
        'AD' => 'EU', 'AE' => 'AS', 'AF' => 'AS', 'AG' => 'NA', 'AI' => 'NA', 'AL' => 'EU', 'AM' => 'AS', 'AO' => 'AF', 'AQ' => 'AN', 'AR' => 'SA',
        'AS' => 'OC', 'AT' => 'EU', 'AU' => 'OC', 'AW' => 'NA', 'AX' => 'EU', 'AZ' => 'AS', 'BA' => 'EU', 'BB' => 'NA', 'BD' => 'AS', 'BE' => 'EU',
        'BF' => 'AF', 'BG' => 'EU', 'BH' => 'AS', 'BI' => 'AF', 'BJ' => 'AF', 'BL' => 'NA', 'BM' => 'NA', 'BN' => 'AS', 'BO' => 'SA', 'BQ' => 'NA',
        'BR' => 'SA', 'BS' => 'NA', 'BT' => 'AS', 'BV' => 'AN', 'BW' => 'AF', 'BY' => 'EU', 'BZ' => 'NA', 'CA' => 'NA', 'CC' => 'AS', 'CD' => 'AF',
        'CF' => 'AF', 'CG' => 'AF', 'CH' => 'EU', 'CI' => 'AF', 'CK' => 'OC', 'CL' => 'SA', 'CM' => 'AF', 'CN' => 'AS', 'CO' => 'SA', 'CR' => 'NA',
        'CU' => 'NA', 'CV' => 'AF', 'CW' => 'NA', 'CX' => 'AS', 'CY' => 'EU', 'CZ' => 'EU', 'DE' => 'EU', 'DJ' => 'AF', 'DK' => 'EU', 'DM' => 'NA',
        'DO' => 'NA', 'DZ' => 'AF', 'EC' => 'SA', 'EE' => 'EU', 'EG' => 'AF', 'EH' => 'AF', 'ER' => 'AF', 'ES' => 'EU', 'ET' => 'AF', 'FI' => 'EU',
        'FJ' => 'OC', 'FK' => 'SA', 'FM' => 'OC', 'FO' => 'EU', 'FR' => 'EU', 'GA' => 'AF', 'GB' => 'EU', 'GD' => 'NA', 'GE' => 'AS', 'GF' => 'SA',
        'GG' => 'EU', 'GH' => 'AF', 'GI' => 'EU', 'GL' => 'NA', 'GM' => 'AF', 'GN' => 'AF', 'GP' => 'NA', 'GQ' => 'AF', 'GR' => 'EU', 'GS' => 'AN',
        'GT' => 'NA', 'GU' => 'OC', 'GW' => 'AF', 'GY' => 'SA', 'HK' => 'AS', 'HM' => 'AN', 'HN' => 'NA', 'HR' => 'EU', 'HT' => 'NA', 'HU' => 'EU',
        'ID' => 'AS', 'IE' => 'EU', 'IL' => 'AS', 'IM' => 'EU', 'IN' => 'AS', 'IO' => 'AS', 'IQ' => 'AS', 'IR' => 'AS', 'IS' => 'EU', 'IT' => 'EU',
        'JE' => 'EU', 'JM' => 'NA', 'JO' => 'AS', 'JP' => 'AS', 'KE' => 'AF', 'KG' => 'AS', 'KH' => 'AS', 'KI' => 'OC', 'KM' => 'AF', 'KN' => 'NA',
        'KP' => 'AS', 'KR' => 'AS', 'KW' => 'AS', 'KY' => 'NA', 'KZ' => 'AS', 'LA' => 'AS', 'LB' => 'AS', 'LC' => 'NA', 'LI' => 'EU', 'LK' => 'AS',
        'LR' => 'AF', 'LS' => 'AF', 'LT' => 'EU', 'LU' => 'EU', 'LV' => 'EU', 'LY' => 'AF', 'MA' => 'AF', 'MC' => 'EU', 'MD' => 'EU', 'ME' => 'EU',
        'MF' => 'NA', 'MG' => 'AF', 'MH' => 'OC', 'MK' => 'EU', 'ML' => 'AF', 'MM' => 'AS', 'MN' => 'AS', 'MO' => 'AS', 'MP' => 'OC', 'MQ' => 'NA',
        'MR' => 'AF', 'MS' => 'NA', 'MT' => 'EU', 'MU' => 'AF', 'MV' => 'AS', 'MW' => 'AF', 'MX' => 'NA', 'MY' => 'AS', 'MZ' => 'AF', 'NA' => 'AF',
        'NC' => 'OC', 'NE' => 'AF', 'NF' => 'OC', 'NG' => 'AF', 'NI' => 'NA', 'NL' => 'EU', 'NO' => 'EU', 'NP' => 'AS', 'NR' => 'OC', 'NU' => 'OC',
        'NZ' => 'OC', 'OM' => 'AS', 'PA' => 'NA', 'PE' => 'SA', 'PF' => 'OC', 'PG' => 'OC', 'PH' => 'AS', 'PK' => 'AS', 'PL' => 'EU', 'PM' => 'NA',
        'PN' => 'OC', 'PR' => 'NA', 'PS' => 'AS', 'PT' => 'EU', 'PW' => 'OC', 'PY' => 'SA', 'QA' => 'AS', 'RE' => 'AF', 'RO' => 'EU', 'RS' => 'EU',
        'RU' => 'EU', 'RW' => 'AF', 'SA' => 'AS', 'SB' => 'OC', 'SC' => 'AF', 'SD' => 'AF', 'SE' => 'EU', 'SG' => 'AS', 'SH' => 'AF', 'SI' => 'EU',
        'SJ' => 'EU', 'SK' => 'EU', 'SL' => 'AF', 'SM' => 'EU', 'SN' => 'AF', 'SO' => 'AF', 'SR' => 'SA', 'SS' => 'AF', 'ST' => 'AF', 'SV' => 'NA',
        'SX' => 'NA', 'SY' => 'AS', 'SZ' => 'AF', 'TC' => 'NA', 'TD' => 'AF', 'TF' => 'AN', 'TG' => 'AF', 'TH' => 'AS', 'TJ' => 'AS', 'TK' => 'OC',
        'TL' => 'AS', 'TM' => 'AS', 'TN' => 'AF', 'TO' => 'OC', 'TR' => 'AS', 'TT' => 'NA', 'TV' => 'OC', 'TW' => 'AS', 'TZ' => 'AF', 'UA' => 'EU',
        'UG' => 'AF', 'UM' => 'OC', 'US' => 'NA', 'UY' => 'SA', 'UZ' => 'AS', 'VA' => 'EU', 'VC' => 'NA', 'VE' => 'SA', 'VG' => 'NA', 'VI' => 'NA',
        'VN' => 'AS', 'VU' => 'OC', 'WF' => 'OC', 'WS' => 'OC', 'YE' => 'AS', 'YT' => 'AF', 'ZA' => 'AF', 'ZM' => 'AF', 'ZW' => 'AF'
    ];

    /**
     * Alternative country name mappings
     *
     * @var array
     */
    private $country_name_mappings = [
        'UK' => 'GB',
        'UNITED KINGDOM' => 'GB',
        'GREAT BRITAIN' => 'GB',
        'ENGLAND' => 'GB',
        'SCOTLAND' => 'GB',
        'WALES' => 'GB',
        'NORTHERN IRELAND' => 'GB',
        'UNITED KINGDOM OF GREAT BRITAIN AND NORTHERN IRELAND' => 'GB',
        'UNITED STATES' => 'US',
        'UNITED STATES OF AMERICA' => 'US',
        'HAWAII' => 'US',
        'UAE' => 'AE',
        'UNITED ARAB EMIRATES' => 'AE',
        'EMIRATES' => 'AE',
        'HOLLAND' => 'NL',
    ];

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_block'));
        $this->user_location = null;
    }

    /**
     * Register the Gutenberg block
     *
     * @return void
     */
    public function register_block() {
        register_block_type('geox/conditional-container', array(
            'editor_script' => 'geox-block',
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'include' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'exclude' => array(
                    'type' => 'string',
                    'default' => '',
                ),
            ),
        ));
    }

    /**
     * Render the block on the frontend
     *
     * @param array  $attributes Block attributes.
     * @param string $content    Block content.
     * @return string            Rendered block HTML.
     */
    public function render_block($attributes, $content) {
        $include = $attributes['include'] ?? '';
        $exclude = $attributes['exclude'] ?? '';
        
        return sprintf(
            '<div class="geox-conditional-container" style="display:none;" data-include="%s" data-exclude="%s">%s</div>',
            esc_attr($include),
            esc_attr($exclude),
            $content
        );
    }

    /**
     * Determine if content should be displayed based on geolocation criteria
     *
     * @param string $include Comma-separated list of locations to include.
     * @param string $exclude Comma-separated list of locations to exclude.
     * @return bool           Whether the content should be displayed.
     */
    public function should_display($include, $exclude = '') {
        $user_location = $this->get_user_location();
        
        // If we couldn't determine the user's location, show the content
        if (!$user_location) {
            return true;
        }
        
        // Parse criteria lists
        $include_criteria = $this->parse_criteria_list($include);
        $exclude_criteria = $this->parse_criteria_list($exclude);
        
        // Check exclusion rules first
        if ($this->matches_any_criteria($user_location, $exclude_criteria)) {
            return false;
        }
        
        // If no inclusion rules, show the content
        if (empty($include_criteria)) {
            return true;
        }
        
        // Check inclusion rules
        return $this->matches_any_criteria($user_location, $include_criteria);
    }
    
    /**
     * Parse a comma-separated list of criteria into an array
     *
     * @param string $criteria_list Comma-separated list of criteria.
     * @return array                Array of trimmed criteria.
     */
    private function parse_criteria_list($criteria_list) {
        if (empty($criteria_list)) {
            return [];
        }
        
        $criteria = explode(',', $criteria_list);
        return array_filter(array_map('trim', $criteria));
    }
    
    /**
     * Check if a location matches any of the given criteria
     *
     * @param array $location Location data.
     * @param array $criteria List of criteria to check.
     * @return bool           Whether the location matches any criterion.
     */
    private function matches_any_criteria($location, $criteria) {
        if (empty($criteria)) {
            return false;
        }
        
        foreach ($criteria as $criterion) {
            if ($this->matches_criterion($location, $criterion)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get the user's location data
     *
     * @return array|false Location data or false on failure.
     */
    public function get_user_location() {
        // Return cached location if available
        if ($this->user_location !== null) {
            return $this->user_location;
        }

        // Try to get location from cache
        $this->user_location = $this->get_cached_location();
        if ($this->user_location) {
            return $this->user_location;
        }

        // Try to get location from Google Maps API
        $this->user_location = $this->get_location_from_primary_source();
        if ($this->user_location) {
            return $this->user_location;
        }

        // Try fallback geolocation
        $this->user_location = $this->fallback_geolocation();
        return $this->user_location;
    }
    
    /**
     * Get cached location data
     *
     * @return array|false Location data or false if not cached.
     */
    private function get_cached_location() {
        $cache_key = 'geox_user_location_' . $this->get_user_ip();
        return get_transient($cache_key);
    }
    
    /**
     * Get location from primary source (Google Maps API)
     *
     * @return array|false Location data or false on failure.
     */
    private function get_location_from_primary_source() {
        $api_key = get_option('geox_google_maps_api_key');
        if (!$api_key) {
            return false;
        }
        
        $location_data = $this->get_location_from_google_api($api_key);
        if (!$location_data) {
            return false;
        }
        
        // Cache the location data
        $cache_key = 'geox_user_location_' . $this->get_user_ip();
        set_transient($cache_key, $location_data, self::CACHE_DURATION);
        
        return $location_data;
    }

    /**
     * Get location data from Google Maps API
     *
     * @param string $api_key Google Maps API key.
     * @return array|false    Location data or false on failure.
     */
    private function get_location_from_google_api($api_key) {
        $coordinates = $this->get_coordinates_from_google($api_key);
        if (!$coordinates) {
            return false;
        }
        
        return $this->get_address_from_coordinates(
            $coordinates['lat'],
            $coordinates['lng'],
            $api_key
        );
    }
    
    /**
     * Get coordinates from Google Geolocation API
     *
     * @param string $api_key Google Maps API key.
     * @return array|false    Coordinates or false on failure.
     */
    private function get_coordinates_from_google($api_key) {
        $url = "https://www.googleapis.com/geolocation/v1/geolocate?key={$api_key}";
        $response = wp_remote_post($url, [
            'timeout' => 10,
            'body' => json_encode(['considerIp' => true])
        ]);
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!isset($data['location']['lat']) || !isset($data['location']['lng'])) {
            return false;
        }
        
        return [
            'lat' => $data['location']['lat'],
            'lng' => $data['location']['lng']
        ];
    }

    /**
     * Get address details from coordinates using Google Maps Geocoding API
     *
     * @param float  $lat     Latitude.
     * @param float  $lng     Longitude.
     * @param string $api_key Google Maps API key.
     * @return array|false    Location data or false on failure.
     */
    private function get_address_from_coordinates($lat, $lng, $api_key) {
        $geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$api_key}";
        $geocode_response = wp_remote_get($geocode_url, ['timeout' => 10]);
        
        if (is_wp_error($geocode_response) || wp_remote_retrieve_response_code($geocode_response) !== 200) {
            return false;
        }
        
        $geocode_data = json_decode(wp_remote_retrieve_body($geocode_response), true);
        
        if (!isset($geocode_data['results'][0]['address_components'])) {
            return false;
        }
        
        return $this->extract_location_from_components($geocode_data['results'][0]['address_components']);
    }
    
    /**
     * Extract location data from address components
     *
     * @param array $components Address components from geocoding API.
     * @return array            Location data.
     */
    private function extract_location_from_components($components) {
        $location = [
            'country' => '',
            'country_name' => '',
            'region' => '',
            'city' => '',
            'continent' => '',
            'continent_code' => ''
        ];
        
        foreach ($components as $component) {
            $this->process_address_component($location, $component);
        }
        
        return $location;
    }
    
    /**
     * Process a single address component and update location data
     *
     * @param array $location  Location data to update (passed by reference).
     * @param array $component Address component from geocoding API.
     * @return void
     */
    private function process_address_component(&$location, $component) {
        $types = $component['types'];
        
        if (in_array('country', $types)) {
            $location['country'] = $component['short_name'];
            $location['country_name'] = $component['long_name'];
            $location['continent_code'] = $this->get_continent_code($component['short_name']);
            $location['continent'] = $this->continents[$location['continent_code']] ?? '';
        } elseif (in_array('administrative_area_level_1', $types)) {
            $location['region'] = $component['long_name'];
        } elseif (in_array('locality', $types)) {
            $location['city'] = $component['long_name'];
        }
    }

    /**
     * Get continent code from country code
     *
     * @param string $country_code Two-letter country code.
     * @return string              Continent code or empty string if not found.
     */
    private function get_continent_code($country_code) {
        return $this->country_to_continent[$country_code] ?? '';
    }

    /**
     * Fallback geolocation using ipapi.co
     *
     * @return array|false Location data or false on failure.
     */
    private function fallback_geolocation() {
        $ip_address = $this->get_user_ip();
        $url = "https://ipapi.co/{$ip_address}/json/";
        $response = wp_remote_get($url, ['timeout' => 10]);
        
        if (is_wp_error($response)) {
            error_log(sprintf('GeoX Fallback Geolocation Error: %s', $response->get_error_message()));
            return false;
        }
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!is_array($data) || isset($data['error'])) {
            return false;
        }
        
        $location = $this->create_location_from_ipapi($data);
        
        // Cache the location data
        $cache_key = 'geox_user_location_' . $ip_address;
        set_transient($cache_key, $location, self::CACHE_DURATION);
        
        return $location;
    }
    
    /**
     * Create location data from ipapi.co response
     *
     * @param array $data API response data.
     * @return array      Location data.
     */
    private function create_location_from_ipapi($data) {
        $location = [
            'country' => $data['country'] ?? '',
            'country_name' => $data['country_name'] ?? '',
            'region' => $data['region'] ?? '',
            'city' => $data['city'] ?? '',
            'continent_code' => '',
            'continent' => ''
        ];
        
        if (!empty($location['country'])) {
            $location['continent_code'] = $this->get_continent_code($location['country']);
            $location['continent'] = $this->continents[$location['continent_code']] ?? '';
        }
        
        return $location;
    }

    /**
     * Check if a user location matches a criterion
     *
     * @param array  $user_location User location data.
     * @param string $criterion     Location criterion to check.
     * @return bool                 Whether the location matches the criterion.
     */
    private function matches_criterion($user_location, $criterion) {
        if (empty($criterion) || empty($user_location)) {
            return false;
        }
        
        $criterion = strtoupper(trim($criterion));
        
        // Try different matching strategies in order
        return $this->is_country_match($user_location, $criterion)
            || $this->is_continent_match($user_location, $criterion)
            || $this->is_city_match($user_location, $criterion);
    }
    
    /**
     * Check if criterion matches a country
     *
     * @param array  $location  User location data.
     * @param string $criterion Location criterion to check.
     * @return bool             Whether it's a country match.
     */
    private function is_country_match($location, $criterion) {
        // Check if it's a country code or alternative country name
        if (strlen($criterion) == 2 || isset($this->country_name_mappings[$criterion])) {
            $country_code = $this->country_name_mappings[$criterion] ?? $criterion;
            return strtoupper($location['country']) == $country_code;
        }
        
        return false;
    }
    
    /**
     * Check if criterion matches a continent
     *
     * @param array  $location  User location data.
     * @param string $criterion Location criterion to check.
     * @return bool             Whether it's a continent match.
     */
    private function is_continent_match($location, $criterion) {
        // Check if it's a continent code
        if (isset($this->continents[$criterion])) {
            return $location['continent_code'] == $criterion;
        }
        
        // Check if it's a continent name
        $continent_code = array_search($criterion, $this->continents);
        if ($continent_code !== false) {
            return $location['continent_code'] == $continent_code;
        }
        
        return false;
    }
    
    /**
     * Check if criterion matches a city
     *
     * @param array  $location  User location data.
     * @param string $criterion Location criterion to check.
     * @return bool             Whether it's a city match.
     */
    private function is_city_match($location, $criterion) {
        return strtoupper($location['city']) == $criterion;
    }

    /**
     * Get the user's IP address
     *
     * @return string IP address.
     */
    private function get_user_ip() {
        $ip_sources = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];
        
        foreach ($ip_sources as $source) {
            $ip = $this->get_valid_ip_from_source($source);
            if ($ip) {
                return $ip;
            }
        }
        
        return '127.0.0.1'; // Default fallback
    }
    
    /**
     * Get a valid IP from a server variable source
     *
     * @param string $source Server variable name.
     * @return string|false  IP address or false if not valid.
     */
    private function get_valid_ip_from_source($source) {
        if (empty($_SERVER[$source])) {
            return false;
        }
        
        // Handle comma-separated list of IPs
        if ($source === 'HTTP_X_FORWARDED_FOR') {
            return $this->get_first_valid_ip(explode(',', $_SERVER[$source]));
        }
        
        return $this->validate_ip($_SERVER[$source]) ? $_SERVER[$source] : false;
    }
    
    /**
     * Get the first valid IP from a list
     *
     * @param array $ip_list List of IP addresses.
     * @return string|false  First valid IP or false if none valid.
     */
    private function get_first_valid_ip($ip_list) {
        foreach ($ip_list as $ip) {
            $ip = trim($ip);
            if ($this->validate_ip($ip)) {
                return $ip;
            }
        }
        
        return false;
    }
    
    /**
     * Validate an IP address
     *
     * @param string $ip IP address to validate.
     * @return bool      Whether the IP is valid.
     */
    private function validate_ip($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP, 
            FILTER_FLAG_IPV4 | 
            FILTER_FLAG_IPV6 | 
            FILTER_FLAG_NO_PRIV_RANGE | 
            FILTER_FLAG_NO_RES_RANGE
        ) !== false;
    }
}
