<?php
/** Search Command Center reusable single template for rip_service. */
if ( ! defined( 'ABSPATH' ) ) exit;

function rip_service_rows( $name, $fallback = array() ) {
	$value = get_field( $name );
	return is_array( $value ) && $value ? $value : $fallback;
}

$service       = get_field( 'service_name' ) ?: get_the_title();
$family        = get_field( 'service_family' ) ?: 'SEO';
$seo_title     = get_field( 'seo_title' ) ?: "$service | Ranked International";
$seo_desc      = get_field( 'seo_description' ) ?: get_field( 'hero_summary' );
$evidence_type = get_field( 'evidence_type' ) ?: 'map';
$hub_url       = rip_url_for_template( 'templates/template-case-studies-hub.php', '/case-studies/' );
$cta_label     = get_field( 'hero_cta_label' ) ?: 'Get my free audit';

$outcomes = rip_service_rows( 'outcomes', array(
	array( 'label' => 'Map Pack visibility', 'detail' => 'Show up where nearby buyers click first.' ),
	array( 'label' => 'More qualified calls', 'detail' => 'Turn high-intent searches into conversations.' ),
	array( 'label' => 'A stronger local reputation', 'detail' => 'Build the trust signals that earn the click.' ),
) );
$problems = rip_service_rows( 'problems', array(
	array( 'title' => 'Your profile is incomplete or inactive', 'symptom' => 'Competitors look more established before a customer ever reaches your website.', 'consequence' => 'Fewer calls from high-intent local searches.', 'response' => 'We rebuild, categorize, publish, and optimize the profile around the searches that lead to work.', 'status' => 'Profile opportunity' ),
	array( 'title' => 'You disappear outside your immediate neighborhood', 'symptom' => 'Rankings fall away a few miles from your office or service area.', 'consequence' => 'Large parts of your viable market never see you.', 'response' => 'We map geographic gaps and reinforce them with relevant pages, links, and local signals.', 'status' => 'Coverage gap' ),
	array( 'title' => 'Your business details conflict across the web', 'symptom' => 'Names, addresses, categories, or service areas do not line up.', 'consequence' => 'Google has less confidence in which business information to trust.', 'response' => 'We clean up priority listings and build a consistent local entity footprint.', 'status' => 'Signal mismatch' ),
	array( 'title' => 'Reviews are not helping the sale', 'symptom' => 'Review volume, recency, or responses lag behind the businesses outranking you.', 'consequence' => 'Customers choose the competitor who looks safer.', 'response' => 'We install a practical review workflow and improve how reputation is presented and managed.', 'status' => 'Trust gap' ),
) );
$workstreams = rip_service_rows( 'workstreams', array(
	array( 'title' => 'Google Business Profile', 'icon' => 'map-pin', 'description' => 'Turn the profile into a complete, active local storefront.', 'deliverable' => 'Category, service, photo, post, Q&A and conversion optimization.', 'outcome' => 'More discovery searches become calls and visits.' ),
	array( 'title' => 'Local keyword strategy', 'icon' => 'search', 'description' => 'Prioritize the combinations of service, intent, and location that indicate a buyer.', 'deliverable' => 'Keyword map and page-to-query plan.', 'outcome' => 'Effort goes toward searches that can create revenue.' ),
	array( 'title' => 'Service and location pages', 'icon' => 'file-text', 'description' => 'Give each valuable service-market combination a useful destination.', 'deliverable' => 'Conversion-led pages with internal links and structured data.', 'outcome' => 'Broader coverage without thin or repetitive content.' ),
	array( 'title' => 'Listings and citations', 'icon' => 'list-checks', 'description' => 'Make core business information consistent where search engines verify it.', 'deliverable' => 'Priority citation cleanup and expansion.', 'outcome' => 'A cleaner, more credible local entity footprint.' ),
	array( 'title' => 'Review system', 'icon' => 'star', 'description' => 'Create a repeatable way to earn, respond to, and learn from reviews.', 'deliverable' => 'Request workflow, response guidance and review monitoring.', 'outcome' => 'Stronger trust and a better profile conversion rate.' ),
	array( 'title' => 'Local authority', 'icon' => 'link-2', 'description' => 'Earn locally relevant mentions and links that competitors cannot easily copy.', 'deliverable' => 'Local link and partnership campaign.', 'outcome' => 'More authority in the markets that matter.' ),
	array( 'title' => 'Tracking and reporting', 'icon' => 'line-chart', 'description' => 'Connect rankings and profile actions to calls, forms, and booked work.', 'deliverable' => 'Monthly lead and market-coverage reporting.', 'outcome' => 'A clear view of what is creating business.' ),
) );
$phases = rip_service_rows( 'phases', array(
	array( 'timeframe' => 'Week 1', 'title' => 'Audit and opportunity map', 'actions' => 'Profile, rankings, site, citations, reviews, competitors and tracking.', 'client_input' => 'Access, priority services and service areas.', 'output' => 'A prioritized 90-day opportunity map.', 'signal' => 'Tracking and critical profile gaps are corrected.' ),
	array( 'timeframe' => 'First 30 days', 'title' => 'Build the foundation', 'actions' => 'Fix priority technical, profile, page and entity issues.', 'client_input' => 'Approvals, accurate business details and brand assets.', 'output' => 'A stable local-search foundation.', 'signal' => 'Improved profile completeness and early movement.' ),
	array( 'timeframe' => 'Days 31-90', 'title' => 'Expand market coverage', 'actions' => 'Publish pages, strengthen reviews, citations and local authority.', 'client_input' => 'Fast feedback and participation in the review workflow.', 'output' => 'More relevant entry points across the target market.', 'signal' => 'Broader rankings and more qualified actions.' ),
	array( 'timeframe' => 'Ongoing', 'title' => 'Optimize what creates leads', 'actions' => 'Measure, test, maintain and focus resources on the winners.', 'client_input' => 'Lead-quality and booked-work feedback.', 'output' => 'Monthly work and outcome reporting.', 'signal' => 'Compounding calls, forms and market coverage.' ),
) );
$faqs = rip_service_rows( 'faqs', array(
	array( 'question' => 'How long does local SEO take?', 'answer' => 'Early corrections can create movement within weeks, while competitive market coverage usually builds over several months. Timing depends on your starting point, competition, location and ability to support the work. We report progress without guaranteeing rankings.' ),
	array( 'question' => 'Do I need a physical storefront?', 'answer' => 'Not always. Eligible service-area businesses can use local SEO without displaying a public address, provided the Google Business Profile follows the applicable guidelines.' ),
	array( 'question' => 'What do I own?', 'answer' => 'Your profiles, website content, tracking accounts and approved assets remain yours. We do not hold core business assets hostage.' ),
	array( 'question' => 'How do you measure results?', 'answer' => 'We connect market coverage and profile activity to calls, forms and other lead actions, then use your feedback to understand lead quality and booked work.' ),
	array( 'question' => 'How is this different from organic SEO?', 'answer' => 'Local SEO emphasizes proximity, Google Business Profile, reviews and local entity signals. Organic SEO focuses more broadly on website rankings. Strong local campaigns usually coordinate both.' ),
) );

