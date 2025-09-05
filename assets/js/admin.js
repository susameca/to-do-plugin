;(function($, window, document, undefined) {
	$('form.to-do-list--form').on('submit', function( e ) {
		e.preventDefault();

		let $form = $(this);
		let data = {
			action: $form.attr('data-action'),
			data: $form.serialize(),
		};

		if ( data.action === 'add' ) {
			data.url = toDo.restUrl + '/todos/';
			data.type = 'POST';
		} else if ( data.action === 'edit' ) {
			data.url = toDo.restUrl + '/todos/' + data.data.id + '/';
			data.type = 'POST';
		} else if ( data.action === 'delete' ) {
			data.url = toDo.restUrl + '/todos/' + data.data.id + '/';
			data.type = 'DELETE';
		}
		
		run_ajax( data, $form );
	});

	function run_ajax( args, $form ) {
		var $messages = $('.to-do-list--messages');
		$messages.html('');
		$('.todo-list').addClass('is-ajax');

		$.ajax( args )
			.done( function( response ) {
				if ( args.action === 'add' ) {
					$form[0].reset();

					let html = '<tr> <td> <strong> ' + response.title + ' </strong> </td> <td> ' + response.description + ' </td> <td> ' + response.category + ' </td> <td> ' + response.created_at + '</td> <td> ' + response.updated_at + '</td> <td> <a class="edit" href="#"><span class="dashicons dashicons-edit"></span></a> <a class="delete" href="#"><span class="dashicons dashicons-no"></span></a> </td> </tr>';

					$('table.wp-list-table--todo-list tbody').append( html );

					$messages.append( '<div class="notice notice-success"><p>' + response.message + '</p></div>' );
				}

			} )
			.fail( function( error ) {
				$messages.append( '<div class="notice notice-error"><p>' + error.responseJSON.message + '</p></div>' );
			} )
			.always( function() {
				setTimeout(function() {
					$('.todo-list').removeClass('is-ajax');
				}, 50);
			} );
	}
})(jQuery, window, document);
