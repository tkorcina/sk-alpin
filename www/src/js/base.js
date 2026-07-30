$(function () {
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl);
	});

	if (typeof GLightbox !== 'undefined' && document.querySelector('.glightbox')) {
		GLightbox({
			selector: '.glightbox',
			loop: true,
		});
	}
});