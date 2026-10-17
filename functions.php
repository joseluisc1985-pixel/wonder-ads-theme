<?php
/**
 * Wonder Ads theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WA_VERSION', '1.0.0' );

/* -------------------------------------------------------------------------
 * Settings helpers
 * ---------------------------------------------------------------------- */

function wa_defaults() {
	return array(
		'stripe_secret'      => '',
		'link_debut'         => 'https://buy.stripe.com/test_6oU7sNfcp2Im4VJbGIdnW00',
		'link_signature'     => 'https://buy.stripe.com/test_dRm4gB4xLfv873R8uwdnW01',
		'link_estate'        => 'https://buy.stripe.com/test_cNicN78O16YC1JxeSUdnW02',
		'notify_email'       => 'hello@wonderads.net',
		'hero_video'         => '',
		'hero_poster'        => '',
		'ex1_video'          => '',
		'ex1_title'          => 'Bay Harbor Residence',
		'ex1_meta'           => 'Miami, FL · $4.2M',
		'ex2_video'          => '',
		'ex2_title'          => 'Coral Gables Estate',
		'ex2_meta'           => 'Coral Gables, FL · $6.8M',
		'ex3_video'          => '',
		'ex3_title'          => 'Brickell Penthouse',
		'ex3_meta'           => 'Miami, FL · $3.1M',
		'compare_photo'      => '',
		'compare_video'      => '',
		'gdrive_sa_json'     => '',
		'gdrive_folder_id'   => '',
		'gsheet_id'          => '',
	);
}

function wa_opt( $key ) {
	$opts     = get_option( 'wa_settings', array() );
	$defaults = wa_defaults();
	if ( isset( $opts[ $key ] ) && '' !== $opts[ $key ] ) {
		return $opts[ $key ];
	}
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Videos in the media library, newest first.
 */
function wa_library_videos() {
	$posts = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'video',
			'post_status'    => 'inherit',
			'numberposts'    => 20,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	return array_map( 'wp_get_attachment_url', wp_list_pluck( $posts, 'ID' ) );
}

/**
 * Video URL for a slot, falling back to library videos.
 */
function wa_video( $slot ) {
	$url = wa_opt( $slot );
	if ( $url ) {
		return $url;
	}
	$lib = wa_library_videos();
	$map = array( 'hero_video' => 0, 'ex1_video' => 1, 'ex2_video' => 2, 'ex3_video' => 3, 'compare_video' => 0 );
	$i   = isset( $map[ $slot ] ) ? $map[ $slot ] : 0;
	if ( isset( $lib[ $i ] ) ) {
		return $lib[ $i ];
	}
	return isset( $lib[0] ) ? $lib[0] : '';
}

/* -------------------------------------------------------------------------
 * Pricing (single source of truth, mirrored in JS)
 * ---------------------------------------------------------------------- */

function wa_pricing() {
	return array(
		'lengths' => array( 30 => 800, 60 => 1600, 90 => 2400 ),
		'formats' => array( 'vertical' => 0, 'horizontal' => 150, 'square' => 150 ),
		'max_qty' => 10,
		'packs'   => array(
			'debut'     => array( 'name' => 'The Debut Collection', 'videos' => 3, 'list' => 2400, 'price' => 1920 ),
			'signature' => array( 'name' => 'The Signature Collection', 'videos' => 6, 'list' => 4800, 'price' => 3840 ),
			'estate'    => array( 'name' => 'The Estate Collection', 'videos' => 10, 'list' => 8000, 'price' => 6400 ),
		),
	);
}

/* -------------------------------------------------------------------------
 * Theme setup and assets
 * ---------------------------------------------------------------------- */

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'script', 'style', 'gallery', 'caption' ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'wa-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap', array(), null );
		wp_enqueue_style( 'wa-style', get_stylesheet_uri(), array( 'wa-fonts' ), WA_VERSION );
		wp_enqueue_script( 'wa-main', get_template_directory_uri() . '/assets/main.js', array(), WA_VERSION, true );
		wp_localize_script(
			'wa-main',
			'WA',
			array(
				'rest'      => esc_url_raw( rest_url( 'wonder/v1/' ) ),
				'nonce'     => wp_create_nonce( 'wp_rest' ),
				'pricing'   => wa_pricing(),
				'checkout'  => (bool) wa_opt( 'stripe_secret' ),
				'email'     => wa_opt( 'notify_email' ),
			)
		);
		// Keep the front end lean.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	},
	20
);

