<?php
/**
 * Shared brief form. $args: type (brief|free), pack, ref, session_id, listings (initial count), max.
 */
$type     = isset( $args['type'] ) ? $args['type'] : 'brief';
$listings = max( 1, (int) ( isset( $args['listings'] ) ? $args['listings'] : 1 ) );
$max      = (int) ( isset( $args['max'] ) ? $args['max'] : 10 );
$prefill  = isset( $args['prefill'] ) ? $args['prefill'] : array();
?>
<form class="brief" data-brief data-type="<?php echo esc_attr( $type ); ?>" data-max="<?php echo (int) $max; ?>" novalidate>
	<input type="hidden" name="pack" value="<?php echo esc_attr( isset( $args['pack'] ) ? $args['pack'] : '' ); ?>">
	<input type="hidden" name="ref" value="<?php echo esc_attr( isset( $args['ref'] ) ? $args['ref'] : '' ); ?>">
	<input type="hidden" name="session_id" value="<?php echo esc_attr( isset( $args['session_id'] ) ? $args['session_id'] : '' ); ?>">
	<div class="hp" aria-hidden="true"><label>Company <input type="text" name="company" tabindex="-1" autocomplete="off"></label></div>

	<section class="brief-block">
		<h3>Your details</h3>
		<div class="grid-2">
			<label class="input"><span>Name</span><input type="text" name="name" required autocomplete="name" placeholder="First and last name" value="<?php echo esc_attr( isset( $prefill['name'] ) ? $prefill['name'] : '' ); ?>"></label>
			<label class="input"><span>Email</span><input type="email" name="email" required autocomplete="email" placeholder="Where we'll send your video" value="<?php echo esc_attr( isset( $prefill['email'] ) ? $prefill['email'] : '' ); ?>"></label>
			<label class="input"><span>Realty group or brokerage</span><input type="text" name="brokerage" autocomplete="organization"></label>
			<label class="input"><span>Website</span><input type="url" name="website" placeholder="https://"></label>
			<label class="input"><span>Phone</span><input type="tel" name="phone" autocomplete="tel" value="<?php echo esc_attr( isset( $prefill['phone'] ) ? $prefill['phone'] : '' ); ?>"></label>
			<label class="input file"><span>Your logo</span><input type="file" name="logo" accept="image/png,image/jpeg,image/webp"><small>PNG with transparent background preferred.</small></label>
		</div>
	</section>

	<div data-listings>
		<?php for ( $i = 0; $i < $listings; $i++ ) : ?>
			<section class="brief-block listing" data-listing>
				<h3>Listing <span data-listing-num><?php echo (int) ( $i + 1 ); ?></span></h3>
				<label class="input file"><span>Listing photos</span><input type="file" name="photos" accept="image/*" multiple><small>Your MLS photos, high resolution, no watermarks.</small></label>
				<label class="input"><span>Listing description</span><textarea name="description" rows="3" placeholder="Price, beds, baths, square footage and the features that make this home special."></textarea></label>
				<label class="input"><span>Areas to showcase</span><input type="text" name="areas" placeholder="For example: living room, main bedroom, outdoors."></label>
				<label class="input"><span>Text on screen</span><input type="text" name="text" placeholder="Address, price, a tagline — anything you'd like to appear."></label>
				<label class="input"><span>Notes</span><textarea name="notes" rows="3" placeholder="The story of this home, the buyer you imagine, anything we should know."></textarea></label>
			</section>
		<?php endfor; ?>
	</div>

	<?php if ( $max > 1 ) : ?>
		<button type="button" class="btn btn-outline btn-sm" data-add-listing>+ Add a listing</button>
	<?php endif; ?>

	<div class="brief-submit">
		<button type="submit" class="btn btn-block"><?php echo 'free' === $type ? 'Claim My Free Video' : 'Send My Brief'; ?></button>
		<?php if ( 'free' === $type ) : ?><p class="muted small center">One free video per agent. No credit card required.</p><?php endif; ?>
		<div class="progress" data-progress hidden><span></span></div>
		<p class="form-msg" data-brief-msg></p>
		<p class="muted small center">Need help with your brief? Our live agent is at the bottom right. Chat or call, anytime.</p>
	</div>
</form>
<div class="brief-done" data-brief-done hidden>
	<p class="eyebrow">Received</p>
	<h2><?php echo 'free' === $type ? 'Your first cinematic experience is on its way.' : 'Your premiere is on its way.'; ?></h2>
	<p class="lead"><?php echo 'free' === $type ? 'Expect it in your inbox within 2 days.' : "We'll be in touch shortly."; ?></p>
	<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Wonder Ads</a>
</div>
