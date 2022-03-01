<?php
/*
*Template Name: cvdtest
* @package PopularFX
*/
 
/* Get user info. */
global $wpdb;
$idnow=get_current_user_id();
$result = $wpdb->get_results("select * from teststable where user_ids = $idnow ");
$userresult = $wpdb->get_results("select * from wpsc_users");

$q = $wpdb->get_results("SELECT * FROM teststable WHERE user_ids = $idnow AND test_result <> 'Сдан' ORDER BY test_date DESC LIMIT 1");
foreach($q as $row)
{
    $td=$row ->test_date;
    $tr=$row ->test_result;
    $ta=$row ->test_adres;
}
$tn=$current_user->display_name;
get_header();

?>

<style>
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 999;
    overflow: auto;
    visibility:hidden;
    opacity: 0;
    transition: opacity 0.7s ease-in 0s;
}
.popup {
    top: 10%;
    left: 0;
    right: 0;       
    font-size: 14px;
    margin: auto;
    width: 80%;
    min-width: 200px;
    max-width: 600px;
    position: absolute;
    padding: 15px 20px;
    border: 1px solid #666;
    background-color: #fefefe;
    z-index: 1000;
    border-radius: 10px;
    font: 14px/18px 'Tahoma', Arial, sans-serif;
    box-shadow: 0 0px 14px rgba(0, 0, 0, 0.4);
}
.close {
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    position: absolute;
    border: none;
    border-radius: 50%;
    background-color: rgba(0, 130, 230, 0.9);
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
    cursor: pointer;
    outline: none;
}
.close:before {
    color: rgba(255, 255, 255, 0.9);
    content: "X";
    font-family:  Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: normal;
    text-decoration: none;
    text-shadow: 0 -1px rgba(0, 0, 0, 0.9);
}
.close:hover {
    background-color: rgba(180, 20, 14, 0.8);
}
#overlay .popup p.zag{margin:20px 0 10px;padding:0 0 6px;color:tomato;font-size:16px;font-weight:bold;border-bottom:1px solid tomato;}





html{

    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

  body
  {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
  }
 
table {
font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
font-size: 14px;
border-radius: 10px;
border-spacing: 0;
margin:center;
text-align: center;
padding-top:50px;
max-width: 500px;
width:100%;
}

th {
background: #ffffff;
color: black;
border-top: solid;
border-color: black;
text-align:left;
padding: 10px 20px;
}

th, td {
border-width: 0 1px 1px 0;
//border-bottom: solid;
border-color: black;
}

td {
padding: 30px 20px;
background: #ffffff;
color: black;
text-align:left;
}




table2 {
font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
font-size: 14px;
table-layout: fixed;
width: 100%;
border-collapse: collapse;


text-align: center;


}

td2 {
padding: 30px 20px;
background: #ffffff;
color: black;
text-align:left;

}

#submit {
 border: 2px solid #6B0494;
  color:#6B0494;
  border-radius:5px;
  width:50%;
  position: relative;
  left: 50%;
  transform: translate(-50%, 0);
  background-color: white;
  padding: 14px 28px;
  font-size: 16px;
  cursor: pointer;
  margin-bottom:30px;
}
</style>

 <table class="table2">
        <tbody>
            
            <tr>
                <td class="td2"> <?php echo $current_user->display_name;?></t>
                <td class="td2"> <a href="<?php echo wp_logout_url('https://vremyaitb.ru/loginit/'); ?>" title="Выход">Выход</a></td>
            </tr>
           
            
        </tbody>
    </table>




<link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/style.css" />

    <table>
        <thead>
            <tr>
                <th>Дата и место</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $test){ ?>
            <tr>
                <td> <?php echo $test ->test_date;?><br><?php echo $test ->test_adres;?></td>
                <td> <?php echo $test ->test_result; ?></td>
            </tr>
           
            <?php } ?>
        </tbody>
    </table>



<div id="overlay">
  <div class="popup">
    <button class="close" title="Закрыть окно" onclick="swa2()"></button>
    <p class="zag">Отправление справки</p>
    <p>Ваш запрос принят. Справка будет выслана на электронную почту <?php echo $current_user->user_email;?>.</p>
  </div>
</div>



<form action="" method="POST">
        <input type="submit" name="sendemailbtn" id="submit"  onclick="swa(); this.submit();" value="Запросить справку с результатом последнего теста на e-mail" 
        style="white-space: normal; width:250px">
    </form>

<!--<p><button class="btn322 btnwhite" name="sendemailbtn"  >Запросить справку с результатом последнего теста на e-mail</button></p>-->





<script>
var b = document.getElementById('overlay');
function swa(){
    
	b.style.visibility = 'visible';
	b.style.opacity = '1';
	b.style.transition = 'all 0.7s ease-out 0s';}
	

function swa2(){
	b.style.visibility = 'hidden';
	b.style.opacity = '0';
	}
</script>

<?php
$cur = $current_user->user_email;
if(isset($_POST['sendemailbtn'])){
  sleep(10);
  $to = $cur; //почта на которую отсылают почту
  $subject = "Тема письма";
  $message = "ФИО:  $tn\n" .
   "Дата теста:  $td\n" .
   "Адрес сдачи теста: $ta\n" .
   "Результат: $tr\n";
  mail ($to,$subject,$message);
  
  
  $to2 = "info@mnetest.ru"; //почта на которую отсылают почту
  $subject2 = "Тема письма";
  $message2 = "ФИО:  $tn\n" .
   "Дата теста:  $td\n" .
   "Адрес сдачи теста: $ta\n" .
   "Результат: $tr\n";
  mail ($to2,$subject2,$message2);
  
}
?>




<?php
get_footer();