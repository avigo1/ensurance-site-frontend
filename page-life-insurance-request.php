<?php
/**
 * Life Insurance Request partner entry point.
 *
 * Public-facing URL and copy stay generic. Partner attribution is carried only
 * in the form metadata so the same consumer experience can be reused later.
 */
add_filter( 'wpseo_title', function () {
    return 'Life Insurance Request | Ensurance';
} );
add_filter( 'pre_get_document_title', function () {
    return 'Life Insurance Request | Ensurance';
}, 99 );
add_filter( 'wpseo_robots', function () {
    return 'noindex, follow';
}, 99 );

$via = isset( $_GET['via'] ) ? sanitize_key( wp_unslash( $_GET['via'] ) ) : '';
$is_partner_referral = ( 'lp01' === $via );

$ensurance_request_context = array(
    'coverage_lock' => 'life',
    'partner_id'    => '',
    'referral_code' => $is_partner_referral ? 'lp01' : '',
    'eyebrow'       => 'Life insurance request',
    'title'         => 'Start your life insurance request.',
    'subcopy'       => 'Share a few focused details about the coverage you are looking for. Ensurance keeps your request organized and your contact information protected while it moves toward licensed review where available.',
);

get_header( 'home' );
?>
<main id="main" class="page-non-auto-request page-life-insurance-request">
  <?php get_template_part( 'template-parts/non-auto-request-form', null, $ensurance_request_context ); ?>
</main>
<?php get_footer( 'home' ); ?>
