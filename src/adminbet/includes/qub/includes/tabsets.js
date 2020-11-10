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

function loadIframe(n, what, table, sql) {
		if (sql) {
			window.sqlQueryToRun = sql;
		} else {
			window.sqlQueryToRun = "";
		}


		for ( i in top.tabsets) {
			if (what == top.tabsets[i][2]) {
				var tabname = i;
			}
		}

		var div = parent.document.getElementById('tabset' + n.toString());
		var ul = utility.dom.getElementsByTagName(div, 'ul')[0];
		var as = utility.dom.getElementsByTagName(ul, 'a');
		Array_each(as, function(a) {
			if (a.id == tabname) {
				utility.dom.classNameAdd(a.parentNode, 'activelink');
			} else {
				utility.dom.classNameRemove(a.parentNode, 'activelink');
			}
		})
		parent["iframe"+n].location.replace(what + "?rnd="+Math.random());
}

top.tabsets = {
	/*tab name	:	[tabset index, tab index inside tabset, filename]*/
	sqlcolumns	: [0, 0, 'sqlRows.html'],
	sqlquery		: [1, 0, 'showQuery.html'],
	sqlresults	: [1, 1, 'queryResult.html']
}
last_tabset_tab = {
0:null,
1:null
}
top.current_tab2 = null;
var MIN_DELTA = 190;
var queue = {};
var click_times = {};
var click_timeout = {};
function ltabset(tabname) {
	var args = queue[tabname];
	var s = "tabset2(";
	for (var i=0; i<args.length; i++) {
		s += "args[" + i +"]" + (i==args.length-1?"":", ");
	}
	s += ")";
	//top.canvas.zdbg("Execute:" + tabname, true);
	eval(s);
}

function tabset(tabname, table, sql) {
	var args = [];
	for(var i=0; i<tabset.arguments.length; i++) {
		var arg = tabset.arguments[i];
		args.push(arg);
	}
	queue[tabname] = args;

	var cur_click = (new Date()).getTime();
	if (typeof(click_times[tabname])!="undefined") {
		//top.canvas.zdbg("Request:"+tabname +":"+click_times[tabname] +":"+ cur_click, true);
		if (cur_click < (click_times[tabname] + MIN_DELTA)) {
			//top.canvas.zdbg("Cancel:"+tabname +":"+click_times[tabname] +":"+ cur_click, true);
			//click again too soon, cancel previous click and set it again
			window.clearTimeout(click_timeout[tabname]);
			click_timeout[tabname] = null;
		}
	}
	click_times[tabname] = cur_click;
	click_timeout[tabname] = window.setTimeout("ltabset('"+(tabname)+"')", MIN_DELTA);
}
function tabset2(tabname, table, sql) {
	if (typeof table == "undefined") {
		table = 0;
	}
	if (typeof sql == "undefined") {
		sql = '';
	}

	var t = top.tabsets[tabname][0];
	var n = top.tabsets[tabname][1];
	targetFile = top.tabsets[tabname][2];

	var div = parent.document.getElementById('tabset' + t.toString());
	var ul = utility.dom.getElementsByTagName(div, 'ul')[0];
	var as = utility.dom.getElementsByTagName(ul, 'a');
	Array_each(as, function(a) {
		if (a.id == tabname) {
			utility.dom.classNameAdd(a.parentNode, 'activelink');
		} else {
			utility.dom.classNameRemove(a.parentNode, 'activelink');
		}
	})

	if (last_tabset_tab[t] == null || table=="fromui") {
		last_tabset_tab[t] = targetFile;
		if (t==1) {
			top.current_tab2 = n;
		}
		if (table=="fromui") {
			table = 0;
		}
		genSql("loadIframe("+t+", '"+targetFile+"', "+table+", '"+sql+"');");
	} else {
		targetFile = last_tabset_tab[t];
		genSql("loadIframe("+t+", '"+targetFile+"', "+table+", '"+sql+"');");
	}

}

top.tabset = tabset;
