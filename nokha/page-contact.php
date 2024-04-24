<?php get_header(); ?>

<div id="content" class="clearfix">

    <div id="page-banner" class="clearfix">

        <div class="row">

            <div class="large-9 medium-9 small-12 columns large-centered medium-centered">

                <div class="section-block">

                    <h1 class="text-center"><?php the_title(); ?></h1>

                </div>

            </div>

        </div>

    </div>

    <div id="main-contact-info" class="clearfix">

        <div class="section-block">

            <div class="row">

                <div class="large-5 medium-6 small-12 columns">

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <?php get_template_part('parts/loop', 'page'); ?>

                    <?php endwhile; endif; ?>

                </div>

                <div class="large-1 medium-6 small-12 columns">
                    <div class="clear1"></div>
                </div>

                <div class="large-6 medium-6 small-12 columns">

                    <?php echo do_shortcode('[elfsight_contact_form id="1"]'); ?>

                </div>

            </div>

        </div>

    </div>

</div> <!-- end #content -->

<?php get_footer(); ?>
