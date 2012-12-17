<div class="container">

    <div class="row">
        <div class="span12">
            
            <ul class="sticky_notes">
                <?php foreach ($user->events() as $event): ?>
                <li>
                    <a href="#">
                        <h3><?php echo $event->name ?></h3>
                        <p>from <?php echo $event->start_time ?></p>
                        <p>to <?php echo $event->end_time ?></p>
                        <p>- <?php echo $event->organizer()->firstname ?> <?php echo $event->organizer()->lastname ?></p>
                    </a>
                </li>
                <?php endforeach ?>

                <?php foreach ($user->events() as $event): ?>
                <li>
                    <a href="#">
                        <h3><?php echo $event->name ?></h3>
                        <p>from <?php echo $event->start_time ?></p>
                        <p>to <?php echo $event->end_time ?></p>
                        <p>- <?php echo $event->organizer()->firstname ?> <?php echo $event->organizer()->lastname ?></p>
                    </a>
                </li>
                <?php endforeach ?>
            </ul>
        
        </div>
    </div>
</div> <!-- /container -->