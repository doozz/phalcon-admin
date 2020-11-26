function Api()
{
    this.get = function(path, data, succ, complete, err) {
        this.base(path, 'GET', data, succ, complete, err);
    }

    this.post = function(path, data, succ, complete, err) {
        this.base(path, 'POST', data, succ, complete,'','json');
    }

    this.getHtml = function(path, data, succ, complete, err) {
        this.base(path, 'GET' , data, succ, complete, err, 'html');
    }

    this.base = function(path, method, data, succ, complete, error, dataType) {
        dataType = dataType || 'json';
        $.ajax({
            type: method,
            url: path,
            data: data,
            dataType: dataType,
            success: function(res) {
                if (res.code == 401) {
                    swal({
                       title: "身份认证已失效，请重新登录!",
                       type: "warning",
                       showCancelButton: false,
                       closeOnConfirm: false,
                       confirmButtonText: "确认",
                       confirmButtonColor: "#ec6c62"
                   }, function() {
                       window.location.href = '/auth/login';
                   });
                   return false;
               }
                typeof succ == "function" && succ(res)
            },
            error: function(err) {
                typeof error == "function" && error()
            },
            complete: function() {
                typeof complete == "function" && complete()
            }
        });
    }
}


var api = new Api;
function token() {
    api.post('/auth/token','',function(res){
        var res = res.data;
            $('#token').attr('name',res.key);
            $('#token').val(res.value);
    }) 
}

function alertMsg(type,msg) {
    swal({
        title: msg,
        type: type,
        showCancelButton: false,
        confirmButtonColor: "#18a689",
        confirmButtonText: "确定",
        closeOnConfirm: false,
        showConfirmButton: false,
        timer: 1500
    });
}