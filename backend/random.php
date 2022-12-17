<pre>
<?php
    require_once "Model/DAO/SessionDAO.php";

    $DAO = new SessionDAO();
    var_dump($DAO->add_session(1,'Firefox','test','2022-12-30 21:40:56'));

?>
</pre>