add_filter( 'show_admin_bar', function ( $show ) { return is_admin() ? $show : false; } );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( is_front_page() ) {
			$parts['title']   = 'Wonder Ads';
			$parts['tagline'] = 'Cinematic Reel Videos for Luxury Real Estate';
		}
		return $parts;
	}
);

/* -------------------------------------------------------------------------
 * Activation: pages, permalinks
 * ---------------------------------------------------------------------- */

function wa_ensure_pages() {
	$pages = array(
		'free-video' => 'Your Free Cinematic Video',
		'brief'      => 'Your Brief',
		'privacy'    => 'Privacy',
		'terms'      => 'Terms',
	);
	foreach ( $pages as $slug => $title ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_name'   => $slug,
					'post_title'  => $title,
				)
			);
		}
	}
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	update_option( 'blogname', 'Wonder Ads' );
	update_option( 'blogdescription', 'Cinematic reel videos for luxury real estate' );
	flush_rewrite_rules();
	update_option( 'wa_pages_version', WA_VERSION );
}
add_action( 'after_switch_theme', 'wa_ensure_pages' );
add_action(
	'init',
	function () {
		if ( get_option( 'wa_pages_version' ) !== WA_VERSION ) {
			wa_ensure_pages();
		}
	},
	20
);

/* -------------------------------------------------------------------------
 * Submissions post type
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	function () {
		register_post_type(
			'wa_submission',
			array(
				'labels'       => array(
					'name'          => 'Briefs',
					'singular_name' => 'Brief',
					'menu_name'     => 'Briefs',
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => 'wonder-ads',
				'supports'     => array( 'title' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap' => true,
			)
		);
	}
);

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box(
			'wa_submission_details',
			'Brief details',
			function ( $post ) {
				$data = get_post_meta( $post->ID, '_wa_data', true );
				if ( ! is_array( $data ) ) {
					echo '<p>No data.</p>';
					return;
				}
				echo '<table class="widefat striped"><tbody>';
				foreach ( wa_flatten_submission( $data ) as $k => $v ) {
					echo '<tr><th style="width:220px">' . esc_html( $k ) . '</th><td>' . wp_kses_post( make_clickable( nl2br( esc_html( $v ) ) ) ) . '</td></tr>';
				}
				echo '</tbody></table>';
			},
			'wa_submission',
			'normal',
			'high'
		);
	}
);

add_filter(
	'manage_wa_submission_posts_columns',
	function ( $cols ) {
		return array(
			'cb'       => $cols['cb'],
			'title'    => 'Client',
			'wa_type'  => 'Type',
			'wa_order' => 'Order',
			'wa_drive' => 'Google Drive',
			'date'     => 'Date',
		);
	}
);
add_action(
	'manage_wa_submission_posts_custom_column',
	function ( $col, $post_id ) {
		$data = get_post_meta( $post_id, '_wa_data', true );
		if ( 'wa_type' === $col ) {
			echo esc_html( isset( $data['type'] ) ? ucfirst( $data['type'] ) : '' );
		} elseif ( 'wa_order' === $col ) {
			echo esc_html( isset( $data['order_label'] ) ? $data['order_label'] : '' );
		} elseif ( 'wa_drive' === $col ) {
			$url = get_post_meta( $post_id, '_wa_drive_url', true );
			echo $url ? '<a href="' . esc_url( $url ) . '" target="_blank">Open folder</a>' : esc_html( get_post_meta( $post_id, '_wa_drive_status', true ) );
		}
	},
	10,
	2
);

/**
 * Flatten a submission into label => value pairs (for admin, email, CSV, Sheets).
 */
function wa_flatten_submission( $d ) {
	$out = array(
		'Type'                     => isset( $d['type'] ) ? ucfirst( $d['type'] ) : '',
		'Order'                    => isset( $d['order_label'] ) ? $d['order_label'] : '',
		'Stripe session'           => isset( $d['session_id'] ) ? $d['session_id'] : '',
		'Name'                     => isset( $d['name'] ) ? $d['name'] : '',
		'Email'                    => isset( $d['email'] ) ? $d['email'] : '',
		'Phone'                    => isset( $d['phone'] ) ? $d['phone'] : '',
		'Realty group / brokerage' => isset( $d['brokerage'] ) ? $d['brokerage'] : '',
		'Website'                  => isset( $d['website'] ) ? $d['website'] : '',
		'Logo'                     => isset( $d['logo'] ) ? implode( "\n", (array) $d['logo'] ) : '',
	);
	if ( ! empty( $d['config'] ) && is_array( $d['config'] ) ) {
		$out['Custom order'] = wa_config_summary( $d['config'] );
	}
	if ( ! empty( $d['listings'] ) ) {
		foreach ( $d['listings'] as $i => $l ) {
			$n = $i + 1;
			$out[ "Listing $n — description" ]     = $l['description'];
			$out[ "Listing $n — areas" ]           = $l['areas'];
			$out[ "Listing $n — text on screen" ]  = $l['text'];
			$out[ "Listing $n — notes" ]           = $l['notes'];
			$out[ "Listing $n — photos" ]          = implode( "\n", (array) $l['photos'] );
		}
	}
	return $out;
}

