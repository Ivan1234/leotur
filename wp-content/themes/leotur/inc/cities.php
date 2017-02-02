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
			$transport_items_html .= '<td><input type="text" name="avia_adult" value="'.$item->avia_adult.'"></td>';
			$transport_items_html .= '<td><input type="text" name="avia_child" value="'.$item->avia_child.'"></td>';
			$transport_items_html .= '<td><input type="text" name="bus_adult" value="'.$item->bus_adult.'"></td>';
			$transport_items_html .= '<td><input type="text" name="bus_child" value="'.$item->bus_child.'"></td>';
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
						<td>Авіа (дорослий)</td>
						<td>Авіа (дитячий)</td>
						<td>Автобус (дорослий)</td>
						<td>Автобус (дитячий)</td>
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
		console.log(jQuery);
		jQuery(document).ready(function ($) {
			$('.add_transport_row').click(function (e) {
				e.preventDefault();
				$('.transport_wrap tbody').append('<tr><td><input type="text" name="name"></td><td><input type="text" name="avia_adult"></td><td><input type="text" name="avia_child"></td><td><input type="text" name="bus_adult"></td></td><td><input type="text" name="bus_child"></td><td><button class="button button-primary save_row">Зберегти</button><button class="delate_row">Видалити</button></td>')
			});
			$('.transport_wrap ').on('click','.save_row', function () {
				$.ajax({
				  url: "test.html",
				  context: document.body
				}).done(function() {
				  $( this ).addClass( "done" );
				});
			});
			$('.transport_wrap ').on('click','.delate_row', function () {
				var that = $(this);
				var fadeTime = 300;
				that.closest('tr').fadeOut(fadeTime);
				setTimeout(function () {
					that.closest('tr').remove();
				},fadeTime);
			})
		})
	</script>
	<?php

}

function save_transport_row() {

}
add_action('wp_ajax_save_transport_row', 'save_transport_row');

function create_transport_table_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'transport';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(250) NULL,
		avia_adult bigint(11)  NULL,
		avia_child bigint(11) NOT NULL,
		bus_adult bigint(11) NOT NULL,
		bus_child bigint(11) NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
create_transport_table_db();