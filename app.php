<?php
require("vendor/autoload.php");

// CORE constants
define("NEWS_API_KEY", "84313d0f49be45adafac4a4071c6beb4");
define("DATA_DIR", __DIR__ . "/data/");
define("MAX_DESC_LENGTH", 1000);

// SITE constants
define("NUM_ARTICLES_PER_PAGE", 20);
define("SITE_NAME", "Techworld TODAY");
define("SITE_SUBTITLE", "Up to date technology news");
define("SITE_DESCRIPTION", "Up to date news about technology, electronics, and gadgets");
define("SITE_KEYWORDS", "tech, technology, electronics, gadgets, news");
define("GOOGLE_ANALYTICS", "
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-67544959-6', 'auto');
        ga('send', 'pageview');

    </script>
");
define("GOOGLE_ADSENSE", '
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- techworld.today - header -->
        <ins class="adsbygoogle"
             style="display:inline-block;width:728px;height:90px"
             data-ad-client="ca-pub-6908363659501977"
             data-ad-slot="6958762048"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
');

// NEWS API settings
define("USE_NEWS_API", true);
define("CATEGORY", "technology");
// newsapi ids in ID|TITLE|URL format. Each source should be then seperated by a comma
define("NEWS_API_IDS", "mtv-news|MTV News|http://www.mtv.com/news");

// XML Feed settings
define("USE_XML_FEEDS", false);
define("FEEDS", "");
