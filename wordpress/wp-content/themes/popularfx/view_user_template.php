<?php
/*
*Template Name: Мой шаблон страницы
* @package PopularFX
*/
 
/* Get user info. */
global $wpdb;
$resultdisplay = $wpdb->get_results("select * from wpsc_users");
get_header();
?>
<link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/style.css" />
<style>
    .form__input {
  font-family: 'Roboto', sans-serif;
  color: #333;
  font-size: 1.2rem;
	margin: 0 auto;
  padding: 1.5rem 2rem;
  border-radius: 0.2rem;
  background-color: rgb(255, 255, 255);
  border: none;
  width: 50%;
  display: block;
  border-bottom: 0.3rem solid transparent;
  transition: all 0.3s;
}
</style>




<div class="card322">
  <h3 class="title333">Ваш профиль</h3>
  <p class="title322">ФИО</p>
  <p class="content322"> <?php echo $current_user->display_name;?>   </p>
  <p class="title322">Номер телефона</p>
  <p class="content322"> <?php echo $current_user->user_login;?>   </p>
  <p class="title322">Место работы</p>
  <p class="content322"> <?php echo $current_user->user_work;?>   </p>
  <p class="title322">Email</p>
  <p class="content322"> <?php echo $current_user->user_email;?>   </p>
  
  
  
  <form role="form" method="post">
      <div class ="form-group">
          <input id = "title" name="email" type="text" placeholder="<?php echo $current_user->user_email;?>"
          class ="form__input" required="">
      </div>
      <div class="row justify-content-center">
          <div class="col-xs-4 col-sm-4 col-md-4">
              <p><button class="btn322 btnwhite" name="submitbtn2">Сохранить</button></p>
              <!--<input type="submit" value="Сохранить" class="btnwhite" name="submitbtn">-->
          </div>
          
      </div>
      
      
  </form>
 
  <?php
  
  if(isset($_POST['submitbtn2'])){
  $data=$_POST['email'];
  $table_name='wpsc_users';
  $idnow=get_current_user_id();
  
  $wpdb->update($table_name,
    array ('user_email'=>$data)
    , array('ID'=> $idnow)
    );
    
  }
  
  ?>

  
  <p><button class="btn322 btnpurple" onclick="window.location.href = 'https://vremyaitb.ru/testresult/';">Результаты тестов</button></p>
  <p><button class="btn322 btndefault">Настройка уведомлений</button></p>
   </div>


<?php
$current_user = wp_get_current_user();?>
<?php

get_footer();