function wa_config_summary( $c ) {
	$p     = wa_pricing();
	$parts = array();
	$parts[] = $c['qty'] . ' × ' . $c['length'] . 's';
	$parts[] = 'Formats: ' . implode( ', ', array_map( 'ucfirst', $c['formats'] ) );
	$parts[] = 'Music: ' . ucfirst( $c['music'] );
	$parts[] = 'Creative storytelling: ' . ( $c['storytelling'] ? 'Yes (' . ucfirst( $c['story_style'] ) . ')' : 'No' );
	$parts[] = 'Custom script: ' . ( $c['custom_script'] ? 'Yes' : 'No' );
	if ( $c['custom_script'] && $c['script'] ) {
		$parts[] = 'Script: ' . $c['script'];
	}
	$parts[] = 'Actors: ' . ( $c['actors'] ? 'Yes' : 'Walkthrough only' );
	$parts[] = 'Captions: ' . ( $c['captions'] ? 'Yes' : 'No' );
	$parts[] = 'Total: $' . number_format( $c['total'] );
	return implode( "\n", $parts );
}

/* -------------------------------------------------------------------------
 * Admin: settings page + export
 * ---------------------------------------------------------------------- */

add_action(
	'admin_menu',
	function () {
		add_menu_page( 'Wonder Ads', 'Wonder Ads', 'manage_options', 'wonder-ads', 'wa_settings_page', 'dashicons-video-alt3', 3 );
		add_submenu_page( 'wonder-ads', 'Settings', 'Settings', 'manage_options', 'wonder-ads', 'wa_settings_page' );
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'wa_settings_group',
			'wa_settings',
			array(
				'sanitize_callback' => function ( $in ) {
					$out = array();
					foreach ( wa_defaults() as $k => $v ) {
						if ( ! isset( $in[ $k ] ) ) {
							continue;
						}
						$out[ $k ] = ( 'gdrive_sa_json' === $k ) ? trim( wp_unslash( $in[ $k ] ) ) : sanitize_text_field( $in[ $k ] );
					}
					return $out;
				},
			)
		);
	}
);

