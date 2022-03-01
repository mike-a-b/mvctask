<?php
  $EXLOG_WORDPRESS_AVAILABLE_ROLES = exlog_get_wp_role_types();
  reset($EXLOG_WORDPRESS_AVAILABLE_ROLES);
  $exlog_first_role = key($EXLOG_WORDPRESS_AVAILABLE_ROLES);

  $EXLOG_EXTERNAL_ROLE_PREFIX = "exlog_role_external_";
  $EXLOG_WORDPRESS_ROLE_PREFIX = "exlog_role_wordpress_";

  $EXLOG_JSON_KEY_EXTERNAL_VALUE = "external_role_value";
  $EXLOG_JSON_KEY_EXTERNAL_NAME = "external_role_name";
  $EXLOG_JSON_KEY_WORDPRESS_VALUE = "wordpress_role_value";
  $EXLOG_JSON_KEY_WORDPRESS_NAME = "wordpress_role_name";

//  Turn option field encoded version of data into php array
  $exlog_external_roles = exlog_decode_json_data(get_option($form_field["field_slug"]));

//  Prepare a variable to store the markup for a role field. This is used by client side JS to create additional fields.
  ob_start();
  include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/role_field.php';
  $exlog_roles_markup = htmlspecialchars(ob_get_clean());
?>
<div class="option-container">
  <div
    class="roles"
    data-exlog-external-role-prefix="<?php echo $EXLOG_EXTERNAL_ROLE_PREFIX; ?>"
    data-exlog-wordpress-role-prefix="<?php echo $EXLOG_WORDPRESS_ROLE_PREFIX; ?>"

    data-exlog-json-key-external-value="<?php echo $EXLOG_JSON_KEY_EXTERNAL_VALUE; ?>"
    data-exlog-json-key-external-name="<?php echo $EXLOG_JSON_KEY_EXTERNAL_NAME; ?>"
    data-exlog-json-key-wordpress-value="<?php echo $EXLOG_JSON_KEY_WORDPRESS_VALUE; ?>"
    data-exlog-json-key-wordpress-name="<?php echo $EXLOG_JSON_KEY_WORDPRESS_NAME; ?>"

    data-exlog-field-markup="<?php echo $exlog_roles_markup; ?>"
  >
    <h4><?php echo $form_field["field_name"]; ?></h4>
    <p><?php echo $form_field["field_description"]; ?></p>
    <?php if (is_array($exlog_external_roles) && sizeof($exlog_external_roles) > 0) : ?>
      <?php foreach ($exlog_external_roles as $exlog_external_role) : ?>
            <?php include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/role_field.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="add_more">
    <p class="description">Click to add a key value pair:</p>
    <input class="add_button button button-primary" type="button" value="+"/>
  </div>

  <input
    class="exlog_custom_roles"
    type="hidden"
    name="<?php echo $form_field["field_slug"]; ?>"
    value="<?php echo get_option($form_field["field_slug"]); ?>"
  />
</div>
