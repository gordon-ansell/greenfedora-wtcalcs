<?php $this->layout('layout', ['webroot' => $webroot]) ?>

<?php $this->start('main') ?>
    <h2>Weight Training Calculations</h2>
    <div>
        <ul>
            <li><a href="/onerm">1-Rep Max Calculator</a>: Calculate your one-rep maximum from the weight you lifted from 2-15 reps.</li>
            <li><a href="/wilks">Wilks Score Calculator</a>: Calculate your Wilks score.</li>
        </ul>
    </div>
 <?php $this->stop() ?>
