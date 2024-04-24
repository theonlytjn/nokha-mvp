<!doctype html>

<html class="no-js" <?php language_attributes(); ?>>

<head>
    <meta charset="utf-8">

    <!-- Force IE to use the latest rendering engine available -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta class="foundation-mq">

    <!-- If Site Icon isn't set in customizer -->
    <?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>
    <!-- Icons & Favicons -->
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
    <link href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-touch.png" rel="apple-touch-icon" />
    <!--[if IE]>
                <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
            <![endif]-->
    <meta name="msapplication-TileColor" content="#f01d4f">
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/assets/images/win8-tile-icon.png">
    <meta name="theme-color" content="#121212">
    <?php } ?>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <!-- Drop FontAwesome Script here -->
    <script src="https://kit.fontawesome.com/3932cc729e.js" crossorigin="anonymous"></script>
    <!-- end script -->

    <!-- Drop AOS here -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- end script -->

    <?php wp_head(); ?>

</head>

<!-- Uncomment this line if using the Off-Canvas Menu -->

<body <?php body_class(); ?>>

    <div id="top-bar" class="clearfix">

        <div class="row align-middle">

            <div class="large-6 medium-6 small-12 columns">

                <div class="hide-for-small-only">

                    <p>
                        <a target="_blank" href="mailto:<?php the_field( 'top_bar_email', 'option' ); ?>"><?php the_field( 'top_bar_email', 'option' ); ?></a> | <a target="_blank" href="tel:<?php the_field( 'top_bar_phone_number', 'option' ); ?>"><?php the_field( 'top_bar_phone_number', 'option' ); ?></a>
                    </p>

                </div>

                <div class="show-for-small-only">

                    <p class="text-center">
                        <a target="_blank" href="mailto:<?php the_field( 'top_bar_email', 'option' ); ?>"><?php the_field( 'top_bar_email', 'option' ); ?></a>
                    </p>
                    <p class="text-center">
                        <a target="_blank" href="tel:<?php the_field( 'top_bar_phone_number', 'option' ); ?>"><?php the_field( 'top_bar_phone_number', 'option' ); ?></a>
                    </p>

                </div>

            </div>

            <div class="large-6 medium-6 small-12 columns">
                <?php ubermenu( 'account_menu' , array( 'menu' => 53 ) ); ?>
            </div>

        </div>

    </div>

    <div id="header" class="clearfix" role="banner">

        <div class="row align-middle">

            <div class="large-3 medium-3 small-12 columns">

                <div class="main-logo hide-for-small-only">

                    <?php
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );

                                if ( has_custom_logo() ) {
                                    echo '<a href="'. get_site_url() . '"><img class="logo-large" src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '"></a>';
                                } else {
                                    echo '<h2>' . get_bloginfo('name') . '</h2>';
                                }

                            ?>

                </div>

                <div class="main-logo show-for-small-only">

                    <?php
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );

                                if ( has_custom_logo() ) {
                                    echo '<a href="'. get_site_url() . '"><img class="logo-small" src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '"></a>';
                                } else {
                                    echo '<h2>' . get_bloginfo('name') . '</h2>';
                                }

                            ?>

                </div>

            </div>

            <div class="large-9 medium-9 small-12 columns">

                <?php ubermenu( 'main' , array( 'theme_location' => 'main-nav' ) ); ?>

            </div>

        </div>

    </div>

    <!-- end .header -->
