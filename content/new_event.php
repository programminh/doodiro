<?php
$all_users = json_encode(User::find_all_names());
?>


<div class="container">
	<div class="row">
		<div class="span6 offset3">
			
			<form id="new_event_form" class="form-horizontal" action="">

				<legend>Nouvel événement</legend>

				<div class="control-group">
					<label for="title" class="control-label">Titre</label>
					<div class="controls">
						<input name="title" type="text" placeholder="Titre" id="title">
					</div>
				</div><!-- End Title -->

				<div class="control-group">
					<label for="description" class="control-label">Description</label>
					<div class="controls">
						<textarea name="description" rows="3" id="description" placeholder="Description"></textarea>
					</div>
				</div><!-- End description -->

				<div class="control-group">
					<label for="duration" class="control-label">Durée (heures)</label>
					<div class="controls">
						<select class="span1" name="duration" id="duration">
							<?php for($i = 1; $i <= 12; $i++): ?>
								<option value="<?php echo $i ?>"><?php echo $i ?></option>
							<?php endfor ?>
						</select>
					</div>
				</div><!-- End duration -->

				<div class="control-group">
					<label for="type" class="control-label">Type</label>
					<div class="controls">
						<select class="span2" name="type" id="type">
							<option value="public">publique</option>
							<option value="private">privé</option>
						</select>
					</div>
				</div><!-- End type -->

				<div class="control-group">
					<label for="invitee" class="control-label">Invités</label>
					<div id="invitee-group" class="controls">
						<div class="input-append">
							<input name="invitee" type="text" placeholder="Nom de l'invité" id="invitee">
							<a id="add_invitee" class="btn"><i class="icon-plus"></i></a>
						</div>
					</div>
				</div><!-- End Invitees -->

			</form>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var users = <?php echo $all_users ?>;
		var usersNames = [];
		var invitees = [];
		var inviteeDiv = $('#invitee-group');
		var addInviteeButton = $('#add_invitee');
		var inviteeInput = $('#invitee');

		// Load all the names into an array for the type ahead
		for(var key in users) {
			if(users.hasOwnProperty(key)) {
				usersNames.push(users[key].fullname);
			}
		}

		// Intializes type ahead
		$('#invitee').typeahead({
			source: usersNames
		});

		// Add an invitee
		addInviteeButton.click(function(){
			if(! inviteeInput.val()) {
				return;
			}

			inviteeDiv.prepend('<div class="alert alert-info alert-invitee">'+inviteeInput.val()+'<a class="close">&times;</a></div>')
		});

	});
</script>