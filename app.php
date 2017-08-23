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
    <script type="text/javascript">
    sa_client = "33e8e5ae48377489183beb2c52cdc810";
    sa_code = "039f5e94e15a712b1583a1d532231122";
    sa_protocol = ("https:"==document.location.protocol)?"https":"http";
    sa_pline = "0";
    sa_maxads = "4";
    sa_bgcolor = "FFFFFF";
    sa_bordercolor = "FFFFFF";
    sa_superbordercolor = "E3E3E3";
    sa_linkcolor = "FF0000";
    sa_desccolor = "000000";
    sa_urlcolor = "CA0000";
    sa_b = "0";
    sa_format = "column_160x600";
    sa_width = "160";
    sa_height = "600";
    sa_location = "0";
    sa_radius = "0";
    sa_borderwidth = "1";
    sa_font = "0";
    </script>
    <script type="text/javascript" src="//sa.entireweb.com/sense2.js"></script>
');

// NEWS API settings
define("USE_NEWS_API", true);
define("CATEGORY", "technology");
// newsapi ids in ID|TITLE|URL format. Each source should be then seperated by a comma
define("NEWS_API_IDS", "mtv-news|MTV News|http://www.mtv.com/news");

// XML Feed settings
define("USE_XML_FEEDS", false);
define("FEEDS", "");
