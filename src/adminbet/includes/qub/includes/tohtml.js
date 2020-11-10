/*
 * ADOBE SYSTEMS INCORPORATED
 * Copyright 2007 Adobe Systems Incorporated
 * All Rights Reserved
 * 
 * NOTICE:  Adobe permits you to use, modify, and distribute this file in accordance with the 
 * terms of the Adobe license agreement accompanying it. If you have received this file from a 
 * source other than Adobe, then your use, modification, or distribution of it requires the prior 
 * written permission of Adobe.
 */

/*
	Copyright (c) S.C. InterAKT Online SRL
	http://www.interakt.ro/
*/

function rs2html(rs,ztabhtml,zheaderarray) {
	var str, rows, typearr, ncols, i, j, field, fname, v, type;
	rows = 0;
	if (!rs) {
		document.write('bad recordset');
		return false;
	}
	
	if (!ztabhtml) ztabhtml = "border='1' width='98%'";

	typearr = new Array();
	if (rs.rows.length > 0) {
		ncols = rs.rows.length;
	} else {
		ncols = 0;
	}

    //"+(rs.fields[i].numeric==1?" align=right":"")+"
	str = "";
	str += "<table id=\"sql_result_table\" cols=" + ncols + " " + ztabhtml + ">";
	
	str += "<tr>";
	for (i=0; i<rs.fields.length; i++) {
		str += "<th>"+rs.fields[i].name+"</th>";
	}
	str += "</tr>";
	for (i=0; i<rs.rows.length; i++) {
		str +="<tr>";
		for (j=0; j<rs.rows[i].length; j++) {
			str +="<td>" + (rs.rows[i][j]==""?"NULL":rs.rows[i][j]) + "</td>";
		}
		str +="</tr>";
	}
	str +="</table>\n\n";
	str = ncols + " row" + (ncols==1?"":"s") + "<br/>" + str;
	document.write(str);
	return ncols;
}
