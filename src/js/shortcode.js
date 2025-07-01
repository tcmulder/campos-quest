import '../css/shortcode.css';

/**
 * Show the game in the iframe on click of the shortcode's button
 */
document.querySelectorAll('.cq-portal')?.forEach((wrap) => {
	const button = wrap.querySelector('.cq-portal-button');
	button.addEventListener('click', function () {
		wrap.classList.add('is-active');
		const iframe = wrap.querySelector('.cq-portal-iframe');
		iframe.src = iframe.dataset.src;
	});
});

/**
 * Show the game in a lightbox on click of any triggering link
 *
 * Simply link to #campos-quest to open this lightbox. Note that if there
 * are multiple light boxes, this will always imply show the first one.
 */
const cqLightbox = document.querySelector('.cq-portal-lightbox');
if (cqLightbox) {
	const links = document.querySelectorAll('a[href="#campos-quest"]');
	const iframe = cqLightbox.querySelector('.cq-portal-iframe');
	links.forEach((link) => {
		link.addEventListener('click', function (event) {
			event.preventDefault();
			cqLightbox.showModal();
			iframe.src = iframe.dataset.src;

			// Add load event listener with fallback timeout
			const loadTimeout = setTimeout(() => {
				iframe.classList.add('is-loaded');
			}, 1000);

			iframe.addEventListener('load', function () {
				clearTimeout(loadTimeout);
				iframe.classList.add('is-loaded');
			});
			document.body.classList.add('cq-lightbox-open');
		});
	});
	// Re-enable scrolling when dialog closes
	cqLightbox.addEventListener('close', function () {
		iframe.src = '';
		document.body.classList.remove('cq-lightbox-open');
	});
}
