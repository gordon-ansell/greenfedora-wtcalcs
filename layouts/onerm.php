<?php $this->layout('layout', ['webroot' => $webroot, 'assets' => $assets]) ?>

<?php $this->start('headdesc') ?>
    <title>1-Rep Maximum Calculator</title>
    <meta name="description" content="Calculate your weight training 1-rep maximum from 2-15 reps. Displays the results for many formulae and gives an average. Epley, Brzycki, McGlothin, Lombardi, Mayhew, Wathan, O'Conner." />
    <meta name="keywords" content="1-rep maximum,one-rep maximum,weight-training,Epley,Brzycki,McGlothin,Lombardi,Mayhew,Wathan,O'Conner" />
<?php $this->stop() ?>

<?php $this->start('main') ?>
    <h2>1-Rep Max Calculator</h2>
    
    <div class="form-container">
        <div class="form-box">
            <?= $form->render(); ?>
        </div>
    </div>

    <?php if ($results): ?>
        <div class="table1">
            <?= $resultsTable->render(); ?>
        </div>
    <?php endif ?>

    <?php if ($percents): ?>
        <div class="table2">
            <p>The following lists shows the various percentages of your 1-rep maximum. Handy when choosing weights to use for various sets.</p> 
            <?= $percentTable->render(); ?>
        </div>
    <?php endif ?>

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
            "name":"1-Rep Maximum Calculator",
            "description":"Calculate your weight training 1-rep maximum from 2-15 reps. Displays the results for many formulae and gives an average. Epley, Brzycki, McGlothin, Lombardi, Mayhew, Wathan, O'Conner.",
            "url":"/onerm",
            "isPartOf":{
                "@id":"/#website"
            },
            "lastReviewed":"2021-04-08T08:00:00.000Z"
        },
        {
            "@type":"WebApplication",
            "name":"1-Rep Maximum Calculator",
            "description":"Calculate your weight training 1-rep maximum from 2-15 reps. Displays the results for many formulae and gives an average. Epley, Brzycki, McGlothin, Lombardi, Mayhew, Wathan, O'Conner.",
            "applicationCategory":"Productivity",
            "operatingSystem":"Any"
        }
    ]
}
</script>
<?php $this->stop() ?>
