<?php
session_start();
session_destroy();
setcookie("usuario","",time()-3600);
setcookie("id_usuario","",time()-3600);
echo "<script>window.location='index.php'</script>";
?>