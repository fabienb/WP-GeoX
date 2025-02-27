<?php
class GeoX_Shortcode {
    private $block;
    private $user_location;

    public function __construct($block) {
        $this->block = $block;
        add_shortcode('geoX', array($this, 'render_conditional_shortcode'));
        add_shortcode('geoX_country', array($this, 'render_country'));
        add_shortcode('geoX_country_code', array($this, 'render_country_code'));
        add_shortcode('geoX_region', array($this, 'render_region'));
        add_shortcode('geoX_city', array($this, 'render_city'));
        add_shortcode('geoX_continent', array($this, 'render_continent'));
        add_shortcode('geoX_continent_code', array($this, 'render_continent_code'));
    }

    public function render_conditional_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'include' => '',
            'exclude' => '',
        ), $atts, 'geoX');

        $include = sanitize_text_field($atts['include']);
        $exclude = sanitize_text_field($atts['exclude']);

        if ($this->block->should_display($include, $exclude)) {
            return sprintf(
                '<span class="geox-conditional-content" data-include="%s" data-exclude="%s">%s</span>',
                esc_attr($include),
                esc_attr($exclude),
                do_shortcode($content)
            );
        }

        return '';
    }

    private function get_user_location() {
        if ($this->user_location === null) {
            $this->user_location = $this->block->get_user_location();
        }
        return $this->user_location;
    }

    public function render_country() {
        $location = $this->get_user_location();
        return isset($location['country_name']) ? esc_html($location['country_name']) : '';
    }

    public function render_country_code() {
        $location = $this->get_user_location();
        return isset($location['country']) ? esc_html($location['country']) : '';
    }

    public function render_region() {
        $location = $this->get_user_location();
        return isset($location['region']) ? esc_html($location['region']) : '';
    }

    public function render_city() {
        $location = $this->get_user_location();
        return isset($location['city']) ? esc_html($location['city']) : '';
    }

    public function render_continent() {
        $location = $this->block->get_user_location();
        return isset($location['continent']) ? esc_html($location['continent']) : '';
    }

    public function render_continent_code() {
        $location = $this->block->get_user_location();
        return isset($location['continent_code']) ? esc_html($location['continent_code']) : '';
    }
}
