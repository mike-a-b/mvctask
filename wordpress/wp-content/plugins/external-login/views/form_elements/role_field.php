<div class="option-container">
    <div class="role">
        <input
            class="external_role"
            type="text"
            value="<?php
              if (isset($exlog_external_role[$EXLOG_JSON_KEY_EXTERNAL_VALUE])) {
                  echo $exlog_external_role[$EXLOG_JSON_KEY_EXTERNAL_VALUE];
              }
            ?>"
            name="<?php
              if (isset($exlog_external_role[$EXLOG_JSON_KEY_EXTERNAL_NAME])) {
                echo $exlog_external_role[$EXLOG_JSON_KEY_EXTERNAL_NAME];
              }
            ?>"
        >

        <select
            class="wordpress_role"
            name="<?php
              if(isset($exlog_external_role[$EXLOG_JSON_KEY_WORDPRESS_NAME])) {
                echo $exlog_external_role[$EXLOG_JSON_KEY_WORDPRESS_NAME];
              }
            ?>"
        >
            <?php $EXLOG_WORDPRESS_AVAILABLE_ROLES[EXLOG_ROLE_BLOCK_VALUE] = "-- BLOCK --"; //Add ability to block based on role ?>

            <?php foreach ($EXLOG_WORDPRESS_AVAILABLE_ROLES as $key => $value) : ?>
                <option
                    <?php if (isset($exlog_external_role[$EXLOG_JSON_KEY_WORDPRESS_VALUE]) && $exlog_external_role[$EXLOG_JSON_KEY_WORDPRESS_VALUE] == $key) :?>
                        selected="selected"
                    <?php endif; ?>
                    value="<?php echo $key; ?>"
                >
                    <?php echo $value; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input
            class="remove_role_pairing button button-primary"
            value="Delete"
            type="button"
        />
    </div>
</div>
