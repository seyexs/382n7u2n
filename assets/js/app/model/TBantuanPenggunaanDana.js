Ext.define('Esmk.model.TBantuanPenggunaanDana', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_penerima_id', type: 'string'},
            {name: 'uraian', type: 'string'},
            {name: 'qty', type: 'string'},
            {name: 'satuan_id', type: 'string'},
            {name: 'harga_satuan', type: 'string'},
            {name: 'harga_total', type: 'string'},
            {name: 'tanggal_transaksi', type: 'string'},
            {name: 'bukti_kwitansi', type: 'string'},
            {name: 'status_data', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'r_peruntukan_dana_bos_id', type: 'string'},
			{name: 't_bantuan_penerima_nama', type: 'string'},
			{name: 'satuan_nama', type: 'string'},
			{name: 'r_peruntukan_dana_bos_nama', type: 'string'},
			{name: 'tanggal_transaksi_bulan', type: 'string'},
			{name: 'no_bukti', type: 'string'},
			{name: 'status_pembelian_pengeluaran', type: 'string'},
			{name: 'toko_pembelian', type: 'string'},
			
    ]
});