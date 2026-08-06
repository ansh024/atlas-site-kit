<?php
/** Plugin virtual template for /privacy-policy/ and /terms-of-service/. */
get_header();

$is_privacy = rip_legal_route() === 'privacy';
$title = $is_privacy ? 'Privacy Policy' : 'Terms of Service';
$updated = 'August 4, 2026';
?>
<main class="rip-legal" id="main-content">
	<section class="rip-legal__hero">
		<div class="rip-legal__wrap">
			<p class="rip-legal__label">Ranked International</p>
			<h1><?php echo esc_html( $title ); ?></h1>
			<p>Last updated: <?php echo esc_html( $updated ); ?></p>
		</div>
	</section>

	<article class="rip-legal__content rip-legal__wrap">
		<?php if ( $is_privacy ) : ?>
			<p>Ranked International respects your privacy. This policy explains how we collect, use, disclose, and protect information when you visit our website or use our services.</p>
			<h2>Information we collect</h2>
			<p>We may collect information you provide directly, including your name, email address, phone number, business name, website address, and messages submitted through a form. We may also collect technical information such as your IP address, browser type, device information, pages visited, and referring URLs.</p>
			<h2>How we use information</h2>
			<p>We use information to respond to inquiries, provide requested services, schedule consultations, improve our website and services, protect our business, and meet legal obligations. With your consent where required, we may also send service updates and marketing communications.</p>
			<h2>Text message communications</h2>
			<p>If you provide a mobile number and affirmatively opt in through one of our forms, you agree to receive SMS messages about appointments, account updates, and marketing offers when applicable. Consent is not a condition of purchase. Message frequency varies, and message and data rates may apply. Reply STOP to unsubscribe or HELP for assistance.</p>
			<h2>How we share information</h2>
			<p>We do not sell or share mobile opt-in data or SMS consent with third parties or affiliates for their marketing or promotional purposes. We may share limited information with trusted service providers that help operate our website, communications, analytics, or business services, and only as needed for those services or as required by law.</p>
			<h2>Cookies and analytics</h2>
			<p>We may use cookies and similar technologies to remember preferences, understand site performance, and improve your experience. You can manage cookies through your browser settings. Disabling cookies may affect some website functionality.</p>
			<h2>Data security and retention</h2>
			<p>We use reasonable administrative, technical, and organizational measures to protect information. No internet transmission or storage system is completely secure. We retain information only for as long as reasonably necessary for the purposes described here, unless a longer period is required by law.</p>
			<h2>Your choices</h2>
			<p>You may request access to, correction of, or deletion of personal information we hold about you, subject to applicable legal limits. You can opt out of marketing emails through the unsubscribe link and opt out of SMS by replying STOP.</p>
			<h2>Third-party links and changes</h2>
			<p>Our website may link to third-party websites. Their privacy practices are governed by their own policies. We may update this policy from time to time, and the revised version will be posted here with a new effective date.</p>
			<h2>Contact</h2>
			<p>For privacy questions or requests, please <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">contact Ranked International</a>.</p>
		<?php else : ?>
			<p>These Terms of Service govern your use of the Ranked International website. By accessing or using this site, you agree to these terms and to our <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>.</p>
			<h2>Use of this website</h2>
			<p>You may use this website to learn about our services, request information, schedule a consultation, and interact with content we make available. You must use the site lawfully and must not interfere with its operation, attempt to gain unauthorized access, scrape or data-mine it without permission, or submit harmful or unlawful content.</p>
			<h2>Services and results</h2>
			<p>Information on this site is provided for general informational purposes. SEO, advertising, and marketing results depend on factors including market conditions, competition, website condition, budgets, and client participation. Unless agreed in a separate written agreement, we do not guarantee rankings, traffic, leads, revenue, or any other specific outcome.</p>
			<h2>Text message communications</h2>
			<p>By providing your mobile number and affirmatively opting in through our website, you consent to receive SMS messages from Ranked International about appointments, account updates, and marketing offers when applicable. Consent is not a condition of purchase. Message frequency varies, and message and data rates may apply. Reply STOP to opt out or HELP for assistance. Carriers are not liable for delayed or undelivered messages.</p>
			<h2>Intellectual property</h2>
			<p>The content on this site, including text, graphics, logos, images, and design, is owned by Ranked International or its licensors and is protected by applicable intellectual property laws. You may not reproduce, distribute, modify, or use this content without our prior written permission, except as allowed by law.</p>
			<h2>Third-party websites</h2>
			<p>This site may include links to third-party websites. We provide these links for convenience and are not responsible for their content, availability, or practices.</p>
			<h2>Disclaimers and limitation of liability</h2>
			<p>This site is provided on an as-is and as-available basis. To the fullest extent permitted by law, Ranked International disclaims warranties of any kind and will not be liable for indirect, incidental, special, consequential, or punitive damages related to your use of, or inability to use, this site.</p>
			<h2>Changes and governing law</h2>
			<p>We may change, suspend, or discontinue any part of this website or these terms at any time. Changes take effect when posted here. These terms are governed by the laws of the State of Texas, without regard to conflict-of-law principles.</p>
			<h2>Contact</h2>
			<p>For questions about these terms, please <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">contact Ranked International</a>.</p>
		<?php endif; ?>
	</article>
</main>
<?php get_footer(); ?>
