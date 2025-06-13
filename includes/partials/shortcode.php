<?php
/**
 * Shortcode output for embedding the game within a WordPress page.
 */
$mode = get_option( 'campos_quest_settings_game_mode', 'client' );
$iframe_url = 'client' === $mode ? get_option( 'campos_quest_settings_iframe_url', '' ) : CAMPOS_QUEST_PLUGIN_URI . 'includes/index.php';
if ( ! $iframe_url ) {
    printf( '<p>%s</p>', __( 'Error: please enter an iframe URL in the plugin settings.', 'campos-quest' ) );
    return;
}
$debug_enabled = get_option( 'campos_quest_settings_debug', false );
if ( $debug_enabled ) {
    $iframe_url = add_query_arg( 'debug', '1', $iframe_url );
}
?>
<?php if ( $parent_shortcode_call_uses_lightbox ) : ?>
    <?php $exit_game_text = get_option( 'campos_quest_settings_exit_game_text', '' ); ?>
    <dialog class="cq-portal-lightbox">
        <iframe class="cq-portal-iframe" data-src="<?php echo esc_url( $iframe_url ); ?>"></iframe>
        <?php if ( $exit_game_text ) : ?>
            <form class="cq-portal-lightbox-close" method="dialog">
                <button autofocus>
                    <?php echo esc_html( $exit_game_text ); ?>
                </button>
            </form>
        <?php endif; ?>
    </dialog>
<?php else : ?>
    <div class="cq-portal">
        <button class="cq-portal-button">
            <?php _e( 'Play Campos Quest', 'campos-quest' ); ?>
        </button>
        <iframe class="cq-portal-iframe" data-src="<?php echo esc_url( $iframe_url ); ?>"></iframe>
    </div>
<?php endif; ?>
