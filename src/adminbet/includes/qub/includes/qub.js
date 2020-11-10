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

$TECHNOTE = 'http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=kb00181';
qs = splitQS(window.location.href);
//use the prompted text to open QuB in other browser window
//prompt("",window.location.href);
function getPostString() {
	return qs.postConn[0];
}
function getQueryName() {
	return qs.name[0];
}

function setQueryName(qn) {
	qs.name[0] = qn;
}

function getNXTQuery() {
	//return "SELECT company_com.name_com AS idcom_con, name_con as caca, contact_con.email_con, contact_con.picture_con, contact_con.id_con FROM (contact_con LEFT JOIN company_com ON contact_con.idcom_con = company_com.id_com)";
	//alert(qs.SQLQuery[0]);
//	return "SELECT journal_jrn.idprd_jrn, journal_jrn.price_jrn, journal_jrn.carno_jrn, journal_jrn.indate_jrn, journal_jrn.outdate_jrn, cities_cit.name_cit AS idcit_jrn, drivers_drv.name_drv AS iddrv_jrn, journal_jrn.id_jrn FROM journal_jrn LEFT JOIN cities_cit ON journal_jrn.idcit_jrn = cities_cit.id_cit LEFT JOIN drivers_drv ON journal_jrn.iddrv_jrn = drivers_drv.id_drv"
	if (qs.SQLQuery) {
		return qs.SQLQuery[0];
	} else {
		return '';
	}
}

