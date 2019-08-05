<?php
for($i = 0; $i < 100; $i++) {
    $sql = "DROP TABLE `cat_payment`.`red_packet_log_$i`;";
    print $sql."\n";
}
?>