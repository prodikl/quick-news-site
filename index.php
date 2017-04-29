<?php
use prodikl\NewsArticle;
use prodikl\View;

/**
 * Tasks
 * - DONE Caching
 * - Cloudflare
 * - Pagination
 * - Seperate out CSS core / site
 */

require("app.php");

$results = NewsArticle::getArticles();

$view = new View("vendor/prodikl/world-today/views/main.php");
$view->articles = $results;
$view->render();
