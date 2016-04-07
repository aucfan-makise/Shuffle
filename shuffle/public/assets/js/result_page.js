(function(){
    $(function(){
        $('#save_button').click(function(){
            $.ajax({
                url: '/ajax/result_save.json',
                type: 'post',
                dataType : 'json',
                data: {result : cookie.get('result')},
                success: function(data){
                    if(data['status'] == true){
                        alert('保存しました。');
                    }else{
                        alert('保存できませんでした。\n' + data['message']);
                    }
                },
                error: function(data){
                    alert('Ajax Error.');
                }
            })
        });
    });
    var cookie = {
            get:function(n, m){
                return (m = ('; ' + document.cookie + ';').match('; ' + n + '=(.*?);')) ? decodeURIComponent(m[1]) : ''
            }
    }
})();