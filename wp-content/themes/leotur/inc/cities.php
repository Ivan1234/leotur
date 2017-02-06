<?php
add_action('admin_menu', function(){
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page( 'Місто', 'Місто', 'edit_pages', 'city', 'add_my_setting', 'dashicons-admin-post', 48 ); 
} );

// функция отвечает за вывод страницы настроек
// подробнее смотрите API Настроек: http://wp-kama.ru/id_3773/api-optsiy-nastroek.html
function add_my_setting(){
	global $wpdb;
	// Resort fields
	$resort_args = array(
		'numberposts' => -1,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'post_type'   => 'resort',
		'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
	);

	$resort_posts = get_posts( $resort_args );
	$resort_option = '<option value="">Вибиріть курот</option>';
	$r_i = 1;


	// Country fields
	$country_args = array(
		'numberposts' => -1,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'post_type'   => 'country',
		'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
	);

	$country_posts = get_posts( $country_args );
	$country_option = '<option value="">Вибиріть крїна</option>';
	$c_i = 1;

	$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'transport ORDER BY name', OBJECT );
	$transport_items_html = '';
	if (!empty($results)) {
		foreach ($results as $item) {
			$transport_items_html .= '<tr data-id="'.$item->id.'">';
			$transport_items_html .= '<td><input type="text" name="name" value="'.$item->name.'"></td>';
			$transport_items_html .= '<td><select name="resort">';
			$transport_items_html .= '<option value="">Вибиріть курот</option>';
			foreach($resort_posts as $r_post){
				if ($r_post->ID == $item->resort) { $r_selected = 'selected'; }
				else { $r_selected = ''; }
			    $transport_items_html .= '<option '.$r_selected.' value="'.$r_post->ID.'">'.$r_post->post_title.'</option>';
			}

			$transport_items_html .= '</select></td>';
			$transport_items_html .= '<td><select name="country">';
			$transport_items_html .= '<option value="">Вибиріть крїна</option>';
			foreach($country_posts as $c_post){
				if ($c_post->ID == $item->country) { $c_selected = 'selected'; }
				else { $c_selected = ''; }
			    $transport_items_html .= '<option '.$c_selected.' value="'.$c_post->ID.'">'.$c_post->post_title.'</option>';
			}
			$transport_items_html .= '</select></td>';
			$transport_items_html .= '<td><button class="button button-primary update_row">Оновити</button><button class="delate_row">Видалити</button></td></tr>';
		}
	}


	foreach($resort_posts as $r_post){
	    $resort_option .= '<option value="'.$r_post->ID.'">'.$r_post->post_title.'</option>';
	}

	foreach($country_posts as $c_post){
	    $country_option .= '<option value="'.$c_post->ID.'">'.$c_post->post_title.'</option>';
	}

	?>
	<pre>
		<?php //print_r($results); ?>
	</pre>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<?php
		// settings_errors() не срабатывает автоматом на страницах отличных от опций
		if( get_current_screen()->parent_base !== 'options-general' )
			settings_errors('название_опции');
		?>
		<style type="text/css">
			.transport_wrap table {
				width: 100%;
				border-collapse: collapse;
			}
			.transport_wrap table button{
				display: inline-block;
				vertical-align: middle;
				line-height: 25px;
				margin-left: 4px;
			    position: relative;
			    text-decoration: none;
			    border: 1px solid #ccc;
			    -webkit-border-radius: 2px;
			    border-radius: 2px;
			    background: #f7f7f7;
			    text-shadow: none;
			    font-weight: 600;
			    font-size: 13px;
			    color: #0073aa;
			    cursor: pointer;
			    outline: 0;
			}
			.transport_wrap table input {
				width: 100%;
				line-height: 20px;
			}
			.transport_wrap table tr {
				border: 1px solid #ddd;
			}
			.transport_wrap tbody tr:nth-child(2n) {
				background: #fff;
			}
			.transport_wrap tbody tr:nth-child(2n+1) {
				background: #f9f9f9;
			}
			.transport_wrap table td {
				padding-right: 5px;
				padding-top: 5px;
				padding-bottom: 5px;
			}
			.message_wrap {
			    position: fixed;
			    top: 40px;
			    right: 20px;
			    border-radius: 10px;
			    overflow: hidden;
			    color: #fff;
			}
			.message_wrap div  {
				padding: 10px 15px;
			}
			.message_wrap .fail {
				background-color: red;
			}
			.message_wrap .success{
				background-color:#0095ff;
			}
		</style>
		<div class="transport_wrap">
			<!-- <div class="transport_heading">
				<ul>
					<li>Місто</li>
					<li>Авіа (дорослий)</li>
					<li>Авіа (дитячий)</li>
					<li>Автобус (дорослий)</li>
					<li>Автобус (дитячий)</li>
				</ul>
			</div> -->
			<table>
				<thead>
					<tr>
						<td>Місто</td>
						<td>Курорт</td>
						<td>Країна</td>
						<td><a href="#" class="btn"><div class="dashicons dashicons-plus add_transport_row"></div></a></td>
					</tr>
				</thead>
				<tbody>
					<?php echo $transport_items_html; ?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">

		jQuery(document).ready(function ($) {
			var popup;
			function showMessage(message,status) {
				clearTimeout(popup);

				if(!$('.message_wrap').length) {
					$('body').append('<div class="message_wrap" style="display:none;"></div>');
				}
				if (status) {
					$('.message_wrap').html('<div class="success">'+message+'</div>');
				} else {
					$('.message_wrap').html('<div class="fail">'+message+'</div>');
				}
				$('.message_wrap').fadeIn(300);
				popup = setTimeout(function () {
					$('.message_wrap').fadeOut();
				},3000);
			}
			$('.add_transport_row').click(function (e) {
				e.preventDefault();
				if (!$('.transport_wrap tbody tr[data-id="none"]').length) {
					$('.transport_wrap tbody').append('<tr data-id="none"><td><input type="text" name="name"></td><td><select name="resort"><?php echo $resort_option ?>"></select></td><td><select name="country"><?php echo $country_option ?>"></select></td><td><button class="button button-primary save_row">Зберегти</button><button class="delate_row">Видалити</button></td>');
				}
			});

			$('.transport_wrap ').on('click','.save_row', function () {
				var that = $( this );
				if (that.closest('tr').find('input[name="name"]').val().trim() == '' ) {
					showMessage('Поле місто не може бути пустим!',false);
				} else if (that.closest('tr').find('select[name="resort"]').val().trim() == '') {
					showMessage('Поле курорт не може бути пустим!',false);
				} else if (that.closest('tr').find('select[name="country"]').val().trim() == '') {
					showMessage('Поле країна не може бути пустим!',false);
				} else  {
					var data = {
						action: 'save_transport_row',
						name: $(this).closest('tr').find('input[name="name"]').val(),
						resort: $(this).closest('tr').find('select[name="resort"]').val(),
						country: $(this).closest('tr').find('select[name="country"]').val(),
					}
					$.ajax({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: data,
					  type: 'post',
					}).done(function(resp) {
						//Deal with all stuff
						var response = jQuery.parseJSON(resp);
						//console.log(response.status);
						if (response.status) {
							that.addClass( "update_row" );
							that.removeClass( "save_row" );
							that.html('Оновити');
							that.closest('tr').attr('data-id', response.id);
							showMessage('Місто успішно додано.',true);
						} else {
							showMessage('Місто з цим іменем вже існує.',false);
						}
					}).fail(function () {
						showMessage('Сталась помилка, спробуйте перегрузити сторінку і повторити, якщо не допомагає звяжіться з розробником!',false);
					});
				}
			});

			$('.transport_wrap ').on('click','.delate_row', function () {
				var that = $(this);
					var fadeTime = 300;
				if (that.closest('tr').attr('data-id') == 'none') {
					that.closest('tr').fadeOut(fadeTime);
					setTimeout(function () {
					  that.closest('tr').remove();
					},fadeTime);
				} else {
					var data = {
					  action: 'remove_transport_row',
					  id: that.closest('tr').attr('data-id'),
					}
					$.ajax({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: data,
					  type: 'post',
					}).done(function(resp) {
					  var response = jQuery.parseJSON(resp);
					  if (response.status) {
						that.closest('tr').fadeOut(fadeTime);
						setTimeout(function () {
						that.closest('tr').remove();
						},fadeTime);
						showMessage('Місто успішно видалено.',true);
					  } else {
					  	showMessage('Сталась помилка, спробуйте перегрузити сторінку і повторити, якщо не допомагає звяжіться з розробником!',false);
					  }
					}).fail(function () {
					  showMessage('Сталась помилка, спробуйте перегрузити сторінку і повторити, якщо не допомагає звяжіться з розробником!',false);
					});
				}
			});

			$('.transport_wrap ').on('click','.update_row', function () {
				var that = $(this);
				if (that.closest('tr').find('input[name="name"]').val().trim() == '' ) {
					showMessage('Поле місто не може бути пустим!',false);
				} else {
					var data = {
					  action: 'update_transport_row',
					  id: that.closest('tr').attr('data-id'),
					  name: that.closest('tr').find('input[name="name"]').val(),
					  resort: $(this).closest('tr').find('select[name="resort"]').val(),
					  country: $(this).closest('tr').find('select[name="country"]').val(),
					}
					$.ajax({
					  url: "<?php echo admin_url('admin-ajax.php'); ?>",
					  data: data,
					  type: 'post',
					}).done(function(resp) {
					  var response = jQuery.parseJSON(resp);
					  if (response.status) {
						showMessage('Місто успішно оновлено.',true);
					  } else {
					  	showMessage('Ви нічого не ввели!',false);
					  }
					}).fail(function () {
					  showMessage('Сталась помилка, спробуйте перегрузити сторінку і повторити, якщо не допомагає звяжіться з розробником!',false);
					});
				}
			});
		})
	</script>
	<?php
}

