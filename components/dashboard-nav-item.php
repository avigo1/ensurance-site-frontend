<?php
/**
 * Agent Dashboard — ONE left-rail nav item. The reusable pattern, not an
 * instance of it.
 *
 * Mirrors the `.ens-nav` row in `templates/agent-dashboard/AgentDashboard.dc.html`
 * (Ensurance Design System): icon + label on one line, light translucent white
 * text on the navy rail, with a subtle translucent white wash on hover and a
 * stronger one on the item matching the current view.
 *
 * Rendered as an `<a>` rather than the design's clickable `<div>` — each view is
 * a real URL server-side (see ensurance_dashboard_current_view()), so the row
 * should be keyboard-focusable, middle-clickable and announced as a link for
 * free.
 *
 * USAGE (components/dashboard-sidebar.php wires up the items — Dashboard is in;
 * the rest land in later iterations):
 *
 *   get_template_part( 'components/dashboard-nav-item', null, array(
 *       'view'  => 'access',
 *       'label' => 'Access Status',
 *       'href'  => add_query_arg( 'view', 'access', home_url( '/dashboard/' ) ),
 *       'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" …></svg>',
 *   ) );
 *
 * ARGS
 *   view  string  required  View slug this item represents. Compared against
 *                           ensurance_dashboard_current_view(); a match adds
 *                           `is-active` and aria-current="page".
 *   label string  required  Visible link text.
 *   href  string  required  Destination URL.
 *   icon  string  optional  Inline SVG markup for the leading glyph. THEME-
 *                           AUTHORED markup only — never user input. Passed
 *                           through wp_kses with an SVG allowlist anyway so a
 *                           future caller cannot turn this slot into an
 *                           injection point. Use `currentColor` for strokes and
 *                           fills so the glyph inherits the row's text color
 *                           through every state.
 *
 * Styling lives in assets/dashboard.css (`.dash-nav__item`); the rail's
 * translucent-white values are tokenized on `.dash-sidebar` there.
 */

$item = wp_parse_args(
	$args ?? array(),
	array(
		'view'  => '',
		'label' => '',
		'href'  => '',
		'icon'  => '',
	)
);

// A nav item with no label or nowhere to go is a bug in the caller, not
// something to render as an empty row.
if ( '' === $item['label'] || '' === $item['href'] ) {
	return;
}

$is_active = ( '' !== $item['view'] && $item['view'] === ensurance_dashboard_current_view() );

// Allowlist covering the stroke-based icon set the design uses (Lucide-style:
// path / circle / rect / line / polyline / polygon inside an <svg>).
$icon_tags = array(
	'svg'      => array(
		'viewbox'          => true,
		'width'            => true,
		'height'           => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'aria-hidden'      => true,
		'focusable'        => true,
		'xmlns'            => true,
	),
	'path'     => array( 'd' => true, 'fill' => true, 'stroke' => true ),
	'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true ),
	'rect'     => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true ),
	'line'     => array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true ),
	'polyline' => array( 'points' => true, 'fill' => true, 'stroke' => true ),
	'polygon'  => array( 'points' => true, 'fill' => true, 'stroke' => true ),
);
?>
<a
	class="dash-nav__item<?php echo $is_active ? ' is-active' : ''; ?>"
	href="<?php echo esc_url( $item['href'] ); ?>"
	<?php echo $is_active ? ' aria-current="page"' : ''; ?>
>
	<?php if ( '' !== $item['icon'] ) : ?>
		<span class="dash-nav__icon" aria-hidden="true"><?php echo wp_kses( $item['icon'], $icon_tags ); ?></span>
	<?php endif; ?>

	<span class="dash-nav__label"><?php echo esc_html( $item['label'] ); ?></span>
</a>
