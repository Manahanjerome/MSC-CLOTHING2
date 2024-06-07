<?php
header("Content-Type: application/json");

if (isset($_GET['query'])) {
    $query = strtolower($_GET['query']);
    $xml = simplexml_load_file('data.xml');
    $results = [];

    foreach ($xml->item as $item) {
        if (strpos(strtolower($item->name), $query) !== false || strpos(strtolower($item->description), $query) !== false) {
            $results[] = [
                'name' => (string)$item->name,
                'price' => (string)$item->price,
                'description' => (string)$item->description,
                 'picture' => (string)$item->picture,
                 'id' => uniqid()
            ];
        }
    }

    echo json_encode($results);
} else {
    echo json_encode([]);
}
?>
