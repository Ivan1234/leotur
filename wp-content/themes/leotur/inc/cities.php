<?php
add_action('admin_menu', function(){
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page( 'Транспорт', 'Транспорт', 'edit_pages', 'transport', 'add_my_setting', 'dashicons-admin-post', 48 ); 
} );

// функция отвечает за вывод страницы настроек
// подробнее смотрите API Настроек: http://wp-kama.ru/id_3773/api-optsiy-nastroek.html
function add_my_setting(){
	global $wpdb;
	$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix . 'transport', OBJECT );
	$transport_items_html = '';
	if (!empty($results)) {
		foreach ($results as $item) {
			$transport_items_html .= '<tr data-id="'.$item->id.'">';
			$transport_items_html .= '<td><input type="text" name="name" value="'.$item->name.'"></td>';
			$transport_items_html .= '<td><button class="button button-primary update_row">Оновити</button><button class="delate_row">Видалити</button></td></tr>';
		}
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
				line-height: 30px;
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
				$('.transport_wrap tbody').append('<tr data-id="none"><td><input type="text" name="name"></td><td><button class="button button-primary save_row">Зберегти</button><button class="delate_row">Видалити</button></td>');
			});
			$('.transport_wrap ').on('click','.save_row', function () {
				var that = $( this );

				var data = {
					action: 'save_transport_row',
					name: $(this).closest('tr').find('input[name="name"]').val(),
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
			})
		})
	</script>
	<?php

}

function save_transport_row() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$name = $_POST['name'];

	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE name='$name'", OBJECT );
	if (!empty($results)) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $results );
		$resp = [
			'status'=> false,
			'message'=>'city exist'
		];
		wp_die(json_encode($resp));
	} else {
		$wpdb->insert( $table_name, array('name'=> trim($name)));
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $results );
		$resp = [
			'status'=> true,
			'message'=>'done',
			'id'=>$wpdb->insert_id
		];
		wp_die(json_encode($resp));
	}
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

function create_transport_table_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(250) NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
create_transport_table_db();