<?php
require_once 'DataReader.php';
$reader = new DataReader();
$reader->read();
$persons = $reader->getPersonsArray();
?>
<!DOCTYPE html>
    <head>
        <title>Shuffle_data_table</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    </head>
    <body>
        <table>
            <tr>
                <td>ID</td><td>名前</td><td>部署</td><td>役職</td><td>状態</td>
            </tr>
            <?php foreach ($persons as $id => $person): ?>
                <tr>
                    <td>
                        <?php echo $id; ?>
                    </td>
                    <td>
                        <?php echo $person['name']; ?>
                    </td>
                    <td>
                        <?php echo $person['department']; ?>
                    </td>
                    <td>
                        <?php echo $person['position']; ?>
                    </td>
                    <td>
                        <?php echo $person['status']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>