<?php get_header('index'); ?>

<div id="content" class="clearfix">

    <div id="main-content" class="clearfix">

        <div class="row align-middle collapse expanded">

            <div class="large-6 medium-6 small-12 columns">

                <div id="login-register-banner" class="clearfix">

                    <div class="home-card">

                        <div class="large-12 medium-12 small-12">

                            <?php the_post_thumbnail('half-banner'); ?>

                            <div class="card-info">

                                <div class="img-box">

                                    <div class="content-holder">

                                        <div class="large-12 medium-12 small-11 large-centered medium-centered small-centered columns">

                                            <div class="large-9 medium-9 small-12 columns large-centered medium-centered small-centered columns">

                                                <a href="<?php bloginfo('url'); ?>">
                                                    <img class="logo" src="<?php bloginfo('url'); ?>/wp-content/uploads/2024/02/main-logo-white.svg">
                                                </a>

                                                <h1>
                                                    Get started!
                                                </h1>
                                                <div class="clear1"></div>

                                                <h3>
                                                    The destination for contracted positions in the Oil and Gas industry
                                                </h3>

                                                <div class="clear1"></div>

                                                <p>
                                                    Already have an account <a href="<?php bloginfo('url'); ?>/login">Login</a>
                                                </p>

                                                <div class="clear2"></div>

                                            </div>

                                            <div class="row align-middle">

                                                <div class="large-9 medium-10 small-12 columns large-centered medium-centered">

                                                    <div class="large-2 medium-2 small-12 columns">
                                                        <img src="http://nokha-mvp/wp-content/uploads/2024/02/signed-up.png">
                                                    </div>

                                                    <div class="large-10 medium-10 small-12 columns">
                                                        <p>3k+ people joined us, now it’s your turn</p>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="large-6 medium-6 small-12 columns">

                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <?php get_template_part('parts/loop', 'page'); ?>

                <?php endwhile; endif; ?>

            </div>

        </div>

    </div>

</div> <!-- end #content -->

<?php get_footer('index'); ?>
