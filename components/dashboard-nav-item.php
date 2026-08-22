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
 * USAGE — you almost certainly do not call this directly. The rail is
 * generated: components/dashboard-sidebar.php loops ensurance_dashboard_views()
 * (functions.php) into one of these per entry, and page-dashboard.php loops the
 * same array into the matching view containers. To add a row, add an entry
 * there. This file is the markup for a single row:
 *
 *   get_template_part( 'components/dashboard-nav-item', null, array(
 *       'view'  => 'settings',
 *       'label' => 'Account & Access Settings',
 *       'href'  => add_query_arg( 'view', 'settings', home_url( '/dashboard/' ) ),
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
 *   badge int     optional  Count shown in an accent pill at the far right of
 *                           the row. ZERO RENDERS NOTHING — the design hides
 *                           the badge rather than showing a "0", so a row with
 *                           an empty queue is indistinguishable from one that
 *                           never carries a count. Anything below zero is
 *                           treated the same way.
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
		'badge' => 0,
	)
);

// A nav item with no label or nowhere to go is a bug in the caller, not
// something to render as an empty row.
if ( '' === $item['label'] || '' === $item['href'] ) {
	return;
}

$is_active = ( '' !== $item['view'] && $item['view'] === ensurance_dashboard_current_view() );

$badge = (int) $item['badge'];

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
	<?php // Icon and label are one group so the badge can sit opposite them; the design pairs them the same way on its History row. ?>
	<span class="dash-nav__main">
		<?php if ( '' !== $item['icon'] ) : ?>
			<span class="dash-nav__icon" aria-hidden="true"><?php echo wp_kses( $item['icon'], $icon_tags ); ?></span>
		<?php endif; ?>

		<span class="dash-nav__label"><?php echo esc_html( $item['label'] ); ?></span>
	</span>

	<?php
	/*
	 * The count pill. The number alone is meaningless out of context — a
	 * screen reader would announce "History 3" and leave three of what to
	 * guess — so the visible digit is followed by text that only assistive
	 * tech hears (.sr-only, assets/home.css). aria-label is deliberately not
	 * used here: it is unreliable on a plain <span>, and it would also
	 * replace the row's own accessible name if it were moved up to the <a>.
	 */
	if ( $badge > 0 ) :
		?>
		<span class="dash-nav__badge">
			<?php echo esc_html( number_format_i18n( $badge ) ); ?>
			<span class="sr-only"> awaiting your decision</span>
		</span>
	<?php endif; ?>
</a>
