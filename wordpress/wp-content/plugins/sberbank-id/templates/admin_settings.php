<div class="wrap">
  <div id="icon-options-general" class="icon32"><br></div>
  <h2>Сбер ID Настройки</h2>
  <form method="POST" action="/wp-admin/admin.php?page=sberbank_id" enctype="multipart/form-data" class="sberbank_id_form">
    <? settings_fields( 'sberbank_id_options' ); ?>
    <table class="form-table">
      <tbody>
       
        
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="woocommerce_rbspayment_ab_enabled">Включить/Выключить </label>
          </th>
          <td class="forminp">
            <input type="hidden" value="off" name="sberbank_id[enabled]" value="off">
            <fieldset>
              <legend class="screen-reader-text"><span>Включить/Выключить</span></legend>
              <label for="woocommerce_rbspayment_ab_enabled">
              <input class="" type="checkbox" name="sberbank_id[enabled]"  style="" <? if ($options['enabled'] == 'on') echo 'checked="checked"' ?>> Включен</label><br>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc" colspan="2">
            <h3>Основные настройки</h3>
          </th>
        </tr>
        <tr valign="top">
          <th colspan="2" class="titledesc" style="padding-top: 0;">
            <div class="update-nag" style="margin-top:0;">Для получения <b>Client ID</b> и <b>Client secret</b> необходимо заключить договор на Сбер ID. Для заключения договора 
              <a target="_blank" href="https://www.sberbank.ru/ru/person/dist_services/sberbankid/forbusiness">оставьте заявку</a>.
            </div>
          </th>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Client ID</label>
          </th>
          <td class="forminp">
            <fieldset>
              <input class="sberbank_id_field" type="text" name="sberbank_id[client_id]" value="<?= esc_attr($options['client_id']); ?>" placeholder="">
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Client Secret</label>
          </th>
          <td class="forminp">
            <fieldset>
              <input class="sberbank_id_field" type="password" name="sberbank_id[client_secret]" value="<?= esc_attr($options['client_secret']); ?>">
            </fieldset>
          </td>
        </tr>
         <tr valign="top">
          <th scope="row" class="titledesc" colspan="2">
            <h3>Настройки сертификата</h3>
          </th>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Файл сертификата</label>
          </th>
          <td class="forminp">
            <fieldset>
              <input class="sberbank_id_field" type="file" name="sertificate">
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Пароль от сертификата</label>
          </th>
          <td class="forminp">
            <fieldset>
              <input class="sberbank_id_field" type="password" name="sberbank_id[sert_password]" value="<?= esc_attr($options['sert_password']); ?>">
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc" colspan="2">
            <h3>Дополнительньные настройки</h3>
          </th>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">PKCE</label>
          </th>
          <td class="forminp">
            <fieldset>
              <input type="hidden" value="off" name="sberbank_id[pkce]">
              <label for=""><input class="sberbank_id_field" type="checkbox" name="sberbank_id[pkce]" <? if ($options['pkce'] == 'on') echo 'checked="checked"' ?>></label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">mWeb2App</label>
          </th>
          <td class="forminp">
            <fieldset>
              <input type="hidden" value="off" name="sberbank_id[web_to_app]">
              <label for=""><input class="sberbank_id_field" type="checkbox" name="sberbank_id[web_to_app]" <? if ($options['web_to_app'] == 'on') echo 'checked="checked"' ?>></label>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Поля (Scope)</label>
          </th>
          <td class="forminp">
            <input type="hidden" value="off" name="sberbank_id[scopes][phone]" value="off">
            <fieldset>
              <label><input class="sberbank_id_field" checked="checked" type="checkbox" disabled="disabled" name=""> Фамилия, имя, отчество</label><br>
              <label><input class="sberbank_id_field" checked="checked" type="checkbox" disabled="disabled" name=""> Адрес электронной почты</label><br>
              <label><input class="sberbank_id_field" type="checkbox" name="sberbank_id[scopes][phone]" <? if ($options['scopes']['phone'] == 'on') echo 'checked="checked"' ?>> Номер мобильного телефона</label><br>
              <input type="hidden" name="sberbank_id[scopes][name]" value="on">
              <input type="hidden" name="sberbank_id[scopes][email]" value="on">
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc" colspan="2">
            <h3>Настройка внешнего вида кнопки авторизации</h3>
          </th>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">размер кнопки</label>
          </th>
          <td class="forminp">
            <fieldset>
              <select class="sberbank_id_field  button-style-changer" type="text" name="sberbank_id[button_size]" onclick="changeStyleButton()">
                <option value="SMALL" <?=  $options['button_size'] == 'SMALL' ? 'selected="selected"' : '' ?>>Маленькая</option>
                <option value="MEDIUM" <?=  $options['button_size'] == 'MEDIUM' ? 'selected="selected"' : '' ?>>Средняя</option>
                <option value="LARGE" <?=  $options['button_size'] == 'LARGE' ? 'selected="selected"' : '' ?>>Большая</option>
              </select>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Форма кнопки</label>
          </th>
          <td class="forminp">
            <fieldset>
              <select class="sberbank_id_field  button-style-changer" type="text" name="sberbank_id[button_form]" onclick="changeStyleButton()">
                <option value="BOX" <?=  $options['button_form'] == 'BOX' ? 'selected="selected"' : '' ?>>Прямоугольная</option>
                <option value="CIRCLE" <?=  $options['button_form'] == 'CIRCLE' ? 'selected="selected"' : '' ?>>Круглая</option>
              </select>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc">
            <label for="sberbank_id__label">Тема</label>
          </th>
          <td class="forminp">
            <fieldset>
              <select class="sberbank_id_field  button-style-changer" type="text" name="sberbank_id[button_style]" onclick="changeStyleButton()">
                <option value="FLAT" <?=  $options['button_style'] == 'FLAT' ? 'selected="selected"' : '' ?>>Зеленая</option>
                <option value="BORDER" <?=  $options['button_style'] == 'BORDER' ? 'selected="selected"' : '' ?>>Белая</option>
              </select>
            </fieldset>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row" class="titledesc" colspan="2">
            <h3>Пример отображения кнопки авторизации</h3>
            <div class="sberbank_button_block">
              <span class="sberbank_button">
              <span class="sberbank_button-logo"></span>
              Войти по Сбер ID
              </span>
            </div>
          </th>
        </tr>
      </tbody>
    </table>
    <?
      submit_button();
    ?>
  </form>
