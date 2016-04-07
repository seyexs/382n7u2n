
<?php
$model = $settings['model'];
$userModel = User::model()->findByPk(Yii::app()->user->id);
$is_pegawai=  TUserPegawaiSiswa::model()->count(array(
    'condition'=>'user_id=:u and is_pegawai_siswa=1',
    'params'=>array(':u'=>Yii::app()->user->id)
));

if($is_pegawai>0){
$r_pesan = Yii::app()->db->createCommand()
        ->select('t.id,t.kepada,t.timestamp,t.pesan,t.image_post,t.time,t.dari as dr,u.displayname as dari,u.avatar_file')
        ->from('t_pesan t')
        ->join('user u', 'u.id=t.dari')
        ->join('t_user_pegawai_siswa ups','t.dari=ups.user_id')
        ->where('t.kepada=0 and u.id=t.dari and ups.is_pegawai_siswa=1')
        ->order('t.timestamp desc')
        ->limit('20')
        ->queryAll();
}else{
    $r_pesan = Yii::app()->db->createCommand()
        ->select('t.id,t.kepada,t.timestamp,t.pesan,t.image_post,t.time,t.dari as dr,u.displayname as dari,u.avatar_file')
        ->from('t_pesan t')
        ->join('user u', 'u.id=t.dari')
        ->join('t_user_pegawai_siswa ups','t.dari=ups.user_id')
        ->where('t.kepada=0 and u.id=t.dari and ups.is_pegawai_siswa=0')
        ->order('t.timestamp desc')
        ->limit('20')
        ->queryAll();
    
}

function timestamp($time) {
    $timestamp = $time; // Get the timestamp for the post update.
    // Calculate how long it's been since the status was updated (relative).
    $currenttime = time();
    $delta = $currenttime - $timestamp;

    // Display how long it's been since the last update.
    $timestampdisplay = " ";

    // Show days if it's been more than a day.
    if (floor($delta / 84600) > 0) {
        $timestampdisplay .= floor($delta / 84600);
        if (floor($delta / 84600) == 1) {
            $timestampdisplay .= ' day, ';
        } else {
            $timestampdisplay .= ' days, ';
        }
        $delta -= 84600 * floor($delta / 84600);
    }

    // Show hours if it's been more than an hour.
    if (floor($delta / 3600) > 0) {
        $timestampdisplay .= floor($delta / 3600);
        if (floor($delta / 3600) == 1) {
            $timestampdisplay .= ' hour, ';
        } else {
            $timestampdisplay .= ' hours, ';
        }
        $delta -= 3600 * floor($delta / 3600);
    }

    // Show minutes if it's been more than a minute.
    if (floor($delta / 60) > 0) {
        $timestampdisplay .= floor($delta / 60);
        if (floor($delta / 60) == 1) {
            $timestampdisplay .= ' minute ago';
        } else {
            $timestampdisplay .= ' minutes ago';
        }
        $delta -= 60 * floor($delta / 60);
    } else {
        $timestampdisplay .= $delta;
        if ($delta == 1) {
            $timestampdisplay .= ' second ago';
        } else {
            $timestampdisplay .= ' seconds ago';
        }
    }


    return $timestampdisplay;
}
?>

