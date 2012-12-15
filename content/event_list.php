<div class="container">

        <table class="table">
        	<thead>
	        	<tr>
	        		<th><h1>Vos événements</h1></th>
	        	</tr>
        	</thead>
        	<tbody>
	            <?php foreach (Event::find_for_user($info['id']) as $event): ?>
	            <tr>
	               	<td><a href="?p=view_event.php?id=<?php echo $event['id'] ?>"><?php echo $event['name'] ?></a></td>
	            </tr>
	            <?php endforeach ?>
            </tbody>
        </table>

</div> <!-- /container -->