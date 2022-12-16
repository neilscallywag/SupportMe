<pre>
<?php
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour
    $expiration_str=date('Y-m-d H:i:s e',$expiration_time );
    var_dump($expiration_str);
?>
</pre>