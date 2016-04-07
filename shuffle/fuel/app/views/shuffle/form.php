<!-- 組織編集フォーム -->
<button id="organization_edit_button">組織編集</button>

<div id="company_table_div" style="display:none">
    <table>
	    <tr>
		    <td>
			    <?php echo Form::open('/test/test'); ?>
				    <?php echo $organization_form
					    ->field('organization_select')
					    ->set_template("\n{label}\n{field}\n");
				    ?>
			    <?php echo Form::close(); ?>
			</td>
			<td>
			    <a id="organization_add_button">+</a>
			</td>
	    </tr>
    </table>
</div>

<div id="organization_add_form_div" style="display:none">
	<?php echo Form::open(array('id' => 'organization_add_form')); ?>
		<?php echo $organization_form
			->field('new_organization_name')
			->set_template("\n\t{field}\n");
		?>
		<?php echo $organization_form
			->field('submit')
			->set_template("\t{field}\n");
		?>
	<?php echo Form::close(); ?>
</div>

<!-- メンバー追加フォーム -->
<?php echo Form::open('/shuffle/add_member'); ?>

    <table>
	    <tr>
		    <td>名前</td><td>社名</td><td>部署</td><td>役職</td><td>性別</td><td>状態</td>
	    </tr>
		<tr>
		    <td>
			    <?php echo Form::input('name', '', array()); ?>
			</td>
			<td>
				<?php echo Form::select('company', 'none', $organization_array); ?>
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
	<?php echo Form::submit('add', 'メンバー追加'); ?>
<?php echo Form::close(); ?>