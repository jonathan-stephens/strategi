<?php
// BlankSlate Core Functions
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-logo', array(
    'height'      => 100,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
) );
add_theme_support( 'html5', array( 'search-form', 'comment-list', 'comment-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'align-wide' );
add_theme_support( 'wp-block-styles' );
add_theme_support( 'editor-styles' );
add_editor_style( 'editor-style.css' );
add_theme_support( 'appearance-tools' );
add_theme_support( 'woocommerce' );
global $content_width;
if ( !isset( $content_width ) ) {
$content_width = 1920;
}
// Updated: Changed 'main-menu' to 'primary' to match custom walker
register_nav_menus( array(
    'primary' => esc_html__( 'Primary Menu', 'blankslate' )
) );
}
add_action( 'admin_notices', 'blankslate_notice' );
function blankslate_notice() {
$user_id = get_current_user_id();
if ( !$user_id || !current_user_can( 'manage_options' ) || get_user_meta( $user_id, 'blankslate_notice_dismissed_2026', true ) ) {
return;
}
$dismiss_url = add_query_arg( array( 'blankslate_dismiss' => '1', 'blankslate_nonce' => wp_create_nonce( 'blankslate_dismiss_notice' ) ), admin_url() );
echo '<div class="notice notice-info"><p><a href="' . esc_url( $dismiss_url ) . '" class="alignright" style="text-decoration:none"><big>' . esc_html__( '×', 'blankslate' ) . '</big></a><big><strong>' . esc_html__( '📝 Thank you for using BlankSlate!', 'blankslate' ) . '</strong></big><p>' . esc_html__( 'Powering over 10k websites! Buy me a sandwich! 🥪', 'blankslate' ) . '</p><a href="https://github.com/webguyio/blankslate/issues/57" class="button-primary" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'How do you use BlankSlate?', 'blankslate' ) . '</strong></a> <a href="https://opencollective.com/blankslate" class="button-primary" style="background-color:green;border-color:green" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Donate', 'blankslate' ) . '</strong></a> <a href="https://wordpress.org/support/theme/blankslate/reviews/#new-post" class="button-primary" style="background-color:purple;border-color:purple" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Review', 'blankslate' ) . '</strong></a> <a href="https://github.com/webguyio/blankslate/issues" class="button-primary" style="background-color:orange;border-color:orange" target="_blank" rel="noopener noreferrer"><strong>' . esc_html__( 'Support', 'blankslate' ) . '</strong></a></p></div>';
}
add_action( 'admin_init', 'blankslate_notice_dismissed' );
function blankslate_notice_dismissed() {
$user_id = get_current_user_id();
if ( isset( $_GET['blankslate_dismiss'], $_GET['blankslate_nonce'] ) && wp_verify_nonce( $_GET['blankslate_nonce'], 'blankslate_dismiss_notice' ) && current_user_can( 'manage_options' ) ) {
add_user_meta( $user_id, 'blankslate_notice_dismissed_2026', 'true', true );
}
}
add_action( 'wp_enqueue_scripts', 'blankslate_enqueue' );
function blankslate_enqueue() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'blankslate_footer' );
function blankslate_footer() {
?>
<script>
(function() {
const ua = navigator.userAgent.toLowerCase();
const html = document.documentElement;
if (/(iphone|ipod|ipad)/.test(ua)) {
html.classList.add('ios', 'mobile');
}
else if (/android/.test(ua)) {
html.classList.add('android', 'mobile');
}
else {
html.classList.add('desktop');
}
if (/chrome/.test(ua) && !/edg|brave/.test(ua)) {
html.classList.add('chrome');
}
else if (/safari/.test(ua) && !/chrome/.test(ua)) {
html.classList.add('safari');
}
else if (/edg/.test(ua)) {
html.classList.add('edge');
}
else if (/firefox/.test(ua)) {
html.classList.add('firefox');
}
else if (/brave/.test(ua)) {
html.classList.add('brave');
}
else if (/opr|opera/.test(ua)) {
html.classList.add('opera');
}
})();
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = esc_html( '|' );
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return esc_html( '...' );
} else {
return wp_kses_post( $title );
}
}
function blankslate_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'blankslate_schema_url', 10 );
function blankslate_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'blankslate_wp_body_open' ) ) {
function blankslate_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'blankslate_skip_link', 5 );
function blankslate_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'blankslate' ) . '</a>';
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'blankslate' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

/* ============================================================================
   CUSTOM HEADER FUNCTIONALITY
   ============================================================================ */

// Removed: Duplicate custom_theme_setup() - merged into blankslate_setup() above

