var url = './services_chat_backend_jquery.php';
var noerror = true;
var ajax;
var stateDocumentReady=1;
function handleResponse(response)
{
    //$('#content').append('<div>' + response['msg'] + '</div>');
    if(response['status']=="ok" && response['type']=="p"){
        //check session expired
        //if(response['reload']){window.location='?ref=w_dashboard';}
        var chatcox=document.createElement("div"); //main div
        chatcox.className="chatcox";
        chatcox.id="u"+response['timestamp']+"_"+response['dr']+"_"+response['id'];
        var chatpic=document.createElement("div");
        chatpic.className="chatpic";
        var img=document.createElement("img");
        img.src="../media/image/"+response['pic'];
        img.style.width="32px";
        img.style.height="32px";
        chatpic.appendChild(img);
        //chatcox.appnedChild(chatpic);
        var chatname=document.createElement("div");
        chatname.className="chatname"
        var dari=document.createElement("span");
        dari.style.fontWeight="bold";
        dari.style.color="#132F4C";
        dari.innerHTML=response['dari'];
        var time=document.createElement("span");
        time.style.cssFloat="right";
        time.id="timedisplay";
        time.style.color="#cccccc";
        time.innerHTML=response['timedisplay'];
        chatname.appendChild(dari);
        chatname.appendChild(time);
        var chatcontent=document.createElement("div");
        chatcontent.className="chatcontent";
        chatcontent.innerHTML=response['msg'];
        var action=document.createElement("div");
        action.className="action";                    
        var clear=document.createElement("div");
        clear.className="clear";
        var clear2=document.createElement("div");
        clear2.className="clear";
                    
        var post_action=document.createElement("div");
        post_action.className="post_action";
        post_action.appendChild(chatname);
        post_action.appendChild(chatcontent);
        post_action.appendChild(action);
                    
        chatcox.appendChild(chatpic);                    
        chatcox.appendChild(post_action);
        chatcox.appendChild(clear2);
        //alert(chatcox.innerHTML);
        var div=document.createElement("div");
        var ulpostresponse=document.createElement("ul");
        ulpostresponse.className="ulpostresponse";
        var ulContent="<li><i></i></li>";
        ulContent+="<li class='comment input' id='input'>"+
            "<div class='commentcox'>"+
            "<div class='commentpic' style='display:none;'><img src='../media/image/thumbpic.jpg' width='32px' height='32px' /></div>"+
            "<div class='commentname'>"+
            "<span style='font-weight:bold;color:#132F4C;display: none;'>"+
            "</span><span style='color:#333333;'><textarea id='"+response['id']+"' placeholder='write a comment'></textarea></span>"+
            "</div>"+
            "<div class='commentcontent'></div>"+
            "<div class='clear'></div>"+
            "</div>"+
            "</li>";
        ulpostresponse.innerHTML=ulContent;
        div.appendChild(ulpostresponse);
        chatcox.appendChild(div);
        chatcox.appendChild(clear);
        var kotak=document.createElement("div");
        kotak.appendChild(chatcox);
        var current=document.getElementById("content").innerHTML;
        document.getElementById("content").innerHTML=kotak.innerHTML+document.getElementById("content").innerHTML;
        addElEvent($("#"+response['id']));
    }else if(response['status']=="logout"){
        document.location="?";
    }
                
}
function connect()
{
    var post= new Array();
    $('#content').children().each(function(i){
        var p=$(this);
        post[i]=p.attr('id');
        if(i==10){return;}
    });
    //alert(post);
    ajax=$.post(url,{'timestamp':timestamp,'pid':post.toString()},function(data,textStatus,jqXHR){
        try{
            eval('var response = '+data);
            timestamp = response['timestamp'];
            handleResponse(response);
            eval(response['script']);
        }catch(e){}
        if(jqXHR.status!=200){
            //error ato gagal
            //alert(1);
            //setTimeout(function(){ if(stateDocumentReady==1)connect() }, 5000);
        }else{
            //berhasil dengan sukses dan menyenangkan
            //alert(2);
            connect();
        }
    });
                
}