</div>
<style>
  .sberbank_id_field {
  width: 420px;
  height: 42px;
  font-size: 14px;
  max-width: 100%;
  }
  .sberbank_id_form .button  {
  width: 400px;
  height: 40px;
  max-width: 100%;
  }
</style>
<script>
  function changeStyleButton() {
  var selects = document.getElementsByClassName("button-style-changer");
  var element = document.getElementsByClassName("sberbank_button")[0];
  element.className = 'sberbank_button';
  for (var i = selects.length - 1; i >= 0; i--) {
  element.classList.add(selects[i].value);
  }
  }
  changeStyleButton();
  
</script>
<style>
  .sberbank_button {
  display: flex;
  width: 200px;
  height: 40px;
  color: #fff;
  font-size: 14px;
  border: 1px solid #19bb4f;
  border-radius: 4px;
  align-items: center;
  align-content: center;
  justify-content: flex-start;
  line-height: 17px;
  outline: none;
  background: #19bb4f;
  box-sizing: border-box;
  max-width: 100%;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  }
  .sberbank_button.FLAT {}
  .sberbank_button.BORDER {
  background: #fff;
  color: #000;
  border:1px solid #cccdd1;
  }
  .sberbank_button.CIRCLE {
  width: 32px;
  height: 32px;
  background-position: center;
  background-size: 24px;
  border-radius: 90px;
  padding: 0;
  font-size: 0;
  padding: 0;
  }
  .sberbank_button.SMALL {
  width: auto;
  padding: 0 10px 0 4px;
  }
  .sberbank_button.MEDIUM {
  padding: 0 20px;
  width: auto;
  }
  .sberbank_button.LARGE {
  width: 100%;
  }
  .sberbank_button.SMALL.CIRCLE {
  width: 32px;
  height: 32px;
  padding: 0;
  }
  .sberbank_button.MEDIUM.CIRCLE {
  width: 52px;
  height: 52px;
  padding: 0;
  }
  .sberbank_button.LARGE.CIRCLE {
  width: 72px;
  height: 72px;
  padding: 0;
  }
  .sberbank_button.CIRCLE.SMALL .sberbank_button-logo {
  width: 100%;
  height: 100%;
  background-position: center;
  background-size: 18px;
  }
  .sberbank_button.CIRCLE.MEDIUM .sberbank_button-logo {
  width: 100%;
  height: 100%;
  background-position: center;
  background-size: 32px;
  }
  .sberbank_button.CIRCLE.LARGE .sberbank_button-logo {
  width: 100%;
  height: 100%;
  background-position: center;
  background-size: 44px;
  }
  .sberbank_button-logo {
  width: 40px;
  height: 40px;
  background-image: url(<?php echo plugins_url('/sberbank-id/img/sberbank_oauth_logo_1.png');?>);
  background-position: center;
  background-repeat: no-repeat;
  background-size: 24px;
  }
  .sberbank_button.BORDER .sberbank_button-logo {
  background-image: url(<?php echo plugins_url('/sberbank-id/img/sberbank_oauth_logo_2.png');?>);
  }
  .sberbank_button.CIRCLE .sberbank_button-logo {
  width: 100%;
  height: 100%;
  background-position: center;
  }
  .sberbank_button_block {
  width: 100%;
  display: flex;
  align-items: center;
  }
</style>