<?php
// Detect if we're on localhost or live server
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    define('BASE_URL', '/MzanziMarket_Project');
} else {
    define('BASE_URL', ''); // InfinityFree (root domain)
}
?>