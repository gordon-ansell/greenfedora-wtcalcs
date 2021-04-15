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
        <?= $resultsTable->render(); ?>
    <?php endif ?>

    <?php if ($percents): ?>
        <p>The following lists shows the various percentages of your 1-rep maximum. Handy when choosing weights to use for various sets.</p> 
        <?= $percentTable->render(); ?>
    <?php endif ?>

<?php $this->stop() ?>

<?php $this->start('schema') ?>
<script type="application/ld+json">

<?= $schema ?>

</script>
<?php $this->stop() ?>
