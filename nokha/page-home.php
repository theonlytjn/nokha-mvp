<?php get_header('index'); ?>

<div id="content" class="clearfix">

    <div id="home-banner" class="clearfix">

        <div class="home-card">

            <div class="large-12 medium-12 small-12">

                <div class="hide-for-small-only">

                    <?php $image = get_field('banner_image'); $size = 'full'; if ($image) { echo wp_get_attachment_image($image, $size); } ?>

                </div>

                <div class="show-for-small-only">

                    <?php $image = get_field('banner_image'); $size = 'article-portrait'; if ($image) { echo wp_get_attachment_image($image, $size); } ?>

                </div>

                <div class="card-info">

                    <div class="img-box">

                        <div class="content-holder">

                            <div class="row" data-equalizer>

                                <div class="large-12 medium-12 small-11 large-centered medium-centered small-centered columns">

                                    <div class="large-5 medium-6 small-12">

                                        <img class="logo" src="<?php bloginfo('url'); ?>/wp-content/uploads/2024/02/main-logo-white.svg">
                                        <div class="clear1"></div>

                                        <h1>
                                            Welcome <br>
                                            to Nokha
                                        </h1>

                                        <p class="tagline">
                                            Revolutionising Telant Acquisition in Oil &amp; Gas
                                        </p>

                                        <div class="divider"></div>

                                    </div>

                                </div>


                                <div class="large-3 medium-5 small-12 columns">

                                    <div class="callout" data-equalizer-watch>

                                        <h4>Get A Job</h4>
                                        <p>
                                            I’m a candidate read to find my dream job
                                        </p>

                                        <a href="/jobs-listing">
                                            <i class="fa-light fa-arrow-right-long"></i>
                                        </a>

                                    </div>

                                </div>

                                <div class="large-3 medium-5 small-12 columns">

                                    <div class="callout" data-equalizer-watch>

                                        <h4>Finding New Talent</h4>
                                        <p>
                                            I’m an employer looking for the perfect candidate
                                        </p>

                                        <a href="/register">
                                            <i class="fa-light fa-arrow-right-long"></i>
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div> <!-- end #content -->

<?php get_footer('index'); ?>
