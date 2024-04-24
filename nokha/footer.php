<div id="footer" class="clearfix" role="contentinfo">

    <div class="row">

        <div class="large-4 medium-4 small-6 columns footer-section">

            <h6 class="footer-title" data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'footer_title_one', 'option' ); ?></h6>

            <?php if (have_rows('footer_links_one', 'options')): ?>

            <ul>

                <?php while (have_rows('footer_links_one', 'options')): the_row(); ?>

                <li data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                    <?php $link_url = get_sub_field( 'link_url' ); ?>
                    <?php if ( $link_url ) : ?>
                    <a href="<?php echo esc_url( $link_url['url'] ); ?>" target="<?php echo esc_attr( $link_url['target'] ); ?>"><?php echo esc_html( $link_url['title'] ); ?></a>
                    <?php endif; ?>
                </li>

                <?php endwhile; ?>
            </ul>

            <?php endif; ?>

        </div>

        <div class="large-4 medium-4 small-6 columns footer-section">

            <h6 class="footer-title" data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'footer_title_two', 'option' ); ?></h6>

            <?php if (have_rows('footer_links_two', 'options')): ?>

            <ul>

                <?php while (have_rows('footer_links_two', 'options')): the_row(); ?>

                <li data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                    <?php $link_url = get_sub_field( 'link_url' ); ?>
                    <?php if ( $link_url ) : ?>
                    <a href="<?php echo esc_url( $link_url['url'] ); ?>" target="<?php echo esc_attr( $link_url['target'] ); ?>"><?php echo esc_html( $link_url['title'] ); ?></a>
                    <?php endif; ?>
                </li>

                <?php endwhile; ?>
            </ul>

            <?php endif; ?>

        </div>

        <div class="large-4 medium-4 small-6 columns footer-section">

            <h6 class="footer-title" data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'footer_title_three', 'option' ); ?></h6>

            <?php if (have_rows('footer_links_three', 'options')): ?>

            <ul>

                <?php while (have_rows('footer_links_three', 'options')): the_row(); ?>

                <li data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                    <?php $link_url = get_sub_field( 'link_url' ); ?>
                    <?php if ( $link_url ) : ?>
                    <a href="<?php echo esc_url( $link_url['url'] ); ?>" target="<?php echo esc_attr( $link_url['target'] ); ?>"><?php echo esc_html( $link_url['title'] ); ?></a>
                    <?php endif; ?>
                </li>

                <?php endwhile; ?>
            </ul>

            <?php endif; ?>

        </div>

        <div class="large-4 medium-4 small-6 columns footer-section">

            <h6 class="footer-title" data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000"><?php the_field( 'footer_title_four', 'option' ); ?></h6>

            <?php if (have_rows('footer_links_four', 'options')): ?>

            <ul>

                <?php while (have_rows('footer_links_four', 'options')): the_row(); ?>

                <li data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                    <?php $link_url = get_sub_field( 'link_url' ); ?>
                    <?php if ( $link_url ) : ?>
                    <a href="<?php echo esc_url( $link_url['url'] ); ?>" target="<?php echo esc_attr( $link_url['target'] ); ?>"><?php echo esc_html( $link_url['title'] ); ?></a>
                    <?php endif; ?>
                </li>

                <?php endwhile; ?>
            </ul>

            <?php endif; ?>

        </div>

        <div class="large-4 medium-4 small-12 columns footer-section">

            <h6 data-equalizer-watch data-aos="fade-up" data-aos-delay="150" data-aos-duration="1000">Stay up to date /</h6>

            <p data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000"></p>

            <a href="#" target="_blank" class="button" data-aos="fade-up" data-aos-delay="450" data-aos-duration="1000">subscribe to our newsletter</a>

        </div>

    </div>

</div>

<div id="bottom-footer" class="clearfix">

    <div class="row">

        <div class="large-6 medium-6 small-12 columns">
            <p class="source-org copyright text-center medium-text-left">
                &copy;
                <?php echo date('Y'); ?>
                <?php bloginfo('name'); ?>. All Rights Reserved</p>
        </div>

        <div class="large-6 medium-6 small-12 columns">
            <p class="source-org powered text-center medium-text-right">
                Crafted by <a target="_blank" href="https://www.theonlytjn.com"><i class="fak fa-tjn-icon"></i></a>.
            </p>
        </div>

    </div>

    <div class="smoothscroll-top">
        <span class="scroll-top-inner">
            <i class="fal fa-long-arrow-up"></i>
        </span>
    </div>

    <!-- end .main-content -->
</div>
<!-- end .off-canvas-wrapper -->

<?php echo do_shortcode('[elfsight_cookie_consent id="1"]'); ?>

<?php wp_footer(); ?>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

<script>
    AOS.init();

</script>

</body>

</html>
<!-- end page -->
