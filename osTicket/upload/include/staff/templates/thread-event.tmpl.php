<div class="thread-event <?php if ($event->uid) echo 'action'; ?>">
        <!--<div class="col-md-2">-->
            <span class="type-icon">
              <i class="faded icon-<?php echo $event->getIcon(); ?>"></i>
            </span>
        <!--</div>-->
        <!--<div class="col-md-10">-->
            <span class="faded description">
                <?php echo $event->getDescription(ThreadEvent::MODE_STAFF); ?>
            </span>
        <!--</div>-->
</div>
