<?php $this->layout('layout', ['webroot' => $webroot]) ?>

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
            <div class="error"><?=$this->e($error)?></div>
            <form action="/wilks#results" method="POST">
                <div class="two-columns-always">
                    <fieldset class="radio horizontal" title="Select your gender.">
                        <span class="label">Gender</span>
                        <input type="radio" id="male" name="gender" value="male" 
                            <?php if ("male" == $gender): ?>checked<?php endif ?> />
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="female" 
                            <?php if ("female" == $gender): ?>checked<?php endif ?> />
                        <label for="female">Female</label>
                    </fieldset>
                    <fieldset>
                        <label for="age" class="newline">Age</label>
                        <input class="runon" type="text" name="age" id ="age" 
                            <?php if ('age' == $af):?>autofocus<?php endif ?>
                            value="<?=$this->e($age)?>" 
                            title="If you want to see the adjustment for your age, enter your age here."
                            />
                    </fieldset>
                </div>
                <div class="two-columns-always">
                    <fieldset>
                        <label for="bodyWeight">Body Weight</label>
                        <input type="text" name="bodyWeight" id ="bodyWeight" 
                            title="Enter your body weight."
                            <?php if ('bodyWeight' == $af):?>autofocus<?php endif ?>
                            value="<?=$this->e($bodyWeight)?>" />
                    </fieldset>
                    <fieldset>
                        <label for="bodyWeightUnits">Units</label>
                        <select name="bodyWeightUnits" id="bodyWeightUnits">
                            <option value="kg" <?php if ('kg' == $bodyWeightUnits): ?>selected<?php endif ?>>kg</option>
                            <option value="lb" <?php if ('lb' == $bodyWeightUnits): ?>selected<?php endif ?>>lb</option>
                        </select><span class="caret">&#9660;</span>
                    </fieldset>
                </div>
                <fieldset class="radio horizontal" title="Select calculation method.">
                    <span class="label">Calculation Method</span>
                    <input type="radio" id="all" name="method" value="all" onclick="javascript:methodCheck();"
                        <?php if ("all" == $method): ?>checked<?php endif ?> />
                    <label for="LL">All Together</label>
                    <input type="radio" id="separate" name="method" value="separate" onclick="javascript:methodCheck();"
                        <?php if ("separate" == $method): ?>checked<?php endif ?> />
                    <label for="female">Separate Lifts</label>
                </fieldset>
                <div class="two-columns-always" id="methodAll" 
                    <?php if ('separate' == $method):?>style="display:none"<?php endif ?> >
                    <fieldset>
                        <label for="weight">Total Weight Lifted</label>
                        <input type="text" name="weight" id ="weight" 
                            value="<?=$this->e($weight)?>" 
                            <?php if ('weight' == $af):?>autofocus<?php endif ?>
                            title="This can be for an invididual lift, but a true Wilks score is based on your combined squat, bench press and deadlift weights." />
                    </fieldset>
                    <fieldset>
                        <label for="weightUnits">Units</label>
                        <select name="weightUnits" id="weightUnits">
                            <option value="kg" <?php if ('kg' == $weightUnits): ?>selected<?php endif ?>>kg</option>
                            <option value="lb" <?php if ('lb' == $weightUnits): ?>selected<?php endif ?>>lb</option>
                        </select><span class="caret">&#9660;</span>
                    </fieldset>
                </div>
                <span id = "methodSeparate" <?php if ('all' == $method):?>style="display:none"<?php endif ?>>
                    <div class="two-columns-always">
                        <fieldset>
                            <label for="squat">Squat</label>
                            <input type="text" name="squat" id ="squat" 
                                <?php if ('squat' == $af):?>autofocus<?php endif ?>
                                value="<?=$this->e($squat)?>" 
                                title="The weight you lifted for the squat." />
                        </fieldset>
                        <fieldset>
                            <label for="squatUnits">Units</label>
                            <select name="squatUnits" id="squatUnits">
                                <option value="kg" <?php if ('kg' == $squatUnits): ?>selected<?php endif ?>>kg</option>
                                <option value="lb" <?php if ('lb' == $squatUnits): ?>selected<?php endif ?>>lb</option>
                            </select><span class="caret">&#9660;</span>
                        </fieldset>
                    </div>
                    <div class="two-columns-always">
                        <fieldset>
                            <label for="bench">Bench Press</label>
                            <input type="text" name="bench" id ="bench" 
                                <?php if ('bench' == $af):?>autofocus<?php endif ?>
                                value="<?=$this->e($bench)?>" 
                                title="The weight you lifted for the bench press." />
                        </fieldset>
                        <fieldset>
                            <label for="benchUnits">Units</label>
                            <select name="benchUnits" id="benchUnits">
                                <option value="kg" <?php if ('kg' == $benchUnits): ?>selected<?php endif ?>>kg</option>
                                <option value="lb" <?php if ('lb' == $benchUnits): ?>selected<?php endif ?>>lb</option>
                            </select><span class="caret">&#9660;</span>
                        </fieldset>
                    </div>
                    <div class="two-columns-always">
                        <fieldset>
                            <label for="dead">Deadlift</label>
                            <input type="text" name="dead" id ="dead" 
                                <?php if ('dead' == $af):?>autofocus<?php endif ?>
                                value="<?=$this->e($dead)?>" 
                                title="The weight you lifted for the deadlift." />
                        </fieldset>
                        <fieldset>
                            <label for="deadUnits">Units</label>
                            <select name="deadUnits" id="deadUnits">
                                <option value="kg" <?php if ('kg' == $deadUnits): ?>selected<?php endif ?>>kg</option>
                                <option value="lb" <?php if ('lb' == $deadUnits): ?>selected<?php endif ?>>lb</option>
                            </select><span class="caret">&#9660;</span>
                        </fieldset>
                    </div>
                </span>
                <fieldset>
                    <input type="hidden" name="form_submitted" value="1" />
                    <button type="submit">Submit</button>
                </fieldset>
            </form>
        </div>
    </div>

    <?php if ($results): ?>
        <a id="results"></a>
        <div class="flextable stripe">
            <div class="tr th">
                <div class="td">Method</div>
                <div class="td alignright">Result</div>
                <div class="td alignright">Multiplier</div>
            </div>
            <?php foreach($results as $item): ?>
                <div class="tr"><div class="td"><?=$item->name?></div>
                <div class="td alignright"><?=number_format($item->value, 2)?></div>
                <div class="td alignright"><?=number_format($item->extra['mult'], 2)?></div></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

<?php $this->end() ?>
