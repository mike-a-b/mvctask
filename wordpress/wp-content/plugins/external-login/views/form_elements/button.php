<div class="option-container">
  <h4><?php echo $form_field["field_name"]; ?></h4>
  <p><?php echo $form_field["field_description"]; ?></p>
  <input
      type="button"
      value="<?php echo $form_field["field_text"]; ?>"
      <?php if($form_field["input_class"]) : ?>
          class="<?php echo $form_field["input_class"]; ?> button button-primary"
      <?php endif; ?>
  />
</div>
