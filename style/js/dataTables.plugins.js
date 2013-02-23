$.fn.dataTableExt.oApi.fnMultiFilter = function( oSettings, oData ) {
	for ( var i=0, iLen=oSettings.aoColumns.length ; i<iLen ; i++ )
    {
            /* Add single column filter */
            oSettings.aoPreSearchCols[ i ].sSearch = oData[i];
    }
	oSettings._iDisplayStart = 0;
    this.oApi._fnDraw( oSettings );
};