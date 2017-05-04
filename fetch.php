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

NewsArticle::fetchAndSaveArticles();

