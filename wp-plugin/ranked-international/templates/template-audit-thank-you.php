<?php
/** Shared post-submission page for campaign-specific thank-you routes. */

get_header();
?>

<?php if ( rip_is_seo_businesses_thank_you() ) : ?>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1975861066398590&amp;ev=PageView&amp;noscript=1" alt=""></noscript>
<?php else : ?>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1686472339304024&amp;ev=Lead&amp;noscript=1" alt=""></noscript>
<?php endif; ?>

<main class="rip-thank-you" id="top">
  <section class="rip-thank-you__hero">
    <div class="rip-thank-you__inner">
      <p class="rip-thank-you__eyebrow">Audit request received</p>
      <h1>Thanks — your free SEO audit is <em>on its way.</em></h1>
      <p>Our team will review your website and send the first findings shortly. Want to move faster? Book a 30-minute call and we’ll walk through the opportunity together.</p>
      <div class="rip-thank-you__actions" aria-label="Choose how to speak with our team">
        <a class="rip-thank-you__cta" href="https://calendly.com/rankedinternational/30min" target="_blank" rel="noopener noreferrer">Book a 30-minute call <span aria-hidden="true">→</span></a>
        <a class="rip-thank-you__cta rip-thank-you__cta--phone" href="tel:+18334024789" aria-label="Call Ranked International at 833-402-4789">Call 833-402-4789</a>
      </div>
      <p class="rip-thank-you__note">No obligation. Schedule a time or call us directly.</p>
    </div>
  </section>
</main>

<?php get_footer(); ?>
