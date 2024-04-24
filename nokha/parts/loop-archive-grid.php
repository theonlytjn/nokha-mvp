<?php
// Adjust the amount of rows in the grid
$grid_columns = 3; ?>

<?php if( 0 === ( $wp_query->current_post  )  % $grid_columns ): ?>

<div class="row archive-grid" data-equalizer>
    <!--Begin Row:-->

    <?php endif; ?>

    <!--Item: -->
    <div class="large-4 medium-4 small-12 columns panel" data-equalizer-watch>

        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?> role="article">

            <section class="featured-image" itemprop="articleBody">
                <?php the_post_thumbnail('post-thumb'); ?>
            </section> <!-- end article section -->

            <header class="article-header">

                <div class="row">

                    <div class="large-6 medium-6 small-6 columns">
                        <h6 class="text-left"><?php the_time('d.n.Y'); ?></h6>
                    </div>

                    <div class="large-6 medium-6 small-6 columns">
                        <h6 class="text-right"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span>-</span> read post</a></h6>
                    </div>

                </div>

                <div class="row">

                    <div class="large-12 medium-12 small-12 columns">

                        <h5><?php the_title(); ?></h5>
                        <div class="clear2"></div>

                    </div>

                </div>

            </header> <!-- end article header -->

        </article> <!-- end article -->

    </div>

    <?php if( 0 === ( $wp_query->current_post + 1 )  % $grid_columns ||  ( $wp_query->current_post + 1 ) ===  $wp_query->post_count ): ?>

</div>
<!--End Row: -->

<?php endif; ?>
