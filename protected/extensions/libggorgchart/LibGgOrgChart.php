<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LibGgOrgChart
 *
 * @author obi
 */
class LibGgOrgChart extends CWidget{
    public $jsonData="";
    
    public function init(){
        $this->registerAsset();
        $this->setInitScript();
    }
    public function run(){
        $this->render('index', array('jsonData'=> $this->jsonData));
    }
    public function registerAsset(){
        $assets = dirname(__FILE__) . '/' . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/raphael-min.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/lib_gg_orgchart_v041.js', CClientScript::POS_HEAD);        
    }
    public function setInitScript(){
        $js = <<<EOS
   var oc_sample_data_to_use = 1;
	/*var oc_data_1 ={
			title : 'Mi organigrama',   // not used
			root : {
				title : 'Kepala Sekolah',
				subtitle: '(Drs.Robi Cahyadi,Spd)',
				children : [
					{ title : 'KETUA KOMITE SEKOLAH', subtitle: '(Drs. NURMAN)', type : 'collateral' },
					{ title : 'WAKIL MANAJEMEN MUTU',  subtitle: '(Drs. SUTARYO)',type : 'staff' },
					{ title : 'KEPALA SUBBAG TU',subtitle:'(H. ABBAS HARAHAB, M.MPd)', type : 'staff',children:[{title:'TATA USAHA'}] },
					{
						title : 'WAKIL BIDANG KURIKULUM',
                                                subtitle:'(Drs. MOH. UBADI)',
					},
					{
						title : 'WAKIL BIDANG KESISWAAN', subtitle: '(Drs. ASEP SUPRIATNA H)',
					},
					{
						title : 'WAKIL BIDANG HIBIN & PSG', subtitle: '( Drs. BAMBANG  AH,M.MPd)',
					},
					{
						title : 'WAKIL BIDANG SARANA',
                                                subtitle:'(Drs. MART BUDIONO)',
					},
                                        {title:'KETUA BIDANG KEAHLIAN',subtitle:''}
				]
			}	
		}*/
	var oc_data_1 ={{$this->jsonData}}
                var oc_data =oc_data_1;
                var use_images = oc_sample_data_to_use == 4;
                var oc_style = {
			container          : 'oc_container',         // name of the DIV where the chart will be drawn
			vline              : 10,                     // size of the smallest vertical line of connectors
			hline              : 10,                     // size of the smallest horizontal line of connectors
			inner_padding      : 10,                     // space from text to box border
			box_color          : '#aaf',                 // fill color of boxes
			box_color_hover    : '#faa',                 // fill color of boxes when mouse is over them
			box_border_color   : '#008',                 // stroke color of boxes
			line_color         : '#f44',                 // color of connectors
			title_color        : '#000',                 // color of titles
			subtitle_color     : '#707',                 // color of subtitles
			title_font_size    : 12,                     // size of font used for displaying titles inside boxes
			subtitle_font_size : 10,                     // size of font used for displaying subtitles inside boxes
			title_char_size    : [ 6, 12 ],              // size (x, y) of a char of the font used for displaying titles
			subtitle_char_size : [ 5, 10 ],              // size (x, y) of a char of the font used for displaying subtitles
			max_text_width     : 15,                     // max width (in chars) of each line of text ('0' for no limit) 
			text_font          : 'Courier',              // font family to use (should be monospaced)
			use_images         : use_images,             // use images within boxes?
			images_base_url    : './images/',            // base url of the images to be embeeded in boxes, with a trailing slash
			images_size        : [ 160, 160 ],           // size (x, y) of the images to be embeeded in boxes
			box_click_handler  : oc_box_click_handler,   // handler (function) called on click on boxes (set to null if no handler)
		};
                var OC_DEBUG = false;
                function oc_box_click_handler (event, box) {
			if (box.oc_id !== undefined)
				alert('clicked on node with ID = ' + box.oc_id);
		}


		// call function 'oc_render()' when you are ready to draw the chart
		// chart will be rendered into a DIV with id = 'oc_container' (or as specified in oc_style)
		//
		window.onload = oc_render;

EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . 1, $js, CClientScript::POS_END);
        return 'Please wait..';
    }
}

?>