function wa_settings_page() {
	$opts   = get_option( 'wa_settings', array() );
	$def    = wa_defaults();
	$fields = array(
		'Stripe'        => array(
			'stripe_secret'  => array( 'Stripe secret key', 'Needed for the Custom Order calculator. Use sk_test_… while testing, sk_live_… when live. Find it in Stripe → Developers → API keys.', 'password' ),
			'link_debut'     => array( 'Debut Collection payment link', '', 'url' ),
			'link_signature' => array( 'Signature Collection payment link', '', 'url' ),
			'link_estate'    => array( 'Estate Collection payment link', '', 'url' ),
		),
		'Notifications' => array(
			'notify_email' => array( 'Send new briefs to', '', 'email' ),
		),
		'Videos'        => array(
			'hero_video'    => array( 'Hero background video URL', 'Leave empty to use the newest video in the Media Library. A compressed web version (under 15 MB, 1080p) loads fastest.', 'url' ),
			'hero_poster'   => array( 'Hero poster image URL', 'Shown while the video loads.', 'url' ),
			'ex1_video'     => array( 'Example 1 video URL', '', 'url' ),
			'ex1_title'     => array( 'Example 1 title', '', 'text' ),
			'ex1_meta'      => array( 'Example 1 details', '', 'text' ),
			'ex2_video'     => array( 'Example 2 video URL', '', 'url' ),
			'ex2_title'     => array( 'Example 2 title', '', 'text' ),
			'ex2_meta'      => array( 'Example 2 details', '', 'text' ),
			'ex3_video'     => array( 'Example 3 video URL', '', 'url' ),
			'ex3_title'     => array( 'Example 3 title', '', 'text' ),
			'ex3_meta'      => array( 'Example 3 details', '', 'text' ),
			'compare_photo' => array( 'Comparison: MLS photo URL', '', 'url' ),
			'compare_video' => array( 'Comparison: cinematic video URL', '', 'url' ),
		),
		'Google Drive'  => array(
			'gdrive_sa_json'   => array( 'Service account key (JSON)', 'Paste the full JSON key of a Google Cloud service account with the Drive and Sheets APIs enabled. Share the folder (on a Shared Drive) and the sheet with the service account email as Editor.', 'textarea' ),
			'gdrive_folder_id' => array( 'Briefs folder ID', 'The ID at the end of the Drive folder URL. A subfolder is created per brief.', 'text' ),
			'gsheet_id'        => array( 'Briefs spreadsheet ID', 'Every brief is added as a new row.', 'text' ),
		),
	);
	$videos = wa_library_videos();
	?>
	<div class="wrap">
		<h1>Wonder Ads</h1>
		<p>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=wa_submission' ) ); ?>">View briefs</a>
			<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=wa_export' ), 'wa_export' ) ); ?>">Export briefs to Excel (CSV)</a>
		</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'wa_settings_group' ); ?>
			<?php foreach ( $fields as $section => $items ) : ?>
				<h2><?php echo esc_html( $section ); ?></h2>
				<table class="form-table" role="presentation">
					<?php foreach ( $items as $key => $f ) : ?>
						<?php $val = isset( $opts[ $key ] ) ? $opts[ $key ] : ''; ?>
						<tr>
							<th scope="row"><label for="wa_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $f[0] ); ?></label></th>
							<td>
								<?php if ( 'textarea' === $f[2] ) : ?>
									<textarea class="large-text code" rows="5" id="wa_<?php echo esc_attr( $key ); ?>" name="wa_settings[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $val ); ?></textarea>
								<?php else : ?>
									<input class="regular-text" type="<?php echo esc_attr( $f[2] ); ?>" id="wa_<?php echo esc_attr( $key ); ?>" name="wa_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>" placeholder="<?php echo esc_attr( $def[ $key ] ); ?>" autocomplete="off">
								<?php endif; ?>
								<?php if ( $f[1] ) : ?><p class="description"><?php echo esc_html( $f[1] ); ?></p><?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
		<?php if ( $videos ) : ?>
			<h2>Videos in your Media Library</h2>
			<p>Copy any of these into the video fields above.</p>
			<ul><?php foreach ( $videos as $v ) : ?><li><code><?php echo esc_html( $v ); ?></code></li><?php endforeach; ?></ul>
		<?php endif; ?>
	</div>
	<?php
}

add_action(
	'admin_post_wa_export',
	function () {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'wa_export' ) ) {
			wp_die( 'Not allowed.' );
		}
		$posts = get_posts( array( 'post_type' => 'wa_submission', 'numberposts' => -1, 'post_status' => 'any' ) );
		$rows  = array();
		$heads = array( 'Date' => true );
		foreach ( $posts as $p ) {
			$flat         = wa_flatten_submission( (array) get_post_meta( $p->ID, '_wa_data', true ) );
			$flat         = array( 'Date' => $p->post_date ) + $flat;
			$flat['Google Drive'] = get_post_meta( $p->ID, '_wa_drive_url', true );
			$heads       += array_fill_keys( array_keys( $flat ), true );
			$rows[]       = $flat;
		}
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wonder-ads-briefs-' . gmdate( 'Y-m-d' ) . '.csv' );
		$out = fopen( 'php://output', 'w' );
		fwrite( $out, "\xEF\xBB\xBF" );
		fputcsv( $out, array_keys( $heads ) );
		foreach ( $rows as $r ) {
			$line = array();
			foreach ( array_keys( $heads ) as $h ) {
				$line[] = isset( $r[ $h ] ) ? $r[ $h ] : '';
			}
			fputcsv( $out, $line );
		}
		fclose( $out );
		exit;
	}
);

/* -------------------------------------------------------------------------
 * Stripe
 * ---------------------------------------------------------------------- */

