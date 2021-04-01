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
            <div class="error"><?=$this->e($error)?></div>
            <form action="/onerm" method="POST">

                <div class="three-columns-always">
                    <fieldset>
                        <label for="weight">Weight</label>
                        <input type="text" placeholder="Weight" name="weight" id ="weight" 
                            title="Enter the weight you lifted (5-9999.99)."
                            <?php if ('weight' == $af):?>autofocus<?php endif ?>
                            value="<?=$this->e($weight)?>" />
                    </fieldset>
                    <fieldset>    
                        <label for="reps">Reps</label>
                        <input type="text" name="reps" id="reps" style="width: 4em;"
                            title="Enter the number of reps you performed (2-15)."
                            <?php if ('reps' == $af):?>autofocus<?php endif ?>
                            value="<?=$this->e($reps)?>" /> 
                    </fieldset>
                    <fieldset>
                        <label for="rounding">Rounding</label>
                        <input type="text" name="rounding" id="rounding" style="width: 5em;" 
                            title="Enter the rounding value (0.01 - 20). This will typically be twice the smallest weight plate you have."
                            <?php if ('rounding' == $af):?>autofocus<?php endif ?>
                            value="<?=$this->e($rounding)?>" /> 
                    </fieldset>
                </div>
                <fieldset>
                    <input type="hidden" name="form_submitted" value="1" />
                    <button type="submit">Submit</button>
                </fieldset>

            </form>
        </div>
    </div>

    <?php if ($results): ?>
        <div class="flextable stripe">
            <div class="tr th">
                <div class="td">Method</div>
                <div class="td alignright">Result (to nearest <?=$rounding?>)</div>
            </div>
            <?php foreach($results as $item): ?>
                <div class="tr"><div class="td"><?=$item->name?></div>
                <div class="td alignright"><?=number_format($item->rounded, 2)?></div></div>
            <?php endforeach ?>
            <div class="tr bold"><div class="td">Average</div>
            <div class="td alignright"><?=number_format($average->rounded, 2)?></div>
        </div>
    <?php endif ?>

 <?php $this->stop() ?>
