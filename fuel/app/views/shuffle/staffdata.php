<!DOCTYPE html>
    <head>
        <title>Shuffle_data_table</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    </head>
    <body>
        <form action='/shuffle/update_data' method='post'>
            <input type='submit' value='更新'>
            <table>
                <tr>
                    <td>名前</td><td>部署</td><td>役職</td><td>状態</td>
                </tr>
                <?php foreach ($persons as $person): ?>
                    <tr>
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
                            <select name='status_array[<?php echo $person['id']; ?>]'>
                            <option value='true'<?php 
                                echo $person['status'] === 'true' ? ' selected' : '';
                                ?>>True</option>
                            <option value='false'<?php
                                echo $person['status'] === 'false' ? ' selected' : '';
                                ?>>False</option>
                            </select>
                        </td>
                        <td>
                            <input type='checkbox' name='delete_checkbox_array[]' value='<?php echo $person['id']; ?>'>delete
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    </body>
</html>