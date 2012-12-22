<?php
include('custom_functions.php');
$all_users = __json_encode(User::find_all_names($user->id));
?>


<div class="container">
	<div class="row">
		<div class="span8 offset2">
			
			<form id="new_event_form" class="form-horizontal" action"javascript:void(0);" onsubmit="return false">

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
							<input name="invitee" type="text" placeholder="Nom de l'invité" id="invitee" autocomplete="off">
							<a id="add-invitee" class="btn"><i class="icon-plus"></i></a>
						</div>
					</div>
				</div><!-- End Invitees -->

				<div class="control-group">
					<label for="datepicker" class="control-label">Date</label>
					<div id="date-group" class="controls">
						<input style="width: 80px;" name="date" type="text" placeholder="Date" id="datepicker" autocomplete="off">
						<select name="from-time" id="from-time" style="width: 80px;">
							<option value="">De</option>
							<?php for($i = 0; $i < 24; $i++): ?>
								<option value="<?php printf("%02d:00:00", $i) ?>"><?php printf("%02d:00", $i) ?></option>
							<?php endfor; ?>
						</select>
						<select name="to-time" id="to-time" style="width: 80px;">
							<option value="">À</option>
							<?php for($i = 0; $i < 24; $i++): ?>
								<option value="<?php printf("%02d:00:00", $i) ?>"><?php printf("%02d:00", $i) ?></option>
							<?php endfor; ?>
						</select>
						<a id="add-date" class="btn"><i class="icon-plus"></i></a>
					</div>
				</div><!-- End Date -->

				<div class="form-actions">
				  	<button type="submit" class="btn btn-primary" id="save-event">Enregistrer</button>
				  	<img style="margin-left: 10px" class="hide" src="img/ajax-loader.gif" id="loader" alt="Loader">
				</div>

			</form>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var users = <?php echo $all_users ?>;
		var usersName = [];
		var inviteesName = [];
		var invitees = [];
		var inviteeDiv = $('#invitee-group');
		var inviteeInput = $('#invitee');
		var dateDiv = $('#date-group');
		var toTime = $('#to-time');
		var fromTime = $('#from-time');
		var datepicker = $('#datepicker');
		var dates = [];
		
		datepicker.datepicker({ dateFormat: "yy-mm-dd"});


		// Load all the names into an array for the typeahead
		for(var key in users) {
			if(users.hasOwnProperty(key)) {
				usersName.push(users[key].fullname);
			}
		}

		// Intializes type ahead
		$('#invitee').typeahead({
			source: usersName
		});

		// Add an invitee
		$('#add-invitee').on('click', function () { console.log(inviteeInput.val()); return addInvitee(inviteeInput.val()) });

		// Add a date
		$('#add-date').on('click', function () { return addDate(datepicker.val(), fromTime.val(), toTime.val()) });

		/**
		 * Add a person to the invitee list and add's his name to the invitee's name array
		 * @param {String} fullname Invitee's name
		 */
		function addInvitee(fullname) {

			if(! fullname) return alert('Le nom de l\'invité ne peut être vide');

			if($.inArray(fullname, usersName) === -1) return alert(fullname + ' n\'existe pas');
			
			if($.inArray(fullname, inviteesName) !== -1) return alert(fullname + ' est déjà invité(e)');

			inviteeInput.val('');

			inviteesName.push(fullname);
			
			// Loop through the users array and push the user object into the invitees array
			for(var key in users) {
				if(users.hasOwnProperty(key)) {
					if (users[key].fullname == fullname) {
						invitees.push(users[key]);
						break;
					}
				}
			}

			var id = invitees.length - 1;

			inviteeDiv.prepend('<div class="alert alert-info alert-invitee">'+fullname+'<a id="close-invitee" class="close">&times;</a></div>');
		
			$('#close-invitee').on('click', function() {
				removeInvitee($(this).parent().text());
				$(this).parent().remove();
			});

			console.log(invitees);
			console.log(inviteesName);
		}

		/**
		 * Remove an invitee from an array
		 * @param  {String} fullname Full name
		 */
		function removeInvitee(fullname) {

			var index = $.inArray(fullname, inviteesName);
			
			inviteesName.splice(index,1);
			invitees.splice(index,1);

			console.log(invitees);
			console.log(inviteesName);
		}

		/**
		 * Adds a date
		 * @param {string} date     Date
		 * @param {string} fromTime From time
		 * @param {string} toTime   To time
		 */
		function addDate(date, fromTime, toTime) {
			var thisDate = { date: date, fromTime: fromTime, toTime: toTime };

			if(! date || ! fromTime || ! toTime) return alert('La date et les heures ne peuvent être vides');
			if(new Date(date + "00:00:00") < new Date()) return alert('La date ne peut être dans le passé');
			if(toTime < fromTime) return alert('L\'heure de fin ne peut être plus petit que l\'heure du début');

			dates.push(thisDate);
			console.log(dates);
			dateDiv.prepend('<div class="alert alert-info alert-invitee">'+date+', '+fromTime+' - '+toTime+'<a id="close-date" class="close">&times;</a></div>');

			$('#close-date').on('click', function() {
				removeDate(dateStringToObject($(this).parent().text()));
				$(this).parent().remove();
			});
		}

		/**
		 * Removes a date from the array
		 * @param  {object} dateToRemove Date to remove
		 */
		function removeDate(dateToRemove) {
			var index = $.inArray(dateToRemove, dates);

			dates.splice(index,1);
			console.log(dates);
		}

		/**
		 * Hack to turn the date string to a date object
		 * @param  {string} string The date string
		 * @return {object}        Date object
		 */
		function dateStringToObject(string) {
			var date = string.substring(0,10);
			var fromTime = string.substring(12,20);
			var toTime = string.substring(23,31);

			return { date: date, fromTime: fromTime, toTime: toTime};
		}

		$('#new_event_form').submit(function() {
			if (! $('#title').val()) return alert('Le titre ne peut être vide.');
			if (! $('#description').val()) return alert('La description ne peut être vide.');
			if (! invitees.length) return alert('La liste des invitées ne peut être vide.');
			if (! dates.length) return alert('La liste des dates ne peut être vide.');

			$('#save-event').attr('disabled', true);
			$('#loader').removeClass('hide');

			var data = {
				title: $('#title').val(),
				description: $('#description').val(),
				duration: $('#duration').val(),
				type: $('#type').val(),
				invitees: invitees,
				dates: dates
			};

			console.log(data);

			$.ajax({
				url: "content/insert_event.php",
				type: "POST",
				data: data,
				success: function(msg) {
					if (msg) {
						$('#new_event_form').parent()
							.prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>L\'événement a été créer avec succès</div>');
					}
					$('#loader').addClass('hide');
					$('#new_event_form :input').attr('disabled', true);
					$('.close').remove;
				}
			});

			return false;
		});
	});
</script>
