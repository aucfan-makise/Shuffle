<!DOCTYPE html>
<html>
    <head>
        <title>Shuffle_data_table</title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <?php echo Asset::css('edit_page.css')?>
        <?php echo Asset::js('edit_page.js'); ?>
    </head>
    <body>
		<?php echo $organization_form; ?>

        <input type="checkbox" id="view-leaved" value="1" checked="checked">退社済みの人を表示する。
        <form action='/shuffle/update_data' method='post'>
            <input type='submit' value='更新'>
            <table id="staff-table">
                <tr id="definition-row">
                    <td>名前</td><td><a id="company-column">社名</a></td><td>部署</td><td>役職</td><td>性別</td><td>状態</td>
                </tr>
                <?php foreach ($persons as $person): ?>
                    <tr>
                        <td>
                            <?php echo $person['name']; ?>
                        </td>
                        <td>
                            <select class="company-status" name="company_status_array[<?php echo $person['id']; ?>]">
                                <?php foreach ($company as $key => $name): ?>
                                    <option value="<?php echo $key; ?>"
                                        <?php echo $person['company'] == $key ? ' selected' : ''?>>
                                        <?php echo $name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                            <select class="status" name='status_array[<?php echo $person['id']; ?>]'>
                            <option value='presence'<?php 
                                echo $person['status'] === 'presence' ? ' selected' : '';
                                ?>>出席</option>
                            <option value='absence'<?php
                                echo $person['status'] === 'absence' ? ' selected' : '';
                                ?>>欠席</option>
                            <option class='option-leaved' value='leaved'<?php
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