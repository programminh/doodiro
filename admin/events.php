<?php

$events = Event::find_all();


?>
<div class="container">
	<div class="row">
		<div class="span12">
			<table class="table table-hover">
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
							<td><?php echo $event->name ?></td>
							<td><?php echo $event->description ?></td>
							<td><?php echo $event->organizer()->firstname ?> <?php echo $event->organizer()->lastname ?></td>
							<td><?php echo $event->type ?></td>
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
		$('delete-event-button').on('click')
	});
</script>