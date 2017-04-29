<?php

namespace prodikl;

class NewsArticle {


    public $author, $title, $description, $url, $urlToImage, $publishedAt, $sourceName, $sourceUrl;

    /**
     * Returns articles from the defined sources
     *
     * @return NewsArticle[]
     */
    public static function getArticles(){
        if(static::hoursSinceLastUpdate() > 2) {
            if (USE_NEWS_API) static::getAndSaveArticlesFromApi();
            if (USE_XML_FEEDS) static::getAndSaveArticlesFromXmlFeeds();
            static::updateLastUpdatedTime();
        }
        return static::getArticlesFromCache();
    }

    /**
     *
     */
    protected static function getAndSaveArticlesFromXmlFeeds(){
        $feedUrls = explode(",", FEEDS);
        /** @var NewsArticle[] $newsArticles */
        $newsArticles = [];
        foreach($feedUrls as $feedUrl){
            $feedUrl = trim($feedUrl);
            $newsArticles = array_merge($newsArticles, static::getArticlesFromXmlFeedSource($feedUrl));
        }

        // loop through each item and save to cache
        foreach($newsArticles as $article){
            Cache::saveArticle($article);
        }
    }

    /**
     * @param $feedUrl  string      The URL to the feed XML
     * @return NewsArticle[]
     */
    private static function getArticlesFromXmlFeedSource($feedUrl) {
        $feedXMl = simplexml_load_file($feedUrl);
        $sourceName = (string) $feedXMl->channel->title;
        $sourceUrl = (string) $feedXMl->channel->link;

        if(is_array($feedXMl->channel->item)) {
            $newsArticles = [];
            /** @var SimpleXMLElement $item */
            foreach ($feedXMl->channel->item as $item) {
                $newsArticles[] = static::newFromXmlItem($item, $sourceName, $sourceUrl);
            }
            return $newsArticles;
        } else {
            /** @var SimpleXMLElement $item */
            $item = $feedXMl->channel->item;
            return [static::newFromXmlItem($item, $sourceName, $sourceUrl)];
        }
    }

    /**
     * @param $item             SimpleXMLElement        The XML element with this item (article)'s data
     * @param $sourceUrl        string                  The URL to the source
     * @param $sourceName       string                  The name of the source
     * @return NewsArticle
     */
    private static function newFromXmlItem($item, $sourceName, $sourceUrl) {
        $newsArticle = new NewsArticle();
        $newsArticle->title = (string) $item->title;
        $newsArticle->url = (string) $item->link;
        $newsArticle->author = (string) $item->children("dc", true)->creator;
        $newsArticle->publishedAt = (string) $item->pubDate;
        $newsArticle->description = strip_tags((string) $item->description);
        $newsArticle->sourceName = $sourceName;
        $newsArticle->sourceUrl = $sourceUrl;
        $newsArticle->urlToImage = static::extractImageUrlFromItem($item);

        return $newsArticle;
    }

    /**
     * @param $item      SimpleXMLElement        The Description that may contain the image
     * @return string
     */
    private static function extractImageUrlFromItem($item) {
        if($item->enclosure['url']){
            return (string) $item->enclosure['url'];
        }

        if($result = static::extractImageUrlFromHtmlString((string) $item->children("content", true)->encoded)){
            return $result;
        };

        if($result = static::extractImageUrlFromHtmlString((string) $item->description)){
            return $result;
        };

        return "";
    }

    protected static function extractImageUrlFromHtmlString($string){
        $doc = new DOMDocument();
        @$doc->loadHTML($string);
        $tags = $doc->getElementsByTagName("img");
        foreach($tags as $tag){
            return $tag->getAttribute("src");
        }
        return null;
    }

    /**
     * @return int      The number of hours since last update
     */
    private static function hoursSinceLastUpdate() {
        if(!file_exists(DATA_DIR)) mkdir(DATA_DIR);
        if(!file_exists(DATA_DIR . "lastupdated.txt")){
            static::updateLastUpdatedTime();
            return 99999999; // basically never
        } else {
            $lastUpdated = file_get_contents(DATA_DIR . "lastupdated.txt");
            $secondsSinceLastUpdate = time() - $lastUpdated;
            return $secondsSinceLastUpdate / 3600;
        }
    }

