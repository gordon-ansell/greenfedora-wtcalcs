<?php $this->layout('layout', ['webroot' => $webroot]) ?>

<h2>1-Rep Max Calculator</h2>

<?php $this->start('main') ?>
    <div class="form-container">
        <div class="form-box">
            <form action="/onerm" method="POST">

                <div class="three-columns-always">
                    <fieldset>
                        <label for="weight">Weight</label>
                        <input type="number" placeholder="Weight" name="weight" id ="weight" 
                            min="1" max="9999.99" step="any" style="width: 7em;" autofocus required 
                            value="<?php echo $weight?>" />
                    </fieldset>
                    <fieldset>    
                        <label for="reps">Reps</label>
                        <input type="number" name="reps" id="reps" 
                            min="2" max="15" step="1" style="width: 4em;" required value="<?php echo $reps?>" /> 
                    </fieldset>
                    <fieldset>
                        <label for="rounding">Rounding</label>
                        <input type="number" name="rounding" id="rounding" 
                            min="0.01" max="20" step="any" style="width: 5em;" required value="<?php echo $rounding?>" /> 
                    </fieldset>
                </div>
                <fieldset>
                    <input type="hidden" name="form_submitted" value="1" />
                    <button type="submit">Submit</button>
                </fieldset>

            </form>
        </div>
    </div>
<?php $this->stop() ?>
