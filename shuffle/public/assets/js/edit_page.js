(function(){
    $(function(){
        $('#organization_add_form').submit(function(){
            var $new_organization_name = $('#form_new_organization_name');
            $.ajax({
                url: '/ajax/add_organization.json',
                type: 'post',
                dataType: 'json',
                data: {new_organization_name : $new_organization_name.val()},
                success: function(data){
                    if(data['status'] == true){
                        $new_organization_name.val('');
                        alert('追加しました。');
                        $('#form_organization_select').append($('<option>').html(data['name']).val('id'));
                    }else{
                        alert('追加できませんでした。\n' + data['message']);
                    }
                },
                error: function(data){
                    alert('Ajax Error.')
                }
            })
            return false;
        });
        $('#organization_edit_button').click(function(){
            $('#company_table_div').toggle();
        });
        
        $('#organization_add_button').click(function(){
            $('#organization_add_form_div').toggle();
        });

        $('#view-leaved').change(function(){
            if($(this).prop('checked')){
                $('#staff-table tr').each(function(i){
                    if($('.option-leaved', $(this)).is(':selected')) $(this).show();
                });
            }
            else {
                $('#staff-table tr').each(function(i){
                    if($('.option-leaved', $(this)).is(':selected')) $(this).hide();
                });
            }
        });

        $('#company-column').click(function(){
            var table_array = new Array();
            var count = 0;
            $('#staff-table tr').each(function(i){
                if($(this).is('#definition-row')) return true;
                var $row = $(this);
                $('.company-status option', $(this)).each(function(i){
                    if($(this).is(':selected')){
                        if(typeof table_array[$(this).val()] == "undefined"){
                            table_array[$(this).val()] = new Array();
                        }
                        table_array[$(this).val()].push($row);
                        return true;
                    }
                });
            });
            $('#staff-table').find('tr:gt(0)').remove();
            table_array.forEach(function(element, index){
                element.forEach(function($element, index){
                    $('#staff-table').append('<tr>'+$element.html()+'</tr>');
                });
            });
        });
    });
})();
