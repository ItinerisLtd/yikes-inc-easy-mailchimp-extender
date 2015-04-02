<?php
	wp_enqueue_script( 'jquery-masonry' );
	wp_enqueue_style( 'animate-css' , '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.6/animate.min.css' );
?>
<!-- animate our team containers -->
<script type="text/javascript">
	jQuery( document ).ready( function() {
		/** Masonry **/
			// initialize
			var $container = jQuery( '#team-member-container' );
			$container.imagesLoaded(function() {
				$container.masonry({	
					columnWidth: '.team-member',
					itemSelector: '.team-member'
				});
			});
		// fade in individually
		var time = 15;
		jQuery( '.about-yikes-inc-text' ).addClass( 'animated fadeIn' );
		 jQuery('.team-member').each(function() {
			  var element = jQuery( this );
			  setTimeout( function(){ element.addClass( 'animated fadeIn' ); }, time)
			  time += 80;
		  });
	});
</script>
<div class="wrap" id="about-yikes">

	<h2><?php _e( 'About the Yikes Inc. Team' , $this->text_domain ); ?></h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox yikes-easy-mc-postbox">

						<div class="inside">
							
							<p class="about-yikes-inc-text">
							<?php _e( "YIKES, Inc. – A Philadelphia based web design and development company
								Dedicated to sustainable business practices since 1996, YIKES has continued to be a socially responsible business observing the triple bottom line: people, planet, profit. YIKES is committed to building a more socially, environmentally and financially sustainable local economy.

								The YIKES office is located in a Platinum LEED certified building and is powered by 100% Renewable electricity through The Energy Cooperative. YIKES recycles and composts office waste and prints promotional materials on recycled paper with soy ink. The YIKES office uses green cleaning and paper products. YIKES is also a 100% Replanted office.", $this->text_domain ); ?>
							</p>
							
							<div id="team-member-container">
																
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/g-philly_shop-talk.jpg" title="Mia Levesque">
									<p class="member-blurb">
										<p><strong>Mia Levesque</strong> • Web Project Manager/Co-Owner</p>
										<p><?php _e( "Mia is essential in maintaining clients relations and project management. She is responsible for overseeing YIKES, Inc. largest projects while making sure all of our clients receive the highest quality work." , $this->text_domain ); ?></p>
									</p>
								</div>
								
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/tracy1.jpg" title="Tracy Levesque">
									<p class="member-blurb">
										<p><strong>Tracy Levesque</strong> • Web Designer &amp; Developer/Co-Owner</p>
										<p><?php _e( "Tracy has been designing websites since 1996 and has a large portfolio of work amassed.  She has expertise in HTML, CSS, WordPress and eCommerce sites." , $this->text_domain ); ?></p>
									</p>
								</div>
								
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/day-1-scott1.jpg" title="Scott Wilson">
									<p class="member-blurb">
										<p><strong>Scott Wilson</strong> • Web Designer/Multimedia Expert</p>
										<p><?php _e( "Scott is a multimedia expert and web designer with 10 years of experience with Flash, HTML, CSS, and encoding video for web using various technologies." , $this->text_domain ); ?></p>
									</p>
								</div>
							
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/Pearson1.png" title="Pearson Barlow">
									<p class="member-blurb">
										<p><strong>Pearson Barlow</strong> • Programmer/ColdFusion Expert</p>
										<p><?php _e( "Pearson has over 10 years of experience in web development. He is expert level at several programming languages including ColdFusion, PHP, CSS, and JavaScript. Pearson also has extensive experience with MySQL and MS SQL databases." , $this->text_domain ); ?></p>
									</p>
								</div>
								
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/jodie1.jpg" title="Jodie Riccelli">
									<p class="member-blurb">
										<p><strong>Jodie Riccelli</strong> • Sales and Marketing Director</p>
										<p><?php _e( "Jodie has nearly 15 years Sales and Marketing experience and passion for technology. Call Jodie today to learn about how we can work together on your web development project." , $this->text_domain ); ?></p>
									</p>
								</div>
								
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/carlos.jpg" title="Carlos Zuniga">
									<p class="member-blurb">
										<p><strong>Carlos Zuniga</strong> • Programmer/PHP Expert</p>
										<p><?php _e( "Carlos has over 12 years of experience in web development. He is an expert level PHP developer with experience in open-source CMS development including creating &amp; modifying themes, plugins and modules." , $this->text_domain ); ?></p>
									</p>
								</div>
	
								<div class="team-member">
									<img src="http://www.yikesinc.com/wp-content/uploads/2011/11/evan2-sm.jpg" title="Evan Herman">
									<p class="member-blurb">
										<p><strong>Evan Herman</strong> • Programmer/WordPress Developer</p>
										<p><?php _e( "Evan is an accomplished WordPress developer, specializing in building custom WordPress plugins and themes." , $this->text_domain ); ?></p>
									</p>
								</div>

							</div>
							
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox yikes-easy-mc-postbox">

						<div class="inside">
							
							<!-- Yikes Inc Logo -->
							<a href="http://www.yikesinc.com" target="_blank" title="<?php _e( 'Yikes Inc. Website' , $this->text_domain ); ?>">
								<img src="<?php echo YIKES_MC_URL . 'includes/images/About_Page/yikes-logo.png'; ?>" width="130" style="display:block;margin:0 auto;margin-top:10px;" />
							</a>
							
							<hr />
							
							<div id="certifications">
								<h4 style="width:100%;text-align:center;"><?php _e( 'Certifications' , $this->text_domain ); ?></h4>
								<ul>
									<a href="https://www.bcorporation.net/community/yikes-inc" target="_blank" title="<?php _e( 'B-Corp Certification' , $this->text_domain ); ?>">
										<img src="<?php echo YIKES_MC_URL . 'includes/images/About_Page/B-Corp.jpg'; ?>" alt="<?php _e( 'B-Corp Certification' , $this->text_domain ); ?>" />
									</a>
									<a href="https://nglcc.org/" target="_blank" title="<?php _e( 'National Gay & Lesbian Chamber of Commerce Certification' , $this->text_domain ); ?>">
										<img style="margin-left:15px;" src="<?php echo YIKES_MC_URL . 'includes/images/About_Page/nglcc.jpg'; ?>" alt="<?php _e( 'NGLCC Certification' , $this->text_domain ); ?>" />
									</a>
								</ul>
								<ul>
									<a href="http://www.wbenc.org/" target="_blank" title="<?php _e( 'Womens Business Enterprise National Council Certification' , $this->text_domain ); ?>">
										<img src="<?php echo YIKES_MC_URL . 'includes/images/About_Page/WBENC_CP.jpg'; ?>" alt="<?php _e( 'WBENC CP Certification' , $this->text_domain ); ?>" />
									</a>
								</ul>
							</div>
							
							<hr />
							
							<?php $this->generate_yikes_RSS_feed(); ?>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->