<?php $this->layout('layout', ['webroot' => $webroot, 'assets' => $assets]) ?>

<?php $this->start('headdesc') ?>
    <title>Wilks Score Calculator</title>
    <meta name="description" content="Calculate your weight training Wilks score, used in powerlifting competitions. Bodyweight and age-adjusted. Also calculates allometric and SIFF scores." />
    <meta name="keywords" content="Wilks,score,weight-training,powerlifting,SIFF,allometric" />
<?php $this->stop() ?>


<?php $this->start('main') ?>

    <script type="text/javascript">

        function methodCheck() {
            if (document.getElementById('all').checked) {
                document.getElementById('methodAll').style.display = 'grid';
                document.getElementById('methodSeparate').style.display = 'none';
            } else {
                document.getElementById('methodAll').style.display = 'none';
                document.getElementById('methodSeparate').style.display = 'flex';
            }
        }

    </script>

    <h2>Wilks Score Calculator</h2>
    
    <div class="form-container">
        <div class="form-box">
            <?= $form->render() ?>
        </div>
    </div>

    <?php if ($results): ?>
        <a id="results"></a>
        <h4>Wilks Results</h4>
        <div class="flextable stripe">
            <div class="tr th">
                <div class="td">Method</div>
                <div class="td alignright">Result</div>
                <div class="td alignright">Multiplier</div>
            </div>
            <?php foreach($results as $item): ?>
                <?php if (substr($item->name, 0, strlen("Wilks")) === "Wilks"): ?>
                    <div class="tr"><div class="td"><?=$item->name?></div>
                    <div class="td alignright"><?=number_format($item->value, 2)?></div>
                    <div class="td alignright"><?=number_format($item->extra['mult'], 4)?></div></div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
        <div class="explain">
            <ul>
                <li><strong>Wilks</strong> is your raw Wilks score. The multiplier column shows the multiplier applied to the score for your body weight. The heavier you are, the bigger the penalty against you. This allows light people to compete fairly with heavy people.</li>
                <?php if ($age): ?>
                    <li><strong>Wilks (AGE)</strong> is your age-adjusted Wilks score. People between 23 and 40 get no adjustment because this is considered the age when you are at maximum strength capacity. People outside these ages get an adjustment for being older or younger. The multiplier column shows what your Wilks score is multiplied by to account for your age.</li>
                <?php endif ?>
            </ul>
        </div>

        <?php if ("separate" == $method): ?>
            <h4>Allometric Results</h4>
            <p>See <a href="https://journals.lww.com/nsca-jscr/Abstract/2000/02000/Allometric_Modeling_of_the_Bench_Press_and_Squat_.6.aspx" tarket="_blank">here</a> for more details.</p>
            <?php if ($age): ?>
                <p>Note that there's no formal age adjustment here and I've just used the Wilks age adjustments.</p>
            <?php endif ?>
            <div class="flextable stripe">
                <div class="tr th">
                    <div class="td">Method</div>
                    <div class="td alignright">Result</div>
                    <div class="td alignright">Multiplier</div>
                </div>
                <?php foreach($results as $item): ?>
                    <?php if (substr($item->name, 0, strlen("AM")) === "AM"): ?>
                        <div class="tr"><div class="td"><?=$item->name?></div>
                        <div class="td alignright"><?=number_format($item->value, 2)?></div>
                        <div class="td alignright"><?=number_format($item->extra['mult'], 4)?></div></div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <div class="explain">
                <ul>
                    <li><strong>AM Squat</strong> is an 'allometric' body weight-adjusted measure for your squat.</li>
                    <?php if ($age): ?>
                        <li><strong>AM Squat (AGE)</strong> is an age-adjusted version of above.</li>
                    <?php endif ?>
                    <li><strong>AM Bench</strong> is an 'allometric' body weight-adjusted measure for your bench press.</li>
                    <?php if ($age): ?>
                        <li><strong>AM Bench (AGE)</strong> is an age-adjusted version of above.</li>
                    <?php endif ?>
                    <li><strong>AM Deadlift</strong> is an 'allometric' body weight-adjusted measure for your deadlift. <strong>Note</strong>: the allometric deadlift figure is known to be less reliable than those for the squat and bench press.</li>
                    <?php if ($age): ?>
                        <li><strong>AM Deadlift (AGE)</strong> is an age-adjusted version of above.</li>
                    <?php endif ?>
                </ul>
            </div>
        <?php endif ?>

        <h4>SIFF Results</h4>
        <p>See <a href="http://web.archive.org/web/20050304042306/http://www.sportsci.com/SPORTSCI/JANUARY/evolution_of_bodymass_adjustment.htm" tarket="_blank">here</a> for more details.</p>
        <?php if ($age): ?>
            <p>Note that there's no formal age adjustment here and I've just used the Wilks age adjustments.</p>
        <?php endif ?>
        <div class="flextable stripe">
            <div class="tr th">
                <div class="td">Method</div>
                <div class="td alignright">Result</div>
                <div class="td alignright">Multiplier</div>
            </div>
            <?php foreach($results as $item): ?>
                <?php if (substr($item->name, 0, strlen("SIFF")) === "SIFF"): ?>
                    <?php if (('separate' == $method) and (substr($item->name, 0, strlen("SIFF Total")) === "SIFF Total")): ?>
                        <div class="tr bold"><div class="td"><?=$item->name?></div>
                        <div class="td alignright"><?=number_format($item->value, 2)?></div>
                        <div class="td alignright"><?=number_format($item->extra['mult'], 4)?></div></div>
                    <?php else: ?>
                        <div class="tr"><div class="td"><?=$item->name?></div>
                        <div class="td alignright"><?=number_format($item->value, 2)?></div>
                        <div class="td alignright"><?=number_format($item->extra['mult'], 4)?></div></div>
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
        </div>
        <div class="explain">
            <ul>
                <?php if ("separate" == $method): ?>
                    <li><strong>SIFF Squat</strong> is the SIFF body weight-adjusted measure for your squat.</li>
                    <?php if ($age): ?>
                        <li><strong>SIFF Squat (AGE)</strong> is an age-adjusted version of above.</li>
                    <?php endif ?>
                    <li><strong>SIFF Bench</strong> is the SIFF body weight-adjusted measure for your bench press.</li>
                    <?php if ($age): ?>
                        <li><strong>SIFF Bench (AGE)</strong> is an age-adjusted version of above.</li>
                    <?php endif ?>
                    <li><strong>SIFF Deadlift</strong> is the SIFF body weight-adjusted measure for your deadlift.
                    <?php if ($age): ?>
                        <li><strong>SIFF Deadlift (AGE)</strong> is an age-adjusted version of above.</li>
                    <?php endif ?>
                <?php endif ?>
                <li><strong>SIFF Total</strong> is the SIFF body weight-adjusted measure for your lifts.</li>
                <?php if ($age): ?>
                    <li><strong>SIFF Total (AGE)</strong> is an age-adjusted version of above.</li>
                <?php endif ?>
            </ul>
        </div>
   <?php endif ?>

<?php $this->end() ?>

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
            "name":"Wilks Score Calculator",
            "description":"Calculate your weight training Wilks score, used in powerlifting competitions. Bodyweight and age-adjusted. Also calculates allometric and SIFF scores.",
            "url":"/onerm",
            "isPartOf":{
                "@id":"/#website"
            },
            "lastReviewed":"2021-04-08T08:00:00.000Z"
        },
        {
            "@type":"WebApplication",
            "name":"Wilks Score Calculator",
            "description":"Calculate your weight training Wilks score, used in powerlifting competitions. Bodyweight and age-adjusted. Also calculates allometric and SIFF scores.",
            "applicationCategory":"Productivity",
            "operatingSystem":"Any"
        }
    ]
}
</script>
<?php $this->stop() ?>