function doRequest(request)
{
    $.ajax(url, {
        type: 'get',
        data: { 'msg' : request }
    }); 
}
function updateDisplayTime(data){
    try{
        eval("var data= "+data);
                    
        for(var i=0;i<data.length;i++){
            $("#"+data[i][0]+" .chatname #timedisplay").html(data[i][1]);
        }
    }catch(e){}
                
}
function updateComment(data,role){
    /*
     *role=0 -> from server
     *role=1 -> user click view all comments
     **/
    if(role==0){
        eval("var data= "+data); 
        var start=0;
        for(var i=0;i<data.length;i++){
            var ul=$("#"+data[i][0]+" .ulpostresponse");
            var strCommentInput="<div class='comment input' id='input'>"+$("#"+data[i][0]+" .ulpostresponse #input").html()+"</div>";
            //var strContent="<li><i></i></li>";
            var strContent="";
            var comments=data[i][1];
            start=(comments.length > 4)?comments.length - 4:0;
            var commentCount=comments.length;
            for(var j=start;j<comments.length;j++){
                var commentTimestamp=comments[j][0];
                var commentIsi=comments[j][1];
                var commentDari=comments[j][2];
                var commentAvatar=comments[j][3];
                strContent+="<li class='comment'>"+
                    "<div class='commentcox'>"+
                    "<div class='commentpic'><img src='../media/image/"+commentAvatar+"' width='32px' height='32px' /></div>"+
                    "<div class='commentname'>"+
                    "<span style='font-weight:bold;color:#132F4C;'>"+commentDari+" "+
                    "</span><span style='color:#333333;'>"+commentIsi+"</span>"+
                    "</div>"+
                    "<div class='commentcontent'>"+commentTimestamp+"</div>"+
                    "<div class='clear'></div>"+
                    "</div>"+
                    "</li>";
            }

            $("#"+data[i][0]+" .ulpostresponse").children( 'li:not(:last)' ).remove();
            $('<li><i></i></li>'+strContent+'').insertBefore("#"+data[i][0]+" .ulpostresponse #input");
            var actionTpl="<button class='like_link stat_elem as_link' type='button' onclick='viewAllComments(this)'>::. View all "+commentCount+" comments .::</button>";
            if(start>0){$("#"+data[i][0]+" .action").html(actionTpl);}
        }
    }else if(role==1){
        eval(data);
        var id=pid.id;
        var strContent="";
        comments=data;
        for(var j=0;j<comments.length;j++){
            var commentTimestamp=comments[j].timestamp;
            var commentIsi=comments[j].pesan;
            var commentDari=comments[j].dari;
            var commentAvatar=comments[j].avatar_file;
            strContent+="<li class='comment'>"+
                "<div class='commentcox'>"+
                "<div class='commentpic'><img src='../media/image/"+commentAvatar+"' width='32px' height='32px' /></div>"+
                "<div class='commentname'>"+
                "<span style='font-weight:bold;color:#132F4C;'>"+commentDari+" "+
                "</span><span style='color:#333333;'>"+commentIsi+"</span>"+
                "</div>"+
                "<div class='commentcontent'>"+commentTimestamp+"</div>"+
                "<div class='clear'></div>"+
                "</div>"+
                "</li>";
        }
        $("#"+id+" .ulpostresponse").children( 'li:not(:last)' ).remove();
        $('<li><i></i></li>'+strContent+'').insertBefore("#"+id+" .ulpostresponse #input");    
        $("#"+id+" .action").html("");
    }

}
function addElEvent($jEl){
    $jEl.bind("keyup",function(){
        var txt=$(this);
        txt.css("height","auto");
    });
    $jEl.bind("blur",function(){
        var txt=$(this);
        txt.css("height","20px");
    });
    $jEl.bind('keydown', function(e) {
        var txt = $(this);
        var key = e.which;
        var str=txt.val();
        if (key == 13 && str.replace(/^\s+|\s+$/g,'')!="") {
            e.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: './services_t_pesan.php',
                data: {'function':'insert','dari':'<?=$_SESSION["id"]?>','kepada':txt.attr('id'),'pesan':txt.val()},
                success: function(){
                    txt.val("");
                },
                error:function(){
                                      
                },
                dataType: 'text'
            });
        }
    });
}
function viewAllComments(obj){
    var btn=obj;
    var pid=btn.parentNode.parentNode.parentNode.id;
    var data=pid.split("_");
    $.ajax({
        type: 'POST',
        url: './services_t_pesan.php',
        data: {'function':'select_comment','datachatid':data[2]},
        success: function(data){
            var script="var pid="+pid+";var data="+data;
            updateComment(script,1);
        },
        error:function(){
                                      
        }
    });
}
function activeEvent(){
    $(".chatcox textarea").bind("keyup",function(){
        var txt=$(this);
        txt.css("height","auto");
    });
    $(".chatcox textarea").bind("blur",function(){
        var txt=$(this);
        txt.css("height","20px");
    });
    $(".chatcox textarea").bind('keydown', function(e) {
        var txt = $(this);
        var key = e.which;
        var str=txt.val();
        if (key == 13 && str.replace(/^\s+|\s+$/g,'')!="") {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: './services_t_pesan.php',
                data: {'function':'insert','dari':'<?=$_SESSION["id"]?>','kepada':txt.attr('id'),'pesan':txt.val()},
                success: function(){
                    txt.val("");
                },
                error:function(){
                                      
                },
                dataType: 'text'
            });
        }
    });
}