<?php
class GeoX_Block {
    private $user_location;

    private $continents = [
        'AF' => 'Africa',
        'AN' => 'Antarctica',
        'AS' => 'Asia',
        'EU' => 'Europe',
        'NA' => 'North America',
        'OC' => 'Oceania',
        'SA' => 'South America'
    ];

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

    public function __construct() {
        add_action('init', array($this, 'register_block'));
        $this->user_location = null;
    }

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

    public function render_block($attributes, $content) {
        $include = isset($attributes['include']) ? $attributes['include'] : '';
        $exclude = isset($attributes['exclude']) ? $attributes['exclude'] : '';
        return sprintf(
            '<div class="geox-conditional-container" style="display:none;" data-include="%s" data-exclude="%s">%s</div>',
            esc_attr($include),
            esc_attr($exclude),
            $content
        );
    }

    public function should_display($include, $exclude = '') {
        $user_location = $this->get_user_location();

        if (!$user_location) {
            return true;
        }

        $include_criteria = array_map('trim', explode(',', $include));
        $exclude_criteria = array_map('trim', explode(',', $exclude));

        // If there's an exclude list and the user matches any criterion, don't display
        foreach ($exclude_criteria as $criterion) {
            if ($this->matches_criterion($user_location, $criterion)) {
                return false;
            }
        }

        // If there's no include list, display (unless excluded above)
        if (empty($include_criteria)) {
            return true;
        }

        // If there's an include list, the user must match at least one criterion
        foreach ($include_criteria as $criterion) {
            if ($this->matches_criterion($user_location, $criterion)) {
                return true;
            }
        }

        return false;
    }

    public function get_user_location() {
        if ($this->user_location !== null) {
            return $this->user_location;
        }

        $cache_key = 'geox_user_location_' . $this->get_user_ip();
        $cached_location = get_transient($cache_key);

        if ($cached_location !== false) {
            $this->user_location = $cached_location;
            return $this->user_location;
        }

        $api_key = get_option('geox_google_maps_api_key');
        if (!$api_key) {
            return $this->fallback_geolocation();
        }

        $ip_address = $this->get_user_ip();
        $url = "https://www.googleapis.com/geolocation/v1/geolocate?key={$api_key}";

        $response = wp_remote_post($url, [
            'body' => json_encode(['considerIp' => true])
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return $this->fallback_geolocation();
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['location']['lat']) || !isset($data['location']['lng'])) {
            return $this->fallback_geolocation();
        }

        $lat = $data['location']['lat'];
        $lng = $data['location']['lng'];
        $geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$api_key}";

        $geocode_response = wp_remote_get($geocode_url);

        if (is_wp_error($geocode_response) || wp_remote_retrieve_response_code($geocode_response) !== 200) {
            return $this->fallback_geolocation();
        }

        $geocode_body = wp_remote_retrieve_body($geocode_response);
        $geocode_data = json_decode($geocode_body, true);

        if (!isset($geocode_data['results'][0]['address_components'])) {
            return $this->fallback_geolocation();
        }

        $this->user_location = [
            'country' => '',
            'country_name' => '',
            'region' => '',
            'city' => '',
            'continent' => '',
            'continent_code' => ''
        ];

        foreach ($geocode_data['results'][0]['address_components'] as $component) {
            if (in_array('country', $component['types'])) {
                $this->user_location['country'] = $component['short_name'];
                $this->user_location['country_name'] = $component['long_name'];
                $this->user_location['continent_code'] = $this->get_continent_code($component['short_name']);
                $this->user_location['continent'] = $this->continents[$this->user_location['continent_code']] ?? '';
            }
            if (in_array('administrative_area_level_1', $component['types'])) {
                $this->user_location['region'] = $component['long_name'];
            }
            if (in_array('locality', $component['types'])) {
                $this->user_location['city'] = $component['long_name'];
            }
        }

