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
                document.getElementById('methodSeparate').style.display = 'inline';
            }
        }

    </script>

    <h2>Wilks Score Calculator</h2>
    
    <div class="form-container">
        <div class="form-box">
            <?= $form->render() ?>
        </div>
    </div>

    <?php if ($resultsWilks): ?>
        <a id="results"></a>

        <h4>Wilks Results</h4>
        <?= $wilksTable->render(); ?>

        <div class="explain">
            <ul>
                <?php if ("separate" == $method): ?>
                    <li><strong>Squat, Bench and Dead</strong> are the pseudo Wilks scores for your individual lifts.</li>
                    <?php if ($age): ?>
                        <li><strong>Squat/Age, Bench/Age, Dead/Age</strong> are age-adjusted versions of the above.</li>
                    <?php endif ?>
                <?php endif ?>
                <li><strong>Wilks</strong> is your raw Wilks score. The multiplier column shows the multiplier applied to the score for your body weight. The heavier you are, the bigger the penalty against you. This allows light people to compete fairly with heavy people.</li>
                <?php if ($age): ?>
                    <li><strong>Wilks/Age</strong> is your age-adjusted Wilks score. People between 23 and 40 get no adjustment because this is considered the age when you are at maximum strength capacity. People outside these ages get an adjustment for being older or younger. The multiplier column shows what your Wilks score is multiplied by to account for your age.</li>
                <?php endif ?>
            </ul>
        </div>

    <?php endif ?>

    <?php if ($resultsAllometric): ?>

        <h4>Allometric Results</h4>
        <p>See <a href="https://journals.lww.com/nsca-jscr/Abstract/2000/02000/Allometric_Modeling_of_the_Bench_Press_and_Squat_.6.aspx" tarket="_blank">here</a> for more details.</p>
        <?php if ($age): ?>
            <p>Note that there's no formal age adjustment here and I've just used the Wilks age adjustments.</p>
        <?php endif ?>

        <?= $alloTable->render(); ?>

        <div class="explain">
            <ul>
                <?php if ("separate" == $method): ?>
                    <li><strong>Squat, Bench and Dead</strong> are the allometric bodyweight-adjusted scores for your individual lifts. <strong>Note</strong>: the allometric deadlift figure is known to be less reliable than those for the squat and bench press.</li>
                    <?php if ($age): ?>
                        <li><strong>Squat/Age, Bench/Age, Dead/Age</strong> are age-adjusted versions of the above.</li>
                    <?php endif ?>
                <?php endif ?>
                <li><strong>Total</strong> is an allometric body weight-adjusted measure for the total. There isn't really an official calculation of this, so I've just averaged them.</li>
                <?php if ($age): ?>
                    <li><strong>Total/Age</strong> is an age-adjusted version of above.</li>
                <?php endif ?>
            </ul>
        </div>

    <?php endif ?>

    <?php if ($resultsSiff): ?>

        <h4>SIFF Results</h4>
        <p>See <a href="http://web.archive.org/web/20050304042306/http://www.sportsci.com/SPORTSCI/JANUARY/evolution_of_bodymass_adjustment.htm" tarket="_blank">here</a> for more details.</p>
        <?php if ($age): ?>
            <p>Note that there's no formal age adjustment here and I've just used the Wilks age adjustments.</p>
        <?php endif ?>

        <?= $siffTable->render(); ?>

        <div class="explain">
            <ul>
                <?php if ("separate" == $method): ?>
                    <li><strong>Squat, Bench and Dead</strong> are the SIFF bodyweight-adjusted scores for your individual lifts.</li>
                    <?php if ($age): ?>
                        <li><strong>Squat/Age, Bench/Age and Dead/Age</strong> are age-adjusted versions of the above.</li>
                    <?php endif ?>
                <?php endif ?>
                <li><strong>Total</strong> is the SIFF body weight-adjusted measure for your lifts.</li>
                <?php if ($age): ?>
                    <li><strong>Total/Age</strong> is an age-adjusted version of above.</li>
                <?php endif ?>
            </ul>
        </div>
   <?php endif ?>

<?php $this->end() ?>

<?php $this->start('schema') ?>

<script type="application/ld+json">

<?= $schema ?>

</script>

<?php $this->stop() ?>

