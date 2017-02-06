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

		foreach($country_posts as $c_post){
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
		foreach($resort_posts as $r_post){ 
			/*echo "<pre>";
			print_r($r_post);
			echo "</pre>";*/
		    $resort_option .= '<option value="'.$r_post->ID.'">'.$r_post->post_title.'</option>';
		}

		global $wpdb;
		$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'transport WHERE country="'.$first_country_id.'"', OBJECT );
		$city_html = '';
		if (!empty($results)) {
			foreach ($results as $item) {
				$city_html .= '<div class="checkbox"><label data-id="'.$item->id.'" data-resort-id="'.$item->resort.'">';
				$city_html .= '<input type="checkbox" name="city[]" value="'.$item->id.'">'.$item->name;
				$city_html .= '</label></div>';
			}
		}

		// Hotel fields
		$hotel_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$first_country_id,
			'post_type'   => 'hotel',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);

		$hotel_posts = get_posts( $hotel_args );
		$hotel_html = '';
		$hotel_cat_arr = [];
		foreach($hotel_posts as $h_post){
			$resortID = get_post_meta($h_post->ID,'resort')[0]['ID'];
			$countryID = get_post_meta($h_post->ID,'country')[0]['ID'];
			$single_hotel_cat = get_field('hotel_cat',$h_post->ID);

			$hotel_cat_arr[] = $single_hotel_cat;
			if ($single_hotel_cat == 3 || $single_hotel_cat == 4 || $single_hotel_cat == 5) {
				$single_hotel_cat_text = $single_hotel_cat.'*';
			} else if ($single_hotel_cat == 'apart') {
				$single_hotel_cat_text = 'Апартаменти';
			} else {
				$single_hotel_cat_text = 'Інші';
			}
		    $hotel_html .= '<div class="checkbox"><label data-id="'.$h_post->ID.'" data-cat-id="'.$single_hotel_cat.'" data-resort-id="'.$resortID.'" data-country-id="'.$countryID.'">';
			$hotel_html .= '<input type="checkbox" name="city[]" value="'.$h_post->ID.'">'.$h_post->post_title .' '.$single_hotel_cat_text;
			$hotel_html .= '</label></div>';
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
			$cat_html .= '<div class="checkbox"><label>';
			$cat_html .= '<input type="checkbox" name="hotel_cat[]" value="'.$cat.'">'.$single_cat_text;
			$cat_html .= '</label></div>';
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
			<div class="col-md-6 form-group">
				<label for="city"><?php _e( 'Курорт', 'leotur' ); ?></label>
				<select class="form-control" name="resort" id="resort">
					<option value=""><?php _e( 'Виберіть курорт', 'leotur' ); ?></option>
					<?php echo $resort_option  ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 form-group city-wrap">
				<label><?php _e( 'Місто', 'leotur' ); ?></label>
				<div>
					<?php echo $city_html; ?>
				</div>
			</div>
			<div class="col-md-3 form-group hotel-wrap">
				<label><?php _e( 'Категорії', 'leotur' ); ?></label>
				<div>
					<?php echo $cat_html; ?>
				</div>
			</div>
			<div class="col-md-3 form-group hotel-wrap">
				<label><?php _e( 'Готель', 'leotur' ); ?></label>
				<div>
					<?php echo $hotel_html; ?>
				</div>
			</div>
			<div class="col-md-3 form-group hotel-wrap">
				<label><?php _e( 'Харчування', 'leotur' ); ?></label>
				<div>
					<div class="checkbox">
						<label><input type="checkbox" name="food[]" value="other">Інші</label>
					</div>
					 <div class="checkbox">
						<label><input type="checkbox" name="food[]" value="no_food">Без харчування</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="food[]" value="breackfest">Сніданок</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="food[]" value="breackfest_and_dinner">Сніданок і вечеря</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="food[]" value="full">Повний пансіон</label>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name="food[]" value="all_inclusive">Все включено</label>
					</div>
				</div>
			</div>
		</div>
	</form>


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
	<?php //get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
