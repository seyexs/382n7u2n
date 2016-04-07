<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="AccordionPanel">
    <div class="AccordionPanelTab">
        <div class="innerAccordionPanelTab">
            Group/Aplikasi/Operasi yang sudah diberikan ke Pengguna
        </div>
    </div>
    <div class="AccordionPanelContent">
        <?php
        if (!empty($model)) {
            foreach ($model as $m) {
                $assign_item = isset($m->authitem->description) ? $m->authitem->description:$m->itemname;
                if(isset($m->authitem->typeDef))
                     $assign_item .= " ({$m->authitem->typeDef})";
                echo '<div class="row">'.$assign_item . '&nbsp;&nbsp;&nbsp;&nbsp;' . CHtml::link('(hapus)', Yii::app()->getController()->createUrl('deleteAkses'), array('class' => 'link-delete', 'aksesrole' => $m->itemname)) . '</div>';
            }
        }
        else
            echo '<div class="row" style="margin:20px 10px 20px 10px;">Belum Ada Group/Aplikasi/Operasi yang diregister di Pengguna ini.</div>';
        ?>
    </div>
</div>
<div class="AccordionPanel">
    <div class="AccordionPanelTab">
        <div class="innerAccordionPanelTab">
            Register Group/Aplikasi/Operasi yang ada
        </div>
    </div>
    <div class="AccordionPanelContent">
        <?php
        $this->renderPartial('//common/registerrto_form', array('model' => $params['modelRTO']));
        ?>
    </div>
</div>

<?php
$js = <<<EOS
    var row;
    $('.link-delete').live('click', function(){
        //alert($(this).attr('href'));
        if(confirm("Apakah anda ingin menghapus Group/Aplikasi/Operasi user?")){
            row = $(this).parent();
            $.ajax({
                url : $(this).attr('href'),
                data : {
                    role : $(this).attr('aksesrole'),
                    userid : {$this->actionParams['userid']}
                },
                success:function(data){
                    if(data == "1")
                        row.fadeOut(500, function(){ $(this).remove();})
                    else
                        alert("Penghapusan Group/Aplikasi/Operasi gagal");

                },
                error: function(data) { 
                    alert("Terjadi error.Silahkan diulang");
                },
                dataType:'html'
            });
        }
        return false;
    });
EOS;
$cs = Yii::app()->getClientScript();
$cs->registerScript(__CLASS__ . '#set_akses-form-', $js, CClientScript::POS_END);
?>