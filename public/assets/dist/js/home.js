var api = new Api();
$(function() {
    begin.message();
    $(".logout").on('click',function(){
        if(!confirm('确认退出登陆?')) {
            return false;
        }
    })
});

var begin = {
    message : function() {
        api.get("/index/checkLogin",{},function(res){
            if(res.code == 401) {
                swal({
                    title: "身份认证已失效，请重新登录！",
                    type: "warning",
                    showCancelButton: false,
                    closeOnConfirm: false,
                    confirmButtonText: "确认",
                    confirmButtonColor: "#ec6c62"
                });
                window.location.href = '/auth/login';
            }  
        }, function(){
            setTimeout('begin.message()', 3000);
        })
    }
}

function showpic(event, img) {
    if (img.indexOf("http") == -1) {
        img = img
    }
    var left = event.clientX+document.body.scrollLeft+20;
    var top = event.clientY+document.body.scrollTop+20;
    
    $("#showpic").css({left:left,top:top,display:""});
	$('#showpic_img').attr('src', img);
    $('#showpic').show()
}



function hiddenpic(){
    $('#showpic').hide()
}