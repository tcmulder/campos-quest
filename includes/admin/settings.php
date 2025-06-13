<?php
/**
 * Plugin settings
 */

/**
 * Create a "settings" link on the plugins page
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'campos_quest_action_links' );
function campos_quest_action_links( $links ) {
    $links[] = '<a href="'. get_admin_url(  ) .'admin.php?page=campos_quest">'.__( 'Settings', 'campos-quest' ).'</a>';
    return $links;
}

/**
 * Add sidebar link
 */
add_action( 'admin_menu', 'campos_quest_add_admin_menu' );
function campos_quest_add_admin_menu(  ) {
    add_menu_page(
        __( 'Campos Quest', 'campos-quest' ),
        __( 'Campos Quest', 'campos-quest' ),
        'manage_options',
        'campos_quest',
        'campos_quest_options_page',
        'dashicons-games',
        50
    );
}

/**
 * Define options page form html
 */
function campos_quest_options_page(  ) {
    echo '<form action="options.php" method="post">';
        printf( '<h1>%s</h1>', __( 'Campos Quest', 'campos-quest' ));
        settings_fields( 'campos_quest_settings' );
        do_settings_sections( 'campos_quest_settings' );
        submit_button();
    echo '</form>';
}

/**
 * Facilitate accordions
 */
function campos_quest_accordion( $content, $summary = '' ) {
    $summary = $summary ? $summary : __( 'Edit Messaging', 'campos-quest' );
    echo sprintf( '<details><summary>%s</summary>%s</details>', $summary, $content);
}

/**
 * Establish option page setting sections/fields
 */
