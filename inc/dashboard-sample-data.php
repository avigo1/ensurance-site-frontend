<?php
/**
 * Agent Dashboard — SAMPLE DATA. Temporary review scaffold, not product code.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * THIS FILE IS MEANT TO BE REVERTED. It exists so the dashboard can be looked at
 * with a populated agency behind it before the matching queue, the agency record
 * and the subscription record exist. It ships one commit, gets verified on
 * staging, and comes back out — see the "Removing this" note at the bottom.
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * HOW IT WORKS. It adds no functions to the dashboard and changes none: every
 * value here arrives through a filter the dashboard already published for the
 * real queue to attach to (ensurance_dashboard_live_request,
 * _request_rows, _service_areas, _match_stats, _profile_fields, _account_rows and
 * the rest). So this is a rehearsal of the real integration, and nothing in
 * functions.php or in any component had to be touched to accept it. Deleting the
 * file returns every surface to its resolver's own answer.
 *
 * IT IS OFF UNLESS ASKED FOR. Nothing here runs without `?demo=1` in the URL, and
 * the arg is capability-gated the same way the dashboard's own `?slot=` preview
 * is (ensurance_dashboard_priority_preview): administrators on production, any
 * signed-in user on staging. A real founding agent cannot reach a fabricated
 * request, a fabricated license number or a fabricated card.
 *
 * THE DATA MODEL IS THE LEADS TABLE. Field names, value vocabularies and
 * distributions are taken from the `leads` export — is_insured,
 * num_of_vehicles, primary_vehicle_{year,make,model,use,annual_miles,
 * ownership_type}, current_insurance_{company,expiration}, marital_status,
 * zip_code, received_at, status / reserved_by / purchased_by. The mapping from
 * that table to the dashboard's four request states is:
 *
 *   available, never reserved        → awaiting   (still asking the agent)
 *   reserved_by set, purchased_by '' → expired    (held, then let go)
 *   purchased_by set                 → accepted   (the agent took it)
 *   available, previously offered    → passed     (the agent let it go)
 *
 * THE VALUES THEMSELVES ARE SYNTHETIC. They are written in the leads table's
 * shape and vocabulary, not copied from its rows: staging19 is reachable, and
 * the export is real consumers' names, emails, phones and street addresses.
 * Nothing on this dashboard renders a shopper's identity anyway — the design
 * withholds contact details until a request is accepted, at which point they are
 * emailed rather than shown — so a faithful demo needs the ZIP, the vehicle, the
 * carrier and the timestamps, and needs none of the PII. ZIPs, counties and
 * carriers are consistent with each other so the rows read as real leads.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * WHAT TO PUT IN THE ADDRESS BAR
 *
 *   /dashboard/?demo=1                       established agency, request waiting
 *   /dashboard/?demo=1&view=requests         the full table, all four badges
 *   /dashboard/?demo=1&view=profile          the agency record, fully populated
 *   /dashboard/?demo=1&view=account          billing, card, sign-in, support
 *
 * The dashboard's own preview args still compose on top, and still win over the
 * resolved state — they are how a state the data does not currently produce gets
 * looked at:
 *
 *   &slot=live | quiet | decided | setup     force the priority slot
 *   &slot=decided&decision=accept            the accepted confirmation panel
 *   &slot=decided&decision=pass              the passed confirmation panel
 *
 * Two knobs of this file's own:
 *
 *   &step=1 | 2 | 3    a founding agency mid-setup instead of an established one.
 *                      Empties the agency record up to that checklist position,
 *                      which puts the slot in `setup` with that step current and
 *                      leaves Requests, Recent and the stat row empty — the real
 *                      first-run picture, not a dressed-up one. Omit it for the
 *                      established agency. There is no `&step=4`: a finished
 *                      checklist is not a setup card, it is `quiet`.
 *
 *   &day=N             where the agent is in the 60-day term (default 18).
 *                      &day=0 first day, &day=59 "1 day left", &day=60 "Last day",
 *                      &day=61 past the mark with the first charge behind them.
 *
 * @package kadence-child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the sample data is switched on for this request.
 *
 * GATED LIKE THE SLOT PREVIEW, deliberately — see
 * ensurance_dashboard_priority_preview(), whose reasoning applies here word for
 * word and more so: these values are a fabricated request, a fabricated license
 * and a fabricated card. Administrators only on production; any signed-in user on
 * staging, where every reviewer is a teammate and /dashboard/ has already bounced
 * everyone else to /login.
 *
 * @return bool
 */
