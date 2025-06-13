<?php 
/**
 * Last screen of the game.
 */
?>

<div class="cq-game-outro" data-message="outro" data-message-has-autoscroll>
    <div class="cq-game-outro-frame cq-auto-scroller">
        
        <h1 class="cq-logo"><?php _e( 'Campos Quest', 'campos-quest' ); ?></h1>

        <div class="cq-game-outro-content">
            <?php echo cq_wp_kses( apply_filters( 'the_content', get_option( 'campos_quest_settings_outro' ) ) ); ?>
        </div>

        <div class="cq-game-outro-footer">
            <p><?php _e( 'Our People Are Our Power', 'campos-quest' ); ?></p>
            <button class="cq-button-yellow cq-restart"><?php _e( 'Play Again', 'campos-quest' ); ?></button>
        </div>

    </div>
</div>