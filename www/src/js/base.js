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

	// GDPR / cookie lišta
	var CONSENT_KEY = 'skCookieConsent';
	var bar = document.getElementById('cookieBar');

	function readConsent() {
		try { return window.localStorage.getItem(CONSENT_KEY); } catch (e) { return null; }
	}

	function loadAnalytics() {
		var id = window.skAnalyticsId;
		if (!id || window.skAnalyticsLoaded) {
			return;
		}
		window.skAnalyticsLoaded = true;
		window.dataLayer = window.dataLayer || [];
		window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
		window.gtag('js', new Date());
		window.gtag('config', id, { anonymize_ip: true });
		var script = document.createElement('script');
		script.async = true;
		script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
		document.head.appendChild(script);
	}

	if (bar) {
		var consent = readConsent();
		if (consent === 'all') {
			loadAnalytics();
		} else if (consent !== 'necessary') {
			bar.hidden = false;
		}

		bar.querySelectorAll('[data-cookie-consent]').forEach(function (button) {
			button.addEventListener('click', function () {
				var value = button.getAttribute('data-cookie-consent');
				try { window.localStorage.setItem(CONSENT_KEY, value); } catch (e) {}
				bar.hidden = true;
				if (value === 'all') {
					loadAnalytics();
				}
			});
		});
	}
});
