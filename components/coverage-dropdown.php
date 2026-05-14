<?php
/**
 * Coverage Dropdown · Panel
 *
 * Figma: node 51:2 ("Dropdown · Panel") in
 * https://www.figma.com/design/Ol8VOpsij7kgp5XkCeHodk/Ensurance-%E2%80%94-Homepage
 *
 * Rendered inside a `.nav-dropdown` wrapper that also contains the trigger
 * button (see header-marketing.php). Visibility is driven by `:hover` /
 * `:focus-within` on `.nav-dropdown` in marketing.css — no JS.
 *
 * Items are intentionally hardcoded to the 8 coverage lines for now.
 * Links are not wired yet.
 */

$coverage_items = array(
    array( 'key' => 'auto',      'title' => 'Auto',      'subtitle' => 'Cars, trucks, motorcycles' ),
    array( 'key' => 'home',      'title' => 'Home',      'subtitle' => 'Houses, condos, mobile' ),
    array( 'key' => 'renters',   'title' => 'Renters',   'subtitle' => 'Apartments and rentals' ),
    array( 'key' => 'life',      'title' => 'Life',      'subtitle' => 'Term, whole, universal' ),
    array( 'key' => 'health',    'title' => 'Health',    'subtitle' => 'Medical, dental, vision' ),
    array( 'key' => 'business',  'title' => 'Business',  'subtitle' => 'Liability, property, BOP' ),
    array( 'key' => 'umbrella',  'title' => 'Umbrella',  'subtitle' => 'Extra liability layer' ),
    array( 'key' => 'specialty', 'title' => 'Specialty', 'subtitle' => 'RVs, boats, valuables' ),
);

if ( ! function_exists( 'ensurance_coverage_icon' ) ) :
    /**
     * Inline 24×24 line-style SVG for a coverage item. `currentColor` so the
     * stroke inherits the label color and shifts with hover.
     */
    function ensurance_coverage_icon( $key ) {
        $attrs = 'width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';
        switch ( $key ) {
            case 'auto':
                // Car: roof, body line, two wheels
                return '<svg ' . $attrs . '><path d="M5 17h14"/><path d="M5 17v-3l2-5a2 2 0 0 1 1.9-1.4h6.2A2 2 0 0 1 17 9l2 5v3"/><circle cx="8" cy="17" r="2"/><circle cx="16" cy="17" r="2"/></svg>';
            case 'home':
                // House: roof + body
                return '<svg ' . $attrs . '><path d="M4 11 12 4l8 7"/><path d="M6 10v9h12v-9"/><path d="M10 19v-5h4v5"/></svg>';
            case 'renters':
                // Key
                return '<svg ' . $attrs . '><circle cx="8" cy="14" r="4"/><path d="m11 11 9-9"/><path d="m17 5 3 3"/><path d="m14 8 2 2"/></svg>';
            case 'life':
                // Heart
                return '<svg ' . $attrs . '><path d="M12 20s-7-4.5-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.5-7 10-7 10z"/></svg>';
            case 'health':
                // Medical plus / cross
                return '<svg ' . $attrs . '><path d="M12 4v16"/><path d="M4 12h16"/></svg>';
            case 'business':
                // Briefcase
                return '<svg ' . $attrs . '><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M3 13h18"/></svg>';
            case 'umbrella':
                // Umbrella: dome + stem + crook
                return '<svg ' . $attrs . '><path d="M12 3v2"/><path d="M3 12a9 9 0 0 1 18 0"/><path d="M3 12h18"/><path d="M12 12v7a2 2 0 0 0 4 0"/></svg>';
            case 'specialty':
                // Star
                return '<svg ' . $attrs . '><polygon points="12 3 14.6 9 21 9.5 16 13.8 17.6 20 12 16.7 6.4 20 8 13.8 3 9.5 9.4 9"/></svg>';
        }
        return '';
    }
endif;
?>
<div class="coverage-dropdown" role="menu" aria-label="Coverage types">
    <?php foreach ( $coverage_items as $item ) : ?>
        <a href="#" class="coverage-dropdown__item" role="menuitem" tabindex="-1">
            <span class="coverage-dropdown__icon" aria-hidden="true">
                <?php echo ensurance_coverage_icon( $item['key'] ); ?>
            </span>
            <span class="coverage-dropdown__labels">
                <span class="coverage-dropdown__title"><?php echo esc_html( $item['title'] ); ?></span>
                <span class="coverage-dropdown__subtitle"><?php echo esc_html( $item['subtitle'] ); ?></span>
            </span>
        </a>
    <?php endforeach; ?>
</div>