add_action( 'admin_init', 'campos_quest_settings_init' );
function campos_quest_settings_init(  ) {

    // Repetitive WYSIWYG settings
    $wysiwyg_settings = array(
        'textarea_rows' => 7,
        'media_buttons' => false,
        'tinymce'       => array(
            'toolbar1'  => 'bold,italic,bullist,numlist,link',
            'toolbar2'  => '',
        ),
        'quicktags'     => false,
    );

    // Mode
    $mode = get_option( 'campos_quest_settings_game_mode', 'client' );
    

    /**
     * Overall section heading
     */
    add_settings_section(
        'cq_section',
        __( 'Welcome to the Campos Quest game settings', 'campos-quest' ),
        function() {
            printf(
                '<p>%s:</p><ol><li>%s.</li><li>%s: <code>[campos-quest type="inline"]</code>.</li><li>%s: <code>[campos-quest type="lightbox"]</code>.</li></ol>',
                __( 'There are three ways to provide access to the game', 'campos-quest' ),
                __( 'Apply the "Game" template to a page', 'campos-quest' ),
                __( 'Add the following shortcode to play the game inline on a page', 'campos-quest' ),
                __( 'Add the following shortcode to play the game in a lightbox, creating a hyperlink to #campos-quest to open the lightbox', 'campos-quest' )
            );
        },
        'campos_quest_settings'
    );

    /**
     * Game Mode
     */
    add_option( 'campos_quest_settings_game_mode', 'client' );
    register_setting( 'campos_quest_settings', 'campos_quest_settings_game_mode' );
    add_settings_field(
        'campos_quest_settings_game_mode',
        __( 'Game Mode:', 'campos-quest' ),
        function() {
            $game_mode = get_option( 'campos_quest_settings_game_mode', 'client' );
            $client_html = sprintf(
                '<p><label><input type="radio" name="campos_quest_settings_game_mode" value="client" %s /> %s</label></p>',
                checked( $game_mode, 'client', false ),
                __( 'Connect to a game hosted on a different server.', 'campos-quest' )
            );
            $host_html = sprintf(
                '<p><label><input type="radio" name="campos_quest_settings_game_mode" value="host" %s /> %s</label></p>',
                checked( $game_mode, 'host', false ),
                __( 'Host the game on this server.', 'campos-quest' )
            );
            $disclaimer_html = sprintf( '<p><em>%s</em></p>', __( 'Note: you must save changes after making a new selection above for the relevant settings to appear.' , 'campos-quest' ) );
            printf( '<fieldset>%s%s%s</fieldset>', $client_html, $host_html, $disclaimer_html );
        },
        'campos_quest_settings',
        'cq_section'
    );

    /**
     * Client settings
     */
    if ( 'client' === $mode ) {
        
        /**
         * Iframe URL
         */
        add_option( 'campos_quest_settings_iframe_url', '' );
        register_setting( 
            'campos_quest_settings', 
            'campos_quest_settings_iframe_url',
            array(
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        add_settings_field(
            'campos_quest_settings_iframe_url',
            __( 'Iframe URL:', 'campos-quest' ),
            function() {
                printf(
                    '<input name="campos_quest_settings_iframe_url" type="url" value="%s" class="regular-text" placeholder="https://" /><p><em>%s</em></p>',
                    esc_url( get_option( 'campos_quest_settings_iframe_url', '' ) ),
                    __( 'Enter the game\'s URL.', 'campos-quest' )
                );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Exit Game button text
         */
        add_option( 'campos_quest_settings_exit_game_text', __( '← Exit Game', 'campos-quest' ) );
        register_setting( 'campos_quest_settings', 'campos_quest_settings_exit_game_text' );
        add_settings_field(
            'campos_quest_settings_exit_game_text',
            __( 'Exit Game Link:', 'campos-quest' ),
            function() {
                printf(
                    '<input name="campos_quest_settings_exit_game_text" type="text" value="%s" class="regular-text" placeholder="%s" /><p><em>%s</em></p>',
                    esc_attr( get_option( 'campos_quest_settings_exit_game_text', __( '← Exit Game', 'campos-quest' ) ) ),
                    __( 'Button hidden (add text to reveal it)', 'campos-quest' ),
                    __( 'Note: to hide the button, leave this field blank.', 'campos-quest' )
                );
            },
            'campos_quest_settings',
            'cq_section'
        );

    /**
     * Host settings
     */
    } elseif ( 'host' === $mode ) {


        /**
         * Level messages
         */
        for ( $i = 1; $i <= CAMPOS_QUEST_LEVELS; $i++ ) {
            add_option( "campos_quest_settings_level_{$i}_intro", __( "Level {$i} Intro", 'campos-quest' ) );
            register_setting( 'campos_quest_settings', "campos_quest_settings_level_{$i}_intro" );
            if ( $i < CAMPOS_QUEST_LEVELS ) {
                add_option( "campos_quest_settings_level_{$i}_outro", __( "Level {$i} Outro", 'campos-quest' ) );
                register_setting( 'campos_quest_settings', "campos_quest_settings_level_{$i}_outro" );
            }
            add_settings_field(
                "campos_quest_settings_level_{$i}_intro",
                __( "Level {$i} Messages:", 'campos-quest' ),
                function() use ( $i, $wysiwyg_settings ) {
                    ob_start();
                    printf( '<h2>%s</h2>', __( "Level {$i} Intro Message:", 'campos-quest' ) );
                    wp_editor(
                        get_option("campos_quest_settings_level_{$i}_intro"),
                        "campos_quest_level_{$i}_intro",
                        array(
                            ...$wysiwyg_settings,
                            'textarea_name' => "campos_quest_settings_level_{$i}_intro",
                        )
                    );
                    if ( $i < CAMPOS_QUEST_LEVELS ) {
                        printf( '<h2>%s</h2>', __( "Level {$i} Outro Message:", 'campos-quest' ) );
                        wp_editor(
                            get_option("campos_quest_settings_level_{$i}_outro"),
                            "campos_quest_level_{$i}_outro",
                            array(
                                ...$wysiwyg_settings,
                                'textarea_name' => "campos_quest_settings_level_{$i}_outro",
                            )
                        );
                    }
                    $field = ob_get_clean();
                    campos_quest_accordion( $field );
                },
                'campos_quest_settings',
                'cq_section'
            );
        }

        /**
         * Loser message
         */
        add_option( 'campos_quest_settings_loser', __( "You're not a loser, you tried... you're a failure", 'campos-quest' ));
        register_setting( 'campos_quest_settings', "campos_quest_settings_loser" );
        add_settings_field(
            'campos_quest_settings_loser',
            __( 'Loser message:', 'campos-quest' ),
            function() use ( $wysiwyg_settings ) {
                ob_start();
                wp_editor(
                    get_option( 'campos_quest_settings_loser' ),
                    'campos_quest_settings_loser',
                    array(
                        ...$wysiwyg_settings,
                        'textarea_name' => 'campos_quest_settings_loser',
                    )
                );
                $field = ob_get_clean();
                campos_quest_accordion( $field );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Winner message
         */
        add_option( 'campos_quest_settings_winner', __( 'New high score!', 'campos-quest' ));
        register_setting( 'campos_quest_settings', "campos_quest_settings_winner" );
        add_settings_field(
            'campos_quest_settings_winner',
            __( 'Winner message:', 'campos-quest' ),
            function() use ( $wysiwyg_settings ) {
                ob_start();
                wp_editor(
                    get_option( 'campos_quest_settings_winner' ),
                    'campos_quest_settings_winner',
                    array(
                        ...$wysiwyg_settings,
                        'textarea_name' => 'campos_quest_settings_winner',
                    )
                );
                $field = ob_get_clean();
                campos_quest_accordion( $field );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * End-game outro message (scrolling text)
         */
        add_option( 'campos_quest_settings_outro', __( 'The end.', 'campos-quest' ));
        register_setting( 'campos_quest_settings', 'campos_quest_settings_outro' );
        add_settings_field(
            'campos_quest_settings_outro',
            __( 'Final message:', 'campos-quest' ),
            function() use ( $wysiwyg_settings ) {
                ob_start();
                wp_editor(
                    get_option( 'campos_quest_settings_outro' ),
                    'campos_quest_settings_outro',
                    array(
                        ...$wysiwyg_settings,
                        'textarea_name' => 'campos_quest_settings_outro',
                    )
                );
                $field = ob_get_clean();
                campos_quest_accordion( $field );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Leaderboard
         * 
         * Allows you to override the 10 most recent users/scores (in case someone
         * cheats or enters something untoward as their username, for instance).
         */
        add_option( 'campos_quest_settings_leaderboard', array());
        register_setting( 
            'campos_quest_settings', 
            'campos_quest_settings_leaderboard',
            array(
                'sanitize_callback' => function( $leaderboard ) {
                    // filter out empty entries
                    $leaderboard = array_filter( $leaderboard, function( $entry ) {
                        return !empty( $entry['user'] );
                    } );
                    // ensure scores are integers
                    array_walk( $leaderboard, function( &$entry ) {
                        $entry['score'] = (int) $entry['score'];
                    } );
                    
                    // sort by score (highest first)
                    usort( $leaderboard, function( $a, $b ) {
                        return $b['score'] - $a['score'];
                    } );

                    return $leaderboard;
                }
            )
        );
        add_settings_field(
            'campos_quest_settings_leaderboard',
            __( 'Leaderboard:', 'campos-quest' ),
            function() {
                $html = sprintf(
                    '<p><em>%s<br>%s<br>%s</em></p>',
                    __( 'Leave username blank to remove an entry.', 'campos-quest' ),
                    __( 'Scores will be automatically sorted highest to lowest on save.', 'campos-quest' ),
                    __( 'Maximum of 6 characters, use underscores instead of spaces.', 'campos-quest' )
                );
                $top_player_max = 10;
                $leaderboard = get_option( 'campos_quest_settings_leaderboard', array() );
                for ( $i = 0; $i < $top_player_max; $i++ ) {
                    $user = isset( $leaderboard[ $i ]['user'] ) ? $leaderboard[ $i ]['user'] : '';
                    $score = isset( $leaderboard[$i][ 'score'] ) ? $leaderboard[ $i ]['score'] : 0;
                    $html .= sprintf(
                        '<div style="margin-block:5px;">
                            <label for="campos_quest_settings_leaderboard[%d]_user" style="display:inline-block;width:80px;">#%d %s:</label>
                            <input type="text" 
                                id="campos_quest_settings_leaderboard[%d]_user"
                                name="campos_quest_settings_leaderboard[%d][user]" 
                                value="%s" 
                                placeholder="username"
                                pattern="[^\s]{1,6}"
                                maxlength="6"
                                style="width: 7em; margin-right: 0.5em;"
                            />
                            <input type="number" 
                                name="campos_quest_settings_leaderboard[%d][score]" 
                                value="%d" 
                                min="0" 
                                step="1"
                                style="width: 6em;"
                            />
                        </div>',
                        $i,
                        $i + 1,
                        __( 'Player', 'campos-quest' ),
                        $i,
                        $i,
                        strtoupper( esc_html( $user ) ),
                        $i,
                        (int) $score
                    );
                }
                echo $html;
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Collision difficulty
         */
        add_option( 'campos_quest_settings_size', 100);
        register_setting( 'campos_quest_settings', 'campos_quest_settings_size' );
        add_settings_field(
            'campos_quest_settings_size',
            __( 'Collision difficulty:', 'campos-quest' ),
            function(  ) {
                printf(
                    '<input name="campos_quest_settings_size" value="%s" type="number" step="1" min="1" max="200" required /><p><em>%s</em></p>',
                    get_option( 'campos_quest_settings_size' ),
                    __( '100% is average. Lower values will make the game easier. (Note that the game was originally calibrated for 60% for jump clearances, etc.)', 'campos-quest' )
                );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Speed difficulty
         */
        add_option( 'campos_quest_settings_speed', 100);
        register_setting( 'campos_quest_settings', 'campos_quest_settings_speed' );
        add_settings_field(
            'campos_quest_settings_speed',
            __( 'Speed difficulty:', 'campos-quest' ),
            function(  ) {
                printf(
                    '<input name="campos_quest_settings_speed" value="%s" type="number" step="1" min="1" max="200" required /><p><em>%s</em></p>',
                    get_option( 'campos_quest_settings_speed' ),
                    __( '100% is average. Lower values will make the game easier. (Note that the game was originally calibrated for 90% for jump trajectories, etc.)', 'campos-quest' )
                );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Milestone duration
         */
        add_option( 'campos_quest_settings_milestone_duration', 3 );
        register_setting( 'campos_quest_settings', 'campos_quest_settings_milestone_duration' );
        add_settings_field(
            'campos_quest_settings_milestone_duration',
            __( 'Milestone duration:', 'campos-quest' ),
            function(  ) {
                printf(
                    '<input name="campos_quest_settings_milestone_duration" value="%s" type="number" step="1" min="1" max="200" required /><p><em>%s</em></p>',
                    get_option( 'campos_quest_settings_milestone_duration' ),
                    __( 'In seconds', 'campos-quest' )
                );
            },
            'campos_quest_settings',
            'cq_section'
        );

        /**
         * Enable/disable sound effects
         */
        add_option( 'campos_quest_settings_sfx', true );
        register_setting( 'campos_quest_settings', 'campos_quest_settings_sfx' );
        add_settings_field(
            'campos_quest_settings_sfx',
            __( 'Sounds:', 'campos-quest' ),
            function() {
                $sfx_enabled = get_option( 'campos_quest_settings_sfx', true );
                $html = sprintf(
                    '<input type="checkbox" id="campos_quest_settings_sfx" name="campos_quest_settings_sfx" value="1" %s />',
                    checked( $sfx_enabled, true, false )
                );
                $html .= sprintf( '<label for="campos_quest_settings_sfx">%s</label>', __( 'Allow Music and Sound Effects', 'campos-quest' ) );
                echo $html;
            },
            'campos_quest_settings',
            'cq_section'
        );

    }

    /**
     * Enable/disable debug mode
     */
    add_option( 'campos_quest_settings_debug', false );
    register_setting( 'campos_quest_settings', 'campos_quest_settings_debug' );
    add_settings_field(
        'campos_quest_settings_debug',
        __( 'Debug Mode:', 'campos-quest' ),
        function() {
            $debug_enabled = get_option( 'campos_quest_settings_debug', false );
            $html = sprintf(
                '<input type="checkbox" id="campos_quest_settings_debug" name="campos_quest_settings_debug" value="1" %s />',
                checked( $debug_enabled, true, false )
            );
            $html .= sprintf( '<label for="campos_quest_settings_debug">%s</label>', __( 'Enable debug mode', 'campos-quest' ) );
            
            if ( $debug_enabled ) {

                $game_template_page = get_posts( array(
                    'post_type'      => 'page',
                    'posts_per_page' => 1,
                    'meta_key'       => '_wp_page_template',
                    'meta_value'     => 'templates/game-template.php',
                    'fields'         => 'ids'
                ) );
                if ( $game_template_page ) {
                    $debug_url = add_query_arg( 'debug', '1', get_permalink( $game_template_page[0] ) );
                    $html .= sprintf(
                        '<p><a href="%s" class="button button-secondary" target="_blank">%s <span style="transform:rotate(-45deg);display:inline-block;">→</span></a></p>',
                        esc_url( $debug_url ),
                        __( 'Open Debug URL', 'campos-quest' )
                    );
                } else {
                    $html .= sprintf(
                        '<p>%s <code>?debug=1</code> %s</p>',
                        __( 'Add a query string to the end of your game page URL like ', 'campos-quest' ),
                        __( 'to enable this feature.', 'campos-quest' )
                    );
                }
                $html .= sprintf(
                    '<p><em>%s</em></p>',
                    __( 'Note: when enabled, scores will not be saved to the leaderboard.', 'campos-quest' )
                );
            }
            
            echo $html;
        },
        'campos_quest_settings',
        'cq_section'
    );

}