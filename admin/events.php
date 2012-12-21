<?php
$events = Event::find_all();
?>
<div class="container">
	<div class="row">
		<div class="span12">
			<table id="event-table" class="table table-hover">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Organisateur</th>
						<th>Type</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($events as $event): ?>
						<tr class="event" data-id=<?php echo $event->id ?>>
							<td class="event-name"><?php echo $event->name ?></td>
							<td class="event-description"><?php echo $event->description ?></td>
							<td class="organizer-fullname"><?php echo $event->organizer()->firstname ?> <?php echo $event->organizer()->lastname ?></td>
							<td class="event-type"><?php echo $event->type ?></td>
							<td><a class="btn btn-danger btn-mini delete-event-button">&times;</a></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('.delete-event-button').on('click', function() {
			var event_name = $(this).parent().siblings('.event-name');
			var event_row = $(this).parent().parent();

			if(confirm('Voulez-vous vraiment effacé l\'événement: ' + event_name.text())) {
				delete_event(event_row);
			}
		});

		function delete_event(event_row) {
			var data = { event_id: event_row.data('id') };

			$.ajax({
				url: "admin/delete_event.php",
				type: "POST",
				data: data,
				success: function(msg) {
					if(msg) {
						var message = '<div class="alert alert-warning"> \
								<button type="button" class="close" data-dismiss="alert">&times;</button> \
								L\'événement ' + event_row.children('.event-name').text() + ' a été supprimé!</div>';

						$('#event-table').parent()
							.prepend(message);

						event_row.slideUp('slow', function() {
							$(this).remove();
						})
					}
				}
			});
		}
	});
</script>