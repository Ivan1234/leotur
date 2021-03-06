<?php
/**
 *
 * @package WordPress
 * @subpackage Leotur
 * @since 1.0
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function leotur_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/leotur
	 * If you're building a theme based on wenty Seventeen, use a find and replace
	 * to change 'leotur' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'leotur' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'leotur-featured-image', 2000, 1200, true );

	add_image_size( 'leotur-thumbnail-avatar', 100, 100, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'leotur' ),
		'social' => __( 'Social Links Menu', 'leotur' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', leotur_fonts_url() ) );

	add_theme_support( 'starter-content', array(
		'widgets' => array(
			'sidebar-1' => array(
				'text_business_info',
				'search',
				'text_about',
			),

			'sidebar-2' => array(
				'text_business_info',
			),

			'sidebar-3' => array(
				'text_about',
				'search',
			),
		),

		'posts' => array(
			'home',
			'about' => array(
				'thumbnail' => '{{image-sandwich}}',
			),
			'contact' => array(
				'thumbnail' => '{{image-espresso}}',
			),
			'blog' => array(
				'thumbnail' => '{{image-coffee}}',
			),
			'homepage-section' => array(
				'thumbnail' => '{{image-espresso}}',
			),
		),

		'attachments' => array(
			'image-espresso' => array(
				'post_title' => _x( 'Espresso', 'Theme starter content', 'leotur' ),
				'file' => 'assets/images/espresso.jpg',
			),
			'image-sandwich' => array(
				'post_title' => _x( 'Sandwich', 'Theme starter content', 'leotur' ),
				'file' => 'assets/images/sandwich.jpg',
			),
			'image-coffee' => array(
				'post_title' => _x( 'Coffee', 'Theme starter content', 'leotur' ),
				'file' => 'assets/images/coffee.jpg',
			),
		),

		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		'theme_mods' => array(
			'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
			'panel_4' => '{{contact}}',
		),

		'nav_menus' => array(
			'top' => array(
				'name' => __( 'Top Menu', 'leotur' ),
				'items' => array(
					'page_home',
					'page_about',
					'page_blog',
					'page_contact',
				),
			),
			'social' => array(
				'name' => __( 'Social Links Menu', 'leotur' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	) );
}
add_action( 'after_setup_theme', 'leotur_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function leotur_content_width() {

	$content_width = 700;

	if ( leotur_is_frontpage() ) {
		$content_width = 1120;
	}

	/**
	 * Filter content width of the theme.
	 *

	 *
	 * @param $content_width integer
	 */
	$GLOBALS['content_width'] = apply_filters( 'leotur_content_width', $content_width );
}
add_action( 'after_setup_theme', 'leotur_content_width', 0 );

/**
 * Register custom fonts.
 */
function leotur_fonts_url() {
	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'leotur' );

	if ( 'off' !== $libre_franklin ) {
		$font_families = array();

		$font_families[] = 'Libre Franklin:300,300i,400,400i,600,600i,800,800i';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function leotur_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'leotur-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'leotur_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function leotur_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'leotur' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'leotur' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'leotur' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Add widgets here to appear in your footer.', 'leotur' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'leotur' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Add widgets here to appear in your footer.', 'leotur' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'leotur_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function leotur_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'leotur' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'leotur_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 */
function leotur_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'leotur_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function leotur_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'leotur_pingback_header' );

/**
 * Display custom color CSS.
 */
function leotur_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once( get_parent_theme_file_path( '/inc/color-patterns.php' ) );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );
?>
	<style type="text/css" id="custom-theme-colors" <?php if ( is_customize_preview() ) { echo 'data-hue="' . $hue . '"'; } ?>>
		<?php echo leotur_custom_colors_css(); ?>
	</style>
<?php }
add_action( 'wp_head', 'leotur_colors_css_wrap' );

/**
 * Enqueue scripts and styles.
 */
function leotur_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'leotur-fonts', leotur_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'leotur-style', get_stylesheet_uri() );

	// Load the dark colorscheme.
	if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'leotur-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'leotur-style' ), '1.0' );
	}

	// Load the Internet Explorer 9 specific stylesheet, to fix display issues in the Customizer.
	if ( is_customize_preview() ) {
		wp_enqueue_style( 'leotur-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'leotur-style' ), '1.0' );
		wp_style_add_data( 'leotur-ie9', 'conditional', 'IE 9' );
	}

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'leotur-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'leotur-style' ), '1.0' );
	wp_style_add_data( 'leotur-ie8', 'conditional', 'lt IE 9' );


	// Load the bootstrap specific stylesheet.
	wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array( 'leotur-style' ), '1.0' );
	wp_enqueue_style( 'jquery-ui-style', get_theme_file_uri( '/assets/css/jquery-ui.css' ), array( 'leotur-style' ), '1.0' );


	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'leotur-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), '1.0', true );

	$leotur_l10n = array(
		'quote'          => leotur_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script( 'leotur-navigation', get_theme_file_uri( '/assets/js/navigation.js' ), array(), '1.0', true );
		$leotur_l10n['expand']         = __( 'Expand child menu', 'leotur' );
		$leotur_l10n['collapse']       = __( 'Collapse child menu', 'leotur' );
		$leotur_l10n['icon']           = leotur_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) );
	}
	wp_enqueue_script( 'leotur-jquery-ui', get_theme_file_uri( '/assets/js/plugins.js' ), array( 'jquery' ), '1.0', true );

	wp_enqueue_script( 'leotur-global', get_theme_file_uri( '/assets/js/global.js' ), array( 'jquery' ), '1.0', true );

	wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ), array( 'jquery' ), '2.1.2', true );

	wp_localize_script( 'leotur-skip-link-focus-fix', 'twentyseventeenScreenReaderText', $leotur_l10n );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'leotur_scripts' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function leotur_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			 $sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'leotur_content_image_sizes_attr', 10, 2 );

