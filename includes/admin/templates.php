<?php
/**
 * Page templates and shortcodes
 * 
 * All the means of displaying the game on the front-end.
 */

/**
 * Add game page template to WordPress template list
 */
add_filter( 'theme_page_templates', 'campos_quest_add_page_template' );
function campos_quest_add_page_template( $templates ) {
    $templates['templates/game-template.php'] = __( 'Game', 'campos-quest' );
    return $templates;
}

add_filter( 'template_include', 'campos_quest_load_page_template' );
function campos_quest_load_page_template( $template ) {
    if ( is_page_template( 'templates/game-template.php' ) ) {
        $template = CAMPOS_QUEST_PLUGIN_DIR . 'templates/game-template.php';
    }
    return $template;
}

/**
 * Create the shortcode
 */
add_shortcode( 'campos-quest', 'shortcode_campos_quest' );
function shortcode_campos_quest( $atts = [], $content = null) {
    ob_start();
    $parent_shortcode_call_uses_lightbox = isset( $atts['type'] ) && 'lightbox' === $atts['type'];
    include CAMPOS_QUEST_PLUGIN_INC . '/partials/shortcode.php';
    $content = ob_get_clean();
    return $content;
}