<div class="form" style="height:99%">
    <div id="forum_posting">
        <textarea placeholder="How are you feeling, <?= $userModel->displayname ?> ?" class="doPost"></textarea>
        <div class="fileviewer" id="fileviewer"></div>
        <div class="post_button">
            <span class="attach_img_icon post_icon" id="attach_file_icon">
                <span id="status" ></span>
            </span>
            <span class="separator"></span>
            <div style="float: right">
                <span class="post_icon">
                    <button class="submit_post_button" id="submit_post_button">Post</button>
                </span>
            </div>

        </div>
    </div>
    <hr style="border-color:#FFFFFF"></hr>
    <div id="forum_content" style="font-size: small">
        <?php
        foreach ($r_pesan as $d) {
            $t = ($d['timestamp'] != "") ? timestamp($d['timestamp']) : "2d";
            $r_comment = Yii::app()->db->createCommand()
                    ->select('t.id,t.kepada,t.timestamp,t.pesan,t.time,t.dari as dr,u.displayname as dari,u.avatar_file')
                    ->from('t_pesan t')
                    ->join('user u', 'u.id=t.dari')
                    ->where('t.kepada<>0 and t.kepada=:postid', array(':postid' => $d['id']))
                    ->order('t.timestamp asc')
                    ->queryAll();
            $data_comment = array();
            foreach ($r_comment as $prop => $val) {
                $temp = array();
                $temp[$prop] = $val;
                $data_comment[] = $temp;
            }
            $start = (count($r_comment) > 4) ? (count($r_comment) - 4) : 0;
            $avatar = ($d['avatar_file'] == "") ? "/images/noimage.gif" : $d['avatar_file'];
            ?>   
            <div class="chatcox" id="u<?= $d['timestamp'] . "_" . $d['dr'] . "_" . $d['id'] ?>">
                <div class="chatpic"><img src="<?= $settings['dataUri'] . '?funct=getImage&fn=' . $avatar ?>" height="50px" width="50px" /></div>
                <div class="post_action">
                    <div class="chatname">
                        <span style="font-weight:bold;color:#132F4C;font-size:13px;"><?= $d['dari'] ?>
                        </span> <span style="float:right;color:gray;" id="timedisplay"><?= $t ?></span></div>
                    <div class="chatforum_content"><?= $d['pesan'] ?></div>
                    <div class="postimageshower" id="postimageshower">
                        <?php
                        $arrimg = json_decode($d['image_post']);
                        if (count($arrimg) > 0) {
                            $moredua = 0;
                            foreach ($arrimg as $key => $img) {
                                if ($key == 0) {
                                    echo '<span><ul class="gallery clearfix"><li><a href="' . $settings['dataUri'] . '?funct=getFullPostImage&fn=' . $settings['avatarUrl'] . $d['dr'] . '/' . $img->post_image . '" rel="prettyPhoto" title="' . $d['pesan'] . '"><img src="' . $settings['dataUri'] . '?funct=getPostImage&fn=' . $settings['avatarUrl'] . $d['dr'] . '/' . $img->post_image . '" /></a></li></ul></span>';
                                } else {
                                    if (count($arrimg) > 1 && $key > 0) {
                                        echo '<span style="float:left"><ul class="gallery clearfix"><li><a href="' . $settings['dataUri'] . '?funct=getFullPostImage&fn=' . $settings['avatarUrl'] . $d['dr'] . '/' . $img->post_image . '" rel="prettyPhoto" title="' . $d['pesan'] . '"><img src="' . $settings['dataUri'] . '?s=120&funct=getPostImage&fn=' . $settings['avatarUrl'] . $d['dr'] . '/' . $img->post_image . '" /></a></li></ul></span>';
                                        $moredua = 1;
                                    } else {
                                        echo '<span><ul class="gallery clearfix"><li><a href="' . $settings['dataUri'] . '?funct=getFullPostImage&fn=' . $settings['avatarUrl'] . $d['dr'] . '/' . $img->post_image . '" rel="prettyPhoto" title="' . $d['pesan'] . '"><img src="' . $settings['dataUri'] . '?funct=getPostImage&fn=' . $settings['avatarUrl'] . $d['dr'] . '/' . $img->post_image . '" /></a></li></ul></span>';
                                    }
                                }
                            }
                            if ($moredua == 1) {
                                echo '<span style="clear:both"></span>';
                            }
                        }
                        ?>
                    </div>
                    <!--info comment lebih dari 2-->
                    <div class="action">
                        <?php if ($start > 0) {
                            $start+=1; ?>
                            <button class="like_link stat_elem as_link" type="button" onclick="viewAllComments(this)">::. View all <?= count($r_comment) ?> comments .::</button> 
    <?php } ?>
                    </div>
                </div>
                <div class="clear"></div>
                <div>
                    <ul class="ulpostresponse">
                        <li><i></i></li>
                        <?php
                        //echo $start;
                        for ($i = $start; $i < count($r_comment); $i++) {
                            $avatar = ($r_comment[$i]['avatar_file'] == "") ? "/images/noimage.gif" : $r_comment[$i]['avatar_file'];
                            ?>
                            <li class="comment">
                                <div class="commentcox">
                                    <div class="commentpic"><img src="<?= $settings['dataUri'] . '?funct=getImage&fn=' . $avatar ?>" width="32px" height="32px" /></div>
                                    <div class="commentname">
                                        <span style="font-weight:bold;color:#132F4C;font-size:12px;"><?= $r_comment[$i]['dari'] ?>
                                        </span><span style="color:#333333;font-size: 11px;"><?= $r_comment[$i]['pesan'] ?></span>
                                    </div>
                                    <div class="commentforum_content"><?= timestamp($r_comment[$i]['timestamp']) ?></div>
                                    <div class="clear"></div>
                                </div>
                            </li>
    <?php } ?>
                        <li class="comment input" id="input">
                            <div class="commentcox">
                                <div class="commentpic" style="display:none;"><img src="<?= $settings['dataUri'] . '?funct=getImage&fn=' . $settings['avatarUrl'] ?>/images/noimage.gif" width="32px" height="32px" /></div>
                                <div class="commentname">
                                    <span style="font-weight:bold;color:#132F4C;display: none;">Admin
                                    </span><span style="color:#333333;"><textarea id="<?= $d['id'] ?>" placeholder="write a comment"></textarea></span>
                                </div>
                                <div class="commentforum_content"></div>
                                <div class="clear"></div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="clear"></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    //google.load("jquery", "1.4.2");
    var timestamp = <?= time() ?>;
    var url = '<?= $settings['dataUri'] ?>';
    var noerror = true;
    var ajax;
    var stateDocumentReady=1;
    var unloading = false;
    function handleResponse(response)
    {
        //$('#forum_content').append('<div>' + response['msg'] + '</div>');
        if(response['status']=="ok" && response['type']=="p"){
            //check session expired
            //if(response['reload']){window.location='?ref=w_dashboard';}
            
            var chatcox=document.createElement("div"); //main div
            chatcox.className="chatcox";
            chatcox.id="u"+response['timestamp']+"_"+response['dr']+"_"+response['id'];
            var chatpic=document.createElement("div");
            chatpic.className="chatpic";
            var img=document.createElement("img");
            var pic=(response['pic']=="")?"noimage.gif":response['pic'];
            img.src="<?= $settings['dataUri'] . '?funct=getImage&fn=' ?>"+pic;
            img.style.width="50px";
            img.style.height="50px";
            chatpic.appendChild(img);
            //chatcox.appnedChild(chatpic);
            var chatname=document.createElement("div");
            chatname.className="chatname"
            var dari=document.createElement("span");
            dari.style.fontWeight="bold";
            dari.style.color="#132F4C";
            dari.style.fontSize="13px";
            dari.innerHTML=response['dari'];
            var time=document.createElement("span");
            time.style.cssFloat="right";
            time.id="timedisplay";
            time.style.color="gray";
            time.innerHTML=response['timedisplay'];
            chatname.appendChild(dari);
            chatname.appendChild(time);
            var chatforum_content=document.createElement("div");
            chatforum_content.className="chatforum_content";
            chatforum_content.innerHTML=response['msg'];
            var postimageshower=document.createElement("div");
            postimageshower.className="postimageshower";
            postimageshower.id="postimageshower";
            var jsonImagePost=eval(response['image_post']);
            var more2=0;
            for(var i=0;i<jsonImagePost.length;i++){
                if(i==0){
                    postimageshower.innerHTML+='<span><ul class="gallery clearfix"><li><a href="<?= $settings["dataUri"] . "?funct=getFullPostImage&fn=" . $settings["avatarUrl"] ?>'+response['dr']+'/'+jsonImagePost[i].post_image+'" rel="prettyPhoto" title="'+response['msg']+'"><img src="<?= $settings["dataUri"] . "?funct=getPostImage&fn=" . $settings["avatarUrl"] ?>'+response['dr']+'/'+jsonImagePost[i].post_image+'" /></a></li></ul></span>';
                }else{
                    if(jsonImagePost.length>1 && i>0){
                        more2=1;
                        postimageshower.innerHTML+='<span style="float:left"><ul class="gallery clearfix"><li><a href="<?= $settings["dataUri"] . "?funct=getFullPostImage&fn=" . $settings["avatarUrl"] ?>'+response['dr']+'/'+jsonImagePost[i].post_image+'" rel="prettyPhoto" title="'+response['msg']+'"><img src="<?= $settings["dataUri"] . "?s=120&funct=getPostImage&fn=" . $settings["avatarUrl"] ?>'+response['dr']+'/'+jsonImagePost[i].post_image+'" /></a></li></ul></span>';
                    }else{
                        postimageshower.innerHTML+='<span><ul class="gallery clearfix"><li><a href="<?= $settings["dataUri"] . "?funct=getFullPostImage&fn=" . $settings["avatarUrl"] ?>'+response['dr']+'/'+jsonImagePost[i].post_image+'" rel="prettyPhoto" title="'+response['msg']+'"><img src="<?= $settings["dataUri"] . "?funct=getPostImage&fn=" . $settings["avatarUrl"] ?>'+response['dr']+'/'+jsonImagePost[i].post_image+'" /></a></li></ul></span>';
                    }
                }
            }
            if(more2==1){
                postimageshower.innerHTML+='<span style="clear:both"></span>';
            }
            var action=document.createElement("div");
            action.className="action";                    
            var clear=document.createElement("div");
            clear.className="clear";
            var clear2=document.createElement("div");
            clear2.className="clear";
                    
            var post_action=document.createElement("div");
            post_action.className="post_action";
            post_action.appendChild(chatname);
            post_action.appendChild(chatforum_content);
            post_action.appendChild(postimageshower);
            post_action.appendChild(action);
                    
            chatcox.appendChild(chatpic);                    
            chatcox.appendChild(post_action);
            chatcox.appendChild(clear2);
            //alert(chatcox.innerHTML);
            var div=document.createElement("div");
            var ulpostresponse=document.createElement("ul");
            ulpostresponse.className="ulpostresponse";
            var ulforum_content="<li><i></i></li>";
            ulforum_content+="<li class='comment input' id='input'>"+
                "<div class='commentcox'>"+
                "<div class='commentpic' style='display:none;'><img src='<?= $settings['dataUri'] . '?funct=getImage&fn=' ?>"+pic+"' width='32px' height='32px' /></div>"+
                "<div class='commentname'>"+
                "<span style='font-weight:bold;color:#132F4C;display: none;'>"+
                "</span><span style='color:#333333;'><textarea id='"+response['id']+"' placeholder='write a comment'></textarea></span>"+
                "</div>"+
                "<div class='commentforum_content'></div>"+
                "<div class='clear'></div>"+
                "</div>"+
                "</li>";
            ulpostresponse.innerHTML=ulforum_content;
            div.appendChild(ulpostresponse);
            chatcox.appendChild(div);
            chatcox.appendChild(clear);
            var kotak=document.createElement("div");
            kotak.appendChild(chatcox);
            var current=document.getElementById("forum_content").innerHTML;
            document.getElementById("forum_content").innerHTML=kotak.innerHTML+document.getElementById("forum_content").innerHTML;
            addElEvent($("#"+response['id']));
        }else if(response['status']=="logout"){
            document.location="?";
        }
        
    }
    function connect()
    {
        var post= new Array();
        $('#forum_content').children().each(function(i){
            var p=$(this);
            post[i]=p.attr('id');
            if(i==10){return;}
        });
        ajax=$.ajax({
            type: 'POST',
            url: '<?= $settings['dataUri'] ?>',
            cache: 'false',
            data: {'funct':'dataFeed','timestamp':timestamp,'pid':post.toString(),'uid':'<?= Yii::app()->user->id ?>'},
            success: function(data,textStatus,jqXHR){
                if(data==""){
                    return;
                }else{
                    try{
                        eval('var response = '+data.toString());
                        timestamp = response['timestamp'];
                        handleResponse(response);
                        eval(response['script']);
                        if(jqXHR.status!=200){
                            //error ato gagal
                            //setTimeout(function(){ if(stateDocumentReady==1)connect() }, 5000);
                            alert("gagal");
                        }else{
                            //berhasil dengan sukses dan menyenangkan
                            //alert(2);
                            connect();
                        }
                    }catch(e){}
                }
                        
            },
            error:function(){
                ajax.abort();              
            }
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
                if(data[i][0]=="") continue;
                var ul=$("#"+data[i][0]+" .ulpostresponse");
                var strCommentInput="<div class='comment input' id='input'>"+$("#"+data[i][0]+" .ulpostresponse #input").html()+"</div>";
                //var strforum_content="<li><i></i></li>";
                var strforum_content="";
                var comments=data[i][1];
                if($("#"+data[i][0]+" .action .like_link stat_elem as_link")){
                    start=(comments.length > 4)?comments.length - 4:0;
                }else{
                    start=0;
                }
                
                var commentCount=comments.length;
                for(var j=start;j<comments.length;j++){
                    var commentTimestamp=comments[j][0];
                    var commentIsi=comments[j][1];
                    var commentDari=comments[j][2];
                    var commentAvatar=(comments[j][3]=="")?"/images/noimage.gif":comments[j][3];
                    strforum_content+="<li class='comment'>"+
                        "<div class='commentcox'>"+
                        "<div class='commentpic'><img src='<?= $settings['dataUri'] . '?funct=getImage&fn=' ?>"+commentAvatar+"' width='32px' height='32px' /></div>"+
                        "<div class='commentname'>"+
                        "<span style='font-weight:bold;color:#132F4C;font-size:12px'>"+commentDari+" "+
                        "</span><span style='color:#333333;font-size:11px'>"+commentIsi+"</span>"+
                        "</div>"+
                        "<div class='commentforum_content'>"+commentTimestamp+"</div>"+
                        "<div class='clear'></div>"+
                        "</div>"+
                        "</li>";
                }

                $("#"+data[i][0]+" .ulpostresponse").children( 'li:not(:last)' ).remove();
                $('<li><i></i></li>'+strforum_content+'').insertBefore("#"+data[i][0]+" .ulpostresponse #input");
                var actionTpl="<button class='like_link stat_elem as_link' type='button' onclick='viewAllComments(this)'>::. View all "+commentCount+" comments .::</button>";
                if(start>0){$("#"+data[i][0]+" .action").html(actionTpl);}
            }
        }else if(role==1){
            eval(data);
            var id=pid.id;
            var strforum_content="";
            comments=data;
            for(var j=0;j<comments.length;j++){
                var commentTimestamp=comments[j].timestamp;
                var commentIsi=comments[j].pesan;
                var commentDari=comments[j].dari;
                var commentAvatar=(comments[j][3]=="")?"/images/noimage.gif":comments[j][3];
                strforum_content+="<li class='comment'>"+
                    "<div class='commentcox'>"+
                    "<div class='commentpic'><img src='<?= $settings['dataUri'] . '?funct=getImage&fn=' ?>"+commentAvatar+"' width='32px' height='32px' /></div>"+
                    "<div class='commentname'>"+
                    "<span style='font-weight:bold;color:#132F4C;'>"+commentDari+" "+
                    "</span><span style='color:#333333;'>"+commentIsi+"</span>"+
                    "</div>"+
                    "<div class='commentforum_content'>"+commentTimestamp+"</div>"+
                    "<div class='clear'></div>"+
                    "</div>"+
                    "</li>";
            }
            $("#"+id+" .ulpostresponse").children( 'li:not(:last)' ).remove();
            $('<li><i></i></li>'+strforum_content+'').insertBefore("#"+id+" .ulpostresponse #input");    
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
                    url: '<?= $settings['dataUri'] ?>',
                    data: {'funct':'dataIn','dari':'<?= Yii::app()->user->id ?>','kepada':txt.attr('id'),'pesan':txt.val(),'tipe_pesan':1},
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
            url: '<?= $settings['dataUri'] ?>',
            data: {'funct':'select_comment','datachatid':data[2]},
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
                    url: '<?= $settings['dataUri'] ?>',
                    data: {'funct':'dataIn','dari':'<?= Yii::app()->user->id ?>','kepada':txt.attr('id'),'pesan':txt.val(),'tipe_pesan':1},
                    success: function(){
                        txt.val("");
                    },
                    error:function(){
                                      
                    },
                    dataType: 'text'
                });
            }
        });
        $(".doPost").bind('keydown', function(e) {
            var txt = $(this);
            var key = e.which;
            var str=txt.val();
            if (key == 13 && str.replace(/^\s+|\s+$/g,'')!="") {
                e.preventDefault();
                txt.val('');
                var imagePost=new Array()
                $(".fileviewer").find("img").each(function(i,el){
                    var r=new Object();
                    r.post_image=$(this).attr("alt");
                    imagePost.push(r);
                });
                
                $.ajax({
                    type: 'POST',
                    url: '<?= $settings['dataUri'] ?>',
                    data: {'funct':'dataIn','dari':'<?= Yii::app()->user->id ?>','kepada':'0','pesan':str,'postimage':JSON.stringify(imagePost).toString(),'tipe_pesan':1},
                    success: function(){
                        txt.val("");
                        $("#fileviewer").children().remove();
                    },
                    error:function(){
                                      
                    },
                    dataType: 'text'
                });
            }
        });
        $(".doPost").bind('focus', function(e) {
            $(".post_button").css("display","block");
        });
        $("#submit_post_button").click(function(){
            var txt=$(".doPost");
            var str=txt.val();
            var imagePost=new Array()
            if(str.replace(/^\s+|\s+$/g,'')!=""){
                $(".fileviewer").find("img").each(function(i,el){
                    var r=new Object();
                    r.post_image=$(this).attr("alt");
                    imagePost.push(r);
                });
                $.ajax({
                    type: 'POST',
                    url: '<?= $settings['dataUri'] ?>',
                    data: {'funct':'dataIn','dari':'<?= Yii::app()->user->id ?>','kepada':'0','pesan':txt.val(),'postimage':JSON.stringify(imagePost).toString(),'tipe_pesan':1},
                    success: function(){
                        txt.val("");
                        $("#fileviewer").children().remove();
                    },
                    error:function(){
                                      
                    },
                    dataType: 'text'
                });
            }
        });
        initLightBox();
    }
    
    function requestAbort(){
    
        if(ajax){
            ajax.abort();
            stateDocumentReady=0;
        }
    }
    function initLightBox(){
        $("area[rel^='prettyPhoto']").prettyPhoto();
				
        $(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
        $(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		
    }
    $(document).ready(function(){
        connect();
        activeEvent();   
        initLightBox();
    });
    $(window).unload(function(){
        requestAbort();
    });
    $(function(){
        var btnUpload=$('#attach_file_icon');
        var status=$('#status');
        new AjaxUpload(btnUpload, {
            action: '<?= $settings['dataUri'] ?>?funct=postImage',
            name: 'postimage',
            onSubmit: function(file, ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                    // extension is not allowed 
                    alert('Sorry,Only JPG, PNG or GIF files are allowed... ^^');
                    return false;
                }
                status.text('Uploading...');
            },
            onComplete: function(file, response){
                //On completion clear the status
                status.text('');
                //Add uploaded file to list
                if(response==="success"){
                    $('<span></span>').appendTo('#fileviewer').html('<img src="<?= $settings['dataUri'] . '?funct=getViewImage&fn=/media/images/' ?>tmp/'+file+'" alt="'+file+'" />').addClass('success');
                } else{
                    $('<span></span>').appendTo('#fileviewer').text(file).addClass('error');
                }
            }
        });
		
    });
</script>
