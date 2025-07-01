import { setLeaderboard } from './leaderboard';
import { setMessage } from './messages';

/**
 * Initialize leaderboard button functionality
 */
export const initLeaderboardButton = () => {
	const leaderboardButton = document.querySelector('[data-show-leaderboard]');
	if (leaderboardButton) {
		leaderboardButton.addEventListener('click', async () => {
			// Load the leaderboard data
			setLeaderboard();
			// Show the leaderboard message
			await setMessage('leaderboard');
			// Go back to intro on close
			await setMessage('intro');
		});
	}
};