        set_transient($cache_key, $this->user_location, HOUR_IN_SECONDS);

        return $this->user_location;
    }

    private function get_continent_code($country_code) {
        return $this->country_to_continent[$country_code] ?? '';
    }

    private function get_country_code_3($country_code_2) {
        $country_codes = [
            'US' => 'USA', 'GB' => 'GBR', 'CA' => 'CAN', 'AU' => 'AUS', 'IT' => 'ITA',
            'IE' => 'IRL', 'CN' => 'CHN', 'JP' => 'JPN', 'FR' => 'FRA', 'ES' => 'ESP',
            'DE' => 'DEU', 'NL' => 'NLD', 'BE' => 'BEL', 'SE' => 'SWE', 'NO' => 'NOR',
            'DK' => 'DNK', 'FI' => 'FIN', 'PT' => 'PRT', 'GR' => 'GRC', 'AT' => 'AUT',
            'CH' => 'CHE', 'PL' => 'POL', 'HU' => 'HUN', 'CZ' => 'CZE', 'RO' => 'ROU',
            'BG' => 'BGR', 'HR' => 'HRV', 'RS' => 'SRB', 'SK' => 'SVK', 'SI' => 'SVN',
            'EE' => 'EST', 'LV' => 'LVA', 'LT' => 'LTU', 'UA' => 'UKR', 'BY' => 'BLR',
            'RU' => 'RUS', 'KZ' => 'KAZ', 'IN' => 'IND', 'PK' => 'PAK', 'BD' => 'BGD',
            'TH' => 'THA', 'VN' => 'VNM', 'ID' => 'IDN', 'MY' => 'MYS', 'PH' => 'PHL',
            'SG' => 'SGP', 'KR' => 'KOR', 'BR' => 'BRA', 'AR' => 'ARG', 'CL' => 'CHL',
            'CO' => 'COL', 'PE' => 'PER', 'VE' => 'VEN', 'MX' => 'MEX', 'ZA' => 'ZAF',
            'EG' => 'EGY', 'MA' => 'MAR', 'NG' => 'NGA', 'KE' => 'KEN', 'IL' => 'ISR',
            'SA' => 'SAU', 'AE' => 'ARE', 'TR' => 'TUR', 'IR' => 'IRN', 'PL' => 'POL',
            'NZ' => 'NZL'
        ];
        return isset($country_codes[$country_code_2]) ? $country_codes[$country_code_2] : '';
    }

    private function fallback_geolocation() {
        $ip_address = $this->get_user_ip();
        $url = "https://ipapi.co/{$ip_address}/json/";

        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            error_log(sprintf('GeoX Fallback Geolocation Error: %s', $response->get_error_message()));
            return false;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            error_log(sprintf('GeoX Fallback Geolocation HTTP Error: %d', $response_code));
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!is_array($data) || isset($data['error'])) {
            return false;
        }

        $this->user_location = [
            'country' => isset($data['country']) ? $data['country'] : '',
            'country_name' => isset($data['country_name']) ? $data['country_name'] : '',
            'region' => isset($data['region']) ? $data['region'] : '',
            'city' => isset($data['city']) ? $data['city'] : '',
            'continent_code' => $this->get_continent_code($data['country'] ?? ''),
            'continent' => '',
        ];

        if ($this->user_location['continent_code']) {
            $this->user_location['continent'] = $this->continents[$this->user_location['continent_code']] ?? '';
        }

        return $this->user_location;
    }

    private function matches_criterion($user_location, $criterion) {
        $criterion = strtoupper(trim($criterion));

        $country_codes = [
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
            // Add more as needed
        ];

        if (strlen($criterion) == 2 || isset($country_codes[$criterion])) {
            $country_code = isset($country_codes[$criterion]) ? $country_codes[$criterion] : $criterion;
            return strtoupper($user_location['country']) == $country_code;
        }

        return strtoupper($user_location['city']) == $criterion;
    }

    private function get_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
