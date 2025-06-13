<?php 
/**
 * Message overlay for levels and more.
 */
?>

<div class="cq-message-container">

    <?php
    /**
     * Level intro/outro messages
     */
    for ( $i = 1; $i <= CAMPOS_QUEST_LEVELS; $i++ ) {
        $html = cq_wp_kses( apply_filters( 'the_content', get_option( 'campos_quest_settings_level_' . $i . '_intro' ) ) );
        $controls = sprintf( '<button type="button" class="cq-button-yellow" data-message-resolve>%s</button>', __( 'Start Game', 'campos-quest' ) );
        echo cq_message( 'level-' . $i . '-intro', $html, $controls );
        // We don't have an outro message or the final level (it goes to the loser/winner messages)
        if ( $i < CAMPOS_QUEST_LEVELS ) {
            $html = cq_wp_kses( apply_filters( 'the_content', get_option( 'campos_quest_settings_level_' . $i . '_outro' ) ) );
            $controls = sprintf( '<button type="button" class="cq-button-yellow" data-message-resolve>%s</button>', __( 'Next Level', 'campos-quest' ) );
            echo cq_message( 'level-' . $i . '-outro', $html, $controls );
        }
    }
    ?>

    <?php
    /**
     * Loser message
     */
    $html = cq_wp_kses( apply_filters( 'the_content', get_option( 'campos_quest_settings_loser' ) ) );
    $controls = sprintf( '<button type="button" class="cq-button-yellow" data-message-resolve>%s</button>', __( 'See Results', 'campos-quest' ) );
    echo cq_message( 'loser', $html, $controls );
    ?>

    <?php
    /**
     * Winner message (contains form)
     */
    $html = cq_wp_kses( apply_filters( 'the_content', get_option( 'campos_quest_settings_winner' ) ) );
    echo cq_message( 'winner', $html );
    ?>

    <?php
    /**
     * Leaderboard message
     */
    require_once( CAMPOS_QUEST_PLUGIN_INC . 'partials/leaderboard.php' );
    ?>

<?php
    /**
     * Outro end-game message
     */
    require_once( CAMPOS_QUEST_PLUGIN_INC . 'partials/outro.php' );
    ?>

</div>
