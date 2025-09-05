<?php 
use To_Do\Database;
$database = new Database();
?>

<div class="wrap">
	<h2><span><?php _e('To-do List', 'to-do') ?></span></h2>

	<div class="to-do-list--messages">
		<div class="notice notice-success"><p>Success notice</p></div>

		<div class="notice notice-error"><p>Error notice alt</p></div>
	</div>

	<form class="to-do-list--form">
		<input type="hidden" name="id">
		<input type="text" name="title" placeholder="<?php _e('Title', 'to-do') ?>">
		<input type="text" name="description" placeholder="<?php _e('Description', 'to-do') ?>">
		<input type="text" name="category" placeholder="<?php _e('Category', 'to-do') ?>">

		<button class="button button-primary"><?php _e('Submit', 'to-do') ?></button>
	</form>

	<br>

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th> <strong> <?php _e('Title', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Description', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Category', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Creation date', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Last update date', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Actions', 'to-do') ?> </strong> </th>
			</tr>
		</thead>

		<tbody>
			<?php if ( $all = $database->get_all() ): ?>
				<?php foreach ( $all as $todo ): ?>
					<tr>
						<td> <strong> <?php echo $todo['title'] ?> </strong> </td>
						<td> <?php echo $todo['description'] ?> </td>
						<td> <?php echo $todo['category'] ?> </td>
						<td> <?php echo date_i18n( 'm/d/Y h:i:s', strtotime( $todo['created_at'] ) ) ?> </td>
						<td> <?php echo date_i18n( 'm/d/Y H:i:s', strtotime( $todo['updated_at'] ) ) ?> </td>
						<td> <a href="#">Edit</a> <a href="#">Delete</a> </td>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
		</tbody>

		<tfoot>
			<tr>
				<th> <strong> <?php _e('Title', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Description', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Category', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Creation date', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Last update date', 'to-do') ?> </strong> </th>
				<th> <strong> <?php _e('Actions', 'to-do') ?> </strong> </th>
			</tr>
		</tfoot>
	</table>
</div>