<?php

namespace prodikl;

class Cache {
    /**
     * Saves a NewsArticle to the Cache
     *
     * @param $article  NewsArticle
     */
    public static function saveArticle($article){
        if(!strlen($article->urlToImage)) return;

        $publishedTime = strtotime($article->publishedAt);
        $folderName = date("Y-m", $publishedTime);

        // make folder if doesn't exist
        if(!file_exists(DATA_DIR . $folderName)) mkdir(DATA_DIR . $folderName);
        $folderName = $folderName . '/';

        $fileName = $publishedTime . '-' . $article->getTitleForSaving() . '.txt';
        if(!file_exists(DATA_DIR . $folderName . $fileName)){
            file_put_contents(DATA_DIR . $folderName . $fileName, serialize($article));
        }
    }

    /**
     * Returns an array of NewsArticles
     *
     * @param $numArticles      int         The number of Articles to return
     * @return NewsArticle[]
     */
    public static function getArticles($numArticles){
        /** @var NewsArticle[] $articles */
        $articles = [];

        foreach(scandir(DATA_DIR, SCANDIR_SORT_DESCENDING) as $directory){
            // skip . .. and last updated file
            if(in_array($directory, ['.', '..', 'lastupdated.txt'])) continue;

            foreach(scandir(DATA_DIR . $directory, SCANDIR_SORT_DESCENDING) as $file){
                if(in_array($file, ['.', '..'])) continue;

                $articles[] = unserialize(file_get_contents(DATA_DIR . $directory . '/' . $file));
                if(count($articles) >= $numArticles) return $articles;
            }
        }

        return $articles;
    }
}