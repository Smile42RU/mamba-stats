<?php

    set_time_limit(0);

    function arrayNormalize(&$item, $key){
        $item = $item['value'].','.$item['cnt'];
    }

    $dbh = new PDO('mysql:host=localhost;port=3306;dbname=mamba', 'root', '', array( PDO::ATTR_PERSISTENT => false));

    $stmt = $dbh->prepare('SELECT value, COUNT(value) as cnt FROM results GROUP BY `value` ORDER BY value');
    $stmt->execute();

    $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_walk($fetch, 'arrayNormalize');

    $fp = fopen('2graph.csv', 'w');

    foreach ($fetch as $line) {
        fputcsv($fp, split(',', $line));
    }

    fclose($fp);

    die();
?>