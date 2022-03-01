<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <title><?=$data['entry__page_title'];?></title>
  </head>
  <body>
    <div class="sberbank-id_wrapper">
      <div class="sberbank-id_inner">
        <div class="sberbank-id_window">
          <span class="sberbank-id_text">
          <b class="sberbank-id_text-name"><?=$data['username'];?></b>
          <?=$data['entry_title'];?>
          <a href="/"><?=$data['sitename'];?></a>
          </span>
          <div class="sberbank-id_users-list">                
            <span class="sberbank-id_users-item" href="#"><?=$data['email'];?></span>
          </div>
          <div class="sberbank-id_text _footer" style="">
            <div>
            </div>
            <div class="sberbank-id__form">
              <?php if($data['auth_error']) {?>
              <div class="sberbank-id__login-error"><?=$data['entry_password_error'];?></div>
              <?php } ?>
              <form class="" action="/?oauth=sberbank_id&login=1" method="post">
                <input type="hidden" name="username" value="<?=$data['username'];?>">
                <input type="hidden" name="sitename" value="<?=$data['sitename'];?>">
                <input type="hidden" name="email" value="<?=$data['email'];?>">
                <input type="hidden" name="sberbank_user_phone" value="<?=$data['sberbank_user']['phone'];?>">
                <input type="hidden" name="sberbank_user_sub" value="<?=$data['sberbank_user']['sub'];?>">
                <input type="hidden" name="sberbank_user_aud" value="<?=$data['sberbank_user']['aud'];?>">
                <input type="hidden" name="sberbank_user_family_name" value="<?=$data['sberbank_user']['family_name'];?>">
                <input type="hidden" name="sberbank_user_given_name" value="<?=$data['sberbank_user']['given_name'];?>">
                <input type="hidden" name="sberbank_user_middle_name" value="<?=$data['sberbank_user']['middle_name'];?>">
                <div>
                  <input class="sberbank-id__field_password" type="password" name="password" placeholder="Введите пароль">
                </div>
                <button type="submit" class="sberbank-id_actions-button"><?=$data['entry_text_auth'];?></button>
              </form>
            </div>
          </div>
          <div class="sberbank-id_actions">
            <!-- <a href="javascript:void(0)" class="sberbank-id_actions-button" onclick="returnAuth()">Продолжить без привязки</a> -->
            <a class="sberbank-id_actions-link" onclick="continueAuth()"><?=$data['entry_text_return'];?></a>
          </div>
        </div>
      </div>
    </div>
    <style>
      html,body {
      height: 100%;
      }
      body {
      margin:0;
      padding: 0;
      box-sizing: border-box;
      }
      .sberbank-id__form {
      background: #e6e6e6;
      padding: 15px 15px 1px;
      border-radius: 6px;
      margin: 15px 0;
      }
      .sberbank-id__login-error {
      color: red;
      }
      .sberbank-id_wrapper {
      width: 100%;
      /* height: 100%; */
      display: flex;
      align-items: center;
      justify-content: center;
      box-sizing: border-box;
      overflow: auto;
      }
      .sberbank-id_inner {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-right: 15px;
      padding-left: 15px;
      box-sizing: border-box;
      position: fixed;
      left: 0;
      top: 0;
      min-height: 100%;
      overflow: auto;
      }
      .sberbank-id_window {
      border:1px solid #ddd;
      padding: 15px;
      border-radius: 8px;
      width: 100%;
      max-width: 500px;
      box-sizing: border-box;
      position: relative;
      padding-top: 10px;
      padding-bottom: 40px;
      background: #fff;
      max-height: 100%;
      overflow: auto;
      }
      .sberbank-id_users-list {
      display: block;
      /* border-top: 1px dashed #e0e0e0; */
      margin: 0 auto;
      max-width: 300px;
      }
      .sberbank-id_users-item {
      font-size: 14px;
      line-height: 22px;
      color: #575757;
      width: 100%;
      display: block;
      /* background: #f3f3f3; */
      margin-bottom: 4px;
      text-align: center;
      text-decoration: none;
      border-radius: 8px;
      font-family: arial;
      padding: 10px 15px;
      width: 100%;
      box-sizing: border-box;
      border: 1px solid #e0e0e0;
      font-weight: bold;        
      }
      .sberbank-id__field_password {
      font-size: 14px;
      line-height: 22px;
      color: #575757;
      width: 100%;
      display: block;
      /* background: #f3f3f3; */
      margin-bottom: 4px;
      text-align: center;
      text-decoration: none;
      border-radius: 4px;
      font-family: arial;
      padding: 8px 15px;
      width: 100%;
      box-sizing: border-box;
      border: 1px solid #e0e0e0;
      font-weight: bold;
      margin: 12px 0 9px;
      font-weight: normal;      
      }
      .sberbank-id_text {
      font-size: 14px;
      line-height: 20px;
      color: #555;
      display: block;
      box-sizing: border-box;
      font-family: arial;
      text-align: center;
      margin:20px auto;
      width: 300px;
      max-width: 100%;
      }
      .sberbank-id_text a {
      color: #2a81db;
      text-decoration: none;
      }
      .sberbank-id_text._footer {
      color: #999;
      font-size: 12px;
      line-height: 17px;
      }
      .sberbank-id_actions {
      display: block;
      box-sizing: border-box;
      max-width: 300px;
      width: 100%;
      margin: 0 auto;
      }
      .sberbank-id_actions-button {
      font-size: 14px;
      line-height: 27px;
      color: #555;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-sizing: border-box;
      margin-bottom: 25px;
      text-decoration:none;
      }
      .sberbank-id_header {
      box-sizing: border-box;
      background: #19bb4f;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 15px;
      position: absolute;
      left: 0;
      top: 0;
      height: 48px;
      width: 100%;
      }
      .sberbank-id_header-logo {
      margin-right: 10px;
      box-sizing: border-box; 
      }
      .sberbank-id_header-logo img {
      width: 38px;
      height: auto;
      }
      .sberbank-id_header-description {
      color: #fff;
      color: #fff;
      font-size: 16px;
      font-family: arial;
      box-sizing: border-box; 
      }
      .sberbank-id_actions {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      }
      .sberbank-id_actions-link {
      color: #19bb4f;
      font-size: 14px;
      line-height: 19px;
      text-decoration: none;
      font-family: arial;
      text-align: center;
      cursor: pointer;
      }
      .sberbank-id_actions-link:hover {
      text-decoration: none;
      }
      .sberbank-id_actions-button {
      font-family: arial;
      background: #19bb4f;
      color: #fff;
      border:none;
      min-height: 42px;
      width: 100%;
      padding: 6px 18px;
      font-size: 14px;
      line-height: 18px;
      border-radius: 6px;
      cursor: pointer;
      }
      .sberbank-id_actions-button:hover,
      .sberbank-id_actions-button:active {
      background: #11903b;
      color: #fff;
      }
      .sberbank-id_actions-or {
      display: block;
      font-size: 14px;
      color: #555;
      line-height: 19px;
      margin:10px 0;
      font-family: arial;
      text-align: center;
      }
      .sberbank-id_text-name {
      display: block;
      margin: 20px 0;
      font-size: 22px;
      line-height: 30px;
      color: #555;
      }
      .sberbank-id_text a {}
    </style>
    <script>
      function continueAuth() {
        var urlFinishAuth = window.location.origin+window.location.pathname+'?auth_continue=1';
      
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4){
                document.getElementById('result').innerHTML = xhr.responseText;
            }
        };
        xhr.open('GET', urlFinishAuth);
        xhr.send();
      
        if(window.opener) {
          window.opener.location = window.location.origin;
          window.close();
        } else {
          window.location = window.location.origin;
        }
      }
      function returnAuth() {
        if(window.opener) {
          window.opener.location = window.location.origin + '/login';
          window.close();
        } else {
          window.location = window.location.origin + '/login';
        }
      }
    </script>
  </body>
</html>