$proof_mode = get_field( 'proof_mode' ) ?: 'case_study';
$proof_post = get_field( 'proof_case_study' );
if ( is_array( $proof_post ) ) $proof_post = reset( $proof_post );
$proof = array(
	'client' => get_field( 'proof_client' ) ?: 'Bella MedSpa & Aesthetics',
	'context' => get_field( 'proof_context' ) ?: 'Relevant agency result · Dallas local SEO',
	'problem' => get_field( 'proof_problem' ) ?: 'Paid acquisition was doing too much of the work, while organic search contributed only about 200 monthly visits.',
	'change' => get_field( 'proof_change' ) ?: 'We rebuilt service intent, local relevance and trust signals so search could become a primary acquisition channel.',
	'metric' => get_field( 'proof_metric' ) ?: '7.5×',
	'metric_label' => get_field( 'proof_metric_label' ) ?: 'organic traffic growth',
	'support_1' => get_field( 'proof_support_1' ) ?: '200 → 1,500 monthly visitors',
	'support_2' => get_field( 'proof_support_2' ) ?: 'Organic became a primary channel',
	'url' => $proof_post ? get_permalink( $proof_post ) : home_url( '/case-studies/bella-med-spa/' ),
);

$schema = array(
	'@context' => 'https://schema.org', '@type' => 'Service',
	'name' => $service, 'description' => wp_strip_all_tags( $seo_desc ),
	'provider' => array( '@type' => 'ProfessionalService', 'name' => 'Ranked International', 'url' => home_url( '/' ) ),
	'areaServed' => get_field( 'primary_market' ) ?: 'Dallas-Fort Worth, Texas',
	'url' => get_permalink(),
);
$breadcrumb_schema = array(
	'@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
	'itemListElement' => array(
		array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
		array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => home_url( '/#services' ) ),
		array( '@type' => 'ListItem', 'position' => 3, 'name' => $service, 'item' => get_permalink() ),
	),
);

