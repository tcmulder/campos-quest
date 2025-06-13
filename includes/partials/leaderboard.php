<div class="cq-leaderboard-wrap" data-message="leaderboard">
    <div class="cq-leaderboard-frame">

        <h2><?php _e( 'Campos Quest', 'campos-quest' ); ?></h2>

        <div class="cq-leaderboard">
			<ol>
				<?php
				for ($i = 0; $i < 10; $i++ ) {
					$ordinal = ( $i + 1 );
					if ($ordinal === 1) {
						$ordinal .= 'st';
					} elseif ($ordinal === 2) {
						$ordinal .= 'nd';
					} elseif ($ordinal === 3) {
						$ordinal .= 'rd';
					} else {
						$ordinal .= 'th';
					}
					printf(
						'<li class="cq-leaderboard-score"><span>%s</span><span></span><span></span></li>',
						$ordinal
					);
				}
				?>
			</ol>
		</div>

        <button class="cq-button-yellow" data-message-resolve>
            <?php esc_html_e( 'Continue', 'campos-quest' ); ?>
        </button>

    </div>
</div>
