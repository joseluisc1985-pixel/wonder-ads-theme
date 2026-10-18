<?php
/**
 * Terms of Service (slug: terms).
 */
get_header();
$email = esc_html( wa_opt( 'notify_email' ) );
?>
<main class="inner">
	<section class="section">
		<div class="wrap narrow prose">
			<p class="eyebrow">Legal</p>
			<h1 class="page-title">Terms of Service</h1>
			<p class="muted">Last updated: October 17, 2026</p>

			<p class="lead">These terms govern your use of wonderads.net and the cinematic reel videos Wonder Ads creates for you. By placing an order, claiming a free video or using our website, you agree to them.</p>

			<h2>1. Our service</h2>
			<p>Wonder Ads is a boutique AI video agency. We create cinematic reel videos for real estate listings from the photos and information you provide, using the latest AI models together with the judgment of professional filmmakers and storytellers. We do not film on location. Our service is designed for high-end properties from $1.5M and up that are fully furnished and move-in ready.</p>

			<h2>2. Who may use it</h2>
			<p>Our services are offered to real estate professionals and businesses. You confirm that you are at least 18 years old and authorized to act for yourself or the brokerage you represent.</p>

			<h2>3. Orders and payment</h2>
			<p>Prices are shown on our website in US dollars and may change from time to time; the price at checkout applies to your order. Payment is due in full at checkout and is processed securely by Stripe. Collections include the number of videos stated and may be used across one or more listings. Custom orders are priced by our online calculator based on the options you select.</p>

			<h2>4. Your free first video</h2>
			<p>New clients may claim one complimentary 30-second vertical cinematic video, with no credit card and no obligation. The offer is limited to one per realtor or brokerage. We may decline requests that fall outside our property criteria or that we reasonably believe abuse the offer.</p>

			<h2>5. Your materials</h2>
			<p>You confirm that you own or have permission to use every photo, logo, text and other material you send us, and that using them to create your video does not infringe anyone’s rights. You also confirm that the property information you provide is accurate. You keep ownership of your materials; you grant us permission to use them only to create, deliver and revise your videos.</p>

			<h2>6. Delivery</h2>
			<p>We aim to deliver within 2 days, about 48 hours, of receiving your complete brief, including all photos and details. Larger orders may be delivered in stages. Delivery times are estimates, not guarantees, and may be longer during peak periods; we will tell you if we expect a delay.</p>

			<h2>7. Revisions</h2>
			<p>Every cinematic video includes two rounds of revisions. Revisions refine the delivered video, such as pacing, music, text, voiceover and order of scenes. A new concept, new property or new materials is a new video. Please request revisions within 14 days of delivery.</p>

			<h2>8. Cancellations and refunds</h2>
			<p>Because every video is custom digital work, sales are final once production begins. If you cancel before we start work on your order, we will refund you in full. If we cannot deliver a video you ordered, we will refund the price of that video. Unused videos in a collection do not expire for 12 months from purchase.</p>

			<h2>9. Truthful marketing and compliance</h2>
			<p>We design every video to represent the home truthfully, based on your real listing photos. You are responsible for reviewing each video before publishing it and for making sure its use complies with your MLS rules, brokerage policies, fair housing laws and any advertising or disclosure requirements in your market, including any rules about AI-generated or enhanced media.</p>

			<h2>10. Your rights to the videos</h2>
			<p>Once your order is paid in full, you receive a perpetual, worldwide license to use your delivered videos to market the property and your real estate business, on social media, websites, listing portals, email and paid advertising. The original music in your videos is licensed to you for use with those videos. Wonder Ads keeps all rights in its methods, tools, templates and underlying music. You may not resell the videos or use them to train AI models.</p>

			<h2>11. Portfolio</h2>
			<p>We will only show your video in our portfolio or marketing with your permission. You can withdraw permission at any time by emailing us.</p>

			<h2>12. Acceptable use</h2>
			<p>Please don’t use our website or services for anything unlawful, misleading or harmful, or try to disrupt or copy our website or systems. We may refuse or stop work on any order that would breach these terms.</p>

			<h2>13. Disclaimers</h2>
			<p>We create every video with care, but results such as faster sales, more views or more leads depend on many factors outside our control and are not guaranteed. Except as stated in these terms, our services are provided “as is.”</p>

			<h2>14. Limitation of liability</h2>
			<p>To the fullest extent permitted by law, Wonder Ads will not be liable for indirect, incidental or consequential damages, or lost profits. Our total liability for any claim relating to an order is limited to the amount you paid for that order.</p>

			<h2>15. Governing law</h2>
			<p>These terms are governed by the laws of the State of Florida, United States. Any dispute will be resolved in the state or federal courts located in Miami-Dade County, Florida.</p>

			<h2>16. Changes</h2>
			<p>We may update these terms. The version posted here applies to orders placed after the date shown above.</p>

			<h2>17. Contact</h2>
			<p>Wonder Ads · 78 SW 7th St, Miami, FL 33130 · <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a> · <a href="tel:+14583120457">(458) 312-0457</a></p>
			<p>See also our <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy Policy</a>.</p>
		</div>
	</section>
</main>
<?php get_footer(); ?>
