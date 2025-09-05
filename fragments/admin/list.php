<?php 
use To_Do\Database;
use To_Do\Fragment;
$database = new Database();
?>

<div class="wrap">
	<h2><span><?php _e('To-do List', 'to-do') ?></span></h2>

	<div class="todo-list">
		<div class="to-do-list--messages"> </div>

		<form class="to-do-list--form" data-action="add">
			<input type="hidden" name="id">
			<input type="text" name="title" placeholder="<?php _e('Title', 'to-do') ?>">
			<input type="text" name="description" placeholder="<?php _e('Description', 'to-do') ?>">
			<input type="text" name="category" placeholder="<?php _e('Category', 'to-do') ?>">

			<button class="button button-primary"><?php _e('Submit', 'to-do') ?></button>
		</form>

		<br>

		<table class="wp-list-table wp-list-table--todo-list widefat fixed striped posts">
			<thead>
				<?php Fragment::render('admin/form-labels'); ?>
			</thead>

			<tbody>
				<?php 
				if ( $all = $database->get_all() ) {
					foreach ( $all as $todo ) {
						Fragment::render( 'admin/list-item', $todo );
					}
				}
				?>
			</tbody>

			<tfoot>
				<?php Fragment::render('admin/form-labels'); ?>
			</tfoot>
		</table>
	</div>
</div>