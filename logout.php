<!-- watched during development:
 https://youtube.com/playlist?list=PLm8sgxwSZofc_jFRsbTHPAW0Kp52KgAAm&si=yJE-go8ZPSrvP-pI
 https://youtube.com/playlist?list=PLOR5hj0X3WPdOWwU7eCCfFcgIkS1WrDYl&si=_uDfh-nC1HIBcn4u
 https://youtube.com/playlist?list=PL5kIDoSdjG7PY_kPyULbbLk4mpvStqdPR&si=Vxt44Xpmhx7jnkji
 -->

 <!-- https://www.w3schools.com/php/php_sessions.asp -->
<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>