function save_transport_row() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$name = $_POST['name'];
	$name = trim($name);

	$resort = $_POST['resort'];
	$resort = trim($resort);

	$country = $_POST['country'];
	$country = trim($country);

	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE name='$name'", OBJECT );
	if (!empty($results)) {
		$resp = [
			'status'=> false,
			'message'=>'city exist'
		];
	} else {
		$wpdb->insert( $table_name, array('name'=> trim($name), 'resort'=> trim($resort), 'country'=> trim($country)));
		$resp = [
			'status'=> true,
			'message'=>'done',
			'id'=>$wpdb->insert_id
		];
	}
	wp_die(json_encode($resp));
}
add_action('wp_ajax_save_transport_row', 'save_transport_row');

function remove_transport_row() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$id = $_POST['id'];

	$results = $wpdb->delete( $table_name , array('id' => $id));

	if ($results) {
		$resp = [
			'status'=> true,
			'message'=>'successfuly deleted'
		];
	} else {
		$resp = [
			'status'=> false,
			'message'=>'no row was founded'
		];
	}
	wp_die(json_encode($resp));
}
add_action('wp_ajax_remove_transport_row', 'remove_transport_row');

function update_transport_row() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$id = $_POST['id'];
	$name = $_POST['name'];
	$resort = $_POST['resort'];
	$country = $_POST['country'];

	$results = $wpdb->update( $table_name ,array('name'=>$name, 'resort'=>$resort, 'country'=>$country), array('id' => $id));
	//echo $result == 0;
	if ($results === false) {
		$resp = [
			'status'=> false,
			'message'=>'error'
		];
	} else {
		if ($results) {
			$resp = [
				'status'=> true,
				'message'=>'ended successfuly'
			];
		} else {
			$resp = [
				'status'=> false,
				'message'=>'no row was founded'
			];
		}
	}
	wp_die(json_encode($resp));
}
add_action('wp_ajax_update_transport_row', 'update_transport_row');

