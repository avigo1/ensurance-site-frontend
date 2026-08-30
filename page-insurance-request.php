<?php
/**
 * Template Name: Ensurance Multi-Product Insurance Request
 *
 * Shared non-auto intake for Home, Life, Health and Renters.
 */
add_filter( 'wpseo_title', function () {
    return 'Insurance Request | Ensurance';
} );
add_filter( 'pre_get_document_title', function () {
    return 'Insurance Request | Ensurance';
}, 99 );
add_filter( 'wpseo_robots', function () {
    return 'noindex, follow';
}, 99 );

$ensurance_request_context = array(
    'coverage_lock' => '',
    'partner_id'    => '',
    'referral_code' => '',
    'eyebrow'       => 'Start your insurance request',
    'title'         => 'One request. A clearer path to insurance options.',
    'subcopy'       => 'Choose the kind of insurance help you need, answer a few focused questions, and let Ensurance keep the request organized through the next step.',
);

get_header( 'home' );
?>
<main id="main" class="page-non-auto-request">
  <?php get_template_part( 'template-parts/non-auto-request-form', null, $ensurance_request_context ); ?>
</main>
<?php get_footer( 'home' ); ?>
