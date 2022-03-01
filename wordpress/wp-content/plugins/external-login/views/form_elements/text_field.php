<div
  class="option-container"
  data-exlog-conditionals="<?php if (isset($form_field["conditionals"])) echo htmlspecialchars(json_encode($form_field["conditionals"])); ?>"
>
    <?php if($form_field["field_name"]) : ?>
      <h4 class="option-title"><?php echo $form_field["field_name"]; ?></h4>
    <?php endif; ?>

    <?php if($form_field["field_description"]) : ?>
      <p><?php echo $form_field["field_description"]; ?></p>
    <?php endif; ?>

  <?php if (!(exlog_is_wpconfig_option_set($form_field["field_slug"]))) : ?>
    <input
        type="text"
        name="<?php echo $form_field["field_slug"]; ?>"
        value="<?php echo exlog_get_option($form_field["field_slug"]); ?>"
        <?php if (isset($form_field["required"]) && $form_field["required"]) : ?>
          required
        <?php endif; ?>
    />
  <?php else : ?>
      <?php include EXLOG_PATH_PLUGIN_VIEWS . "/partials/wpconfig_option_set_message.php"?>
  <?php endif; ?>
</div>
