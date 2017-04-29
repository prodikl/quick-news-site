<!DOCTYPE html>
<html>
<head>
    <title><?= SITE_NAME; ?></title>
    <meta name="Description" content="<?= SITE_DESCRIPTION; ?>">
    <meta name="Keywords" content="<?= SITE_KEYWORDS; ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Raleway|Roboto+Slab" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- START GOOGLE ANALYTICS -->
    <?= GOOGLE_ANALYTICS; ?>
    <!-- END GOOGLE ANALYTICS -->
</head>
<body>
<div id="header">
    <div id="logo"><img src="images/logo.png" /> <?= SITE_NAME; ?></div>
    <p><?= SITE_SUBTITLE; ?></p>
</div>
<?php
/** @var NewsArticle $article */
$article = array_shift($articles);
?>
<div id="hero" style="background-image: url(<?= $article->urlToImage; ?>);">
    <h2>
        <a target="_blank" href="<?= $article->url; ?>"><?= $article->title; ?></a>
    </h2>
    <p>
        <?= $article->description; ?><br />
        <a target="_blank" href="<?= $article->url; ?>">Read More &raquo;</a>
    </p>
</div>
<div class="googleadwide">
    <!-- START GOOGLE AD -->
    <?= GOOGLE_ADSENSE; ?>
    <!-- END GOOGLE AD -->
</div>
<div id="wrapper">
    <ul>
        <?php
        /** @var NewsArticle $article */
        foreach($articles as $article){ ?>
            <li>
                <h2><a target="_blank" href="<?= $article->url; ?>"><?= $article->title; ?></a></h2>
                <a target="_blank" href="<?= $article->url; ?>"><img src="<?= $article->urlToImage; ?>" /></a>
                <p class="source">From: <a target="_blank" href="<?= $article->sourceUrl; ?>"><?= $article->sourceName; ?></a></p>
                <p class="author">By: <?= $article->author; ?></p>
                <p class="description"><?= $article->description; ?></p>
                <a target="_blank" class="readmore" href="<?= $article->url; ?>">Read More &raquo;</a>
                <div class="clear"></div>
            </li>
        <?php } ?>
    </ul>
</div>
<div id="footer">
    <p>&copy; <?php echo date("Y"); ?> <?= SITE_NAME; ?>. All rights reserved.</p>
    <?php if(USE_NEWS_API){ ?>
        <p><a target="_blank" href="http://newsapi.org">Powered by News API</a></p>
    <?php } ?>
</div>
</body>

</html>