// This template prints its own canonical SEO tags. Guarantee core cannot add
// a second title/canonical even when this CPT was resolved by the safe
// top-level fallback late in the main query.
remove_action( 'wp_head', '_wp_render_title_tag', 1 );
remove_action( 'wp_head', 'rel_canonical' );
remove_theme_support( 'title-tag' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo esc_html( $seo_title ); ?></title>
<?php if ( $seo_desc ) : ?><meta name="description" content="<?php echo esc_attr( wp_strip_all_tags( $seo_desc ) ); ?>"><?php endif; ?>
<link rel="canonical" href="<?php echo esc_url( get_permalink() ); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@1&display=swap" rel="stylesheet">
<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ); ?></script>
<script type="application/ld+json"><?php echo wp_json_encode( $breadcrumb_schema, JSON_UNESCAPED_SLASHES ); ?></script>
<?php if ( $faqs ) : ?><script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map( function( $f ) { return array( '@type' => 'Question', 'name' => wp_strip_all_tags( $f['question'] ), 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $f['answer'] ) ) ); }, $faqs ) ), JSON_UNESCAPED_SLASHES ); ?></script><?php endif; ?>
<?php
ob_start();
wp_head();
$service_wp_head = ob_get_clean();
// Last-line defense against themes or SEO integrations that register their
// title/canonical callbacks after template_redirect. Our canonical tags above
// remain untouched because only the captured wp_head() fragment is filtered.
$service_wp_head = preg_replace( '#<title\b[^>]*>.*?</title>\s*#is', '', $service_wp_head );
$service_wp_head = preg_replace( '#<link\b[^>]*rel=["\']canonical["\'][^>]*>\s*#is', '', $service_wp_head );
echo $service_wp_head; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
</head>
<body class="rip-service-page evidence-<?php echo esc_attr( $evidence_type ); ?>">
<?php wp_body_open(); ?>

<header class="nav" id="nav">
  <div class="nav__inner">
    <a class="nav__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ranked International home"><img src="<?php echo rip_asset( 'rankd-international-logo.png' ); ?>" alt="Ranked International" class="nav__logo-img" width="96" height="32"></a>
    <nav class="nav__menu" aria-label="Primary"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><a href="<?php echo esc_url( home_url( '/#services' ) ); ?>">Services</a><a href="<?php echo esc_url( home_url( '/#industries' ) ); ?>">Industries We Serve</a><a href="<?php echo esc_url( $hub_url ); ?>">Case Studies</a><a href="#audit">Contact</a></nav>
    <div class="nav__actions"><a href="tel:+16805542324" class="nav__phone">Dallas · (680) 554-2324</a><a href="#audit" class="btn btn--primary btn--sm">Get my free audit</a></div>
    <button class="nav__burger" id="navBurger" aria-label="Open menu" aria-expanded="false" aria-controls="navMenuMobile"><span></span><span></span><span></span></button>
  </div>
  <nav class="nav__menu-mobile" id="navMenuMobile"><a href="/">Home</a><a href="/#services">Services</a><a href="/#industries">Industries</a><a href="<?php echo esc_url( $hub_url ); ?>">Case Studies</a><a href="#audit" class="btn btn--primary btn--block">Get my free audit</a></nav>
</header>

