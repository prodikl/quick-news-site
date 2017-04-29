<?php
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

$view = new View("views/main.php");
$view->articles = $results;
$view->render();