function wa_stripe( $method, $path, $params = array() ) {
	$key = wa_opt( 'stripe_secret' );
	if ( ! $key ) {
		return new WP_Error( 'wa_no_key', 'Online checkout is not configured yet.' );
	}
	$args = array(
		'method'  => $method,
		'timeout' => 30,
		'headers' => array(
			'Authorization' => 'Bearer ' . $key,
			'Content-Type'  => 'application/x-www-form-urlencoded',
		),
	);
	$url = 'https://api.stripe.com/v1/' . ltrim( $path, '/' );
	if ( 'GET' === $method ) {
		$url .= $params ? '?' . http_build_query( $params ) : '';
	} else {
		$args['body'] = http_build_query( $params );
	}
	$res = wp_remote_request( $url, $args );
	if ( is_wp_error( $res ) ) {
		return $res;
	}
	$body = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( wp_remote_retrieve_response_code( $res ) >= 400 ) {
		return new WP_Error( 'wa_stripe', isset( $body['error']['message'] ) ? $body['error']['message'] : 'Stripe error' );
	}
	return $body;
}

/**
 * Validate and price a custom-order configuration on the server.
 */
function wa_normalize_config( $raw ) {
	$p      = wa_pricing();
	$qty    = max( 1, min( $p['max_qty'], (int) ( isset( $raw['qty'] ) ? $raw['qty'] : 1 ) ) );
	$length = (int) ( isset( $raw['length'] ) ? $raw['length'] : 30 );
	if ( ! isset( $p['lengths'][ $length ] ) ) {
		$length = 30;
	}
	$formats = array( 'vertical' );
	foreach ( (array) ( isset( $raw['formats'] ) ? $raw['formats'] : array() ) as $f ) {
		if ( isset( $p['formats'][ $f ] ) && ! in_array( $f, $formats, true ) ) {
			$formats[] = $f;
		}
	}
	$music        = in_array( isset( $raw['music'] ) ? $raw['music'] : '', array( 'cinematic', 'classical', 'modern' ), true ) ? $raw['music'] : 'cinematic';
	$storytelling = ! empty( $raw['storytelling'] );
	$story_style  = in_array( isset( $raw['story_style'] ) ? $raw['story_style'] : '', array( 'warm', 'energetic', 'focused' ), true ) ? $raw['story_style'] : 'warm';
	$custom       = ! $storytelling && ! empty( $raw['custom_script'] );
	$script       = $custom ? sanitize_textarea_field( isset( $raw['script'] ) ? $raw['script'] : '' ) : '';

	$per_video = $p['lengths'][ $length ];
	foreach ( $formats as $f ) {
		$per_video += $p['formats'][ $f ];
	}
	return array(
		'qty'           => $qty,
		'length'        => $length,
		'formats'       => $formats,
		'music'         => $music,
		'storytelling'  => $storytelling,
		'story_style'   => $storytelling ? $story_style : '',
		'custom_script' => $custom,
		'script'        => $script,
		'actors'        => ! empty( $raw['actors'] ),
		'captions'      => ! empty( $raw['captions'] ),
		'per_video'     => $per_video,
		'total'         => $per_video * $qty,
	);
}

/* -------------------------------------------------------------------------
 * REST API
 * ---------------------------------------------------------------------- */

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'wonder/v1',
			'/checkout',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => 'wa_rest_checkout',
			)
		);
		register_rest_route(
			'wonder/v1',
			'/upload',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => 'wa_rest_upload',
			)
		);
		register_rest_route(
			'wonder/v1',
			'/submit',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => 'wa_rest_submit',
			)
		);
	}
);

function wa_rest_checkout( WP_REST_Request $req ) {
	$cfg  = wa_normalize_config( (array) $req->get_json_params() );
	$ref  = wp_generate_password( 12, false );
	set_transient( 'wa_order_' . $ref, $cfg, 60 * DAY_IN_SECONDS );

	$desc = $cfg['qty'] . ' × ' . $cfg['length'] . '-second cinematic reel video' . ( $cfg['qty'] > 1 ? 's' : '' )
		. ' · ' . implode( ', ', array_map( 'ucfirst', $cfg['formats'] ) )
		. ' · ' . ucfirst( $cfg['music'] ) . ' music'
		. ( $cfg['storytelling'] ? ' · ' . ucfirst( $cfg['story_style'] ) . ' storytelling voiceover' : '' )
		. ( $cfg['custom_script'] ? ' · Custom voiceover script' : '' )
		. ( $cfg['actors'] ? ' · Actors' : ' · Walkthrough' )
		. ( $cfg['captions'] ? ' · Captions' : '' );

	$session = wa_stripe(
		'POST',
		'checkout/sessions',
		array(
			'mode'                    => 'payment',
			'line_items'              => array(
				array(
					'quantity'   => $cfg['qty'],
					'price_data' => array(
						'currency'     => 'usd',
						'unit_amount'  => $cfg['per_video'] * 100,
						'product_data' => array(
							'name'        => 'Custom Cinematic Reel Video — ' . $cfg['length'] . 's',
							'description' => mb_substr( $desc, 0, 500 ),
						),
					),
				),
			),
			'phone_number_collection' => array( 'enabled' => 'true' ),
			'client_reference_id'     => $ref,
			'metadata'                => array(
				'order_ref' => $ref,
				'summary'   => mb_substr( $desc, 0, 490 ),
			),
			'success_url'             => home_url( '/brief/?pack=custom&ref=' . $ref . '&session_id={CHECKOUT_SESSION_ID}' ),
			'cancel_url'              => home_url( '/#custom-order' ),
		)
	);
	if ( is_wp_error( $session ) ) {
		return new WP_REST_Response( array( 'error' => $session->get_error_message() ), 400 );
	}
	return array( 'url' => $session['url'] );
}

