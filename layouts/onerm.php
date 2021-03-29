<?php $this->layout('layout', ['webroot' => $webroot]) ?>

<?php $this->start('main') ?>
    <h2>1-Rep Max Calculator</h2>
    
    <div class="form-container">
        <div class="form-box">
            <div class="error"><?=$this->e($error)?></div>
            <form action="/onerm" method="POST">

                <div class="three-columns-always">
                    <fieldset>
                        <label for="weight">Weight</label>
                        <input type="number" placeholder="Weight" name="weight" id ="weight" 
                            min="1" max="9999.99" step="any" style="width: 7em;" autofocus required 
                            title="Enter the weight you lifted."
                            value="<?=$this->e($weight)?>" />
                    </fieldset>
                    <fieldset>    
                        <label for="reps">Reps</label>
                        <input type="number" name="reps" id="reps" 
                            min="2" max="15" step="1" style="width: 4em;" required 
                            title="Enter the number of reps you performed (2-15)."
                            value="<?=$this->e($reps)?>" /> 
                    </fieldset>
                    <fieldset>
                        <label for="rounding">Rounding</label>
                        <input type="number" name="rounding" id="rounding" 
                            min="0.01" max="20" step="any" style="width: 5em;" required 
                            title="Enter the rounding value (0.01 - 20). This will typically be twice the smallest weight plate you have."
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
