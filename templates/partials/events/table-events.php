<?php $events = $args['events']; ?>
<?php if($events) { ?>
  <div class="xinc-events__event_list">
    <?php foreach($events as $event) { ?>
      <div class="xinc-events__event_list__event_tile">
        <div class="row">
          <?php if($event['image_url']) { ?>
            <div class="col-12 col-md-4">
              <img src="<?php echo $event['image_url']; ?>" alt="<?php echo $event['title']; ?>" title="<?php echo $event['title']; ?>">
            </div>
          <?php } ?>
          <div class="col-12 col-md-8">
            <div class="xinc-events__event_list__event_tile__details">
            <?php if($event['has_subevents']) { ?>
                <?php echo '<strong>Series</strong>'; ?>
              <?php } ?>
              <h3>
                <a href="<?php echo $event['event_url']; ?>" target="_blank"><?php echo $event['title']; ?></a>
              </h3>
              <?php
              $date_from = date('jS M Y', strtotime($event['date_from']));
              $date_to = $event['date_to'] ? date('jS M Y', strtotime($event['date_to'])) : null;
              $time_from = date('H:i e', strtotime($event['date_from'])) ?>

              <div class="xinc-events__event_list__event_tile__details__date">
                <div class="xinc-events__event_list__event_tile__details__item">
                  <?php echo get_template_part('src/xinc-events/svg/date'); ?>
                  <?php if($date_to && ($date_from !== $date_to)) { ?>
                    <?php echo $date_from; ?> - <?php echo $date_to; ?>
                  <?php } else { ?>
                    <?php echo $date_from; ?>
                  <?php } ?>
                </div>
                <div class="xinc-events__event_list__event_tile__details__item">
                  <?php echo get_template_part('src/xinc-events/svg/time'); ?>Begins at <?php echo $time_from; ?>
                </div>
              </div>
              <?php if($event['location']) { ?>
                <div class="xinc-events__event_list__event_tile__details__item">
                  <?php echo get_template_part('src/xinc-events/svg/location'); ?><?php echo xinc_events_link_urls($event['location']); ?>
                </div>
              <?php } ?>

              <div>
                <?php $extract = xinc_events_first_words(strip_tags($event['description']), 50) ?>
                <?php echo $extract; ?>&hellip;
              </div>

              <div>
                <a class="btn btn-primary" href="<?php echo $event['event_url']; ?>" target="_blank">Get Tickets & More Info</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } ?>