/**
 * Upload a single file into the Media Library, tagged with a draft token.
 */
function wa_rest_upload( WP_REST_Request $req ) {
	$draft = sanitize_key( $req->get_param( 'draft' ) );
	if ( strlen( $draft ) < 16 || empty( $_FILES['file'] ) ) {
		return new WP_REST_Response( array( 'error' => 'Missing file.' ), 400 );
	}
	$count = (int) get_transient( 'wa_draft_count_' . $draft );
	if ( $count > 400 ) {
		return new WP_REST_Response( array( 'error' => 'Too many files.' ), 429 );
	}
	$file  = $_FILES['file']; // phpcs:ignore
	$types = array( 'jpg|jpeg|jpe' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'heic' => 'image/heic', 'heif' => 'image/heif' );
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array', 99 );
	$up = wp_handle_upload( $file, array( 'test_form' => false, 'mimes' => $types ) );
	remove_filter( 'intermediate_image_sizes_advanced', '__return_empty_array', 99 );
	if ( isset( $up['error'] ) ) {
		return new WP_REST_Response( array( 'error' => $up['error'] ), 400 );
	}
	$id = wp_insert_attachment(
		array(
			'post_mime_type' => $up['type'],
			'post_title'     => sanitize_file_name( pathinfo( $file['name'], PATHINFO_FILENAME ) ),
			'post_status'    => 'inherit',
		),
		$up['file']
	);
	update_post_meta( $id, '_wa_draft', $draft );
	set_transient( 'wa_draft_count_' . $draft, $count + 1, DAY_IN_SECONDS );
	return array( 'id' => $id, 'url' => $up['url'] );
}

