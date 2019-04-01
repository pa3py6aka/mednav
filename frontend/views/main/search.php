<?php

use yii\helpers\Html;
use core\helpers\TextHelper;

/* @var $this \yii\web\View */

$this->title = 'Поиск по сайту';

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

        <div id="ya-site-results" onclick="return {'tld': 'ru','language': 'ru','encoding': '','htmlcss': '1.x','updatehash': true}"></div><script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0];s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Results.init();})})(window,document,'yandex_site_callbacks');</script>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">

        </div><!-- // right col -->
    </div>
</div>

