<?php $this->layout('layout', ['webroot' => $webroot, 'assets' => $assets]) ?>

<?php $this->start('main') ?>
    <h2>Weight Training Calculations</h2>
    <div>
        <ul>
            <li><a href="/onerm">1-Rep Max Calculator</a>: Calculate your one-rep maximum from the weight you lifted from 2-15 reps.</li>
            <li><a href="/wilks">Wilks Score Calculator</a>: Calculate your Wilks score (also calculates weight-adjusted allometric and SIFF scores).</li>
        </ul>
    </div>
 <?php $this->stop() ?>

 <?php $this->start('schema') ?>
<script type="application/ld+json">
{
     "@context":"https://schema.org",
     "@graph":[
        {
            "@id":"/#publisher",
            "@type":"Organization",
            "name":"Gordy's Discourse",
            "url":"/",
            "logo":{
                "@type":"ImageObject",
                "url":["https://gordonansell.com/assets/images/greenhat-1024x1024.png"]
            }
        },
        {
            "@id":"/#author-gordon-ansell",
            "@type":"Person",
            "name":"Gordon Ansell",
            "url":"https://gordonansell.com/about/",
            "image":{
                "@type":"ImageObject",
                "url":["https://gordonansell.com/assets/images/greenhat-1024x1024.png"]
            }
        },
        {
            "@id":"/#website",
            "@type":"WebSite",
            "name":"Weight Training Calculations",
            "url":"/",
            "description":"Calculate 1-rep maximum, Wilks score, SIFF score and allometric scores. A range of bodyweight and age-adjusted weight training calculations.",
            "image":{
                "@type":"ImageObject",
                "url":["https://gordonansell.com/assets/images/greenhat-1024x1024.png"]
            }
        },
        {
            "@id":"/#webpage",
            "@type":"WebPage",
            "name":"Weight Training Calculations",
            "description":"Calculate 1-rep maximum, Wilks score, SIFF score and allometric scores. A range of bodyweight and age-adjusted weight training calculations.",
            "url":"/",
            "isPartOf":{
                "@id":"/#website"
            },
            "lastReviewed":"2021-04-08T08:00:00.000Z"
        }
    ]
}
</script>
<?php $this->stop() ?>

