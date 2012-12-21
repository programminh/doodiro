<?php
$users = User::find_all();
?>
<div class="container">
	<div class="row">
		<div class="span12">
			<a id="add-users" class="btn btn-success pull-right"><i class="icon-plus"></i></a>
			<a id="hide-add-users-form" class="btn pull-right hide"><i class="icon-minus"></i></a>
		</div>
		<div class="span12">
			<form onsubmit="return false" action="javascript:void(0)" class="form-horizontal pull-right" style="display: none" id="add-user-form">
				<legend style="text-align: right">Nouvel usager</legend>

				<div class="control-group">
					<label for="email" class="control-label">E-mail</label>
					<div class="controls">
						<input type="text" class="span2" id="email">
					</div>
				</div><!-- End email -->

				<div class="control-group">
					<label for="firstname" class="control-label">Prénom</label>
					<div class="controls">
						<input type="text" class="span2" id="firstname">
					</div>
				</div><!-- End firstname -->

				<div class="control-group">
					<label for="lastname" class="control-label">Nom de famille</label>
					<div class="controls">
						<input type="text" class="span2" id="lastname">
					</div>
				</div><!-- End lastname -->

				<div class="control-group">
					<label for="password" class="control-label">Mot de passe</label>
					<div class="controls">
						<input type="password" class="span2" id="password">
					</div>
				</div><!-- End password -->

				<div class="control-group">
					<label for="password2" class="control-label">Confirmation</label>
					<div class="controls">
						<input type="password" class="span2" id="password2">
					</div>
				</div><!-- End password2 -->

				<div class="form-actions">
				  	<button type="submit" class="btn btn-primary" id="save-user">Ajouter</button>
				  	<img style="margin-left: 10px" class="hide" src="img/ajax-loader.gif" id="loader" alt="Loader">
				</div>
			</form>
		</div>
		<div class="span12">
			<table id="users-table" class="table table-hovered">
				<thead>
					<tr>
						<th>Nom</th>
						<th>E-mail</th>
						<th style="text-align: right">Nombre d'événements organisés</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user): ?>
						<tr data-id="<?php echo $user->id ?>" class="user">
							<td class="name"><?php echo $user->firstname ?> <?php echo $user->lastname ?></td>
							<td class="email"><?php echo $user->email ?></td>
							<td class="events_count" style="text-align: right"><?php echo $user->count_events() ?></td>
							<td style="text-align: right"><a class="btn btn-danger btn-mini delete-user-button">&times;</a></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('.delete-user-button').on('click', function() {
			var user_name = $(this).parent().siblings('.name');
			var user_row = $(this).parent().parent();

			if(confirm('Voulez-vous vraiment effacé l\'usager: ' + user_name.text())) {
				delete_user(user_row);
			}
		});

		$('#add-users').on('click', function() {
			$('#add-user-form').slideDown('slow', function() {
				$('#add-users').addClass('hide');
				$('#hide-add-users-form').removeClass('hide');
			});
		});


		$('#hide-add-users-form').on('click', function() {
			$('#add-user-form').slideUp('slow', function() {
				$('#hide-add-users-form').addClass('hide');
				$('#add-users').removeClass('hide');
			});
		});

		function delete_event(user_row) {
			var data = { user_id: user_row.data('id') };

			$.ajax({
				url: "admin/delete_user.php",
				type: "POST",
				data: data,
				success: function(msg) {
					if(msg) {
						var message = '<div class="alert alert-warning"> \
								<button type="button" class="close" data-dismiss="alert">&times;</button> \
								L\'usager' + user_row.children('.name').text() + ' a été supprimé!</div>';

						$('#users-table').parent()
							.prepend(message);

						event_row.slideUp('slow', function() {
							$(this).remove();
						})
					}
				}
			});
		}

		$('#add-user-form').submit(function() {
			var email = $('#email').val();
			var firstname = $('#firstname').val();
			var lastname = $('#lastname').val();
			var password = $('#password').val();
			var password2 = $('#password2').val();

			if (! email || ! firstname || ! lastname || ! password || ! password2) 
				return alert('Tous les champs doivent être remplies');

			if (password != password2) 
				return alert('La confirmation ne correspond pas au mot de passe');

			var data = {
				email: email,
				firstname: firstname,
				lastname: lastname,
				password: password
			};

			$('#save-user').attr('disabled', true);
			$('#loader').removeClass('hide');

			$.ajax({
				url: "admin/insert_user.php",
				data: data,
				type: "POST",
				success: function(user_id) {
					$('#add-user-form :input').val('');
					$('#save-user').attr('disabled', false);
					$('#loader').addClass('hide');

					var message = '<div class="alert alert-success"> \
								<button type="button" class="close" data-dismiss="alert">&times;</button> \
								L\'usager' + data.firstname + ' ' + data.lastname + ' a été ajouté!</div>';

					var newRow = '<tr data-id="'+user_id+'" class="user"> \
									<td class="name">'+data.firstname+' '+data.lastname+'</td> \
									<td class="email">'+data.email+'</td> \
									<td class="events_count" style="text-align: right">0</td> \
									<td style="text-align: right"><a class="btn btn-danger btn-mini delete-user-button">&times;</a></td> \
								</tr>';

					$('#users-table').parent()
								.prepend(message);

					$('#users-table').append(newRow);
				}
			});
		});
	});
</script>