function wa_rest_submit( WP_REST_Request $req ) {
	$b     = (array) $req->get_json_params();
	$type  = ( isset( $b['type'] ) && 'free' === $b['type'] ) ? 'free' : 'brief';
	$draft = sanitize_key( isset( $b['draft'] ) ? $b['draft'] : '' );

	if ( ! empty( $b['company'] ) ) { // Honeypot.
		return array( 'ok' => true );
	}
	$email = sanitize_email( isset( $b['email'] ) ? $b['email'] : '' );
	$name  = sanitize_text_field( isset( $b['name'] ) ? $b['name'] : '' );
	if ( ! $name || ! is_email( $email ) ) {
		return new WP_REST_Response( array( 'error' => 'Please add your name and a valid email.' ), 400 );
	}

	if ( 'free' === $type ) {
		$dupe = get_posts(
			array(
				'post_type'   => 'wa_submission',
				'post_status' => 'any',
				'numberposts' => 1,
				'meta_query'  => array(
					array( 'key' => '_wa_free_email', 'value' => strtolower( $email ) ),
				),
			)
		);
		if ( $dupe ) {
			return new WP_REST_Response( array( 'error' => 'A free video has already been claimed with this email. Explore our collections to keep going.' ), 409 );
		}
	}

	$url_of = function ( $ids ) use ( $draft ) {
		$urls = array();
		foreach ( (array) $ids as $id ) {
			$id = (int) $id;
			if ( $id && get_post_meta( $id, '_wa_draft', true ) === $draft ) {
				$urls[ $id ] = wp_get_attachment_url( $id );
			}
		}
		return $urls;
	};

	$listings = array();
	$max      = 'free' === $type ? 1 : 10;
	foreach ( array_slice( (array) ( isset( $b['listings'] ) ? $b['listings'] : array() ), 0, $max ) as $l ) {
		$photos     = $url_of( isset( $l['photos'] ) ? $l['photos'] : array() );
		$listings[] = array(
			'description' => sanitize_textarea_field( isset( $l['description'] ) ? $l['description'] : '' ),
			'areas'       => sanitize_textarea_field( isset( $l['areas'] ) ? $l['areas'] : '' ),
			'text'        => sanitize_textarea_field( isset( $l['text'] ) ? $l['text'] : '' ),
			'notes'       => sanitize_textarea_field( isset( $l['notes'] ) ? $l['notes'] : '' ),
			'photos'      => array_values( $photos ),
			'photo_ids'   => array_keys( $photos ),
		);
	}
	$logo = $url_of( isset( $b['logo'] ) ? $b['logo'] : array() );

	// Order context.
	$pack        = sanitize_key( isset( $b['pack'] ) ? $b['pack'] : '' );
	$packs       = wa_pricing()['packs'];
	$order_label = 'free' === $type ? 'Free video' : '';
	$config      = null;
	if ( isset( $packs[ $pack ] ) ) {
		$order_label = $packs[ $pack ]['name'] . ' ($' . number_format( $packs[ $pack ]['price'] ) . ')';
	} elseif ( 'custom' === $pack ) {
		$ref    = sanitize_key( isset( $b['ref'] ) ? $b['ref'] : '' );
		$config = $ref ? get_transient( 'wa_order_' . $ref ) : null;
		$order_label = $config ? 'Custom: ' . $config['qty'] . ' × ' . $config['length'] . 's ($' . number_format( $config['total'] ) . ')' : 'Custom order';
	}

	$data = array(
		'type'        => $type,
		'pack'        => $pack,
		'order_label' => $order_label,
		'session_id'  => sanitize_text_field( isset( $b['session_id'] ) ? $b['session_id'] : '' ),
		'config'      => $config,
		'name'        => $name,
		'email'       => $email,
		'phone'       => sanitize_text_field( isset( $b['phone'] ) ? $b['phone'] : '' ),
		'brokerage'   => sanitize_text_field( isset( $b['brokerage'] ) ? $b['brokerage'] : '' ),
		'website'     => esc_url_raw( isset( $b['website'] ) ? $b['website'] : '' ),
		'logo'        => array_values( $logo ),
		'logo_ids'    => array_keys( $logo ),
		'listings'    => $listings,
	);

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'wa_submission',
			'post_status' => 'publish',
			'post_title'  => $name . ( $data['brokerage'] ? ' — ' . $data['brokerage'] : '' ),
		)
	);
	update_post_meta( $post_id, '_wa_data', $data );
	if ( 'free' === $type ) {
		update_post_meta( $post_id, '_wa_free_email', strtolower( $email ) );
	}

	// Email notification.
	$lines = array();
	foreach ( wa_flatten_submission( $data ) as $k => $v ) {
		if ( '' !== $v ) {
			$lines[] = $k . ":\n" . $v . "\n";
		}
	}
	$lines[] = 'Open in WordPress: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' );
	wp_mail(
		wa_opt( 'notify_email' ),
		( 'free' === $type ? 'New free video request: ' : 'New brief: ' ) . $name,
		implode( "\n", $lines ),
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	// Google Drive + Sheets sync runs in the background.
	if ( wa_opt( 'gdrive_sa_json' ) ) {
		update_post_meta( $post_id, '_wa_drive_status', 'Queued' );
		wp_schedule_single_event( time() + 5, 'wa_drive_sync', array( $post_id ) );
	}

	return array( 'ok' => true );
}

/* -------------------------------------------------------------------------
 * Google Drive / Sheets sync (service account)
 * ---------------------------------------------------------------------- */

function wa_b64url( $s ) {
	return rtrim( strtr( base64_encode( $s ), '+/', '-_' ), '=' );
}

function wa_google_token() {
	$cached = get_transient( 'wa_google_token' );
	if ( $cached ) {
		return $cached;
	}
	$sa = json_decode( wa_opt( 'gdrive_sa_json' ), true );
	if ( empty( $sa['client_email'] ) || empty( $sa['private_key'] ) ) {
		return new WP_Error( 'wa_google', 'Invalid service account JSON.' );
	}
	$now    = time();
	$header = wa_b64url( wp_json_encode( array( 'alg' => 'RS256', 'typ' => 'JWT' ) ) );
	$claims = wa_b64url(
		wp_json_encode(
			array(
				'iss'   => $sa['client_email'],
				'scope' => 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/spreadsheets',
				'aud'   => 'https://oauth2.googleapis.com/token',
				'iat'   => $now,
				'exp'   => $now + 3600,
			)
		)
	);
	$sig = '';
	if ( ! openssl_sign( $header . '.' . $claims, $sig, $sa['private_key'], 'sha256WithRSAEncryption' ) ) {
		return new WP_Error( 'wa_google', 'Could not sign Google token.' );
	}
	$res = wp_remote_post(
		'https://oauth2.googleapis.com/token',
		array(
			'timeout' => 20,
			'body'    => array(
				'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
				'assertion'  => $header . '.' . $claims . '.' . wa_b64url( $sig ),
			),
		)
	);
	$body = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( empty( $body['access_token'] ) ) {
		return new WP_Error( 'wa_google', 'Google auth failed: ' . wp_remote_retrieve_body( $res ) );
	}
	set_transient( 'wa_google_token', $body['access_token'], 3000 );
	return $body['access_token'];
}

