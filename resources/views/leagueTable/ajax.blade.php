<div class="container-fluid">
    <div class="row">


<div class="col-sm">
    <h3>League Table</h3>
    <table class="table" id="league">
        <tr>
            <td>name</td>
            <td>played</td>
            <td>sum_goals</td>
            <td>sum_win</td>
            <td>sum_drawn</td>
            <td>sum_lost</td>
            <td>sum_gd</td>
            <td>sum_pts</td>
        </tr>
        <?php foreach ($data as $row) {?>
            <tr>
                <td><?=$row->name?></td>
                <td><?=$row->played?></td>
                <td><?=$row->sum_goals?></td>
                <td><?=$row->sum_win?></td>
                <td><?=$row->sum_drawn?></td>
                <td><?=$row->sum_lost?></td>
                <td><?=$row->sum_gd?></td>
                <td><?=$row->sum_pts?></td>
            </tr>
        <?php } ?>

    </table>

    <?php if ($currentWeek < 6) {?>
        <button id="playAll">playAll</button>
        <button id="next">next</button>
    <?php } ?>
    <?php if ($currentWeek == 6) {?>
        <button id="new">prepare new tournament</button>
    <?php } ?>
</div>

<div class="col-sm">
    <?php foreach ($grouppedMatches as $key => $res) {?>
        <h3><?=$key?><sup>th</sup> Week Match Result</h3>
        <table class="table">
        <?php
        $goalsToCommands = [];
            foreach ($res as $row) {
                foreach ($row->info as $info) {
                    $goalsToCommands[$info->command] = $info->goals;
                }
                ?>
                <tr>
                <td><?=$row->commands1->name?></td>
                <td><?php echo $goalsToCommands[$row->command1] . ' : ' . $goalsToCommands[$row->command2]?></td>
                <td><?=$row->commands2->name?></td>
                </tr>
            <?php
        }
        ?>

        </table>
    <?php } ?>
</div>

<?php if ($resultsForPrediction) {?>
    <div class="col-sm">
    <h3><?=$currentWeek?><sup>th</sup> Week Prediction</h3>
    <table class="table">
        <?php foreach ($resultsForPrediction as $key => $row) {?>
            <tr>
                <td><?=$key?></td>
                <td><?=$row['res']?> %</td>
            </tr>
        <?php } ?>
    </table>
    </div>
<?php } ?>

</div>
</div>
