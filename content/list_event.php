<div class="container">

    <div class="row">
        <div class="span12">

            <ul class="sticky_notes">
                <?php foreach ($user->events() as $event): ?>
                <li>
                    <a href="?p=register_event&id=<?php echo $event->id ?>">
                        <h3><?php echo $event->name ?></h3>
                        <p><?php echo $event->description ?></p>
                        <p>- <?php echo $event->organizer_name ?></p>
                    </a>
                </li>
                <?php endforeach ?>

            </ul>

        </div>
    </div>
</div> <!-- /container -->