function wa_drive_create_folder( $token, $name, $parent ) {
	$res = wp_remote_post(
		'https://www.googleapis.com/drive/v3/files?supportsAllDrives=true&fields=id,webViewLink',
		array(
			'timeout' => 20,
			'headers' => array( 'Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode(
				array(
					'name'     => $name,
					'mimeType' => 'application/vnd.google-apps.folder',
					'parents'  => $parent ? array( $parent ) : array(),
				)
			),
		)
	);
	return json_decode( wp_remote_retrieve_body( $res ), true );
}

function wa_drive_upload( $token, $path, $name, $parent ) {
	if ( ! file_exists( $path ) ) {
		return false;
	}
	$boundary = 'wa' . wp_generate_password( 16, false );
	$mime     = wp_check_filetype( $path )['type'] ?: 'application/octet-stream';
	$meta     = wp_json_encode( array( 'name' => $name, 'parents' => array( $parent ) ) );
	$body     = "--$boundary\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n$meta\r\n"
		. "--$boundary\r\nContent-Type: $mime\r\n\r\n" . file_get_contents( $path ) . "\r\n--$boundary--";
	$res = wp_remote_post(
		'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&supportsAllDrives=true',
		array(
			'timeout' => 120,
			'headers' => array( 'Authorization' => 'Bearer ' . $token, 'Content-Type' => 'multipart/related; boundary=' . $boundary ),
			'body'    => $body,
		)
	);
	return 200 === wp_remote_retrieve_response_code( $res );
}

add_action(
	'wa_drive_sync',
	function ( $post_id ) {
		$data = get_post_meta( $post_id, '_wa_data', true );
		if ( ! is_array( $data ) ) {
			return;
		}
		$token = wa_google_token();
		if ( is_wp_error( $token ) ) {
			update_post_meta( $post_id, '_wa_drive_status', $token->get_error_message() );
			return;
		}
		$parent = wa_opt( 'gdrive_folder_id' );
		$folder = wa_drive_create_folder( $token, gmdate( 'Y-m-d' ) . ' — ' . $data['name'] . ( $data['order_label'] ? ' — ' . $data['order_label'] : '' ), $parent );
		if ( empty( $folder['id'] ) ) {
			update_post_meta( $post_id, '_wa_drive_status', 'Folder error' );
			return;
		}
		foreach ( (array) $data['logo_ids'] as $id ) {
			wa_drive_upload( $token, get_attached_file( $id ), 'logo-' . basename( get_attached_file( $id ) ), $folder['id'] );
		}
		foreach ( (array) $data['listings'] as $i => $l ) {
			$sub = wa_drive_create_folder( $token, 'Listing ' . ( $i + 1 ), $folder['id'] );
			if ( empty( $sub['id'] ) ) {
				continue;
			}
			foreach ( (array) $l['photo_ids'] as $id ) {
				wa_drive_upload( $token, get_attached_file( $id ), basename( get_attached_file( $id ) ), $sub['id'] );
			}
		}
		update_post_meta( $post_id, '_wa_drive_url', $folder['webViewLink'] );
		update_post_meta( $post_id, '_wa_drive_status', 'Synced' );

		$sheet = wa_opt( 'gsheet_id' );
		if ( $sheet ) {
			$flat = wa_flatten_submission( $data );
			$row  = array_merge( array( current_time( 'mysql' ) ), array_values( $flat ), array( $folder['webViewLink'] ) );
			wp_remote_post(
				'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( $sheet ) . '/values/A1:append?valueInputOption=RAW&insertDataOption=INSERT_ROWS',
				array(
					'timeout' => 20,
					'headers' => array( 'Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json' ),
					'body'    => wp_json_encode( array( 'values' => array( array_map( 'strval', $row ) ) ) ),
				)
			);
		}
	}
);
