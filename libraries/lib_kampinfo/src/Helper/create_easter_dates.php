<?php

foreach (range(2004, 2037) as $year) {
    $goede_vrijdag = \date('d-m-Y', \strtotime('-2 days', \easter_date($year)));
    echo "$year => \"$goede_vrijdag\",\n";
}
