<?php
use Shuffle\Evaluator;
$evaluator = new Evaluator();
?>
<!DOCTYPE html>
    <head>
        <title>Result</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <?php foreach ($result as $group => $members): ?>
            グループ:<?php echo $group; ?>
            <table>
                <tr>
                    <td>名前</td><td>部署</td><td>役職</td>
                </tr>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td>
                            <?php echo $member['name']; ?>
                        </td>
                        <td>
                            <?php echo $department_array[$member['department']]['name']; ?>
                            <?php $evaluator->add($member['department']); ?>
                        </td>
                        <td>
                            <?php echo $position_array[$member['position']]['name']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php var_dump($evaluator->calcDensity()); ?>
            <?php $evaluator->clear(); ?>
        <?php endforeach; ?>
        
    </body>
</html>