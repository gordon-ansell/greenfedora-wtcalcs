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
                <p>Not that there's no formal age adjustment here and I've just used the Wilks age adjustments.</p>
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
            <p>Not that there's no formal age adjustment here and I've just used the Wilks age adjustments.</p>
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
