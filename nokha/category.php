<?php get_header(); ?>

<div id="content" class="clearfix">

    <div id="page-banner" class="clearfix">

        <div class="row">

            <div class="large-9 medium-9 small-12 columns large-centered medium-centered">

                <div class="section-block">

                    <h1 class="text-center"><?php single_cat_title();?></h1>
                    <p><?php the_archive_description('');?></p>

                </div>

            </div>

        </div>

    </div>

    <div id="main-content" class="clearfix">

        <div class="section-block">

            <div class="row">

                <div class="large-12 medium-12 small-12 columns">

                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <!-- To see additional archive styles, visit the /parts directory -->
                    <?php get_template_part( 'parts/loop', 'archive-grid' ); ?>

                    <?php endwhile; ?>

                    <?php joints_page_navi(); ?>

                    <?php else : ?>

                    <?php get_template_part( 'parts/content', 'missing' ); ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div> <!-- end #content -->

<?php get_footer(); ?>
