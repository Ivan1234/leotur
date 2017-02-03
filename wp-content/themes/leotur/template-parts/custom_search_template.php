<?php
/**
 * Template name: Пошук
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	
	<!-- <iframe src="http://tcc.com.ua/online/default.php?page=search_tour" width="100%" height="600"></iframe> -->
	<?php
		// Country fields
		$country_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => '',
			'meta_value'  =>'',
			'post_type'   => 'country',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);

		$country_posts = get_posts( $country_args );
		$country_option = '';
		$c_i = 1;

		foreach($country_posts as $c_post){ setup_postdata($post);
			if ($c_i == 1) {
				$first_country_id = $c_post->ID;
				$selected = 'selected';
			} else {
				$selected = '';
			}
		    /*echo "<pre>";
		    	print_r($c_post);
		    echo "</pre>";*/
		    $country_option .= '<option '.$selected.' value="'.$c_post->ID.'">'.$c_post->post_title.'</option>';
		    $c_i++;
		}


		// Resort fields
		$resort_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$first_country_id,
			'post_type'   => 'resort',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);

		$resort_posts = get_posts( $resort_args );
		$resort_option = '';
		$r_i = 1;
		foreach($resort_posts as $r_post){ setup_postdata($post);
			if ($r_i == 1) {
				$first_id = $r_post->ID;
			}
			/*echo "<pre>";
			print_r($r_post);
			echo "</pre>";*/
		    $resort_option .= '<option value="'.$r_post->ID.'">'.$r_post->post_title.'</option>';
		    $r_i++;
		}

		global $wpdb;
		$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'transport', OBJECT );
		$city_html = '';
		if (!empty($results)) {
			foreach ($results as $item) {
				$city_html .= '<div class="checkbox"><label data-id="'.$item->id.'">';
				$city_html .= '<input type="checkbox" name="city" value="'.$item->id.'">'.$item->name;
				$city_html .= '</label></div>';
			}
		}
	?>
	<form action="#" method="post">
		<div class="row">
			<div class="col-md-6 form-group">
				<label for="departure_city"><?php _e( 'Місто відправлення', 'leotur' ); ?></label>
				<select class="form-control" name="countrydeparture_city" id="departure_city">
					<option>Львів</option>
				</select>
			</div>
			<div class="col-md-6 form-group">
				<label for="city"><?php _e( 'Тип транспорту', 'leotur' ); ?></label>
				<select class="form-control" name="country" id="country">
					<option value="">---</option>
					<option value="1"><?php _e( 'Авіапереліт', 'leotur' ); ?></option>
					<option value=2><?php _e( 'Автобус', 'leotur' ); ?></option>
				</select>
			</div>
			<div class="col-md-6 form-group">
				<label for="city"><?php _e( 'Країна', 'leotur' ); ?></label>
				<select class="form-control" name="country" id="country">
					<?php echo $country_option  ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 form-group">
				<label for="city"><?php _e( 'Курорт', 'leotur' ); ?></label>
				<select class="form-control" name="resort" id="resort">
					<option value=""><?php _e( 'Виберіть курорт', 'leotur' ); ?></option>
					<?php echo $resort_option  ?>
				</select>
			</div>
			<div class="col-md-6 form-group">
				<label><?php _e( 'Місто', 'leotur' ); ?></label>
				<div>
					<?php echo $city_html; ?>
				</div>
			</div>
		</div>
	</form>
	<header class="page-header">
		<?php if ( have_posts() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'leotur' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		<?php else : ?>
			<h1 class="page-title"><?php _e( 'Nothing Found', 'leotur' ); ?></h1>
		<?php endif; ?>
	</header><!-- .page-header -->

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/post/content', 'excerpt' );

			endwhile; // End of the loop.

			the_posts_pagination( array(
				'prev_text' => leotur_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'leotur' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'leotur' ) . '</span>' . leotur_get_svg( array( 'icon' => 'arrow-right' ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'leotur' ) . ' </span>',
			) );

		else : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'leotur' ); ?></p>
			<?php
				get_search_form();

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