function ensurance_demo_active() {
	if ( empty( $_GET['demo'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	$capability = ( 'production' === wp_get_environment_type() ) ? 'manage_options' : 'read';

	/**
	 * Filter the capability required to see the dashboard's sample data.
	 *
	 * @param string $capability Capability checked before honoring `?demo=`.
	 */
	$capability = (string) apply_filters( 'ensurance_demo_cap', $capability );

	return (bool) current_user_can( $capability );
}

/**
 * Which setup step `&step=` is asking to sit on, or 0 for the established agency.
 *
 * @return int 0, 1, 2 or 3.
 */
function ensurance_demo_step() {
	if ( ! ensurance_demo_active() || empty( $_GET['step'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return 0;
	}

	$step = (int) $_GET['step']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	return ( $step >= 1 && $step <= 3 ) ? $step : 0;
}

/**
 * Whether the demo is showing a fully set-up agency — the one that has requests,
 * history, stats and a complete record.
 *
 * The single condition behind almost every filter below, so a half-set-up agency
 * cannot end up with a matched request in its queue or a month of history behind
 * a checklist that says matching has not started.
 *
 * @return bool
 */
function ensurance_demo_established() {
	return ensurance_demo_active() && 0 === ensurance_demo_step();
}

/**
 * The sample agency's own record.
 *
 * Every surface that names the agency reads from here, the same way the real ones
 * read from one resolver each — so the rail's user card, the profile's chips, the
 * quiet panel's sentence and Today's reference column cannot disagree.
 *
 * The phone is a 555 number and the card is Stripe's universal test card, so
 * neither can reach or charge anyone if this outlives its welcome.
 *
 * @return array<string,mixed>
 */
function ensurance_demo_agency() {
	return array(
		'name'      => 'Harbor & Vine Insurance Group',
		'license'   => 'CA-0H21847',
		'phone'     => '(714) 555-0164',
		'inbox'     => 'requests@harborvine.example',
		// County names carry no "County" here: the surfaces that print the whole
		// list say the word once for all of them. A single request's county does
		// carry it — see ensurance_demo_requests().
		'areas'     => array( 'Orange', 'Los Angeles', 'Ventura' ),
		'coverages' => array( 'Auto', 'Home', 'Life' ),
		'payment'   => 'Visa •••• 4242 — expires 09/28',
		// Days since the password was last changed.
		'password'  => 24,
	);
}

/**
 * Every request the sample agency has been matched, newest first.
 *
 * ONE LIST FOR BOTH SURFACES. The first entry is the request Today is asking
 * about and the rest are history, which is exactly how
 * ensurance_dashboard_request_rows() assembles the table — so the awaiting row at
 * the top of Requests is the same request as the card on Today rather than a
 * fourteenth invention, and accepting on Today re-reads that row as Accepted.
 *
 * `offset` is seconds before now rather than a date, so the whole set ages with
 * the site instead of drifting into last spring the week after it was written.
 *
 * The auto rows carry the leads table's own fields: primary vehicle year / make /
 * model, driver and vehicle counts, the ZIP, and the current carrier with its
 * renewal — including the uninsured case, which the export has 58 of and which
 * reads "Not currently insured" rather than blank.
 *
 * @return array<int,array<string,mixed>>
 */
function ensurance_demo_requests() {
	return array(
		array(
			'key'      => 'demo-live',
			'coverage' => 'Auto',
			'county'   => 'Orange County',
			'zip'      => '92704',
			'detail'   => '2021 Kia Soul · 1 driver, 1 vehicle · ZIP 92704',
			'carrier'  => 'Geico — renews 09/2026',
			'household' => '1 driver, 1 vehicle',
			'offset'   => 2 * HOUR_IN_SECONDS,
			'expires'  => ( 6 * HOUR_IN_SECONDS ) + ( 12 * MINUTE_IN_SECONDS ),
			'status'   => 'awaiting',
		),
		array(
			'key'    => 'demo-1',
			'title'  => 'Auto — Los Angeles County',
			'detail' => '2019 Toyota Camry · 2 drivers, 2 vehicles · ZIP 91345',
			'offset' => 7 * HOUR_IN_SECONDS,
			'status' => 'accepted',
		),
		array(
			'key'    => 'demo-2',
			'title'  => 'Auto — Ventura County',
			'detail' => '2014 Ford Focus · 1 driver, 1 vehicle · ZIP 93003',
			'offset' => DAY_IN_SECONDS + ( 3 * HOUR_IN_SECONDS ),
			'status' => 'passed',
		),
		array(
			'key'    => 'demo-3',
			'title'  => 'Home — Orange County',
			'detail' => 'Single family, purchased 2019 · ZIP 92618',
			'offset' => 2 * DAY_IN_SECONDS,
			'status' => 'accepted',
		),
		array(
			'key'    => 'demo-4',
			'title'  => 'Auto — Orange County',
			'detail' => '2023 Honda Civic · not currently insured · ZIP 92801',
			'offset' => 3 * DAY_IN_SECONDS,
			'status' => 'expired',
		),
		array(
			'key'    => 'demo-5',
			'title'  => 'Auto — Ventura County',
			'detail' => '2017 Chevrolet Malibu · 2 drivers, 3 vehicles · ZIP 91360',
			'offset' => 4 * DAY_IN_SECONDS,
			'status' => 'accepted',
		),
		array(
			'key'    => 'demo-6',
			'title'  => 'Life — Los Angeles County',
			'detail' => 'Term, age 41 · ZIP 91403',
			'offset' => 6 * DAY_IN_SECONDS,
			'status' => 'passed',
		),
		array(
			'key'    => 'demo-7',
			'title'  => 'Auto — Los Angeles County',
			'detail' => '2022 Nissan Rogue · 1 driver, 1 vehicle · ZIP 93550',
			'offset' => 8 * DAY_IN_SECONDS,
			'status' => 'accepted',
		),
		array(
			'key'    => 'demo-8',
			'title'  => 'Auto — Ventura County',
			'detail' => '2016 Hyundai Accent · not currently insured · ZIP 93003',
			'offset' => 11 * DAY_IN_SECONDS,
			'status' => 'expired',
		),
		array(
			'key'    => 'demo-9',
			'title'  => 'Auto — Orange County',
			'detail' => '2018 GMC Acadia · 2 drivers, 3 vehicles · ZIP 92867',
			'offset' => 15 * DAY_IN_SECONDS,
			'status' => 'accepted',
		),
		array(
			'key'    => 'demo-10',
			'title'  => 'Auto — Orange County',
			'detail' => '2012 Toyota Sienna · 1 driver, 1 vehicle · ZIP 92704',
			'offset' => 19 * DAY_IN_SECONDS,
			'status' => 'passed',
		),
		array(
			'key'    => 'demo-11',
			'title'  => 'Home — Ventura County',
			'detail' => 'Condo, purchased 2021 · ZIP 91360',
			'offset' => 24 * DAY_IN_SECONDS,
			'status' => 'accepted',
		),
		array(
			'key'    => 'demo-12',
			'title'  => 'Auto — Los Angeles County',
			'detail' => '2020 Chevrolet Equinox · 2 drivers, 2 vehicles · ZIP 91345',
			'offset' => 31 * DAY_IN_SECONDS,
			'status' => 'accepted',
		),
	);
}

// ── The agency record ────────────────────────────────────────────────

add_filter(
	'ensurance_dashboard_agency_name',
	function ( $name ) {
		if ( ! ensurance_demo_active() ) {
			return $name;
		}

		// Step 1 of the checklist is "agency name and license verified", and the
		// resolver's own test for done is a non-empty name — so the only honest
		// way to sit on step 1 is to have no name to show.
		return ( 1 === ensurance_demo_step() ) ? '' : ensurance_demo_agency()['name'];
	},
	20
);

add_filter(
	'ensurance_dashboard_request_inbox',
	function ( $inbox ) {
		return ensurance_demo_active() ? ensurance_demo_agency()['inbox'] : $inbox;
	},
	20
);

add_filter(
	'ensurance_dashboard_license_number',
	function ( $license ) {
		return ensurance_demo_established() ? ensurance_demo_agency()['license'] : $license;
	},
	20
);

add_filter(
	'ensurance_dashboard_agency_phone',
	function ( $phone ) {
		return ensurance_demo_established() ? ensurance_demo_agency()['phone'] : $phone;
	},
	20
);

/*
 * SERVICE AREAS AND COVERAGE TYPES ARE THE SETUP CHECKLIST. Rather than rewrite
 * ensurance_dashboard_setup_steps() to force a position, `&step=` empties the two
 * resolvers that checklist actually tests — so the step statuses, the "Step N of
 * 3" eyebrow, the card's headline and its sentence are all derived by the real
 * resolver from the real rule, and none of that copy is duplicated here. It also
 * means the rest of the dashboard tells the same story: a half-set-up agency's
 * quiet sentence names no counties, and its reference column drops those rows.
 */
add_filter(
	'ensurance_dashboard_service_areas',
	function ( $areas ) {
		if ( ! ensurance_demo_active() ) {
			return $areas;
		}

		// Steps 1 and 2 are at or before service areas, so they are not set yet.
		return ( ensurance_demo_step() >= 1 && ensurance_demo_step() <= 2 ) ? array() : ensurance_demo_agency()['areas'];
	},
	20
);

add_filter(
	'ensurance_dashboard_coverage_types',
	function ( $coverages ) {
		if ( ! ensurance_demo_active() ) {
			return $coverages;
		}

		// Coverage types are the last step, so they are outstanding at every
		// position on the checklist.
		return ensurance_demo_step() ? array() : ensurance_demo_agency()['coverages'];
	},
	20
);

// ── The queue ────────────────────────────────────────────────────────

/**
 * How many requests are waiting — the rail's Requests badge, and what puts the
 * priority slot in `live`.
 *
 * A mid-setup agency has none: nothing can be matched to an agency that is not
 * matchable yet, which is the whole point of the `setup` state.
 */
add_filter(
	'ensurance_dashboard_request_count',
	function ( $count ) {
		if ( ! ensurance_demo_established() ) {
			return $count;
		}

		$waiting = 0;

		foreach ( ensurance_demo_requests() as $request ) {
			if ( 'awaiting' === $request['status'] ) {
				$waiting++;
			}
		}

		return $waiting;
	},
	20
);

/**
 * The request in Today's live card.
 *
 * Built from the first entry of ensurance_demo_requests(), whose `offset` and
 * `expires` are turned into real moments here so the countdown and the Submitted
 * tile are computed on every render rather than written down.
 *
 * FOUR TILES, and they are the design's four — ZIP, household, current carrier,
 * submitted. The vehicle is in `detail` instead, which is where the Requests row
 * prints it: a fifth tile would wrap to a row of its own, and the card's fact row
 * is four wide.
 */
add_filter(
	'ensurance_dashboard_live_request',
	function ( $request ) {
		if ( ! ensurance_demo_established() ) {
			return $request;
		}

		$requests = ensurance_demo_requests();
		$live     = $requests[0];
		$now      = time();
		$matched  = $now - (int) $live['offset'];

		return array(
			'coverage'   => $live['coverage'],
			'county'     => $live['county'],
			'expires_at' => $now + (int) $live['expires'],
			'matched_at' => $matched,
			'detail'     => $live['detail'],
			'facts'      => array(
				array(
					'label' => 'Shopper ZIP',
					'value' => $live['zip'],
				),
				array(
					'label' => 'Household',
					'value' => $live['household'],
				),
				array(
					'label' => 'Current carrier',
					'value' => $live['carrier'],
				),
				array(
					'label' => 'Submitted',
					'value' => ensurance_dashboard_relative_time( $matched, $now ),
				),
			),
		);
	},
	20
);

/**
 * The county a passed request moved on to, so the passed confirmation names one
 * instead of falling back to "another agent covering that area".
 */
add_filter(
	'ensurance_dashboard_decided_county',
	function ( $county ) {
		if ( ! ensurance_demo_established() ) {
			return $county;
		}

		$requests = ensurance_demo_requests();

		return $requests[0]['county'];
	},
	20
);

/**
 * The Requests table.
 *
 * The awaiting row is NOT added here. ensurance_dashboard_request_rows() has
 * already prepended it from the live request above, with its status following
 * whatever decision the agent has made — so accepting on Today re-reads the top
 * row as Accepted and Undo puts it back. Adding it again would put the same
 * request in the table twice and break that tie.
 *
 * EVERYTHING ELSE INCOMING IS DROPPED, though, and that is not tidiness: with a
 * `?slot=` preview also in effect, the resolver has already merged its own four
 * sample rows (ensurance_dashboard_sample_history) before this runs, and
 * appending to them would put two fabricated agencies in one table — Coastal
 * County rows interleaved with Orange County ones, describing a history neither
 * of them has. The live row is kept by its key, which the resolver sets itself.
 */
add_filter(
	'ensurance_dashboard_request_rows',
	function ( $rows, $user_id, $now ) {
		unset( $user_id );

		if ( ! ensurance_demo_established() ) {
			return $rows;
		}

		$live = array();

		foreach ( $rows as $row ) {
			if ( isset( $row['key'] ) && 'live' === $row['key'] ) {
				$live[] = $row;
			}
		}

		$history = array();

		foreach ( ensurance_demo_requests() as $request ) {
			if ( 'awaiting' === $request['status'] ) {
				continue;
			}

			$history[] = array(
				'key'    => $request['key'],
				'title'  => $request['title'],
				'detail' => $request['detail'],
				'at'     => $now - (int) $request['offset'],
				'status' => $request['status'],
			);
		}

		// Newest first is the contract, and the live row is by definition newer
		// than anything closed.
		return array_merge( $live, $history );
	},
	20,
	3
);

// ── The quiet state's evidence ───────────────────────────────────────

/**
 * The quiet panel's three stats.
 *
 * COUNTED FROM THE ROWS, not written down: "last match" is the newest closed
 * request and the month's tally is however many of them fall in the current
 * calendar month, so the stat row can never disagree with the table two clicks
 * away — and neither goes stale as the set ages.
 */
add_filter(
	'ensurance_dashboard_match_stats',
	function ( $stats ) {
		if ( ! ensurance_demo_established() ) {
			return $stats;
		}

		$now    = time();
		$month  = (int) wp_date( 'n', $now );
		$year   = (int) wp_date( 'Y', $now );
		$latest = 0;
		$count  = 0;

		foreach ( ensurance_demo_requests() as $request ) {
			// The quiet state is "nothing is waiting on you", so the request
			// that IS waiting is not what the stat row is counting.
			if ( 'awaiting' === $request['status'] ) {
				continue;
			}

			$at = $now - (int) $request['offset'];

			if ( $at > $latest ) {
				$latest = $at;
			}

			if ( (int) wp_date( 'n', $at ) === $month && (int) wp_date( 'Y', $at ) === $year ) {
				$count++;
			}
		}

		return array(
			array(
				'label' => 'Last match',
				'value' => ensurance_dashboard_relative_time( $latest, $now ),
			),
			array(
				'label' => sprintf( 'Matched in %s', wp_date( 'F', $now ) ),
				'value' => sprintf( _n( '%d request', '%d requests', $count, 'kadence-child' ), $count ),
			),
			array(
				'label' => 'Typical pace here',
				'value' => '2–4 per week',
			),
		);
	},
	20
);

/**
 * Today's Recent column.
 *
 * DECISIONS AND RECORD CHANGES ONLY. The column does not date a match — the live
 * card's Submitted tile and the quiet panel's "Last match" stat already carry
 * that moment, and Step 15 forbids putting it on Today a second time. The two
 * decision rows here are the same two decisions the Requests table's newest
 * closed rows describe, so one agency is being described once.
 */
add_filter(
	'ensurance_dashboard_activity',
	function ( $entries, $user_id, $limit, $now ) {
		unset( $user_id, $limit );

		if ( ! ensurance_demo_established() ) {
			return $entries;
		}

		$agency = ensurance_demo_agency();

		return array(
			array(
				'key'  => 'demo-accepted',
				'what' => 'Auto request accepted — Los Angeles',
				'at'   => $now - ( 7 * HOUR_IN_SECONDS ),
			),
			array(
				'key'  => 'demo-passed',
				'what' => 'Auto request passed — Ventura',
				'at'   => $now - ( DAY_IN_SECONDS + ( 3 * HOUR_IN_SECONDS ) ),
			),
			array(
				'key'  => 'demo-areas',
				'what' => 'Service areas updated',
				'at'   => $now - ( 9 * DAY_IN_SECONDS ),
			),
			array(
				'key'  => 'demo-license',
				'what' => sprintf( 'License %s verified', $agency['license'] ),
				'at'   => $now - ( 38 * DAY_IN_SECONDS ),
			),
		);
	},
	20,
	4
);

// ── Billing and the term ─────────────────────────────────────────────

/**
 * Where the agent is in the 60-day term — `&day=N`, default 18 (the design's).
 *
 * Moves the START rather than the term, because the start is what every date on
 * the timeline is counted from: one filter puts day N, the 60-day mark, the
 * cancel window and the first charge all in the right places, and the Account
 * view's rows read the same two segments.
 */
add_filter(
	'ensurance_dashboard_access_start',
	function ( $start ) {
		if ( ! ensurance_demo_active() ) {
			return $start;
		}

		$day = isset( $_GET['day'] ) ? (int) $_GET['day'] : 18; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$day = max( 0, min( 400, $day ) );

		// Noon rather than the current time, so a day boundary is never one hour
		// away from flipping the counter mid-review.
		$origin = ( new DateTimeImmutable( 'now', wp_timezone() ) )->setTime( 12, 0 );

		return $origin->modify( sprintf( '-%d days', $day ) )->getTimestamp();
	},
	20
);

add_filter(
	'ensurance_dashboard_payment_method',
	function ( $payment ) {
		return ensurance_demo_active() ? ensurance_demo_agency()['payment'] : $payment;
	},
	20
);

add_filter(
	'ensurance_dashboard_password_changed',
	function ( $changed ) {
		if ( ! ensurance_demo_active() ) {
			return $changed;
		}

		return time() - ( (int) ensurance_demo_agency()['password'] * DAY_IN_SECONDS );
	},
	20
);

/*
 * ─────────────────────────────────────────────────────────────────────────────
 * REMOVING THIS
 *
 *   1. Delete inc/dashboard-sample-data.php
 *   2. Delete the require_once for it at the foot of functions.php
 *
 * Nothing else in the theme refers to either, and no component, template or
 * resolver was modified to accommodate them — every surface goes straight back to
 * what its own resolver returns. `git revert` of the commit that added them does
 * both in one step.
 * ─────────────────────────────────────────────────────────────────────────────
 */
