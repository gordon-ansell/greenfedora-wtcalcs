<!doctype html>
<html lang="en">

<head>
    <!-- Meta setup -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8"/>
    <!-- Always force latest IE rendering engine (even in intranet) -->
    <!--[if IE ]><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta http-equiv="cleartype" content="on" /><![endif]-->
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <?php $this->insert('includes/colours') ?>

    <link rel="stylesheet" href="<?=$this->e($webroot)?>css/app.css" />

    <title><?=$this->e($title)?></title>
</head>

<body>
    <div class="wrapper">
        <header class="sitehdr">
            <span class="sitehdr-branding">
                <a href="https://gordonansell.com" title="Go to the host site, Gordy's Discourse.">
                    <img src="<?=$this->e($webroot)?>images/greenhat-60x60.png" alt="Logo for Gordy's Discourse site." />
                    <span>Gordy's Discourse</span>
                </a>
            </span>
            <nav class="sitehdr-menu">
                <input type="checkbox" id="menu-btn" />
                <label for="menu-btn"><span class="icon"></span></label>
                <ul>
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        <a href="/onerm">One Rep Maximum</a>
                    </li>
                    <li>
                        <a href="wilks">Wilks Score</a>
                    </li>
                </ul>
            </nav>
        </header>
        <main>
            <?=$this->section('main');?>
        </main>
    </div>
</body>

</html>