<main id="top">
  <section class="svc-hero" id="overview">
    <div class="svc-hero__grain" aria-hidden="true"></div>
    <div class="svc-wrap svc-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span>/</span><a href="/#services">Services</a><span>/</span><span><?php echo esc_html( $service ); ?></span></div>
    <div class="svc-wrap svc-hero__grid">
      <div class="svc-hero__copy">
        <p class="svc-eyebrow"><span></span><?php echo esc_html( get_field( 'hero_eyebrow' ) ?: "$service services" ); ?></p>
        <h1><?php echo wp_kses_post( get_field( 'hero_title' ) ?: 'Own the searches happening <em>five miles</em> from your business.' ); ?></h1>
        <p class="svc-hero__summary"><?php echo esc_html( get_field( 'hero_summary' ) ?: 'Show up in Google Maps and local results when nearby customers are ready to call, book, or visit before they choose a competitor.' ); ?></p>
        <div class="svc-hero__actions"><a href="#audit" class="btn btn--primary btn--lg"><?php echo esc_html( $cta_label ); ?></a><a href="#included" class="svc-text-link">See what’s included <i data-lucide="arrow-down"></i></a></div>
        <div class="svc-hero__proof"><span><b>★★★★★</b> 5.0 Google rating</span><span></span><strong><?php echo esc_html( get_field( 'hero_proof' ) ?: '100+ businesses ranked' ); ?></strong></div>
      </div>
      <div class="search-console" aria-label="Local search performance example">
        <div class="search-console__top"><span class="console-dots"><i></i><i></i><i></i></span><span>Local search workspace</span><span class="console-live"><i></i> Live market</span></div>
        <div class="search-console__body">
          <div class="console-sidebar"><span class="is-active"><i data-lucide="map"></i></span><span><i data-lucide="bar-chart-3"></i></span><span><i data-lucide="star"></i></span><span><i data-lucide="phone"></i></span></div>
          <div class="console-map">
            <div class="console-map__grid" aria-hidden="true"></div>
            <span class="map-road road-a"></span><span class="map-road road-b"></span><span class="map-road road-c"></span>
            <span class="map-pin p1">1</span><span class="map-pin p2">3</span><span class="map-pin p3">7</span><span class="map-pin p4">2</span>
            <div class="map-card"><small>Google Maps</small><strong><?php echo esc_html( get_field( 'evidence_business' ) ?: 'Your Business' ); ?></strong><span><b>4.9 ★</b> · 128 reviews</span><em>Open · Dallas, TX</em></div>
          </div>
          <div class="console-metrics">
            <div><small>Calls</small><strong><?php echo esc_html( get_field( 'evidence_calls' ) ?: '186' ); ?></strong><span>↗ 42%</span></div>
            <div><small>Directions</small><strong><?php echo esc_html( get_field( 'evidence_directions' ) ?: '94' ); ?></strong><span>↗ 18%</span></div>
            <div><small>Avg. position</small><strong><?php echo esc_html( get_field( 'evidence_position' ) ?: '3.2' ); ?></strong><span>Top 3</span></div>
          </div>
        </div>
      </div>
    </div>
    <div class="svc-outcomes"><div class="svc-wrap"><p>What this service improves</p><?php foreach ( $outcomes as $outcome ) : ?><div><strong><?php echo esc_html( $outcome['label'] ); ?></strong><span><?php echo esc_html( $outcome['detail'] ?? '' ); ?></span></div><?php endforeach; ?></div></div>
  </section>

  <section class="svc-journey svc-section" id="why-it-matters"><div class="svc-wrap svc-two-col"><div><p class="svc-eyebrow svc-eyebrow--dark"><span></span>Why this matters now</p><h2>A nearby customer can choose you in <em>four taps</em>.</h2><div class="svc-definition"><h3>What is <?php echo esc_html( $service ); ?>?</h3><p><?php echo esc_html( get_field( 'service_definition' ) ?: 'Local SEO helps your business appear in Maps and nearby Google results through your website, Business Profile, reviews, listings, and local authority.' ); ?></p></div></div><div class="journey-flow" aria-label="The local customer journey"><div><i data-lucide="search"></i><span>01</span><strong>Nearby search</strong><small>“service near me”</small></div><i data-lucide="arrow-right"></i><div><i data-lucide="map-pin"></i><span>02</span><strong>Map Pack</strong><small>Three visible choices</small></div><i data-lucide="arrow-right"></i><div><i data-lucide="star"></i><span>03</span><strong>Trust check</strong><small>Reviews + relevance</small></div><i data-lucide="arrow-right"></i><div><i data-lucide="phone-call"></i><span>04</span><strong>Call or booking</strong><small>The business outcome</small></div></div></div></section>

  <section class="svc-problems svc-section" id="problems"><div class="svc-wrap"><div class="svc-section-head"><p class="svc-eyebrow svc-eyebrow--dark"><span></span>What we fix</p><h2>What is costing you <em>leads</em>?</h2><p>Four gaps that make local buyers choose someone else.</p></div><div class="problem-list"><?php foreach ( $problems as $i => $problem ) : ?><article class="problem-row"><div class="problem-row__num"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div><div><p class="problem-row__status"><i></i><?php echo esc_html( $problem['status'] ?? 'Opportunity' ); ?></p><h3><?php echo esc_html( $problem['title'] ); ?></h3><p><?php echo esc_html( $problem['symptom'] ); ?></p></div><div class="problem-row__cost"><small>Business consequence</small><p><?php echo esc_html( $problem['consequence'] ); ?></p></div><div class="problem-row__response"><small>What Ranked changes</small><p><?php echo esc_html( $problem['response'] ); ?></p></div></article><?php endforeach; ?></div></div></section>

  <section class="svc-blueprint svc-section" id="included"><div class="svc-wrap"><div class="svc-section-head"><p class="svc-eyebrow svc-eyebrow--dark"><span></span>What’s included</p><h2>Every workstream has a <em>job</em>.</h2></div><div class="blueprint"><div class="blueprint__tabs" role="tablist" aria-label="Service workstreams"><?php foreach ( $workstreams as $i => $item ) : ?><button role="tab" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="workstream-panel" data-workstream="<?php echo esc_attr( $i ); ?>"><span><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span><i data-lucide="<?php echo esc_attr( $item['icon'] ?: 'check' ); ?>"></i><strong><?php echo esc_html( $item['title'] ); ?></strong><i data-lucide="arrow-up-right"></i></button><?php endforeach; ?></div><div class="blueprint__panel" id="workstream-panel" role="tabpanel"><div class="blueprint__canvas"><div class="blueprint__signal"><span></span><span></span><span></span></div><i data-lucide="<?php echo esc_attr( $workstreams[0]['icon'] ); ?>" class="blueprint__icon"></i><p>Workstream <span id="blueprintNumber">01</span></p><h3 id="blueprintTitle"><?php echo esc_html( $workstreams[0]['title'] ); ?></h3><p id="blueprintDescription"><?php echo esc_html( $workstreams[0]['description'] ); ?></p><div class="blueprint__output"><div><small>What you receive</small><strong id="blueprintDeliverable"><?php echo esc_html( $workstreams[0]['deliverable'] ); ?></strong></div><div><small>Why it matters</small><strong id="blueprintOutcome"><?php echo esc_html( $workstreams[0]['outcome'] ); ?></strong></div></div></div></div></div></div><script type="application/json" id="workstreamData"><?php echo wp_json_encode( $workstreams ); ?></script></section>

  <section class="svc-system svc-section"><div class="svc-wrap svc-system__grid"><div><p class="svc-eyebrow"><span></span>Inside the system</p><h2>Local authority is a <em>connected system</em>.</h2><p>Profiles, pages, reputation, citations, links and measurement reinforce one another. Optimizing only one leaves the campaign easy to outrank.</p><a href="#audit" class="btn btn--primary">Map my biggest gaps</a></div><div class="system-map"><div class="system-map__core"><i data-lucide="phone-call"></i><strong>Calls &amp;<br>bookings</strong></div><?php $nodes = array( array('Business Profile','map-pin'), array('Service pages','files'), array('Reviews','star'), array('Citations','list-checks'), array('Local links','link-2'), array('Tracking','line-chart') ); foreach ( $nodes as $i => $node ) : ?><div class="system-node node-<?php echo $i + 1; ?>"><i data-lucide="<?php echo $node[1]; ?>"></i><span><?php echo esc_html( $node[0] ); ?></span></div><?php endforeach; ?><svg viewBox="0 0 600 440" preserveAspectRatio="none" aria-hidden="true"><path d="M105 85 C205 85 210 190 300 220"/><path d="M300 55 C300 120 300 155 300 220"/><path d="M495 85 C395 85 390 190 300 220"/><path d="M105 355 C205 355 210 250 300 220"/><path d="M300 385 C300 320 300 285 300 220"/><path d="M495 355 C395 355 390 250 300 220"/></svg></div></div></section>

  <?php if ( $proof_mode !== 'hidden' ) : ?><section class="svc-proof svc-section" id="results"><div class="svc-wrap"><div class="proof-card"><div class="proof-card__story"><p class="svc-eyebrow svc-eyebrow--dark"><span></span><?php echo esc_html( $proof['context'] ); ?></p><h2><?php echo esc_html( $proof['client'] ); ?></h2><div><small>The problem</small><p><?php echo esc_html( $proof['problem'] ); ?></p></div><div><small>What changed</small><p><?php echo esc_html( $proof['change'] ); ?></p></div><a href="<?php echo esc_url( $proof['url'] ); ?>" class="svc-text-link svc-text-link--dark">Read the full case study <i data-lucide="arrow-right"></i></a></div><div class="proof-card__result"><p>Primary outcome</p><strong><?php echo esc_html( $proof['metric'] ); ?></strong><span><?php echo esc_html( $proof['metric_label'] ); ?></span><div class="proof-bars" aria-hidden="true"><i style="height:18%"></i><i style="height:22%"></i><i style="height:28%"></i><i style="height:36%"></i><i style="height:48%"></i><i style="height:64%"></i><i style="height:82%"></i><i style="height:100%"></i></div><ul><li><?php echo esc_html( $proof['support_1'] ); ?></li><li><?php echo esc_html( $proof['support_2'] ); ?></li></ul></div></div></div></section><?php endif; ?>

  <section class="svc-compare svc-section"><div class="svc-wrap"><div class="svc-section-head"><p class="svc-eyebrow svc-eyebrow--dark"><span></span>The Ranked difference</p><h2>Built around business outcomes, <em>not activity</em>.</h2></div><div class="compare-table"><div class="compare-row compare-row--head"><span>Typical agency</span><span>Ranked International</span></div><?php $comparisons = array( array('Reports rankings and traffic','Reports calls, leads and booked work'), array('Competes within the same market','One client per industry, per city'), array('Locks clients into long contracts','Month-to-month engagement'), array('Routes questions through support','Direct access to a real strategist') ); foreach ( $comparisons as $row ) : ?><div class="compare-row"><span><i data-lucide="minus"></i><?php echo esc_html( $row[0] ); ?></span><strong><i data-lucide="check"></i><?php echo esc_html( $row[1] ); ?></strong></div><?php endforeach; ?></div></div></section>

  <section class="svc-process svc-section" id="process"><div class="svc-wrap"><div class="svc-section-head"><p class="svc-eyebrow svc-eyebrow--dark"><span></span>How the engagement works</p><h2>A clear path from audit to <em>compounding growth</em>.</h2><p>Timelines describe the work, not guaranteed ranking dates.</p></div><ol class="phase-list"><?php foreach ( $phases as $i => $phase ) : ?><li><div class="phase-index"><span><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span><i></i></div><div class="phase-title"><small><?php echo esc_html( $phase['timeframe'] ); ?></small><h3><?php echo esc_html( $phase['title'] ); ?></h3></div><div><small>What we do</small><p><?php echo esc_html( $phase['actions'] ); ?></p></div><div><small>What we need</small><p><?php echo esc_html( $phase['client_input'] ); ?></p></div><div><small>You receive</small><p><?php echo esc_html( $phase['output'] ); ?></p><span class="phase-signal"><i></i><?php echo esc_html( $phase['signal'] ); ?></span></div></li><?php endforeach; ?></ol></div></section>

  <section class="svc-fit svc-section"><div class="svc-wrap"><div class="svc-section-head"><p class="svc-eyebrow svc-eyebrow--dark"><span></span>Is this a fit?</p><h2>Good partnerships start with <em>honest alignment</em>.</h2></div><div class="fit-grid"><div><div class="fit-grid__icon is-fit"><i data-lucide="check"></i></div><h3>A strong fit if…</h3><ul><?php foreach ( rip_service_rows( 'fit_items', array( array('text'=>'You can respond quickly when qualified leads arrive.'), array('text'=>'You are willing to participate in review generation.'), array('text'=>'You see search as a sustained growth channel.'), array('text'=>'You want clear work and outcome reporting.') ) ) as $item ) : ?><li><i data-lucide="check"></i><?php echo esc_html( $item['text'] ); ?></li><?php endforeach; ?></ul></div><div><div class="fit-grid__icon not-fit"><i data-lucide="x"></i></div><h3>Probably not right if…</h3><ul><?php foreach ( rip_service_rows( 'not_fit_items', array( array('text'=>'You need guaranteed rankings by a fixed date.'), array('text'=>'No one can answer or follow up with new leads.'), array('text'=>'The business details or offer are still changing weekly.'), array('text'=>'You want a one-time trick instead of ongoing improvement.') ) ) as $item ) : ?><li><i data-lucide="x"></i><?php echo esc_html( $item['text'] ); ?></li><?php endforeach; ?></ul></div></div></div></section>

  <section class="svc-faq svc-section" id="faqs"><div class="svc-wrap svc-faq__grid"><div><p class="svc-eyebrow svc-eyebrow--dark"><span></span>FAQs</p><h2>What business owners ask <em>before starting</em>.</h2><p>Clear answers about fit, ownership, timing and measurement.</p></div><div class="svc-faq__list"><?php foreach ( $faqs as $i => $faq ) : ?><article class="svc-faq__item<?php echo $i === 0 ? ' is-open' : ''; ?>"><button type="button" aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>"><span><?php echo esc_html( $faq['question'] ); ?></span><i data-lucide="chevron-down"></i></button><div class="svc-faq__answer"><div><p><?php echo esc_html( $faq['answer'] ); ?></p></div></div></article><?php endforeach; ?></div></div></section>

  <?php if ( $guide = get_field( 'service_guide' ) ) : ?><section class="svc-guide svc-section"><div class="svc-wrap"><p class="svc-eyebrow svc-eyebrow--dark"><span></span>Service guide</p><div class="svc-guide__content"><?php echo wp_kses_post( $guide ); ?></div></div></section><?php endif; ?>

  <section class="svc-final"><div class="svc-wrap"><div><p>Free service audit · No commitment</p><h2><?php echo wp_kses_post( get_field( 'final_cta_title' ) ?: 'Find out why you’re missing from the <em>Map Pack</em>.' ); ?></h2><span><?php echo esc_html( get_field( 'final_cta_summary' ) ?: 'We’ll show you the three local-search gaps costing you calls.' ); ?></span></div><a href="#audit" class="btn btn--dark btn--lg"><?php echo esc_html( get_field( 'final_cta_label' ) ?: $cta_label ); ?></a></div></section>
</main>

<?php rip_render_audit_modal(); ?>

<footer class="footer"><div class="footer__inner"><div class="footer__brand"><a class="nav__logo" href="/"><img src="<?php echo rip_asset( 'rankd-international-logo.png' ); ?>" alt="Ranked International" class="nav__logo-img" width="96" height="32" loading="lazy"></a><p>Dallas SEO that gets the phone ringing. One client per industry.</p><p class="footer__addr">Dallas, TX · (680) 554-2324</p></div><div class="footer__cols"><div><h3>SEO</h3><a href="/local-seo-services/">Local SEO</a><a href="/#services">Organic SEO</a><a href="/#services">Technical SEO</a><a href="/#services">Link Building</a></div><div><h3>Paid</h3><a href="/#services">Google Ads</a><a href="/#services">PPC Management</a></div><div><h3>Consulting</h3><a href="/#services">SEO Consulting</a><a href="/#services">CRO Audit</a></div><div><h3>Company</h3><a href="<?php echo esc_url( $hub_url ); ?>">Results</a><a href="/#process">About</a><a href="#audit">Contact</a></div></div></div><div class="footer__base"><span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Ranked International</span><span>Dallas, Texas</span></div></footer>
<?php wp_footer(); ?>
</body></html>