function create_transport_table_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(250) NULL,
		resort bigint(11) NULL,
		country bigint(11) NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
create_transport_table_db();


/* Добавляем блоки в основную колонку на страницах постов и пост. страниц */
function city_box() {
	$screens = array( 'room' );
	foreach ( $screens as $screen )
		add_meta_box( 'city_section', 'Місто', 'city_box_callback', $screen );
}
add_action('add_meta_boxes', 'city_box');

/* HTML код блока */
function city_box_callback() {
	global $wpdb, $post;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$results = $wpdb->get_results( "SELECT * FROM $table_name", OBJECT );
	$option_html = '';
	$city = get_post_meta( $post->ID, 'city', true);
	foreach ($results as $value) {
		if ($city ==$value->id) {
			$selected = 'selected';
		} else {
			$selected = '';
		}
		$option_html .= '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>';
	}
	// Используем nonce для верификации
	wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );

	// Поля формы для введения данных 
	?>
	<table class="form-table">
		<tbody>
			<tr class="form-field pods-field pods-field-input pods-form-ui-row-type-pick pods-form-ui-row-name-country ">
		        <th scope="row" valign="top"><label>Місто <!-- <abbr title="required" class="required">*</abbr> --></label></th>
		        <td>
		            <div class="pods-submittable-fields">
		                <select id="city" name="city">
		                    <option value="">-- Select One --</option>
		                    <?php echo $option_html; ?>
		                </select>
		            </div>
		        </td>
		    </tr>
		</tbody>
	</table>
    <?php
}

/* Сохраняем данные, когда пост сохраняется */
function save_city_postdata( $post_id ) {
/*	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	if ( ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
		return $post_id;*/

	// проверяем, если это автосохранение ничего не делаем с данными нашей формы.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// проверяем разрешено ли пользователю указывать эти данные
	if (! current_user_can( 'edit_page', $post_id ) ) {
		  return $post_id;
	} elseif( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Убедимся что поле установлено.
	if ( ! isset( $_POST['city'] ) )
		return;

	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля input.
	if (isset($_POST['city'])) {
		$my_data = sanitize_text_field( $_POST['city'] );

		// Обновляем данные в базе данных.
		update_post_meta( $post_id, 'city', $my_data );
	}
}
add_action( 'save_post', 'save_city_postdata' );