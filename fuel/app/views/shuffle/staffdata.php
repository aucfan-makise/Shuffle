<!DOCTYPE html>
    <head>
        <title>Shuffle_data_table</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    </head>
    <body>
        <?php echo Form::open('/shuffle/add_member'); ?>
        <table>
            <tr>
                <td>名前</td><td>部署</td><td>役職</td><td>性別</td><td>状態</td>
            </tr>
            <tr>
                <td>
                    <?php echo Form::input('name', '', array()); ?>
                </td>
                <td>
                    <?php echo Form::select('department', 'none', $department_form_options); ?>
                </td>
                <td>
                    <?php echo Form::select('position', 'none', $position_form_options); ?>
                </td>
                <td>
                    <?php echo Form::select('sex', 'none', $sex_form_options); ?>
                </td>
                <td>
                    <?php echo Form::select('status', 'none', $status_form_options); ?>
                </td>
            </tr>
        </table>
            <?php echo Form::submit('add', 'add'); ?>
        <?php echo Form::close(); ?>
        <form action='/shuffle/update_data' method='post'>
            <input type='submit' value='更新'>
            <table>
                <tr>
                    <td>名前</td><td>部署</td><td>役職</td><td>性別</td><td>状態</td>
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
                            <?php echo $person['sex']; ?>
                        </td>
                        <td>
                            <select name='status_array[<?php echo $person['id']; ?>]'>
                            <option value='presence'<?php 
                                echo $person['status'] === 'presence' ? ' selected' : '';
                                ?>>出席</option>
                            <option value='absence'<?php
                                echo $person['status'] === 'absence' ? ' selected' : '';
                                ?>>欠席</option>
                            <option value='leaved'<?php
                                echo $person['status'] === 'leaved' ? ' selected' : '';
                                ?>>退社</option>
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