    private static function getAndSaveArticlesFromApi($category = CATEGORY){
        // fetch data from API
        $rawResults = file_get_contents("https://newsapi.org/v1/sources?category=" . $category);
        $results = json_decode($rawResults);

        // parse to article and save momentarily
        /** @var NewsArticle[] $newsArticles */
        $newsArticles = [];
        foreach($results->sources as $result){
            $newsArticles = array_merge($newsArticles, static::getArticlesFromApiSource($result->id, $result->name, $result->url));
        }

        // loop through each item and save to cache
        foreach($newsArticles as $article){
            Cache::saveArticle($article);
        }
    }

    /**
     * @param $numArticles      int         The number of Articles to fetch
     * @return NewsArticle[]
     */
    private static function getArticlesFromCache($numArticles = NUM_ARTICLES_PER_PAGE){
        return Cache::getArticles($numArticles);
    }

    /**
     * Updates the "lastupdated.txt" file with the current timestamp
     */
    private static function updateLastUpdatedTime(){
        file_put_contents(DATA_DIR . "lastupdated.txt", time());
    }

    /**
     * @param $sourceId         string      The "ID" of the source. Which is just the source name all lower case
     * @param $sourceName       string      The Source Name to display publisher
     * @param $sourceUrl        string      The Source URL to get back to the Publisher
     *
     * @return NewsArticle[]
     */
    protected static function getArticlesFromApiSource($sourceId, $sourceName, $sourceUrl){
        $rawResults = file_get_contents("https://newsapi.org/v1/articles?source=". $sourceId ."&apiKey=" . NEWS_API_KEY);
        return static::newFromApiRawResults($rawResults, $sourceName, $sourceUrl);
    }

    /**
     * @param $rawResults       string      The Raw result from News API
     * @param $sourceName       string      The Source Name to display publisher
     * @param $sourceUrl        string      The Source URL to get back to the Publisher
     *
     * @return NewsArticle[]
     */
    private static function newFromApiRawResults($rawResults, $sourceName, $sourceUrl) {
        $results = json_decode($rawResults);

        $output = [];
        foreach($results->articles as $article){
            $news = new NewsArticle();
            $news->url = $article->url;
            $news->author = $article->author;
            $news->title = $article->title;
            $news->description = $article->description;
            $news->urlToImage = $article->urlToImage;
            $news->publishedAt = $article->publishedAt;
            $news->sourceName = $sourceName;
            $news->sourceUrl = $sourceUrl;
            $output[] = $news;
        }
        return $output;
    }

    /**
     * Returns a file system-safe version of this Article's title
     *
     * @return string
     */
    public function getTitleForSaving() {
        return static::cleanStringForSaving($this->title);
    }

    /**
     * Returns a string after converting it into alphanumeric plus hyphens and lower case
     *
     * @param $string      string      The string to convert
     * @return string               The clean string
     */
    protected static function cleanStringForSaving($string) {
        // Remove any character that is not alphanumeric, white-space, or a hyphen
        $string = preg_replace("/[^a-z0-9\s\-]/i", "", $string);
        // Replace multiple instances of white-space with a single space
        $string = preg_replace("/\s\s+/", " ", $string);
        // Replace all spaces with hyphens
        $string = preg_replace("/\s/", "-", $string);
        // Replace multiple hyphens with a single hyphen
        $string = preg_replace("/\-\-+/", "-", $string);
        // Remove leading and trailing hyphens
        $string = trim($string, "-");
        // Lowercase the URL
        $string = strtolower($string);

        if (strlen($string) > 80)
        {
            // Cut the URL after 80 characters
            $string = substr($string, 0, 80);

            if (strpos(substr($string, -20), '-') !== false)
            {
                // Cut the URL before the last hyphen, if there is one
                $string = substr($string, 0, strrpos($string, '-'));
            }
        }
        return $string;
    }
}