function parseNXTQuery(sql) {
	var ret = {distinct:'', hasJoins:false};

	//an array holding for each table an array of selected columns 
	var queryTables = [];
	//an object holding for each table name the true value 
	var queryTablesHash = [];
	var queryTablesCount = 0;
	//an array of columns that does not belong to any table (found in sql without its table, like "select columnName")
	var orphanColumns = [];
	
	var joins = [];
	var m = null;
	m = sql.match(/^SELECT\s*(DISTINCT)?\s*(.*)?\s*FROM\s*\(*([^\s\(\)]*)\s*(.*)?/i);
	if (!m) {
		return ret;
	}

	ret.distinct = m[1];
	sqlSelectWhat = m[2];
	sqlSelectFromTable = m[3];//alert(sqlSelectFromTable);
	sqlSelectJoins = m[4];
	var sqlWhatParts = sqlSelectWhat.split(/\s*,\s*/gi);
	for(var i=0; i<sqlWhatParts.length; i++) {
		var oneColumn = sqlWhatParts[i];
		if (/\./.test(oneColumn)) {
			var tmp = oneColumn.match(/([^\.\s\(\)]*)?(\.)?([^\s\(\)]*)\s*(as)?\s*([^\s\(\)]*)/i);
			if(tmp) {
				var tbl = tmp[1];
				var clmn = tmp[3];
				var alias = tmp[5];
				if (typeof queryTablesHash[tbl] == "undefined") {
					queryTablesHash[tbl] = queryTablesCount;
					queryTables[queryTablesCount++] = [];
				}
				queryTables[queryTablesHash[tbl]].push({table:tbl, column:clmn, alias:alias});
			}
		} else {
			var tmp = oneColumn.match(/([^\s\(\)]*)\s*(as)?\s*([^\s\(\)]*)/i);
			if (tmp) {
				var clmn = tmp[1];
				var alias = tmp[3];
				orphanColumns.push({column:clmn, alias:alias});
			}
		}
	}
	if (sql.match(/ join /gi)) {
		while (true) {
			var tmp_join = sqlSelectJoins.match(/\s*LEFT JOIN\s*([^\s\(\)]*)\s*ON\s*([^\s\(\)]*)\.([^\s\(\)]*)\s*=\s*([^\s\(\)]*)\.([^\s\(\)]*)/i);
			if (tmp_join) {
				sqlSelectJoins = sqlSelectJoins.substring(tmp_join[0].length);
				ret.hasJoins = true;
				if (sqlSelectFromTable == tmp_join[4]) {
					joins.push({t1:tmp_join[4], f1:tmp_join[5], t2:tmp_join[2], f2:tmp_join[3]});
				} else {
					joins.push({t1:tmp_join[2], f1:tmp_join[3], t2:tmp_join[4], f2:tmp_join[5]});
				}
			} else {
				break;
			}
		}
	}
	ret.queryTables = queryTables;
	ret.orphanColumns = orphanColumns;
	ret.joins = joins;
	return ret;
}

function getPassword() {
	if (qs.pass) {
		return qs.pass[0];
	} else {
		return '';
	}
}

function getServerExtension() {
	return qs.mmdbext[0];
}

function getOldTables() {
	if (qs.oldTables && qs.oldTables.length>0) {
		return qs.oldTables[0];
	} else {
		return '';
	}
}

function getQUBVersion() {
	if (qs.qub_version && qs.qub_version.length>0) {
		return qs.qub_version[0];
	} else {
		//versioning start
		return '2.10.6';
	}
}
function getQUBMinVersion() {
	if (qs.qub_min_version && qs.qub_min_version.length>0) {
		return qs.qub_min_version[0];
	} else {
		//versioning start
		return '2.10.4.9';
	}
}

original_query_name = getQueryName();
one_time_add_old_tables = true;

function updateWindowTitle(str) {
	if (!str) str = top.ui.selectedQueryName;
	document.title = "Query Builder (QuB) :: "+str;
}

if (opener){
	var helpWindow = opener;
}

function openHelp() {
	showQUBHelp('qub');
	return;
	if(!helpWindow || helpWindow.closed){
		helpWindow = window.open("./doc/index.html", "_help");
	}
	helpWindow.focus();
}

function closeWindow(){
	try { 
		if (helpWindow) {
			if (!helpWindow.closed) {
				helpWindow.close();
			}
		}
	} catch (e) { }
}

function importRelations() {
	alert(locales["Available for PostgreSQL only."]);
}

if(document.all){
	var indrag=false;
	var captura_este_la=null;
	var drag_index = null;
	function tabbar_drag(){
		if(!indrag)
			return;

		if (drag_index == "0") {
			preview_y1 = start_h_canvas_td - (start_y-event.y);
			preview_y2 = start_h_iframe0_td + (start_y-event.y);
		} else if(drag_index == "1") {
			preview_y1 = start_h_canvas_td - (start_y-event.y);
			preview_y2 = start_h_iframe1_td + (start_y-event.y);
		} else if(drag_index == "2") {
			preview_y1 = start_h_left_top_td - (start_y-event.y);
			preview_y2 = start_h_left_bottom_td + (start_y-event.y);
		}

		if( drag_index == "0" || drag_index == "1" ) {
			var canvas_td = document.getElementById('canvas_td');
			if (preview_y2 < 0 || preview_y1 < 0) {
				canvas_td.style.pixelHeight = start_h_canvas_td;
				if (drag_index == "0") {
					iframe0_td.style.pixelHeight = start_h_iframe0_td;
				} else {
					iframe1_td.style.pixelHeight = start_h_iframe1_td;
				}
				return;
			}
			canvas_td.style.pixelHeight = preview_y1;
		} else if (drag_index == "2") {
			//if not good restoe and return;
			if (preview_y2 < 0 || preview_y1 < 0) {
				left_top_td.style.pixelHeight = start_h_left_top_td;
				left_bottom_td.style.pixelHeight = start_h_left_bottom_td;
				return;
			}
		}

		if (drag_index == "0") {
			iframe0_td.style.pixelHeight = preview_y2;
		} else if(drag_index == "1") {
			iframe1_td.style.pixelHeight = preview_y2;
		} else if(drag_index == "2") {
			left_bottom_td.style.pixelHeight = preview_y2;
		}
	}

	function tabbar_begindrag(){
		if(!event.button)return true;
	
		captura_este_la = event.srcElement;
		var pel = captura_este_la;
		drag_index = null;
	
		while(pel && !(drag_index=pel.getAttribute("drag_index")) ) {
			if (pel.getAttribute("dont_drag")) {
				return;
			}
			pel = pel.parentElement;
		}
		if (!drag_index) {
			return;
		}
		drag_index += "";
		var drag_bottom = ui.cs.get("iframe1_state");
		if (drag_index=="1" && drag_bottom!=null && drag_bottom==false) {
			return;
		}
		captura_este_la.setCapture();
		captura_este_la.onmouseup=tabbar_enddrag;
		indrag=true;

		start_h_canvas_td=canvas_td.offsetHeight;
		if (drag_index == "0") {
			var iframe0_td = document.getElementById('iframe0_td');
			var iframe0 = document.getElementById('iframe0');
			start_h_iframe0_td = iframe0_td.offsetHeight;
			start_h_iframe0 = iframe0.offsetHeight;
			start_h_iframe0 = iframe0.offsetHeight;
			iframe0_td.style.height = start_h_iframe0_td;
			iframe0.style.height = iframe0_td.offsetHeight;
			iframe0.style.height ="100%";
		} else if(drag_index == "1") {
			var iframe1_td = document.getElementById('iframe1_td');
			var iframe1 = document.getElementById('iframe1');
			start_h_iframe1_td = iframe1_td.offsetHeight;
			start_h_iframe1 = iframe1.offsetHeight;
			iframe1_td.style.height=start_h_iframe1_td;
			iframe1.style.height=iframe1_td.offsetHeight;
			iframe1.style.height="100%";
		} else if(drag_index == "2") {
			var left_top_td = document.getElementById('left_top_td');
			var left_bottom_td = document.getElementById('left_bottom_td');
			start_h_left_top_td = left_top_td.offsetHeight;
			start_h_left_bottom_td = left_bottom_td.offsetHeight;

			left_bottom_td.style.height = start_h_left_bottom_td + "px";
			left_top_td.style.height = "100%";
			query_list.style.height = "100%";
		}
	
		start_y = event.y;
		event.cancelBubble=true;
		return false;
	}

	function tabbar_enddrag(){
		if(captura_este_la)captura_este_la.releaseCapture();
		if (!indrag) {
			return;
		}
		indrag = false;

		var iframe0_td = document.getElementById('iframe0_td');
		var iframe0 = document.getElementById('iframe0');
		var iframe1_td = document.getElementById('iframe1_td');
		var iframe1 = document.getElementById('iframe1');


		if (drag_index == "0") {
			iframe0_td.style.height = iframe0_td.offsetHeight;
			ui.cs.set("iframe0_td_style_height", iframe0_td.offsetHeight);
		} else {
			iframe1_td.style.height = iframe1_td.offsetHeight;
			ui.cs.set("iframe1_td_style_height", iframe1_td.offsetHeight);
		}
		canvas_td.style.height = "100%";
		if (drag_index == "0") {
			iframe0.style.height = iframe0_td.offsetHeight;
		} else if(drag_index == "1") {
			iframe1.style.height = iframe1_td.offsetHeight;
		} else if(drag_index == "2") {
			query_list.style.height = query_list.offsetHeight;
			left_top_td.style.height = query_list.offsetHeight;
			left_bottom_td.style.height = "100%";
			ui.cs.set("iframe2_td_style_height", query_list.offsetHeight);
		}
	}
}
later = {timeout:null};
function showPleaseWait(msg, w, h, howmuch, locked) {
	if (typeof later.wd=="undefined") {
		later.wd = document.getElementById('please_wait_div');
		later.md = document.getElementById('please_wait_message_div');
		later.me = document.getElementById('please_wait_message_td');
	}
	if (typeof locked=="undefined") {
		locked = false;
	}
	later.msg = msg;
	later.w = w;
	later.h = h;
	later.locked = locked;
	if (typeof howmuch=="undefined") {
		howmuch = 0;
	}
	later.tick = howmuch;
	later.start = new Date();
	//show the wait message only after some time to avoid flicker on local intranet, where the saves are lightning fast
	//the timeout will actually be canceled by a call to hidePleaseWait() inside the calling function
	later.timeout = window.setTimeout("late_showPleaseWait()", 1);
}
function late_showPleaseWait() {
	later.wd.style.display = "block";
	later.md.style.display = "block";
	later.me.innerHTML = later.msg;
	later.me.style.width = later.w;
	later.me.style.height = later.h;
	if (is.ie) {
		later.me.onblur = function () {later.me.focus();return false;}
		later.me.focus();
	}
	later.timeout = null;
}

function hidePleaseWait(forced) {
	if (later.locked) {
		window.clearTimeout(later.timeout);
		return;
	}

	if(later.timeout) {
		window.clearTimeout(later.timeout);
		later.timeout = null;
		later.tick = 0;
		return;
	}
	if (typeof forced=='undefined') {
		forced = false;
	}

	//hide the waiter if forced
	if (!forced && later.tick>0) {
		later.tick--;
		return;
	}
window.setTimeout(function() {
	if (later.locked) {
		return;
	}
	var me = document.getElementById('please_wait_message_td');
	me.onblur = null;
	me.innerHTML = "";
	var md = document.getElementById('please_wait_message_div');
	md.style.display = "none";
	var wd = document.getElementById('please_wait_div');
	wd.style.display = "none";
},
20);
}

function bodySelect() {
	if (window.getSelection) {
		var selObj = window.getSelection();
		if (selObj.toString()) {
			selObj.removeAllRanges();
		}
	}
}
var queriesIndexFromName = [];

function CookieSettings() {
	this.vals = {};
	this.load();
}

function CookieSettings_save () {
	var s = "{";
	for(var i in this.vals) {
		if (!this.vals.hasOwnProperty(i) ) {
			continue;
		}
		s += (s=='{'?'':',') + '"' + i + '":';
		
		var tof = typeof(this.vals[i]);
		switch (tof) {
			case "string":
				s += '"' + this.vals[i] + '"';
				break;
			case "boolean":case "number":
				s += '' + this.vals[i] + '';
				break;
		}
	}
	s += "}";
	utility.cookie.set("qub_settings", s, 60, "/");
}

function CookieSettings_load () {
	var s = utility.cookie.get("qub_settings");
	var obj = this;
	if (s) {
		eval('obj.vals = ' + s);
	}
}

function CookieSettings_set (pn, pv) {
	this.vals[pn] = pv;
	this.save();
}

function CookieSettings_get (pn) {
	if (typeof this.vals[pn] == 'undefined') {
		return null;
	} else {
		return this.vals[pn];
	}
}

CookieSettings.prototype.save = CookieSettings_save;
CookieSettings.prototype.load = CookieSettings_load;
CookieSettings.prototype.set = CookieSettings_set;
CookieSettings.prototype.get = CookieSettings_get;
function defval(vn, dv) {
	if (typeof vn == 'undefined') {
		return dv;
	} else {
		return vn;
	}
}
function UIState() {
	this.selectedQueryIndex = null;
	this.selectedQueryName = original_query_name;
	this.all_queries = [];
	this.all_queriesIndexFromName = [];
	this.tables = [];
	this.tablesIndexFromName = [];
	this.mustSave = false;
	this.sqlQueryRebuild = true;
	this.loaded = false;
	this.myQuery = null;

	this.is_new_query = false;
	this.sql_warnings = [];
	this.selection = [];
	this.queryFilterHasFocus = false;
	this.previous_filter = "";
	this.cs = new CookieSettings();
	var ua = navigator.userAgent;
	this.is = {
		ns:/gecko/i.test(ua),
		ie:/msie/i.test(ua)
	};
	this.invalidate = function (rebuildsql, must_save, tabs) {	
		if ((rebuildsql = defval(rebuildsql, null))!=null) {
			this.sqlQueryRebuild=rebuildsql;
			//document.getElementById("must_rebuild_span").innerText = rebuildsql;
		}
		if ((must_save = defval(must_save, null))!=null) {
			this.mustSave = must_save;
			//document.getElementById("must_save_span").innerHTML = "&nbsp;" + must_save +"&nbsp;" + (new Date());
		}
		var tabs = defval(tabs, '');
		if (top.canvas.tabset && tabs) {
			tabs = tabs.split(",");
			Array_each(tabs, function(item, i) {
				tabset(item);
			});
		}
	}
}

top.ui = new UIState();

function set_iframe1_state(state) {
	ui.cs.set("iframe1_state", state);
	var fr1 = document.getElementById('iframe1_container');
	var frt = document.getElementById('iframe1_toggler');

	if (!state) {
		fr1.style.display='none';
		frt.src = frt.src.replace(/[^\/]*.gif$/i, "open_tab.gif");
	} else {
		fr1.style.display = '';
		frt.src = frt.src.replace(/[^\/]*.gif$/i, "close_tab.gif");
	}
}
function toggle_iframe1() {
	set_iframe1_state(!ui.cs.get("iframe1_state"));
}

function load_queries(do_select) {
	if (typeof do_select == "undefined") {
		do_select = false;
	}
	var arr = [];
	var selectedQueryName = ui.selectedQueryName;
	ui.all_queries = [];
	var sorter_value = document.getElementById("query_list_sorter").value;

	if (!ui.queryFilterHasFocus) {
		ui.queryFilterHasFocus = true;
		document.getElementById('query_list_waiter').style.display = "";
		//get all queries
		arr = executeQuery("select name_que, query_que from qub3_queries_que order by name_que");
		document.getElementById('query_list_waiter').style.display = "none";
		ui.db_queries = [];
		if (typeof arr!="string") {
			for(var i=0;i<arr.length;i++){
				//sql name,sql query
				ui.db_queries[i] = [arr[i][0], unescape(arr[i][1]).toLowerCase()];
				if (selectedQueryName.toLowerCase() == arr[i][0].toLowerCase()) {
					ui.is_new_query = false;
				}
			}
		}
		if (ui.is_new_query) {
			ui.db_queries.push([selectedQueryName, canvas.sqlQuery]);
		}
	}

	ui.selectedQueryIndex = null;

	var qCount = 0;
	sorter_value = sorter_value.toLowerCase();
	for(var i=0; i < ui.db_queries.length ; i++){
		var qname =  ui.db_queries[i][0];
		var qsql =  ui.db_queries[i][1];
		//test if match filter, if any
		if (
			sorter_value == ''
			|| 
			(
				sorter_value != ''
				&&
				(
				qname.toLowerCase().indexOf(sorter_value)>=0
				|| 
				qsql.toLowerCase().indexOf(sorter_value)>=0
				)
			)
			) {
			ui.all_queries[qCount] =qname;
			if (qname.toLowerCase() == selectedQueryName.toLowerCase()) {
				ui.selectedQueryIndex = qCount;
				do_select = true;
			}
			qCount++;
		}
	}
	ui.all_queries.sort();
	indexArray("all_queries"); 
	ui.selectedQueryIndex = ui.all_queriesIndexFromName[selectedQueryName];
	uiQueriesRepaint(do_select);
//	if (doSelect) {
//		window.clearTimeout(ui.selectFirstQueryTimeout);
//		ui.selectFirstQueryTimeout = window.setTimeout("selectFirstQuery()", 1000);
//	}
}

function selectFirstQuery() {
	selectQuery(0);
}

function QueryBuilderSignature() {
	return "Query Builder (QuB) - version [" + getQUBVersion() + "].";
}

function qubLateLoader() {
	var ret = init_qub_environment();
	if (ret) {
		loaded();
		loaded2();
	}
}

function init_qub_environment () {
	if (window.opener) {
		try {
			var obj = window.opener;
			obj.opener = null;
			obj.close();
			window.opener = null;
		} catch(e) { }
	}

	window.moveTo(0, 0);
	window.resizeTo(screen.availWidth, screen.availHeight);
	var error_happened = false, to_alert = '';
	var i, x;
	i = 2;
	document.getElementById('qub_signature').innerHTML = QueryBuilderSignature();
	// create qub3_queries_que
	for (i=0;i<3;i++) {
		x = executeQuery("select name_que from qub3_queries_que", undefined, undefined, true);
		if (x === false || x==='') {
			if (i==0) {
				executeQuery("CREATE TABLE qub3_queries_que (name_que varchar(50) NOT NULL, query_que LONGTEXT NOT NULL, desc_que LONGTEXT NOT NULL, tables_que LONGTEXT NOT NULL, version_que varchar (10) NOT NULL)", undefined, undefined, true);
			} else {
				executeQuery("CREATE TABLE qub3_queries_que (name_que varchar(50) NOT NULL, query_que text NOT NULL, desc_que text NOT NULL, tables_que text NOT NULL, version_que varchar (10) NOT NULL)", undefined, undefined, true);
			}
		} else {
			clearSQLErrors();
			break;
		}
	}
	if (x===false || x==='') {
		to_alert += "Cannot create table qub3_queries_que\n";
		error_happened = true;
	}
	
	// create qub3_relations_rel 
	for (i=0;i<2;i++) {
		x = executeQuery("select table1_rel from qub3_relations_rel", undefined, undefined, true);
		if (x === false || x==='') {
			executeQuery("CREATE TABLE qub3_relations_rel (table1_rel varchar (100) NOT NULL, table2_rel varchar (100) NOT NULL, t1id_rel varchar (100) NOT NULL, t2id_rel varchar (100) NOT NULL, type_rel varchar (10) NOT NULL, restrict_rel int NOT NULL)", undefined, undefined, true);
		} else {
			clearSQLErrors();
			break;
		}
	}
	if (x===false || x==='') {
		to_alert += "Cannot create table qub3_relations_rel\n";
		error_happened = true;
	}

	// create qub3_settings_set 
	for(var i=0;i < 3; i++) {
		x = executeQuery("select setting_name_set from qub3_settings_set", undefined, undefined, true);
		if (x === false || x === '') {
			executeQuery("CREATE TABLE qub3_settings_set (setting_name_set varchar(32) NOT NULL,setting_value_set varchar(32) NOT NULL)", undefined, undefined, true);
			y = executeQuery("insert into qub3_settings_set (setting_name_set,setting_value_set) values ('dateseparator','\\\'')", undefined, undefined, true);
			if (y === false || y === '') {
				y = executeQuery("insert into qub3_settings_set (setting_name_set,setting_value_set) values ('dateseparator','''')", undefined, undefined, true);
			}
			executeQuery("insert into qub3_settings_set (setting_name_set,setting_value_set) values ('notequals','!=')", undefined, undefined, true);
			executeQuery("insert into qub3_settings_set (setting_name_set,setting_value_set) values ('use_asname','true')", undefined, undefined, true);
		} else {
			clearSQLErrors();
			break;
		}
	}
	if (x===false || x==='') {
		to_alert += "Cannot create table qub3_settings_set\n";
		error_happened = true;
	}
	var x = executeQuery("select * from qub3_queries_que", undefined, undefined, true);
	if (x === false || x === '') {
		to_alert += "Cannot select from qub3_queries_que";
	}
	if (error_happened) {
		to_alert = 'There have been some problems connection to your database:\n' + to_alert + "\nPlease see the manual for possible reasons and solutions. \n Also you can click click OK to open a technical note on the Adobe website with more details for your problem.";
		var open_technote = confirm(to_alert);
		if (open_technote) {
			var rand = Math.random().toString().substring(3, 10);
			var wnd = window.open($TECHNOTE, 'technote_' + rand, '');
		}
		
		window.opener = null;
		var errs = getSQLErrors();
		if (errs.length) {
			hidePleaseWait(true);
			showPleaseWait(errs.join("<br"), '260px', '', 99, true);
		}
		return false;
	}
	return true;
}
function checkSaveQuery() {
	if (top.ui.loaded && top.ui.mustSave) {
		//there's no way I can detect what was the answer
		return locales["Query modified. Leave page?"];
	}
}
function loaded2() {
	try {
	document.getElementById("query_list_sorter").value = "";
	if (top.ui.loaded && top.ui.mustSave) {
		if(confirm(locales["Save current Query?"])) {
			save();
		}
	}
	top.ui = new UIState();
	window.onbeforeunload = checkSaveQuery;
	var iframe0_td = document.getElementById('iframe0_td');
	var iframe0 = document.getElementById('iframe0');
	var iframe1_td = document.getElementById('iframe1_td');
	var iframe1 = document.getElementById('iframe1');

if(document.all){
	var iframe1_state = ui.cs.get("iframe1_state");
	if (iframe1_state != null) {
		set_iframe1_state(iframe1_state);
	}

	var iframe0_td_style_height = ui.cs.get("iframe0_td_style_height");
	if (iframe0_td_style_height == null) {
		iframe0_td_style_height = 170;
	}
	drag_index = "0";
	indrag = true;
	iframe0_td.style.height = iframe0_td_style_height + "px";
	tabbar_enddrag();

	var iframe1_td_style_height = ui.cs.get("iframe1_td_style_height");
	if (iframe1_td_style_height == null) {
		iframe1_td_style_height = 170;
	}
	iframe1.style.height=iframe1_td_style_height + "px";

	var iframe2_td_style_height = ui.cs.get("iframe2_td_style_height");
	if (iframe2_td_style_height == null) {
		iframe2_td_style_height = 320;
	}
	query_list.style.height=iframe2_td_style_height + "px";
}
	load_queries();

	var arr = [];
	document.getElementById('table_list_waiter').style.display = "";
	arr = getTables();
	document.getElementById('table_list_waiter').style.display = "none";

	for(var i=0, j=0;i<arr.length;i++) {
		if (arr[i].indexOf("qub3_") != 0 && arr[i].indexOf("qub_") != 0) {
			ui.tables[ui.tables.length] = arr[i];
		}
	}
	indexArray("tables");

	if (typeof ui.selectedQueryIndex == 'undefined' || ui.selectedQueryIndex == null) {
		ui.nxtQuery = getNXTQuery();
		if (ui.nxtQuery) {
			ui.nxtQuery = parseNXTQuery(ui.nxtQuery);
		}
		uiNewQuery(ui.selectedQueryName);
	} else {
		selectQuery(ui.selectedQueryName, false);
	}

	uiTablesRepaint();	

	top.ui.loaded = true;

	if(document.all){
		document.getElementById('resize_handler_0').onmousemove=tabbar_drag;
		document.getElementById('resize_handler_0').onmousedown=tabbar_begindrag;
		document.getElementById('resize_handler_0').onmouseup=tabbar_enddrag;

		document.getElementById('resize_handler_1').onmousemove=tabbar_drag;
		document.getElementById('resize_handler_1').onmousedown=tabbar_begindrag;
		document.getElementById('resize_handler_1').onmouseup=tabbar_enddrag;

		document.getElementById('resize_handler_2').onmousemove=tabbar_drag;
		document.getElementById('resize_handler_2').onmousedown=tabbar_begindrag;
		document.getElementById('resize_handler_2').onmouseup=tabbar_enddrag;
	}
	} catch(e) {
		//alert(e.description);
	}
	document.getElementById('query_list_sorter').focus();

	window.onresize = function() {
		if (is.mozilla) {
			var page_height = utility.dom.getPageInnerSize();
			page_height = page_height.y;
			var calculated_height = page_height - (21 + 23 + 22 + 23 + 180 + 23);
			var tables = document.getElementById('table_list');
			tables.style.height = calculated_height +  'px';
		}
	}
	window.onresize();


}

function indexArray(arrName) {
	ui[arrName+"IndexFromName"] = [];
	for(var i=0; i<ui[arrName].length; i++) {
		ui[arrName+"IndexFromName"][ui[arrName][i]] = i;
	}
}

function stopOnCheckbox(e) {
	var o = utility.dom.setEventVars(e);
	var ch = o.targ.checked;
	utility.dom.stopEvent(o.e);
	//alert(o.targ.checked = ch);
	return false;
}
function Resumer(scaller, args) {
	this.args = [];
	for(var i=0; i<args.length; i++) {
		this.args[i] = args[i];
	}

	this.exec = function() {
		var args = this.args;
		this.command = 'ret = ' + scaller +'(';
		var s = '';
		for(var i=0; i<args.length; i++) {
			s += (s==''?'':', ') + 'args['+i+']';
		}
		this.command += s +')';
		var ret = null;
		eval(this.command);
		ui.resumer = null;
		return ret;
	}
}
//idx may be index or query name
function selectQuery(idx, force_select) {
	if (typeof force_select == 'undefined') {
		force_select = false;
	}

	if (!force_select && ui.selectedQueryIndex == idx) {
		return;
	}

	if (typeof idx!='string') {
		target_query_name = ui.all_queries[idx];
	} else {
		target_query_name = idx;
		idx = ui.all_queriesIndexFromName[idx];
	}

	if (top.ui.loaded && top.ui.mustSave) {
		ui.resumer = new Resumer("selectQuery", arguments);
		openIT("dlgLoad.html", 350, 90, 120, 120, "_dlgConfirmSave", "Edit Relation");
		return;
	}
	showPleaseWait(locales["Please wait while loading..."], '160px', '', 2);
window.setTimeout(function() {
	clearQueryTablesSelection();

	deselectUIQuery(ui.selectedQueryIndex);
	top.ui.invalidate(true);

	ui.selectedQueryName = target_query_name;

	selectUIQuery(idx);
	ui.selectedQueryIndex  = idx;
	
	setQueryName(ui.all_queries[ui.selectedQueryIndex]);
	canvas.window.location.replace("canvas.html?rnd="+Math.random());
	//top.ui.invalidate(true, false, 'sqlcolumns,sqlresults');
}, 
40);
}

function checkQueryName(queryName) {
	//alert('checking');
	if ( /[^a-zA-Z0-9_]/.test(queryName) ) {
		alert(locales["Invalid query name! Please use only alphabetic characters, digits or underscores."]);
		return false;
	}
	return true;
}

function suggestQueryName() {
	var prefix = "query";
	var idx = 1;
	var ret = "";
	while(true) {
		ret = prefix+idx;
		if (typeof(ui.all_queriesIndexFromName[ret]) == "undefined") {
			break;
		}
		idx++;
	}
	return ret;
}

function uiNewQuery(queryName) {
	//should save the modified query here
	if (typeof queryName=="undefined") {
		if (top.ui.loaded && top.ui.mustSave) {
			ui.resumer = new Resumer("uiNewQuery", arguments);
			not_saved_query_index = ui.selectedQueryIndex;
			openIT("dlgLoad.html", 350, 90, 120, 120, "_dlgConfirmSave", "Edit Relation");
			return;
		}

		response = "";
		openIT("dlgNewQuery.html", 350, 140, 120, 120, "_dlgNewQuery", "New Query");
	} else {
		newQuery(queryName);
	}
}

function newQuery(queryName) {
	ui.all_queries[ui.all_queries.length] = queryName;
	ui.all_queries.sort();
	indexArray("all_queries");
	uiQueriesRepaint();
	selectQuery(ui.all_queriesIndexFromName[queryName], true);
	ui.is_new_query = true;
}

function uiDeleteQuery() {
	var queryContainer = document.getElementById('query_list');
	var zSelect_options = queryContainer.getElementsByTagName("div");

	if (!confirm(locales["Delete selected queries?"]) ) {
		return;
	}

	var must_reselect = false;
	deleteDBQuery(ui.all_queries[ui.selectedQueryIndex]);

	var one_option = document.getElementById('query_name'+ui.selectedQueryIndex);
	one_option.parentNode.removeChild(one_option);

	ui.all_queries.splice(ui.selectedQueryIndex, 1);
	indexArray("all_queries");

	top.ui.invalidate(null, false);

	if (ui.selectedQueryIndex >= ui.all_queries.length) {
		//must select next query
		var toRemoveIDX = ui.selectedQueryIndex;
		var newSelectedIndex = Math.min(toRemoveIDX, ui.all_queries.length-1);
	} else {
		var newSelectedIndex = ui.selectedQueryIndex;
	}
	ui.selectedQueryIndex = null;
	uiQueriesRepaint();
	selectQuery(newSelectedIndex);
}
function uiQueriesRepaint(doSelect) {
	var sHTML = '';
	for(var i=0;i<ui.all_queries.length;i++){
		sHTML += '<div id="query_name'+i+'" onclick="selectQuery('+i+')" class="query">'+ui.all_queries[i]+'</div>';
	}
	
	var tables = document.getElementById('query_list');
	if (ui.all_queries.length) {
		tables.innerHTML = sHTML;
		if (doSelect && ui.selectedQueryIndex!= null) {
			selectUIQuery(ui.selectedQueryIndex);
		}
	} else {
		tables.innerHTML = '<div class="no_query">'+locales["No queries found"]+'</div>';
	}
}

function uiTableCheckboxClicked(el, i) {
	if(el.checked){
		canvas.newTable(ui.tables[i]);
		canvas.undo.addUndo("Add table " + ui.tables[i] + ".");
	} else {
		var ret = canvas.delTable(ui.tables[i], true, true);
		if (ret) {
			canvas.undo.addUndo("Remove table " + ui.tables[i] + ".");
			top.tabset('sqlcolumns');
			top.tabset('sqlquery');
		} else {
			el.checked=true;
		}
	}
}
function uiTablesRepaint() {
	var sHTML = '<table cellpadding="0px" cellspacing="0px" border="0px">';
	for(var i=0; i < ui.tables.length; i++) {
		sHTML += '<tr><td><input onclick="uiTableCheckboxClicked(this, '+i+')" value="'+ui.tables[i]+'" type="checkbox" id="cbt'+i+'">';
		sHTML += '</td><td><label id="label_cbt'+i+'" for="cbt'+i+'">'+ui.tables[i]+'</label></td></tr>';
	}
	sHTML += '</table>';

	var tables = document.getElementById('table_list');
	tables.innerHTML = sHTML;
}

function deselectUIQuery(idx) {
	if (idx==null) {
		return;
	}
	if (idx>=ui.all_queries.length || idx<0) {
		return;
	}
	updateWindowTitle(' ');
	utility.dom.classNameRemove(document.getElementById("query_name" + idx), 'selected');
}
function selectUIQuery(idx) {
	if (idx>=ui.all_queries.length || idx<0) {
		return;
	}
	updateWindowTitle();
	var curSelection = 
	utility.dom.classNameAdd(document.getElementById("query_name" + idx), 'selected');
}

function selectQueryTable(idx) {
	if (typeof idx=='string') {
		idx = ui.tablesIndexFromName[idx];
	}
	try {
		document.getElementById("cbt" + idx).checked = true;
		utility.dom.classNameAdd(document.getElementById("label_cbt" + idx), 'selected');
	} catch(e) {}
}

function deselectQueryTable(idx) {
	if (typeof idx == 'string') {
		idx = ui.tablesIndexFromName[idx];
	}
	if (idx >= 0) {
		try {
			document.getElementById("cbt"+idx).checked = false;
			utility.dom.classNameRemove(document.getElementById("label_cbt" + idx), 'selected');
		} catch(e) { }
	}
}

function clearQueryTablesSelection() {
	for(var i = 0; i < ui.tables.length; i++) {
		try{
			document.getElementById("cbt"+i).checked = false;
			utility.dom.classNameRemove(document.getElementById("label_cbt" + i), 'selected');
		} catch(e) { }
	}
}

/* help functions*/

function showQUBHelp(topicID) {
	if (typeof help_topics[topicID] == "undefined") {
		window.status = "Topic ID not defined:["+topicID+"]";
		return;
	}
	openHelpWindowIT(HELP_SITE_ROOT + "?content=" + help_topics[topicID], 650, 490);
	return true;
}
top.showQUBHelp = showQUBHelp;
