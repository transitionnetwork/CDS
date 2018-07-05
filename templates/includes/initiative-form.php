<form id="create-initiative" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
  <input type="hidden" name="formaction" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
  <?php if ($editmode) { ?>
    <input type="hidden" name="editid" value="<?php echo $_POST['editid']; ?>" readonly="readonly">
  <?php } ?>
  
  <div class="form-row <?php if ($titleError) echo 'error'; ?>">
    <label>Title <span class="required">*</span></label>
    <input type="text" name="title" class="title" value="<?php echo $title; ?>">
  </div>
  
  <div class="form-row <?php if ($descriptionError) echo 'error'; ?>">
    <label>Description <span class="required">*</span></label>
    <textarea name="description" class="description"><?php echo $description; ?></textarea>
  </div>
  
  <div class="form-row">
    <label>Health Check Last Completed Date</label>
    <input type="text" name="healthcheck" class="healthcheck transdatepicker"  value="<?php echo $healthcheck; ?>">
  </div>
  
  <div class="form-row">
    <label>Logo</label>
    <?php if ($logo) { ?>
      <img src="<?php echo $logo['sizes']['resource']; ?>" alt="Initiative Logo">
    <?php } ?>
    <input type="file" name="uploaded_logo" multiple accept='image/*' value="<?php echo $_POST['uploaded_logo']; ?>">
  </div>
  <hr>
  <fieldset>
    <legend>Address and Location</legend>
    
    <div class="form-row <?php if ($addressError) echo 'error'; ?>">
      <label>Address Line 1 <span class="required">*</span></label>
      <input type="text" name="address-line-1" class="address" id="address" value="<?php echo $address; ?>">
    </div>
      
    <div class="form-row <?php if ($cityError) echo 'error'; ?>">
      <label>City <span class="required">*</span></label>
      <input type="text" name="city" class="city" id="city" value="<?php echo $city; ?>">
    </div>
      
    <div class="form-row">
      <label>Province</label>
      <input type="text" name="province" class="province" id="province" value="<?php echo $province; ?>">
    </div>
      
    <div class="form-row">
      <label>Post Code</label>
      <input type="text" name="postcode" class="post-code" id="postcode" value="<?php echo $postcode; ?>">
    </div>
        
    <div class="form-row <?php if ($countryError) echo 'error'; ?>">
      <select name="country" class="country-dropkick" id="country-dropkick">
        <option value="">Select a Country <span class="required">*</span></option>
        <?php $countries = get_terms(array(
          'taxonomy' => 'country',
          'hide_empty' => false
        ));
        foreach ($countries as $countryData) { ?>
          <option value="<?php echo $countryData->slug; ?>" <?php echo ($country == $countryData->slug) ? 'selected' : '' ?>><?php echo $countryData->name; ?></option>
        <?php } ?>
      </select>
    </div>
      
    <div class="form-row">
      <p class="map-description">Add your address to the initiatives map, by clicking the button below</p>
      <a href="#" class="btn btn-primary find-on-map">Find Address on Map</a>
      <div class="form-map-holder <?php if ($mapError) echo 'error'; ?>">
        <div id="leaflet-form-map" class="form-map"></div>
        <div class="geo-search">
          <input type="text" id="geo-search" placeholder="Enter location">
        </div>
        
        <p class="location-text"></p>
        
        <input type="hidden" name="geocoded-address" class="geocoded-address" id="geocoded-address" value="<?php echo $geocodedAddress; ?>">
        <input type="hidden" name="latitude" class="latitude" id="latitude" value="<?php echo $latitude; ?>">
        <input type="hidden" name="longitude" class="longitude" id="longitude" value="<?php echo $longitude; ?>">
      </div>
    </div>
  </fieldset>
  <hr>
  <fieldset>
    <legend>Social and Links</legend>
    <div class="form-row <?php if ($emailError) echo 'error'; ?>">
      <label>Email <span class="required">*</span></label>
      <input type="email" name="email" class="email" value="<?php echo $email; ?>">
    </div>
        
    <div class="form-row">
      <label>Website</label>
      <input type="text" name="website" class="website" value="<?php echo $website; ?>">
    </div>
        
    <div class="form-row">
      <label>Twitter</label>
      <input type="text" name="twitter" class="twitter" value="<?php echo $twitter; ?>">
    </div>
        
    <div class="form-row">
      <label>Facebook</label>
      <input type="text" name="facebook" class="form-facebook" value="<?php echo $facebook; ?>">
    </div>
        
    <div class="form-row">
      <label>Youtube</label>
      <input type="text" name="youtube" class="youtube" value="<?php echo $youtube; ?>">
    </div>
        
    <div class="form-row">
      <label>Instagram</label>
      <input type="text" name="instagram" class="instagram" value="<?php echo $instagram; ?>">
    </div>
  </fieldset>
      
  <hr>
      
  <fieldset>
    <legend>Topics</legend>
    <?php $topics = get_terms(array(
      'taxonomy' => 'topic',
      'hide_empty' => false
    ));
    $topicCount = 1;

    foreach ($topics as $topic) {
      $checked = "";

      if ($postedTopics) {
        foreach ($postedTopics as $postTopic) {
          if ($postTopic->term_id) $postTopic = $postTopic->term_id;
          if ($postTopic == $topic->term_id) {
            $checked = "checked";
          }
        }
      } ?>

      <div class="checkbox">
        <input type="checkbox" value="<?php echo $topic->term_id; ?>" name="topics[]" id="topic-checkbox-<?php echo $topicCount; ?>" <?php echo $checked; ?>>
        <label for="topic-checkbox-<?php echo $topicCount; ?>"><?php echo $topic->name; ?></label>
      </div>
          
      <?php $topicCount++;
    } ?>
  </fieldset>
  <hr>
  <h3>Terms and Conditions</h3>
  <div class="terms-box">
    [TODO] Terms and conditions to come in here
  </div>
  
  <div class="form-row">
    <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
    <input type="submit" value="Submit" class="button">
  </div>
</form>
