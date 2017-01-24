@include('admin/static/header')
<div id="wrapper">
    @include('admin/static/leftside')

    <div class="content-r">
        <div class="table-box">
            <div class="search-box">
                <div class = "table_tit" style="float: left;padding: 15px;"><h1>{{$navigation}}</h1></div>
            </div>
            <div style="width:700px;margin: 0 auto;border-left: 1px solid #ddd; " class="table-con">
                <table class ="prodict_edit">
                    <tbody
                    <tr>
                        <td class="tr"> 新密码 :</td>
                        <td class="tl"><input type="password" id="new_pass" name="new_pass" value="" /></td>
                    </tr>
                    <tr>
                        <td class="tr"> 密码确认 :</td>
                        <td class="tl"><input type="password" id="new_pass_confirm" name="new_pass_confirm" value="" /></td>
                    </tr>
                    <tr>
                        <th colspan = "2" >
                            <button type="button" id="passwd_submit" class="btn btn-success" >确认提交</button>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin/static/footer')
</div>
</body>
<script>
    $(function() {
        $('#passwd_submit').bind('click', function(){
            if (false != check_submit_data()) {
                $.ajax({
                    type:"post",
                    dataType:"json",
                    url: "{{url('pass/modify')}}",
                    data:{
                        'new_pass':$('#new_pass').val(),
                        'new_pass_confirm':$('#new_pass_confirm').val()
                    },
                    async:false,
                    success:function($data) {
                        if ($data.status == 'error') {
                            alert($data.info);
                        } else {
                            if (confirm('密码修改成功, 请重新登录')) {
                                window.location.href = "{{url('logout')}}/";
                            }
                        }
                    }
                });
            }
        });
    });

    function check_submit_data(){
        var new_pass = $('#new_pass').val().trim();
        var new_pass_confirm = $('#new_pass_confirm').val().trim();

        if (new_pass.length==0 || new_pass == ''){
            alert('请填写新密码');
            return false;
        }
        if (new_pass_confirm.length==0 || new_pass_confirm == ''){
            alert('请填写新密码确认');
            return false;
        }
        if (new_pass!=new_pass_confirm){
            alert('两次输入的密码不一致');
            return false;
        }
        return true;
    }
</script>