/**
 * Filter the `sizes` value in the header image markup.
 *
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function leotur_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'leotur_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function leotur_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'leotur_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function leotur_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template',  'leotur_front_page_template' );

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );


/**
 * Create new admin page.
 */
require get_parent_theme_file_path( '/inc/cities.php' );


function change_form_fields () {
	if (isset($_POST['country_id'])) {
		$country_id = $_POST['country_id'];
		// Resort fields
		$resort_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$country_id,
			'post_type'   => 'resort',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);

		$resort_posts = get_posts( $resort_args );
		$resort_option = "<option value=''>Виберіть курорт</option>";
		foreach($resort_posts as $r_post){ 
			/*echo "<pre>";
			print_r($r_post);
			echo "</pre>";*/
		    $resort_option .= "<option value='".$r_post->ID."'>".$r_post->post_title."</option>";
		}


		// City fields
		$city_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$country_id,
			'post_type'   => 'city',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);
		$city_posts = get_posts( $city_args );
		$city_html = '';
		$avia_dates_arr = [];
		foreach($city_posts as $c_post){
			$city_html .= "<div class='checkbox'><label data-id='".$c_post->ID."' data-resort-id='". get_post_meta($c_post->ID,"resort")[0]["ID"]."'>";
			$city_html .= "<input type='checkbox' class='town_to' data-id='".$c_post->ID."' name='city[]' value='".$c_post->ID."'>".$c_post->post_title;
			$city_html .= "</label></div>";
			$avia = get_row('avia',$c_post);


			if( have_rows('avia',$c_post) ){

			    while( have_rows('avia',$c_post) ) { the_row();
					$avia_dates_arr[] = get_sub_field('departure');
			    }

			}

		}
		$avia_dates_arr = array_unique($avia_dates_arr);

		// Hotel fields
		$hotel_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$country_id,
			'post_type'   => 'hotel',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);

		$hotel_posts = get_posts( $hotel_args );
		$hotel_html = '';
		$hotel_cat_arr = [];
		foreach($hotel_posts as $h_post){
			$resortID = get_post_meta($h_post->ID,'resort')[0]['ID'];
			$countryID = get_post_meta($h_post->ID,'country')[0]['ID'];
			$cityID = get_post_meta($h_post->ID,'city')[0]['ID'];
			$single_hotel_cat = get_field('hotel_cat',$h_post->ID);

			$hotel_cat_arr[] = $single_hotel_cat;
			if ($single_hotel_cat == 3 || $single_hotel_cat == 4 || $single_hotel_cat == 5) {
				$single_hotel_cat_text = $single_hotel_cat.'*';
			} else if ($single_hotel_cat == 'apart') {
				$single_hotel_cat_text = 'Апартаменти';
			} else {
				$single_hotel_cat_text = 'Інші';
			}
		    $hotel_html .= "<div class='checkbox'><label>";
			$hotel_html .= "<input type='checkbox' name='city[]' class='hotel_list' data-id='".$h_post->ID."' data-cat-id='".$single_hotel_cat."' data-resort-id='".$resortID."' data-country-id='".$countryID."' data-city-id='".$cityID."' value='".$h_post->ID."'>".$h_post->post_title ." ".$single_hotel_cat_text;
			$hotel_html .= "</label></div>";
		}

		// Cat fields
		$hotel_cat_arr = array_unique($hotel_cat_arr);
		$cat_html = '';
		foreach($hotel_cat_arr as $cat){
			if ($cat == 3 || $cat == 4 || $cat == 5) {
				$single_cat_text = $cat.'*';
			} else if ($cat == 'apart') {
				$single_cat_text = 'Апартаменти';
			} else {
				$single_cat_text = 'Інші';
			}
			$cat_html .= "<div class='checkbox'><label>";
			$cat_html .= "<input type='checkbox' class='hotel_stars' name='hotel_cat[]' value='".$cat."'>".$single_cat_text;
			$cat_html .= "</label></div>";
		}
	}
	wp_die(json_encode(array('resort_option' => $resort_option, 'city_html' => $city_html, 'hotel_html' => $hotel_html, 'cat_html' => $cat_html )));
}
add_action('wp_ajax_change_form_fields', 'change_form_fields');
add_action('wp_ajax_nopriv_change_form_fields', 'change_form_fields');

function search_hotels () {

	if (isset($_POST['country'])) {
		$country_id =  $_POST['country'];

		$rooms_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$country_id,
			'post_type'   => 'room',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);

		$rooms_posts = get_posts( $rooms_args );
		$rooms_option = '';
		foreach($rooms_posts as $r_post){ 
			$hotel_url =  get_post_meta($r_post->ID,'hotel')[0]['guid'];
		 ?>
			<article class="row">
				<div class="col-md-3">
					<a href="<?php echo $hotel_url; ?>"><?php echo  get_the_post_thumbnail($r_post); ?></a>
				</div>
				<div class="col-md-9">
					<h3><a href="<?php echo $hotel_url; ?>";"><?php echo $r_post->post_title  ?></a></h3>
				</div>
			</article>
			<?php
		    //$resort_option .= '<option value="'.$r_post->ID.'">'.$r_post->post_title.'</option>';
		}
	}
	wp_die();
}

add_action('wp_ajax_search_hotels', 'search_hotels');
add_action('wp_ajax_nopriv_search_hotels', 'search_hotels');