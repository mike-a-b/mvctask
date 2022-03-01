<?php
// print_r($_GET);
header('Location: '.'/?oauth=sberbank_id' . '&state='.$_GET['state'] . '&code='.$_GET['code']);

?>