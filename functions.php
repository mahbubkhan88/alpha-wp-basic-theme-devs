<?php

if ( class_exists( 'Attachments' ) ) {
    require_once("lib/attachments.php");
}


if ( site_url() == "http://demo.lwhh.com" ) {
    define( "VERSION", time() );
} else {
    define( "VERSION", wp_get_theme()->get( "Version" ) );
}


function alpha_bootstrapping() {
    load_theme_textdomain( "alpha" );
    add_theme_support( "post-thumbnails" );
    add_theme_support( "title-tag" );
    add_theme_support( 'html5', array( 'search-form' ) );
    $alpha_custom_header_details = array(
        'header-text'            => true,
        'default-text-color'     => '#222',
        'width'                  => '1200',
        'height'                 => '400',
        'flex-height'            => true,
        'flex-width'             => true
    );
    add_theme_support( "custom-header", $alpha_custom_header_details );
    $alpha_custom_logo_defaults = array(
        'width'                 => '100',
        'height'                => '100',
    );
    add_theme_support( "custom-logo", $alpha_custom_logo_defaults );
    add_theme_support( "custom-background" );
    register_nav_menu( "topmenu", __( "Top Menu", "alpha" ) );
    register_nav_menu( "footermenu", __( "Footer Menu", "alpha" ) );

    add_theme_support("post-formats", array("image", "quote", "audio", "video", "link"));

    // add_image_size("alpha-square", 400, 400, true); //center center
    // add_image_size("alpha-portrait", 400, 9999);
    // add_image_size("alpha-landscape", 9999, 400);
    // add_image_size("alpha-landscape-hard-cropped", 600, 400);

    // add_image_size("alpha-square-new", 405, 405, array("left","top")); //left top
    // add_image_size("alpha-square-newnew", 410, 410, array("left","bottom")); //left bottom

    add_image_size("alpha-square", 400, 400, true); //center center
    add_image_size("alpha-square-new1", 405, 405, array("left","top")); //left top
    add_image_size("alpha-square-new1", 500, 500, array("center","center")); //center center
    add_image_size("alpha-square-new1", 600, 600, array("right","center")); //right center
}

add_action( "after_setup_theme", "alpha_bootstrapping" );

function alpha_assets() {
    wp_enqueue_style( "bootstrap", "//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" );
    wp_enqueue_style( "featherlight-css", "//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css" );
    wp_enqueue_style("dashicons");

    wp_enqueue_style( "tns-style","//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.7.1/tiny-slider.css" );

    wp_enqueue_style( "alpha", get_stylesheet_uri(), null, VERSION );

    wp_enqueue_style( "alpha-style", get_template_directory_uri()."/assets/css/alpha.css" );

    wp_enqueue_script( "tns-js", "//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.7.1/min/tiny-slider.js", null, "0.0.1", true );

    wp_enqueue_script( "featherlight-js", "//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js", array( "jquery" ), "0.0.1", true );

    wp_enqueue_script( "alpha-main", get_theme_file_uri( "/assets/js/main.js" ), array(
        "jquery",
        "featherlight-js"
    ), VERSION, true );
}

add_action( "wp_enqueue_scripts", "alpha_assets" );

function alpha_sidebar() {
    register_sidebar(
        array(
            'name'          => __( 'Single Post Sidebar', 'alpha' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Right Sidebar', 'alpha' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Footer Left', 'alpha' ),
            'id'            => 'footer-left',
            'description'   => __( "Widgetized Area On The Left Side", 'alpha' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '',
            'after_title'   => '',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Footer Right', 'alpha' ),
            'id'            => 'footer-right',
            'description'   => __( "Widgetized Area On The Right Side", 'alpha' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '',
            'after_title'   => '',
        )
    );
}

add_action( "widgets_init", "alpha_sidebar" );

function alpha_the_excerpt( $excerpt ) {
    if ( ! post_password_required() ) {
        return $excerpt;
    } else {
        echo get_the_password_form();
    }
}

add_filter( "the_excerpt", "alpha_the_excerpt" );


function alpha_protected_title_change() {
    return "%s";
}

add_filter( "protected_title_format", "alpha_protected_title_change" );


function alpha_menu_item_class( $classes, $item ) {
    $classes[] = "list-inline-item";

    return $classes;
}

add_filter( "nav_menu_css_class", "alpha_menu_item_class", 10, 2 );



if(!function_exists("alpha_about_page_template_banner")){
    function alpha_about_page_template_banner(){
        if(is_page()) {
            $alpha_feat_image = get_the_post_thumbnail_url(null,"large");
            ?>
            <style>
                .page-header{
                    background-image: url(<?php echo $alpha_feat_image;?>);
                }
            </style>
            <?php
        }


        if ( is_front_page() ) {
            if ( current_theme_supports( "custom-header" ) ) {
                ?>
                <style>
                    .header{
                        background-image: url(<?php echo header_image();?>);
                        background-size: cover;
                        margin-bottom: 50px;
                    }

                    .header h1.heading a, h3.tagline{
                        color: #<?php echo get_header_textcolor(); ?>;

                        <?php
                            if(!display_header_text()){
                                echo "display: none;";
                            }
                        ?>
                    }
                </style>
                <?php
            }
        }  
    }

    add_action("wp_head","alpha_about_page_template_banner",11);
}



function alpha_body_class($classes){
    unset($classes[array_search("second_class", $classes)]);
    unset($classes[array_search("third_class", $classes)]);
    $classes [] = "forth_class";
    return $classes;
}

add_filter("body_class", "alpha_body_class");



function alpha_post_class($classes){
    unset($classes[array_search("first_post_class", $classes)]);
    unset($classes[array_search("third_post_class", $classes)]);
    $classes [] = "forth_post_class";
    return $classes;
}

add_filter("post_class", "alpha_post_class");


//remove image srcset for this code
function alpha_image_srcset(){
    return null;
}

add_filter("wp_calculate_image_srcset", "alpha_image_srcset");
// add_filter("wp_calculate_image_srcset", "__return_null");

if(!function_exists("todays_date")){
    function todays_date(){
        echo date("d/m/Y");
    }
}


function alpha_highlight_search_results($text){
    if(is_search()){
        $pattern = '/('. join('|', explode(' ', get_search_query())).')/i';
        $text = preg_replace($pattern, '<span class="search-highlight">\0</span>', $text);
    }
    return $text;
}
add_filter('the_content', 'alpha_highlight_search_results');
add_filter('the_excerpt', 'alpha_highlight_search_results');
add_filter('the_title', 'alpha_highlight_search_results');


