<?php

function CalTimestamp($time) {
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

$model = $settings['model'];
$listUser = $model->findAll(array(
    'condition' => 'state_online=1 and id <>:myid',
    'params' => array(':myid' => Yii::app()->user->id)
        ));
$hour = 24;
$today = strtotime("$hour:00:00");
$yesterday = strtotime('-1 day', $today);
?>
<div style="margin-bottom:3px;width:100%;background:#132F4C;" id="user-search-box">
    <input type="text" class="cari" placeholder="type here to search user.."/>
</div>
<hr style="border-color:#ffffff"></hr>
<ul class="ul-user-online">
    <li><i></i></li>
    <?php
    //echo $start;
    foreach ($listUser as $d) {
        $avatar = ($d->avatar_file == "") ? "noimage.gif" : $d->id . "/" . $d->avatar_file;
        $sql = "select t.id,t.pesan,t.dari,t.kepada,t.timestamp,t.tipe_pesan,CASE WHEN t.dari=" . Yii::app()->user->id . " THEN 'Me' ELSE 'Him / Her' END AS sebagai";
        $sql.=" from t_pesan t";
        $sql.=" where t.tipe_pesan=2 AND (t.dari=" . Yii::app()->user->id . " OR t.dari=" . $d->id . ") AND (t.kepada=" . Yii::app()->user->id . " OR t.kepada=" . $d->id . ") AND t.timestamp BETWEEN " . $yesterday . " AND " . $today;
        $sql.=" order by t.timestamp asc";
        $r_chat = Yii::app()->db->createCommand($sql)->queryAll();
        ?>
        <li class="userlist" id="userlist<?= $d->id ?>">
            <div class="commentcox" style="margin-bottom:2px;">
                <div class="commentpic"><img src="<?= $settings['dataUri'] . '?funct=getImage&fn=' . $settings['avatarUrl'] . $avatar ?>" width="28px" height="28px" style="margin-top:3px;"/></div>
                <div class="commentname">
                    <span style="color:#132F4C;font-size:10px;float:left;padding-top:8px;"><?= $d->displayname ?>
                    </span><span style="color:#333333;font-size: 11px;float:right;padding:8px;">
                        <img height="1" width="1" alt="&nbsp;&nbsp;" class="img-status">
                    </span>
                </div>
                <div class="commentforum_content"></div>
                <div class="clear"></div>
            </div>
            <div id="online-chat-container" class="online-chat-container">
                <div class="chat-msg-list" id="chat-msg-list">
                    <?php
                    foreach ($r_chat as $msg) {
                        ?>
                        <div class="chat-msg">
                            <span class="user-identitas"><?= $msg['sebagai'] ?> : </span>
                            <span class="user-msg"><?= $msg['pesan'] ?></span>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <span id="chat_status_upload" ></span>
                <div class="chat-msg-input" id="chat-msg-input">
                    <textarea id="<?= $d->id ?>" placeholder="type your message here.." name="chat_msg_input" id="chat-msg-input" class="chat-msg-input"></textarea>
                    <span class="attach_file_icon post_icon" id="chat_attach_file_icon" style="margin:2px;"></span>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>
<script>
    
    $(document).ready(function(){
        userOnlineTrigger();
        $(".chat-msg-input textarea").bind('keydown', function(e) {
            var txt = $(this);
            var key = e.which;
            var str=txt.val();
            if (key == 13 && str.replace(/^\s+|\s+$/g,'')!="") {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '<?= $settings['dataUri'] ?>',
                    data: {'funct':'dataIn','dari':'<?= Yii::app()->user->id ?>','kepada':txt.attr('id'),'pesan':txt.val(),'tipe_pesan':2},
                    success: function(){
                        txt.val("");
                        var height=0;
                        $("#userlist"+txt.attr('id')).find(".chat-msg-list").children().each(function(){
                            height+=$(this).height();
                        });
                        if($("#userlist"+txt.attr('id')).find(".chat-msg-list").height()<height){
                            $("#userlist"+txt.attr('id')).find(".chat-msg-list").animate({ scrollTop: height }, 1000);
                        }
                    },
                    error:function(){
                                
                    },
                    dataType: 'text'
                });
            }
        });
        $("#chat_attach_file_icon").click(function(){
            alert("Sorry, \nthis one is still on working..");
        });
    });
    
    function updateMyChat(data){
        var dataJson=eval(data);
        var str="";
        var msg={};
        var uid="<?= Yii::app()->user->id ?>";
        var target="0";
        var listId=new Array();
        for(var i=0;i<dataJson.length;i++){
            str="";
            target=(dataJson[i].dari==uid)?dataJson[i].kepada:dataJson[i].dari;
            str='<div class="chat-msg">'+
                '<span class="user-identitas">'+dataJson[i].sebagai+' : </span>'+
                '<span class="user-msg">'+dataJson[i].pesan+'</span>'+
                '</div>';
            if(msg["userlist"+target]==null){
                listId.push("userlist"+target);
                msg["userlist"+target]=str;
            }else{
                msg["userlist"+target]+=str;
            }
            
        }
        for(var i=0;i<listId.length;i++){
            try{
                $("#"+listId[i]).find(".chat-msg-list").children().remove();
                $("#"+listId[i]).find(".chat-msg-list").html(msg[listId[i]]);
                
            }catch(e){
                console.log(e);
            }
        }
    }
    function userOnlineTrigger(){
        $(".commentcox").click(function(){
            if($(this).parent("li").find(".online-chat-container").is(":hidden")){
                $(this).parent("li").find(".online-chat-container").slideDown();
            }else{
                $(this).parent("li").find(".online-chat-container").slideUp();
            }
        
        });
        $("#user-search-box .cari").keypress(function(e){
            //if(e.which == 13) {
            var el=$(this);
            //var tdindex=el.parents("td").index();
            var search = el.val();
            var ul = $('.ul-user-online');
            ul.find('li').each(function(index, row) {
                var allCells = $(row);
                if (allCells.length > 0) {
                    var found = false;
                    allCells.each(function(index, el) {
                        var regExp = new RegExp(search, 'i');
                        if (regExp.test($(el).text())) {
                            found = true;
                            return false;
                        }
                    });
                    if (found == true)
                        $(row).show();
                    else
                        $(row).hide();
                }
            });
            //}
        });
    
    }
    function updateUserList(data){
        var json=eval(data);
        var res="";
        var uid='<?= Yii::app()->user->id ?>';
        for(var i=0;i<json.length;i++){
            if(json[i].id==uid) continue;
            
            var stateChatUse=""
            var stateUserOnline=true;
            if($("#userlist"+json[i].id).length>0){
                stateChatUse=($("#userlist"+json[i].id).find(".online-chat-container").is(":hidden"))?"none":"block";
            }else{
                stateChatUse=($("#userlist"+json[i].id).find(".online-chat-container").is(":hidden"))?"block":"none";
                var ava=(json[i].avatar_file=="")?"noimage.gif":json[i].id+"/"+json[i].avatar_file;
                res+='<li class="userlist" id="userlist'+json[i].id+'">'+
                    '<div class="commentcox" style="margin-bottom:2px;">'+
                    '<div class="commentpic"><img src="<?= $settings['dataUri'] . '?funct=getImage&fn=' . $settings['avatarUrl'] ?>' + ava + '" width="28px" height="28px" style="margin-top:3px;" /></div>'+
                    '<div class="commentname">'+
                    '<span style="color:#132F4C;font-size:10px;float:left;padding-top:8px;">'+json[i].displayname+
                    '</span><span style="color:#333333;font-size: 11px;float:right;padding:8px;">'+
                    '<img height="1" width="1" alt="&nbsp;&nbsp;" class="img-status">'+
                    '</span>'+
                    '</div>'+
                    '<div class="commentforum_content"></div>'+
                    '<div class="clear"></div>'+
                    '</div>'+
                    '<div id="online-chat-container" class="online-chat-container" style="display:'+stateChatUse+';">'+
                    '<div class="chat-msg-list" id="chat-msg-list">'+
                    '</div>'+
                    '<div class="chat-msg-input" id="chat-msg-input">'+
                    '<textarea placeholder="type your message here.." name="chat_msg_input" id="chat-msg-input" class="chat-msg-input"></textarea>'+
                    '<span class="attach_file_icon post_icon" id="chat_attach_file_icon" style="margin:2px;">'+
                    '<span id="chat_status_upload" ></span>'+
                    '</span>'+
                    '</div>'+
                    '</div>'+
                    '</li>';
            }
            
            //alert(d.displayname);
        }
        //alert(data);
        //$(".ul-user-online").children().remove();
        $(res).appendTo(".ul-user-online");
        //userOnlineTrigger();
    }
</script>