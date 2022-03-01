<? wp_enqueue_script('sberid-universallink', plugins_url('/sberbank-id/js/sberid-universallink.min.js')); ?>
<div class="com-sberbankid__block">
  <a class="sberbank_button <?= $options['button_size'];?> <?= $options['button_style'];?> <?= $options['button_form'];?>" href="<?= $sberbank_id_url;?>">
  <span class="sberbank_button-logo"></span>
    Войти по Сбербанк ID
  </a>
</div>
<script>
  <?php if($options['web_to_app'] == 'on') {?>
    document.addEventListener("DOMContentLoaded", function() {
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        (function () {
              function myCallback(result) {
                  console.log(result);
              }
              try {
                  var sberidUniversallink = new SberidUniversallink({
                      params: '<?=$sberbank_id_url;?>',
                      selector: '.sberbank_button',
                      formSelector: '.formSelector',
                      callback: myCallback,
                      needAdditionalRedirect: true
                  });
              } catch (error) {
                  console.log(error);
              }
          })();
        $('.sberbank_button').attr("onclick", null).off("click");
      }
    });
  <?php } ?>
</script>
<style>
  @import url('https://fonts.googleapis.com/css?family=Roboto&display=swap');
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
  text-decoration: none;
  cursor: pointer;
  font-family: roboto, arial;
  }
  .sberbank_button:hover,
  .sberbank_button:focus {
  text-decoration: none;
  color: #fff;
  }
  .sberbank_button.FLAT {}
  .sberbank_button.BORDER {
  background: #fff;
  color: #000;
  border:1px solid #cccdd1;
  }
  .sberbank_button.BORDER:hover,
  .sberbank_button.BORDER:focus {
  text-decoration: none;
  color: #000;
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
  .com-sberbankid__block {
  margin: 10px 0;
  padding: 0;
  display: flex;
  }
</style>