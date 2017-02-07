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


		// City fields
		$city_args = array(
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_key'    => 'country',
			'meta_value'  =>$first_country_id,
			'post_type'   => 'city',
			'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		);
		$city_posts = get_posts( $city_args );
		$city_html = '';
		foreach($city_posts as $c_post){
			$city_html .= '<div class="checkbox"><label data-id="'.$c_post->ID.'" data-resort-id="'. get_post_meta($c_post->ID,'resort')[0]['ID'].'">';
			$city_html .= '<input type="checkbox" name="city[]" value="'.$c_post->ID.'">'.$c_post->post_title;
			$city_html .= '</label></div>';
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
			<div class="col-md-6 form-group">
				<label for="city"><?php _e( 'Дата відправлення', 'leotur' ); ?></label>
				<input type="text" name="search_tour_date_from" id="search_tour_date_from" class="form-control">
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
<script type="text/javascript">
	jQuery(document).ready(function ($) {

		function updateQueryStringParameter(uri, key, value) {
			var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
			var separator = uri.indexOf('?') !== -1 ? "&" : "?";
			if (uri.match(re)) {
				return uri.replace(re, '$1' + key + "=" + value + '$2');
			}
			else {
				return uri + separator + key + "=" + value;
			}
		}

		function copyTextToClipboard(text) {
			var t = document.createElement("textarea");
			t.style.position = 'fixed';
			t.style.top = 0;
			t.style.left = 0;
			t.style.width = '2em';
			t.style.height = '2em';
			t.style.padding = 0;
			t.style.border = 'none';
			t.style.outline = 'none';
			t.style.boxShadow = 'none';
			t.style.background = 'transparent';
			t.value = text;
			document.body.appendChild(t);
			t.select();
			try {
				var successful = document.execCommand('copy');
				//var msg = successful ? 'successful' : 'unsuccessful';
				//console.log('Copying text command was ' + msg);
				alert("Ссылка скопирована в буфер обмена!");
			} catch (err) {
				//console.log('Oops, unable to copy');
				alert("Не удалось скопировать ссылку!");
			}
			document.body.removeChild(t);
		}

		$(document.body).on('click','.shortlink-share',function (e) {
			e.preventDefault();
			copyTextToClipboard( $(this).attr("href") );
			return false;
		});

		/**
		 *
		 *  Датапикер
		 */

		//if ($('#checkin_dates').val()) {
		var dates = ["08.02.2017","11.02.2017","15.02.2017","18.02.2017","22.02.2017","25.02.2017","01.03.2017","04.03.2017","08.03.2017","11.03.2017","15.03.2017","18.03.2017","22.03.2017","25.03.2017"];
			//$('#checkin_dates').val().split(',');
		//}
		//console.log( dates );

		$("#search_tour_date_from").datepicker({
			defaultDate   : $("#search_tour_date_from").val(),
			changeMonth   : false,
			numberOfMonths: 2,
			dateFormat    : "dd.mm.yy",
			altFormat     : "yy/mm/dd",
			altField      : '#hotel_checkin_date',
			beforeShow	  : function () {
				//console.log( dates );
			},	
			beforeShowDay: function (date) {
				if (typeof dates !== "undefined") {
					//console.log( date );
					fDate = $.datepicker.formatDate('dd.mm.yy', date);
					//console.log( fDate );
					if($.inArray(fDate, dates)>-1) {
						return [true, 'checkin-date-active'];
					}
					return [false];
				}
			},
			onClose       : function (selectedDate) {
				fromDate = $("#search_tour_date_from").val().split('.');
				secondDate = new Date(fromDate[2],fromDate[1]-1,fromDate[0]);
				secondDate.setDate(secondDate.getDate() + 4);
				secondDate = $.datepicker.formatDate('dd.mm.yy', secondDate);
				$("#search_tour_date_to").val(secondDate);
			},
			minDate       : 0
		});

		$("#search_tour_date_to").datepicker({
			defaultDate   : $("#search_tour_date_to").val(),
			changeMonth   : false,
			numberOfMonths: 2,
			dateFormat    : "dd.mm.yy",
			altFormat     : "yy/mm/dd",
			altField      : '#hotel_checkout_date',
			beforeShowDay: function (date) {
				if (typeof dates !== "undefined") {
					//console.log( date );
					fDate = $.datepicker.formatDate('dd.mm.yy', date);
					//console.log( fDate );
					if($.inArray(fDate, dates)>-1) {
						return [true, 'checkin-date-active'];
					}
					return [false];
				}
			},
			onClose       : function (selectedDate) {
				/*
				$("#search_tour_date_from").datepicker("option", "maxDate", selectedDate);
				if ($("#hotel_checkin_date").val() && $("#hotel_checkout_date").val()) {
					$("#summarydays").text(Math.round(((new Date($("#hotel_checkout_date").val()).getTime() - new Date($("#hotel_checkin_date").val()).getTime()) / 1000 / 60 / 60 / 24)+1));
					$("#summarynights").text(Math.round((new Date($("#hotel_checkout_date").val()).getTime() - new Date($("#hotel_checkin_date").val()).getTime()) / 1000 / 60 / 60 / 24) );
					$("#summarydays_declination").text(GetNoun((new Date($("#hotel_checkout_date").val()).getTime() - new Date($("#hotel_checkin_date").val()).getTime()) / 1000 / 60 / 60 / 24, "день", "дня", "дней"));
					$("#summarynights_declination").text(GetNoun(((new Date($("#hotel_checkout_date").val()).getTime() - new Date($("#hotel_checkin_date").val()).getTime()) / 1000 / 60 / 60 / 24) - 1, "ночь", "ночи", "ночей"));
					jQuery('.button_submit').val('Показать цены');
					$("#hotelpriceshow").css("background","#ff6500");
					$("#hotelpriceshow").attr("data-id","0");
					$("#hotelnights").text("");
				} else {
					$("#summarydays").text("");
					$("#summarydays_declination").text("-");
					$("#summarynights").text("");
					$("#summarynights_declination").text("-");
				}
				*/
			},
			minDate       : 0
		});

		/**
		 *
		 * Прячем датапикер при ресайзе окна
		 *
		 */
		$(window).bind('resize', function () {
			if ($("#ui-datepicker-div").length>0) {
				$("#search_tour_date_from,#search_tour_date_to").datepicker("hide");
			}
		});
	});

</script>
<?php get_footer();
