Ext.define('Esmk.view.TBantuanPersyaratanPenerimaInquery._display_container',{
	extend:'Ext.tab.Panel',
	title:'Data Usulan Dapodikmen',
	iconCls: 'icon-grid',
	id:'persyaratanpenerimainquerydisplaycontainer',
    initComponent: function() {
		var me=this;
        this.items=this.dataItems;

        

        this.callParent(arguments);
		

    },


    actionCancel: function(button, e, options) {

        var win = button.up('window');
        win.close();

    },
	actionSum:function(records,dataIndex,v){
		
			var i = 0,
				length = records.length,
				total = 0,
				record;

			for (; i < length; ++i) {
				record = records[i];
				if(this.isNumeric(record.get(dataIndex))){
					total += parseInt(record.get(dataIndex));
				}
			}
			return (total==0)?'-':Ext.util.Format.number(total,'0,000');
		
		return records[1].get(dataIndex);
	},
	actionGroupSum:function(value,summaryData,dataIndex){
		return value;
	},
	isNumeric:function(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
});