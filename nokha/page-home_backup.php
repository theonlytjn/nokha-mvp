<?php get_header(); ?>

<div id="content" class="clearfix">

    <div id="main-content" class="large-12 medium-12 small-12 columns">

        <div id="home-banner" class="clearfix">

            <div class="row">

                <div class="large-12 medium-12 small-12 columns">
                    <div class="clear4"></div>
                </div>

                <div class="large-10 medium-10 small-12 columns">

                    <h1 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'banner_title' ); ?></h1>

                </div>

                <div class="large-8 medium-8 small-12 columns">

                    <p data-equalizer-watch data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000"><?php the_field( 'banner_text' ); ?></p>

                </div>

            </div>

            <div class="row collapse expanded">

                <div class="large-12 medium-12 small-12 columns" data-equalizer-watch data-aos="fade-up" data-aos-delay="450" data-aos-duration="1000">

                    <div class="hide-for-small-only">

                        <?php $image = get_field('banner_image'); $size = 'full'; if ($image) { echo wp_get_attachment_image($image, $size); } ?>

                    </div>

                    <div class="show-for-small-only">

                        <?php $image = get_field('banner_image'); $size = 'article-thumb'; if ($image) { echo wp_get_attachment_image($image, $size); } ?>

                    </div>

                </div>

            </div>

        </div>

        <div id="who-we-are" class="clearfix">

            <div class="section-block">

                <div class="row">

                    <div class="large-6 medium-6 small-12 columns">

                        <h2 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><span><?php the_field( 'wwa_title' ); ?></span></h2>

                    </div>

                    <div class="large-6 medium-6 small-12 columns">

                        <p data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'wwa_description' ); ?></p>
                        <div class="clear2"></div>

                        <h3 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><span><?php the_field( 'what_we_do_title' ); ?></span></h3>
                        <div class="clear1"></div>

                        <?php if ( have_rows( 'what_we_do_list' ) ) : ?>

                        <ul>
                            <?php while ( have_rows( 'what_we_do_list' ) ) : the_row(); ?>

                            <li data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"> <?php the_sub_field( 'list_item' ); ?></li>

                            <?php endwhile; ?>

                        </ul>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        <div id="how-hire" class="clearfix">

            <div class="section-block">

                <div class="row">

                    <div class="large-6 medium-6 small-12 columns">

                        <h2 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">We're <span>Hiring</span></h2>

                    </div>

                    <div class="large-6 medium-6 small-12 columns">

                        <h2 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">How <span>We Hire</span></h2>

                        <?php if ( have_rows( 'how_we_hire_list' ) ) : ?>

                        <ul>

                            <?php while ( have_rows( 'how_we_hire_list' ) ) : the_row(); ?>

                            <li data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">
                                <?php the_sub_field( 'step_description' ); ?>
                            </li>

                            <?php endwhile; ?>

                        </ul>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        <div id="our-culture" class="clearfix">

            <div class="section-block">

                <div class="row">

                    <div class="large-12 medium-12 small-12 columns">
                        <h2 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'oc_title' ); ?></h2>
                    </div>

                    <div class="large-6 medium-6 small-12 columns">
                        <p class="first" data-equalizer-watch data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000"><?php the_field( 'oc_description' ); ?></p>
                    </div>

                </div>

                <?php if ( have_rows( 'oc_steps' ) ) : ?>
                <?php $counter = 1;  //this sets up the counter starting at 0 ?>

                <div class="row">

                    <?php while ( have_rows( 'oc_steps' ) ) : the_row(); ?>

                    <div class="large-3 medium-3 small-6 columns">

                        <div class="oc-container" data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">

                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) : ?>
                            <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
                            <?php endif; ?>

                            <div class="overlay-<?php echo $counter; ?>"></div>

                            <div class="oc-description oc-text-<?php echo $counter; ?>">
                                <?php the_sub_field( 'description' ); ?>
                            </div>

                            <div class="step-count counter-<?php echo $counter; ?>">
                                <p>/<?php echo $counter; ?></p>
                            </div>

                        </div>

                        <?php $counter++; // add one per row ?>
                    </div>
                    <?php endwhile; ?>


                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div id="groups-history" class="clearfix">

            <div class="row">

                <div class="large-12 medium-12 small-12 columns">
                    <h2 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'gh_title' ); ?> </h2>
                </div>

            </div>

            <div class="row expanded collapse">

                <div class="large-12 medium-12 small-12 columns">

                    <?php $gh_image = get_field( 'gh_image' ); ?>
                    <?php if ( $gh_image ) : ?>
                    <img data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000" src="<?php echo esc_url( $gh_image['url'] ); ?>" alt="<?php echo esc_attr( $gh_image['alt'] ); ?>" />
                    <?php endif; ?>

                </div>

            </div>

            <div class="row">

                <div class="section-block">

                    <div class="large-6 medium-6 small-12 columns">

                        <h1 data-equalizer-watch data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                            <span><?php the_field( 'gh_tagline' ); ?></span>
                        </h1>

                    </div>

                    <div class="large-6 medium-6 small-12 columns">

                        <p data-equalizer-watch data-aos="fade-up" data-aos-delay="450" data-aos-duration="1000"><?php the_field( 'gh_description' ); ?></p>
                        <div class="clear1"></div>

                        <?php if ( have_rows( 'gh_list' ) ) : ?>

                        <ul>
                            <?php while ( have_rows( 'gh_list' ) ) : the_row(); ?>

                            <li data-equalizer-watch data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                                <?php the_sub_field( 'list_item' ); ?>
                            </li>

                            <?php endwhile; ?>

                        </ul>

                        <?php else : ?>
                        <?php // No rows found ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        <div id="locations" class="clearfix">

            <div class="section-block">

                <div class="row">

                    <div class="large-12 medium-12 small-12 columns">
                        <h2 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">Global <span>Locations</span></h2>
                    </div>

                </div>

                <?php if ( have_rows( 'locations_list' ) ) : ?>

                <div class="row">

                    <?php while ( have_rows( 'locations_list' ) ) : the_row(); ?>

                    <div class="large-4 medium-4 small-6 columns">

                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) : ?>
                        <img data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
                        <?php endif; ?>

                        <h3 data-equalizer-watch data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000"><?php the_sub_field( 'city' ); ?></h3>
                        <p data-equalizer-watch data-aos="fade-up" data-aos-delay="450" data-aos-duration="1000"><?php the_sub_field( 'address' ); ?></p>

                    </div>

                    <?php endwhile; ?>

                </div>

                <?php else : ?>
                <?php // No rows found ?>
                <?php endif; ?>

            </div>

        </div>

        <div id="testimonials" class="clearfix">

            <div class="section-block">

                <div class="row">

                    <div class="large-4 medium-4 small-12 columns">
                        <h5 data-equalizer-watch data-aos="fade-right" data-aos-delay="150" data-aos-duration="1000"><span><?php the_field( 'testimonials_title' ); ?></span></h5>
                    </div>

                    <div class="large-8 medium-8 small-12 columns">

                        <?php if ( have_rows( 'testimonials_list' ) ) : ?>

                        <div class="carousel carousel-gallery" data-flickity='{"pageDots": true, "freeScroll": false, "prevNextButtons": false, "wrapAround": true, "hash": true }'>
                            <?php while ( have_rows( 'testimonials_list' ) ) : the_row(); ?>

                            <div class="carousel-cell" id="carousel-cell" data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">

                                <h2>
                                    <?php the_sub_field( 'testimonial' ); ?>
                                </h2>

                                <h6 class="text-caps">
                                    <span>
                                        <?php the_sub_field( 'name' ); ?> (<?php the_sub_field( 'job_role' ); ?>)
                                    </span>
                                </h6>

                            </div>

                            <?php endwhile; ?>

                        </div>

                        <?php else : ?>
                        <?php // No rows found ?>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div> <!-- end #content -->

<?php get_footer(); ?>