/**
 * Custom Walker for Mega Menu Navigation
 */
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '<div class="mega-menu"><ul class="mega-menu__list">';
        } else {
            $output .= '<ul class="mega-menu__sublist">';
        }
    }

    function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</ul></div>';
        } else {
            $output .= '</ul>';
        }
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        // Check if this is a contact page
        $is_contact = in_array( 'contact', $classes ) || stripos( $item->title, 'contact' ) !== false;

        if ( $is_contact && $depth === 0 ) {
            $classes[] = 'nav-cta';
        }

        if ( in_array( 'menu-item-has-children', $classes ) && $depth === 0 ) {
            $classes[] = 'has-dropdown';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= '<li' . $class_names . '>';

        $atts = array();
        $atts['href'] = ! empty( $item->url ) ? $item->url : '';
        $atts['aria-label'] = ! empty( $item->attr_title ) ? $item->attr_title : $item->title;

        if ( in_array( 'menu-item-has-children', $classes ) && $depth === 0 ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;

        if ( in_array( 'menu-item-has-children', $classes ) && $depth === 0 ) {
            $item_output .= '<span class="dropdown-icon" aria-hidden="true"></span>';
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= $item_output;
    }
}

/**
 * Enqueue Custom Header Styles and Scripts (CUBE CSS)
 * Updated: Changed priority to 20 to load after BlankSlate styles
 */
add_action( 'wp_enqueue_scripts', 'custom_theme_scripts', 20 );
function custom_theme_scripts() {
    // Composition layer (design tokens)
    wp_enqueue_style(
        'custom-tokens',
        get_template_directory_uri() . '/css/tokens.css',
        array(),
        filemtime( get_template_directory() . '/css/tokens.css' )
    );

    // Utility layer (depends on tokens)
    wp_enqueue_style(
        'custom-utilities',
        get_template_directory_uri() . '/css/utilities.css',
        array( 'custom-tokens' ),
        filemtime( get_template_directory() . '/css/utilities.css' )
    );

    // Block layer (depends on utilities)
    wp_enqueue_style(
        'custom-header-block',
        get_template_directory_uri() . '/css/blocks/header.css',
        array( 'custom-utilities' ),
        filemtime( get_template_directory() . '/css/blocks/header.css' )
    );

    // Exception layer (loads last)
    wp_enqueue_style(
        'custom-exceptions',
        get_template_directory_uri() . '/css/exceptions.css',
        array( 'custom-header-block' ),
        filemtime( get_template_directory() . '/css/exceptions.css' )
    );

    // JavaScript (deferred, in footer)
    wp_enqueue_script(
        'custom-header-script',
        get_template_directory_uri() . '/js/header.js',
        array(),
        filemtime( get_template_directory() . '/js/header.js' ),
        array( 'in_footer' => true )
    );
}

/**
 * Add helpful text to menu editor CSS classes field
 */
add_action( 'admin_footer-nav-menus.php', 'custom_add_menu_description' );
function custom_add_menu_description() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        if ($('.field-css-classes').length) {
            $('.field-css-classes').each(function() {
                if (!$(this).find('.custom-help-text').length) {
                    $(this).append('<p class="description custom-help-text" style="margin-top: 5px; font-style: italic;">Add "contact" to style this item as a button</p>');
                }
            });
        }
    });
    </script>
    <?php
}

/**
 * Auto-enable CSS Classes field in menu editor
 */
add_action( 'admin_init', 'custom_enable_menu_classes_init' );
function custom_enable_menu_classes_init() {
    $user_id = get_current_user_id();
    if ( $user_id ) {
        custom_enable_menu_classes( $user_id );
    }
}

function custom_enable_menu_classes( $user_id ) {
    $meta_key = 'managenav-menuscolumnshidden';
    $hidden = get_user_meta( $user_id, $meta_key, true );

    if ( ! $hidden ) {
        $hidden = array();
    } elseif ( ! is_array( $hidden ) ) {
        $hidden = (array) $hidden;
    }

    // Remove 'css-classes' from hidden columns
    $hidden = array_diff( $hidden, array( 'css-classes' ) );

    update_user_meta( $user_id, $meta_key, $hidden );
}

/**
 * Add Customizer options for header
 */
add_action( 'customize_register', 'custom_header_customizer' );
function custom_header_customizer( $wp_customize ) {
    // Add section for header settings
    $wp_customize->add_section( 'custom_header_section', array(
        'title'    => __( 'Header Settings', 'blankslate' ),
        'priority' => 30,
    ) );

    // Logo height setting
    $wp_customize->add_setting( 'header_logo_height', array(
        'default'           => '50',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'header_logo_height', array(
        'label'       => __( 'Logo Height (px)', 'blankslate' ),
        'section'     => 'custom_header_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 150,
            'step' => 5,
        ),
    ) );

    // Custom CSS setting
    $wp_customize->add_setting( 'header_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ) );

    $wp_customize->add_control( 'header_custom_css', array(
        'label'       => __( 'Additional Header CSS', 'blankslate' ),
        'description' => __( 'Add custom CSS for the header (advanced users only)', 'blankslate' ),
        'section'     => 'custom_header_section',
        'type'        => 'textarea',
    ) );
}

/**
 * Output customizer settings as inline CSS
 * Updated: Changed priority to 21 to run after custom_theme_scripts
 */
add_action( 'wp_enqueue_scripts', 'custom_header_customizer_css', 21 );
function custom_header_customizer_css() {
    $logo_height = get_theme_mod( 'header_logo_height', '50' );
    $custom_css = get_theme_mod( 'header_custom_css', '' );

    $css = "
        :root {
            --size-logo-height: {$logo_height}px;
        }
        {$custom_css}
    ";

    wp_add_inline_style( 'custom-tokens', $css );
}
