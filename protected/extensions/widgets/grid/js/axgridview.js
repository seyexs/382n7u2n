var contentId='';
var rowsId='';
var notifyTime = 2000;
function doAjaxAction(url, msg, gridId, chkBxClmn){
    var arrVal = $.fn.yiiGridView.getChecked(gridId, chkBxClmn);
    if(arrVal.length == 0){
        alert('Silahkan pilih minimal satu baris data');
        return false;
    }
    if(!confirm(msg)) return false;
    var pKey = '';
    for(i=0; i<arrVal.length; i++){
        if(i > 0)
            pKey += ',';
        pKey += arrVal[i];
    };
    var opt = {
        url:url+'/id/'+pKey,
        success:function(data) {
            $.fn.yiiGridView.update(gridId);
            showOnNotify(data.type, data.msg, notifyTime);
        },
        error:function(XHR) {
            showOnNotify(data.type, XHR.msg, notifyTime);
        }
    };
    $.fn.yiiGridView.update(gridId, opt);
    return false;
}
function MM_goToURL(ulink)
{
    window.location=ulink;
}
function PopUpWin(ulink, width, height)
{
    $.colorbox({iframe:true, overlayClose:false, scrolling:true, href:ulink, width:width, height:height, opacity:0.25});
}
function expandRow(row, grid, column, data, url){
    var divId = column+'-expand-'+row;
    rowsId = grid + '-gridvw-row-' + row;
    contentId = grid + '-expand-content-' + row;
    if($('#'+divId).attr('state')=='collapse'){
        $('#'+divId).removeClass('v-grid-row-expanded');
        $('#'+divId).addClass('v-grid-row-collapsed');
        $('#'+divId).attr('state','expanded');
        $.ajax({
            type:'GET',
            url:url,
            success:function(data, status){
                $('#'+contentId).html(data);
                $('table tr').filter('#'+rowsId).show(100);
            }
            
        });
        
        
    }
    else{
        $('#'+divId).removeClass('v-grid-row-collapsed');
        $('#'+divId).addClass('v-grid-row-expanded');
        $('#'+divId).attr('state', 'collapse');
        $('table tr').filter('#'+rowsId).hide(100);
    }
}
function sendDataAjax(gridid, url)
{
 
   var data=$("#{$formId}").serialize();
 
 
  $.ajax({
    type: 'POST',
    url: url,
    data:data,
    success:function(data){
                parent.$.colorbox.close();
                parent.$.fn.yiiGridView.update(gridid);
              },
    error: function(data) { // if error occured
         alert("Error occured.please try again");
    },
    dataType:'html'
  });
 
}