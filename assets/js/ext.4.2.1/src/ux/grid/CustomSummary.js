/*Ext.define('Ext.ux.grid.CustomSummary', {
 override : 'Ext.grid.feature.Summary',
 //alias: 'feature.summary',
 getSummary: function(store, type, field, group) {
    var reader = store.proxy.reader;
    if (this.remoteRoot && reader.rawData) {
        // reset reader root and rebuild extractors to extract summaries data
		
        root = reader.root;
        reader.root = this.remoteRoot;
        reader.buildExtractors(true);
        summaryRow = reader.getRoot(reader.rawData);
        // restore initial reader configuration
		//alert(JSON.stringify(summaryRow));
        reader.root = root;
        reader.buildExtractors(true);
        if (typeof summaryRow[field] != 'undefined') {
			alert("ada val field="+field);
            return summaryRow[field];
        }

        return '';
    }

    return this.callParent(arguments);
  }
});*/
Ext.grid.feature.Summary.override({
    createSummaryRecord: function(view) {
        var columns = view.headerCt.getVisibleGridColumns(),
            info = {
                records: view.store.getRange()
            },
            colCount = columns.length, i, column,
            summaryRecord = this.summaryRecord || (this.summaryRecord = new view.store.model(null, view.id + '-summary-record'));

        // Set the summary field values
        summaryRecord.beginEdit();

        if (this.remoteRoot) {
            if (view.store.proxy.reader.rawData) {
                summaryRecord.set(view.store.proxy.reader.rawData.summaryData); // hardcoded "summaryData"
            }
        } else {
            for (i = 0; i < colCount; i++) {
                column = columns[i];

                // In summary records, if there's no dataIndex, then the value in regular rows must come from a renderer.
                // We set the data value in using the column ID.
                if (!column.dataIndex) {
                    column.dataIndex = column.id;
                }

                summaryRecord.set(column.dataIndex, this.getSummary(view.store, column.summaryType, column.dataIndex, info));
            } 
        }

        summaryRecord.endEdit(true);
        // It's not dirty
        summaryRecord.commit(true);
        summaryRecord.isSummary = true;

        return summaryRecord;
    }
});