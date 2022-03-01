<div
  class="option-container"
  data-exlog-conditionals="<?php if (isset($form_field["conditionals"])) echo htmlspecialchars(json_encode($form_field["conditionals"])); ?>"
>
  <h4 class="option-title"><?php echo $form_field["field_name"]; ?></h4>
  <p><?php echo $form_field["field_description"]; ?></p>

  <?php if (!(exlog_is_wpconfig_option_set($form_field["field_slug"]))) : ?>

    <input
      id="<?php echo $form_field["field_slug"]; ?>"
      type="checkbox"
      name="<?php echo $form_field["field_slug"]; ?>"
        <?php if (exlog_get_option($form_field["field_slug"]) == "on") :?>
          checked
        <?php endif; ?>
    />

  <?php else : ?>
      <?php include EXLOG_PATH_PLUGIN_VIEWS . "/partials/wpconfig_option_set_message.php"?>
  <?php endif; ?>
</div>
