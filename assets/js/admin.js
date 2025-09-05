;(function($, window, document, undefined) {
	$('.todo-list table').on('click', ' tr a.delete', function( e ) {
		e.preventDefault();

		let $button = $(this);
		let id = $button.closest('tr').attr('data-id');
		let data = {
			action: 'delete',
			url: toDo.restUrl + '/todos/' + id + '/',
			data: { id },
			type: 'DELETE'
		}

		run_ajax( data );
	});

	$('.todo-list table').on('click', ' tr a.edit', function( e ) {
		e.preventDefault();

		let $button = $(this);
		let $form = $button.closest('.todo-list').find('form.to-do-list--form');
		let $row = $button.closest('tr');

		let id = $row.attr('data-id');
		let title = $row.find('td:nth-child(1) strong').html();
		let description = $row.find('td:nth-child(2)').html();
		let category = $row.find('td:nth-child(3)').html();

		$form.find('input[name="id"]').val( id );
		$form.find('input[name="title"]').val( title.slice(1, -1) );
		$form.find('input[name="description"]').val( description.slice(1, -1) );
		$form.find('input[name="category"]').val( category.slice(1, -1) );

		$form.attr('data-action', 'edit');
		$form.find('a.button-cancel').show();
	});

	$('form.to-do-list--form a.button-cancel').on('click', function (e) {
		e.preventDefault();

		let $button = $(this);
		let $form = $button.closest('form.to-do-list--form');

		$button.hide();
		$form.attr('data-action', 'add');
		$form.find('input[name="id"]').val( '' );
		$form.find('input[name="title"]').val( '' );
		$form.find('input[name="description"]').val( '' );
		$form.find('input[name="category"]').val( '' );
	})

	$('form.to-do-list--form').on('submit', function( e ) {
		e.preventDefault();

		let $form = $(this);
		let data = {
			action: $form.attr('data-action'),
			data: $form.serialize(),
			type: 'POST'
		};

		if ( data.action === 'add' ) {
			data.url = toDo.restUrl + '/todos/';
		} else if ( data.action === 'edit' ) {
			let id = $form.find('input[name="id"]' ).val();
			data.url = toDo.restUrl + '/todos/' + id + '/';
		}

		run_ajax( data, $form );
	});

	function run_ajax( args, $form = '' ) {
		var $toDoList = $('.todo-list');
		var $messages = $toDoList.find('.to-do-list--messages');

		$messages.html('');
		$toDoList.addClass('is-ajax');

		args.beforeSend = function ( xhr ) {
	        xhr.setRequestHeader( 'X-WP-Nonce', toDo.restNonce );
	    };

		$.ajax( args )
			.done( function( response ) {
				if ( args.action === 'add' ) {
					$form[0].reset();

					let html = '<tr data-id="' + response.id + '"> <td> <strong> ' + response.title + ' </strong> </td> <td> ' + response.description + ' </td> <td> ' + response.category + ' </td> <td> ' + response.created_at + '</td> <td> ' + response.updated_at + '</td> <td> <a class="edit" href="#"><span class="dashicons dashicons-edit"></span></a> <a class="delete" href="#"><span class="dashicons dashicons-no"></span></a> </td> </tr>';

					$('table.wp-list-table--todo-list tbody').append( html );
				} else if ( args.action === 'delete' ) {
					$toDoList.find('table tr[data-id="' + args.data.id + '"]').remove();
				} else if ( args.action === 'edit' ) {
					let html = '<tr data-id="' + response.id + '"> <td> <strong> ' + response.title + ' </strong> </td> <td> ' + response.description + ' </td> <td> ' + response.category + ' </td> <td> ' + response.created_at + '</td> <td> ' + response.updated_at + '</td> <td> <a class="edit" href="#"><span class="dashicons dashicons-edit"></span></a> <a class="delete" href="#"><span class="dashicons dashicons-no"></span></a> </td> </tr>';

					$('table.wp-list-table--todo-list tbody tr[data-id="' + response.id + '"]').replaceWith( html );
				}
				
				$messages.append( '<div class="notice notice-success"><p>' + response.message + '</p></div>' );
			} )
			.fail( function( error ) {
				if ( error.responseJSON.message !== "undefined" ) {
					$messages.append( '<div class="notice notice-error"><p>' + error.responseJSON.message + '</p></div>' );
				}
			} )
			.always( function() {
				setTimeout(function() {
					$toDoList.removeClass('is-ajax');
				}, 50);
			} );
	}
})(jQuery, window, document);
