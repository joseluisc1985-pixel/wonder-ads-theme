<?php
$pack       = isset( $_GET['pack'] ) ? sanitize_key( wp_unslash( $_GET['pack'] ) ) : ''; // phpcs:ignore
$ref        = isset( $_GET['ref'] ) ? sanitize_key( wp_unslash( $_GET['ref'] ) ) : ''; // phpcs:ignore
$session_id = isset( $_GET['session_id'] ) ? sanitize_text_field( wp_unslash( $_GET['session_id'] ) ) : ''; // phpcs:ignore
$packs      = wa_pricing()['packs'];
$count      = 1;
$label      = '';
$prefill    = array();

if ( isset( $packs[ $pack ] ) ) {
	$count = $packs[ $pack ]['videos'];
	$label = $packs[ $pack ]['name'];
} elseif ( 'custom' === $pack && $ref ) {
	$cfg = get_transient( 'wa_order_' . $ref );
	if ( $cfg ) {
		$count = $cfg['qty'];
		$label = 'Custom order · ' . $cfg['qty'] . ' × ' . $cfg['length'] . '-second video' . ( $cfg['qty'] > 1 ? 's' : '' );
	}
}

if ( $session_id && 0 === strpos( $session_id, 'cs_' ) && wa_opt( 'stripe_secret' ) ) {
	$s = wa_stripe( 'GET', 'checkout/sessions/' . rawurlencode( $session_id ) );
	if ( ! is_wp_error( $s ) && ! empty( $s['customer_details'] ) ) {
		$prefill = array(
			'name'  => isset( $s['customer_details']['name'] ) ? $s['customer_details']['name'] : '',
			'email' => isset( $s['customer_details']['email'] ) ? $s['customer_details']['email'] : '',
			'phone' => isset( $s['customer_details']['phone'] ) ? $s['customer_details']['phone'] : '',
		);
	}
}
$count = min( 10, max( 1, $count ) );

get_header();
?>
<main class="inner">
	<section class="section">
		<div class="wrap narrow">
			<div class="section-head">
				<p class="eyebrow"><?php echo $label ? esc_html( $label ) : 'Your Brief'; ?></p>
				<h1 class="page-title">Thank you. Your cinematic video begins now.</h1>
				<p class="lead">Share the details below and our team takes it from here. Your 48-hour delivery window starts once the brief is complete.</p>
			</div>
			<?php
			get_template_part(
				'template-parts/brief-form',
				null,
				array(
					'type'       => 'brief',
					'pack'       => $pack,
					'ref'        => $ref,
					'session_id' => $session_id,
					'listings'   => $count,
					'max'        => 10,
					'prefill'    => $prefill,
				)
			);
			?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
