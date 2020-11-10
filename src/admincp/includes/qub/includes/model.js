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

sprintf = utility.string.sprintf;
relation_color = {
	no : '#0000CC'
	, yes : '#CC0000'
}
var NEW_LINE = "\r\n";

function Data_objserialize(name) {
	var y = this.getFullYear() + "";
	var m = this.getMonth();
	m = (m<10?"0"+m:m) + "";
	var d = this.getDate();
	d = (d<10?"0"+d:d) + "";
	var h = this.getHours();
	h = (h<10?"0"+h:h) + "";
	var i = this.getMinutes();
	i = (i<10?"0"+i:i) + "";
	var s = this.getSeconds();
	s = (s<10?"0"+s:s) + "";

	var data = y+m+d+h+i+s;
	return data;
}

function Date_objdeserialize(str) {
	var matches = str.match(/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/);
	return new Date(matches[1], matches[2], matches[3], matches[4], matches[5], matches[6]);
}
Date.prototype.objserialize = Data_objserialize;
Date.prototype.objdeserialize = Date_objdeserialize;

function String_objdeserialize(version_check) {
	version_check = typeof(version_check)=="undefined"?true:version_check;
	var compiler_error = "";
	ret = null;

	if (version_check) {
		var current_qub_version = parent.getQUBVersion();
		//match version [2.10.4.9]
		var this_version = (this+"").match(/version\s\[((?:Pro-)?[0-9\.]*)\]/)

		if (this_version && version_compare(this_version[1], current_qub_version)>0) {
			compiler_error = sprintf(locales["QuB cannot edit queries saved with QuB versions greater than [%s]."], current_qub_version);
		}
		var min_version = parent.getQUBMinVersion();
	
		if (!this_version || this_version && version_compare(this_version[1], min_version)<0) {
			compiler_error = sprintf(locales["QuB cannot edit queries saved with QuB versions prior to version [%s]."], min_version);
		}
	}
	if (compiler_error=="" ) {
		//alert("try{\r\n" + this + "\r\n}catch(qubobjcreationerror) {compiler_error = sprintf(locales['Invalid query source!'], qubobjcreationerror.description);}");
		eval("try{" + NEW_LINE + this + NEW_LINE + "}catch(qubobjcreationerror) {compiler_error = sprintf(locales['Invalid query source!'], qubobjcreationerror.description);}");
	}
	if (compiler_error!="") {
		alert(compiler_error);
		ret = new SQLQuery('myQuery');
	}

	return ret;
}
String.prototype.objdeserialize = String_objdeserialize;
function version_compare(ver1, ver2) {
	var pro_check = 0;
	var pro1 = pro2 = false;
	if (ver1.indexOf("Pro-") == 0) {
		ver1 = ver1.substring(4);
		pro1 = true;
	}
	if (ver2.indexOf("Pro-") == 0) {
		ver2 = ver2.substring(4);
		pro2 = true;
	}
	//normal version check
	var arr1 = ver1.split(".");
	var arr2 = ver2.split(".");

        // allow to edit queries saved with QUB from Kollection 3.7.1
        if(arr1.length == 3 && parseInt(arr1[0]) < 3) {
          arr1[0] = parseInt(arr1[0]) + 4;
          pro1 = true;
        }
        if(arr2.length == 3 && parseInt(arr2[0]) < 3) {
          arr2[0] = parseInt(arr2[0]) + 4;
          pro2 = true;
        }

	var cmp = 0;
	var ix = 0;
	while (cmp == 0 && (arr1.length > ix || arr2.length > ix)) {
		if (((arr1.length <= ix) && (arr2.length > ix)) || (parseInt(arr1[ix]) < parseInt(arr2[ix]))) {
			cmp = -1;
		} else if (((arr2.length <= ix) && (arr1.length > ix)) || (parseInt(arr1[ix]) > parseInt(arr2[ix]))) {
			cmp = 1;
		}
		ix++;
	}

	//can use QuB pro to edit queries saved with QuB non-Pro, no matter the versions
	if (pro1 && !pro2) {
		cmp = 1;
	}
	//can not use QuB non-Pro to edit queries saved with QuB Pro, no matter the versions
	if (!pro1 && pro2) {
		cmp = -1;
	}
	return cmp;
}

var zindex_auto_incrementer = 91;
// simple UID generator
function UIDGenerator(name) {
	if (typeof(name) == 'undefined') {
		name = 'iaktuid_' + Math.random().toString().substring(2, 6) + '_';
	}
	this.name = name;
	this.counter = 1;
}
function UIDGenerator_generate() {
	return (this.name + this.counter++ + '_');
}
UIDGenerator.prototype.generate = UIDGenerator_generate;

top.uidgen = new UIDGenerator();

function SQLCollection (name) {
	this.name = name;
	this.items = [];
	this.itemsNameToIndex = {};
	this.length = 0;
}

function SQLCollection_reIndex () {
	this.itemsNameToIndex = {};
	for(var i=0; i<this.items.length; i++) {
		var oItem = this.items[i];
		if ( typeof(oItem) == "string" ) {
			this.itemsNameToIndex[oItem] = i;
		} else {
			this.itemsNameToIndex[oItem.name] = i;
		}
	}
	this.length = this.items.length;
}

function SQLCollection_item (idx) {
	if(typeof(idx)=="number") {
		return this.items[idx];
	} else if(typeof(idx)=="string") {
		return this.items[this.itemsNameToIndex[idx]];
	} else {
		return this.items[this.itemsNameToIndex[idx.name]];
	}
}

function SQLCollection_insert (item, idx) {
	this.items.splice(idx, 0, item);
	this.reIndex();
}

function SQLCollection_add (item, sorted) {
	if(typeof(sorted)=="undefined") {
		sorted = false;
	}
	this.items[this.items.length] = item;

	if (sorted) {
		this.items.sort(osort);
		this.reIndex();
	} else {
		if (typeof(item)=="object") {
			this.itemsNameToIndex[item.name] = this.items.length-1;
		} else {
			this.itemsNameToIndex[item] = this.items.length-1;
		}
		this.length = this.items.length;
	}
}
function SQLCollection_remove (idx) {
	if(typeof(idx)=="number") {
		this.items.splice(idx, 1);
	} else if(typeof(idx)=="string") {
		this.items.splice(this.itemsNameToIndex[idx], 1);
	} else {
		this.items.splice(this.itemsNameToIndex[idx.name], 1);
	}
	this.reIndex();
}
function SQLCollection_replace (item_old, item_new) {
	idx = this.itemsNameToIndex[item_old.name];
	this.items[idx] = item_new;
	this.reIndex();
}
function SQLCollection_item_rename (item_old_name, item_new_name) {
	idx = this.itemsNameToIndex[item_old_name];
	if(typeof(this.items[idx])=="string") {
		this.items[idx] = item_new_name;
	} else {
		this.items[idx].name = item_new_name;
	}

	this.reIndex();
}
function SQLCollection_removeAll () {
	this.items = [];
	this.itemsNameToIndex = {};
	this.length = 0;
}

SQLCollection.prototype.reIndex = SQLCollection_reIndex;
SQLCollection.prototype.item = SQLCollection_item;
SQLCollection.prototype.insert = SQLCollection_insert;
SQLCollection.prototype.add = SQLCollection_add;
SQLCollection.prototype.remove = SQLCollection_remove;
SQLCollection.prototype.replace = SQLCollection_replace;
SQLCollection.prototype.item_rename = SQLCollection_item_rename;
SQLCollection.prototype.removeAll = SQLCollection_removeAll;

function osort(o1, o2) {
	if (typeof(o1)=="object") {
		return o1.name==o2.name?0:(o1.name>o2.name?1:-1);
	} else {
		return o1==o2?0:(o1>o2?1:-1);
	}
}

function SQLQuery(name) {
	this.name = name;
	this.distinct = false;
	this.date_creation = new Date();
	this.date_modified = new Date();
	this.tables = new SQLCollection("tables");
	this.relations = new SQLCollection("relations");
	this.conditions = new SQLCollection("conditions");
	this.version = parent.getQUBVersion();
}

function SQLQuery_new_table(table_name, alias_name) {
	if(typeof(alias_name) == "undefined") {
		alias_name = table_name;
	}
	var new_table = new SQLTable(table_name, alias_name);
	new_table.query = this;
	this.tables.add(new_table);
	return new_table;
}

function SQLQuery_new_condition (condition) {
	var newCondition = new SQLCondition(condition);
	newRondition.query = this;
	this.conditions.add(newCondition);
	return newCondition;
}

function SQLQuery_new_relation (relation) {
	var newRelation = new SQLRelation(relation);
	newRelation.query = this;
	this.relations.add(newRelation);
	return newRelation;
}
function SQLQuery_save () {
	//
	if(typeof(this.date_creation) == "undefined") {
		this.date_creation= new Date();
	}
	this.date_modified = new Date();
	//serialize and save it
}

function SQLQuery_serialize () {
	var uniq = "z";//top.uidgen.generate("tmp");
	var s = '';
	s += sprintf('/* Created with Query Builder (QuB) - version [%s].*/' + NEW_LINE, this.version);
	s += sprintf('var %s = new SQLQuery("%s");' + NEW_LINE, uniq, this.name);
	s += sprintf('var ret = %s;' + NEW_LINE, uniq);
	s += sprintf('%s.version = "%s";' + NEW_LINE, uniq, this.version + '');
	s += sprintf('%s.date_creation = new Date().objdeserialize("%s");' + NEW_LINE, uniq, this.date_creation.objserialize());
	s += sprintf('%s.date_modified = new Date().objdeserialize("%s");' + NEW_LINE, uniq, this.date_modified.objserialize());
	s += sprintf('%s.distinct = %b;' + NEW_LINE, uniq, this.distinct);
	for(var i=0;i<this.tables.length; i++) {
		//s += sprintf('var tmp = %s.new_table("%s", "%s");' + NEW_LINE, uniq, this.tables.item(i).table_name, this.tables.item(i).name);
		s += this.tables.item(i).serialize(uniq);
	}
	for(var i=0;i<this.conditions.length; i++) {
		s += sprintf('var tmp = %s.new_condition("%s");' + NEW_LINE, uniq, this.conditions.item(i).name);
		s += this.conditions.item(i).serialize(uniq);
	}
	for(var i=0;i<this.relations.length; i++) {
		//s += sprintf('var tmp = %s.new_relation("%s");' + NEW_LINE, uniq, this.relations.item(i).name);
		s += this.relations.item(i).serialize(uniq);
	}

	//s += 'alert(ret)';
	return s;
}

function cindex_sort(c1,c2) {
	if(!c1.checked && !c2.checked) {
		return 0;
	}
	if(!c1.checked) {
		return -1;
	}
	if(!c2.checked) {
		return 1;
	}
	return c1.cindex>c2.cindex?1:(c1.cindex==c2.cindex?0:-1);
}
function SQLQuery_reindex_columns() {
	var ordered_columns = [];
	for(var i=0;i<this.tables.length; i++) {
		var oTable = this.tables.item(i);
		for(var j=0; j<oTable.columns.length; j++) {
			if (oTable.columns.item(j).checked) {
				ordered_columns[ordered_columns.length] = oTable.columns.item(j);
			} else {
				oTable.columns.item(j).cindex = -1;
			}
		}
	}
	ordered_columns.sort(cindex_sort);
	for(var i=0; i<ordered_columns.length; i++) {
		ordered_columns[i].cindex = i;
	}
}

function SQLQuery_selectAll() {
	for (var i=0; i<this.tables.length; i++) {
		this.tables.item(i).select();
	}
}

function SQLQuery_print(el, mode, target_wnd) {
	var uniq = top.uidgen.generate("tmp");
	var s = '';
	this.target_wnd = target_wnd;

	switch(mode) {
	case "print":
		s += sprintf('<div>Query:%s<br>', this.name);
		s += sprintf('Created on: %s<br>', this.date_creation);
		s += sprintf('Last modified on: %s</div>', this.date_modified);

		return s;
	case "table_checkboxes":
		top.clearQueryTablesSelection();
		for(var i=0; i<this.tables.length; i++) {
			top.selectQueryTable(this.tables.item(i).table_name);
		}
		return;
	case "diagram":
		//s += this.date_creation+":"+this.date_modified;
		var zel = utility.dom.createElement("DIV", {}, this.target_wnd);
		zel = el.appendChild(zel);

		this.container = zel;
		for(var i=0;i<this.tables.length; i++) {
			this.tables.item(i).print(uniq, mode);
		}
		for(var i=0;i<this.conditions.length; i++) {
			this.conditions.item(i).print(uniq, mode);
		}
		for(var i=0;i<this.relations.length; i++) {
			this.relations.item(i).print(uniq, mode);
		}
		return;
	case "columns":
		var ordered_columns = [];
		var ordered_o = [];
		for(var i=0;i<this.tables.length; i++) {
			var oTable = this.tables.item(i);
			for(var j=0; j<oTable.columns.length; j++) {
				if (oTable.columns.item(j).checked) {
					o = new Object();
					o.table    = oTable.name;
					o.column   = oTable.columns.item(j).column_name;
					o.alias    = oTable.columns.item(j).name==oTable.columns.item(j).column_name?'':oTable.columns.item(j).name;
					o.aggFunc  = oTable.columns.item(j).aggFunc;
					o.order    = oTable.columns.item(j).order;
					o.output   = oTable.columns.item(j).output;
					o.tdType   = oTable.columns.item(j).data_type;
					o.tdSep    = oTable.columns.item(j).sep;
					o.sqlCondition = '';
					if (typeof oTable.columns.item(j).condition != "undefined") {
						o.sqlCondition = oTable.columns.item(j).condition.print("to", "expression");
					}
					o.hasWhere = 0;
					oTable.columns.item(j).o = o;
					ordered_columns[ordered_columns.length] = oTable.columns.item(j);
					ordered_o[oTable.columns.item(j).cindex] = o;
				}
			}
		}
		if (ordered_columns.length == 0) {
			top.iframe0.zTable.style.display = "none";
			top.iframe0.zAllColumns.style.display = "block";
		} else {
			top.iframe0.zAllColumns.style.display = "none";
			top.iframe0.zTable.style.display = "block";
		}
		ordered_columns.sort(cindex_sort);
		for(var j=0; j<ordered_columns.length; j++) {
			top.iframe0.insertTR(ordered_columns[j].column_name, ordered_columns[j].o);
		}
		return;
	}
}

SQLQuery.prototype.new_table = SQLQuery_new_table;
SQLQuery.prototype.new_condition = SQLQuery_new_condition;
SQLQuery.prototype.new_relation = SQLQuery_new_relation;
SQLQuery.prototype.save = SQLQuery_save;
SQLQuery.prototype.serialize = SQLQuery_serialize;
SQLQuery.prototype.reindex_columns = SQLQuery_reindex_columns;
SQLQuery.prototype.selectAll = SQLQuery_selectAll;
SQLQuery.prototype.print = SQLQuery_print;


function UIObject(name, x, y, sselected) {
	this.name = name;
	this.x = x;
	this.y = y;
	this.selected=sselected;
}

function UIObject_move (x, y) {
		this.x = x;
		this.y = y;
		var o = document.getElementById(this.name);
		try {
			o.style.left = x;
			o.style.top = y;
		} catch(e){}
}
UIObject.prototype.move = UIObject_move;

function SQLTable(table_name, alias_name) {
	//the name is actually the alias name
	this.name = alias_name;
	this.table_name = table_name;
	this.tDetails = getTableInfo(table_name);
	this.tKeys = getKeysOfTable(table_name);

	this.container = null;
	this.select_all = true;
	this.columns = new SQLCollection("columns");
	//holds information about UI:table position in diagram (x/y)
	this.ui = new UIObject(this.name, 12, 12, false);
}

function SQLTable_duplicate() {
	return duplicateTable(this.name);
}

function SQLTable_realias(new_alias) {
	var old_alias = this.name;
	var old_column_name = this.table_name;

	this.query.tables.item_rename(old_alias, new_alias); 
	this.container.removeAttribute("id");
	this.container.setAttribute("id", new_alias);
	this.container.setAttribute("tableName", new_alias);
	this.ui.name = new_alias;
	var cbs = this.container.getElementsByTagName("INPUT");
	for(var i=0; i<cbs.length; i++) {
		if (cbs[i].className == "cbfortablecolumn") {
			cbs[i].table_name = new_alias;
		}
	}
	this.container.firstChild.rows[0].cells[0].innerHTML = new_alias;

	for(var i=0; i<this.query.relations.length; i++) {
		var rel = this.query.relations.item(i);
		if (rel.table1 == old_alias) {
			rel.table1 = new_alias;
			//rel.repaint();
		}
		if (rel.table2 == old_alias) {
			rel.table2 = new_alias;
		//	rel.repaint();
		}
	}

	top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
}

function SQLTable_new_column (alias_name, column_name) {
	if ( typeof(column_name)=="undefined" ) {
		column_name = alias_name;
	}
	var newColumn = new SQLColumn(alias_name, column_name);
	newColumn.table = this;
	newColumn.query = this.query;
	newColumn.is_pk = typeof this.tKeys[column_name] != "undefined";
	if (column_name=="*") {
		sep = "";
		data_type = "";
		column_length = 0;
	} else if (typeof this.tDetails[column_name] == "undefined") {
		//alert("Column " + column_name + " was removed");
		sep = "";
		data_type = "int";
		column_length = 0;
		newColumn.was_deleted = true;
	} else {
		var tdi = (this.tDetails[column_name]['data_type']+"").toLowerCase();
		if (tdi.indexOf("date") != -1 || tdi.indexOf("time") != -1 || tdi.indexOf("year") != -1) {
			sep = "2";
		} else if (tdi.indexOf("int") != -1) {
			sep = "";
		} else if (tdi.indexOf("bit") != -1 || tdi.indexOf("counter") != -1 || tdi.indexOf("float") != -1 || tdi.indexOf("double") != -1 || tdi.indexOf("money") != -1 || tdi.indexOf("currency") != -1 || tdi.indexOf("number") != -1 || tdi.indexOf("decimal") != -1) {
			sep = "";
		} else {
			sep = "1";
		}
		data_type = this.tDetails[column_name]['data_type'];
		column_length = this.tDetails[column_name]['column_length'];
		newColumn.was_deleted = false;
	}
	newColumn.sep = sep;
	newColumn.data_type = data_type;
	newColumn.column_length = column_length;
	this.columns.add(newColumn);
	return newColumn;
}
function SQLTable_addAllColumns () {
	this.new_column("*");
	for (var i in this.tDetails) {
		if(i=="length") {
			continue;
		}
		this.new_column(this.tDetails[i]['name']);
	}
}
function SQLTable_remove () {
	for(var i=0; i<this.query.relations.length; i++) {
		var oneRel = this.query.relations.item(i);
		if (oneRel.table1 == this.name || oneRel.table2 == this.name) {
			oneRel.remove();
			i--;
		}
	}

	if (top.iframe0) {
		for(var i=0; i<this.columns.length; i++) {
			if (this.columns.item(i).checked) {
				top.iframe0.delRow(this.columns.item(i).name, this.name);
			}
		}
		if (this.query.tables.length == 1) {
			top.iframe0.document.getElementById('select_distinct').checked = false;
			this.query.distinct = false;
		}
	}
	if (this.container) {
		//remove the div
		this.container.parentNode.removeChild(this.container);
	}	
	//remove the table object from its parent query tables collection
	this.query.tables.remove(this.name);
	this.query.reindex_columns();
}
function SQLTable_serialize (uniq) {
	var s = '';
	if (typeof uniq=="undefined") {
		s += sprintf('var tmp = new SQLTable("%s", "%s");' + NEW_LINE, this.table_name, this.name);
	} else {
		s += sprintf('var tmp = %s.new_table("%s", "%s");' + NEW_LINE, uniq, this.table_name, this.name);
	}
	s += sprintf('tmp.table_name = "%s";' + NEW_LINE, this.table_name);
	s += sprintf('tmp.select_all = %s;' + NEW_LINE, this.select_all);
	s += sprintf('tmp.ui = new UIObject("%s",%s,%s,%s);' + NEW_LINE, this.name, this.ui.x, this.ui.y, this.ui.selected);
	for(var i=0;i<this.columns.length; i++) {
		s += sprintf('var tmp2 = tmp.new_column("%s", "%s");' + NEW_LINE, this.columns.item(i).name, this.columns.item(i).column_name);
		s += this.columns.item(i).serialize(uniq);
	}

	return s;
}
function SQLTable_deselect () {
	var o = document.getElementById(this.name);
	this.ui.selected = false;
	o.style.borderColor="#EEEEEE";
	o.style.borderStyle="solid";
	top.mo.selectedTable = "";
	for(i=0;i<currentDiv.length;i++){
		if(currentDiv[i].id == this.name) {
			break;
		}
	}
	if(i!=currentDiv.length){
		currentDiv[i] = currentDiv[currentDiv.length-1];
		currentDiv.pop();
	}
}

function SQLTable_select(do_ui, do_navigate) {
	if(typeof(do_ui) == "undefined") {
		do_ui = true;
	}
	if (do_ui) {
		var o = this.container;
		this.ui.selected = true;
		top.mo.selectedTable = this.name;
		o.style.borderColor="black";
		o.style.borderStyle="dashed";
		if (zindex_auto_incrementer>990) {
			//reset zindexes if they went to high
			var arr = utility.dom.getElementsByClassName(document.body, 'qtable');
			var max_z = 0, cur_z = 0;
			for (var i = 0; i < arr.length; i++) {
				arr[i].parentNode.style.zIndex = arr[i].parentNode.style.zIndex - 900;
				cur_z = arr[i].parentNode.style.zIndex;
				max_z = max_z>cur_z?max_z:cur_z;
			};
			zindex_auto_incrementer = max_z;
		}
		//just bring this div to front :)
		o.style.zIndex = ++zindex_auto_incrementer;
		currentDiv.push(o);
	}
}

function SQLTable_print(uniq, mode) {
	var s = "";
	switch(mode) {
	case "print":
		s += sprintf('<div>Table:%s</div>', this.name);
		break;
	case "diagram":
		var zDiv = utility.dom.createElement("DIV", {
			id: this.name,
			tableName: this.name,
			realTableName: this.table_name, 
			className:'table',
			style:sprintf('position:absolute; left:%spx; top:%spx; z-index:%s;', this.ui.x, this.ui.y, ++zindex_auto_incrementer)
		}, this.query.target_wnd);
		this.container = this.query.container.appendChild(zDiv);
		this.container.innerHTML = sprintf('<table border=0 cellpadding=0 cellspacing=0 class="qtable">\n\
				<tbody>\n\
					<tr><th colspan=4 class="qth" valign="baseline" align=center nowrap>%s</th></tr>\
				</tbody>\n\
			</table>', this.name);
		//paint table columns
		for(var i=0;i<this.columns.length; i++) {
			this.columns.item(i).print(uniq, mode);
		}
		this.container.ondblclick = tableDblClick;
		this.container.onmousedown = divMouseDown;
		this.container.onmouseup = divMouseUp;

		top.selectQueryTable(this.table_name);
		break;
	}
	return '';
}


SQLTable.prototype.duplicate = SQLTable_duplicate;
SQLTable.prototype.realias = SQLTable_realias;
SQLTable.prototype.new_column = SQLTable_new_column;
SQLTable.prototype.addAllColumns = SQLTable_addAllColumns;
SQLTable.prototype.remove = SQLTable_remove;
SQLTable.prototype.serialize = SQLTable_serialize;
SQLTable.prototype.deselect = SQLTable_deselect;
SQLTable.prototype.select = SQLTable_select;
SQLTable.prototype.print = SQLTable_print;

//the column name is an index in the table's columns collection, so it must be unique
//the name is actually the alias of the column
//the real column name is column_name
function SQLColumn(alias, name) {
	this.name = alias;
	if (typeof(name) == "undefined") {
		name = alias;
	}
	this.column_name = name;
	this.is_pk = false;
	this.is_fk = false;
	this.table = null;
	this.checked = false;
	//this is the column index in the ordered columns output (the rowIndex-1 of the column in the columns tab)
	this.cindex = -1;

	this.aggFunc  = "";
	this.order    = "";
	this.output   = name=="*";
	this.sqlCondition = "";
}

function SQLColumn_copy (clmn) {
	this.is_pk = clmn.is_pk;
	this.is_fk = clmn.is_fk;
	this.checked = clmn.checked;
	this.cindex = clmn.cindex;
	this.aggFunc = clmn.aggFunc;
	this.order = clmn.order;
	this.data_type = clmn.data_type;
	this.sep = clmn.sep;
	this.column_length = clmn.column_length;
	this.output = clmn.output;
	this.sqlCondition = clmn.sqlCondition;
	if (typeof clmn.condition!="undefined") {
		this.condition = clmn.condition;
	}
}

function SQLColumn_replace(new_column) {
	var zTable = this.table;
	var idx = this.table.columns.itemsNameToIndex[this.name];
	zTable.columns.insert(new_column, idx);
	new_column.table = zTable;
	new_column.print("", "diagram");
	delete this.condition;
	this.remove(true);
}
//create a relation between this column and another column (the target column) (link f1 to f2)
function SQLColumn_link (f2, check_existing_relations) {
	if (typeof check_existing_relations == "undefined") {
		check_existing_relations = true;
	}
	var type = 'inner';
	var card1 = '1';
	var card2 = 'n';
	var restrict = 'no';

	if (check_existing_relations) {
		for(var i=0; i<this.table.query.relations.length; i++) {
			var rel = this.table.query.relations.item(i);
			is_1 = rel.table1 == this.table.name && rel.table2 == f2.table.name;
			is_2 = rel.table2 == this.table.name && rel.table1 == f2.table.name
			if ( is_1 || is_2 ) {
				var dupe = f2.table.duplicate();
				dupe.ui.move(f2.table.ui.x, f2.table.ui.y + f2.table.container.offsetHeight + 20);
				if (f2.name!=f2.column_name) {
					var new_col = dupe.new_column(f2.name, f2.column_name);
					new_col.copy(f2);
					new_col.print("to", "diagram");
				}
				return this.link(dupe.columns.item(f2.name), false);;
			}
		}
	}

	var rel = this.query.new_relation(top.uidgen.generate("relation"));
	rel.table1 = this.table.name;
	rel.field1 = this.name;

	rel.table2 = f2.table.name;
	rel.field2 = f2.name;

	rel.restrict = restrict;
	/*
	relation create effects:
		- will change the cardinality of the relation and...
		- may change the FK status of one of the columns 
	as in the following table:
	Field1->Field2 card1 card2  FK
		PK  ->  PK    1     1     -
		PK  ->  N     1     n     F2
		N	  ->  PK    n     1     F1
		N   ->  N     n     1     F1
	*/
	rel.card1 = card1;
	rel.card2 = card2;
	//zdbg(this.is_pk+":"+rel.card1+":"+rel.card2);
	rel.print("ceva", "diagram");

	this.repaint();
	this.table.query.tables.item(f2.table.name).columns.item(f2.name).repaint();
	return rel;
}

function SQLColumn_set_checked (what) {
	var old_checked = this.checked;
	this.checked = what;
	if (!old_checked && what) {
		//put this column last in output order
		this.cindex = 1000000;
		this.table.query.reindex_columns();
	}

	if (old_checked && !what) {
		//remove this column from output
		this.cindex = -1;
		this.table.query.reindex_columns();
	}

	if (this.name == "*") {
		this.table.select_all = what;
		this.output = what;
	}
	if (this.checked) {
		if (this.name != "*") {
			if (myQuery.tables.item(this.table.name).columns.item("*").checked) {
				myQuery.tables.item(this.table.name).columns.item("*").set_checked(false);
			}
		}
	} else {
		if (this.name != "*" && !this.doomed) {
			if (!myQuery.tables.item(this.table.name).columns.item("*").checked) {
			//check if there is at least one column checked
			//if not, check the "*" column (from ACO)
				var check_the_asterix = true;
				for(var i=0; i<myQuery.tables.item(this.table.name).columns.length; i++) {
					if (
						myQuery.tables.item(this.table.name).columns.item(i).name != "*"
							&&
						myQuery.tables.item(this.table.name).columns.item(i).checked
					) {
						check_the_asterix = false;
						break;
					}
				}
				if (check_the_asterix) {
					select_all_checkbox_clicked(null, true, this.table.name);
				}
			}
		}
	}

	try {
		var row_container = utility.dom.getElementsByTagName(this.table.container, 'table')[0];
		row_container.rows[this.table.columns.itemsNameToIndex[this.name]+1].cells[1].firstChild.checked = what;
	}catch(e) {}
}

function SQLColumn_repaint () {
	var row_container = utility.dom.getElementsByTagName(this.table.container, 'table')[0];
	row_container.deleteRow(this.table.columns.itemsNameToIndex[this.name]+1);
	this.print("some", "diagram");
}
function SQLColumn_isFK() {
	if (this.is_pk || this.was_deleted) {
		return false;
	}
	var rels = this.table.query.relations;
	var toret = false;
	for(var i=0; i<rels.length; i++) {
		var rel = rels.item(i);
		if (rel.table1 == this.table.name && rel.field1 == this.name
			||  
		rel.table2 == this.table.name && rel.field2 == this.name
		) {
			toret =  true;
		}
	}
	return toret;
}

function SQLColumn_remove(conf, forced) {
	if (typeof forced=="undefined") {
		forced = false;
	}
	if (!forced && this.name == this.column_name) {
		//cant remove columns that are not aliases
		//but remove the coresponding row from the columns tab
		this.set_checked(false);
		if (top.iframe0.delRow) {
			top.iframe0.delRow(this.name, this.table.name);
		}
		this.sqlCondition = '';
		delete this.condition;
		this.output = false;
		return false;
	}
	if (!conf) {
		if(!confirm(sprintf(locales["Are you sure you want to remove"], "column " + this.name))) {
			return false;
		}
	}
	this.sqlCondition = '';
	delete this.condition;
	//doomed signals other objects to not use this column because is going to be kicked out from the columns collection
	this.doomed = true;

	for(var i=0; i<this.table.query.relations.length; i++) {
		var oneRel = this.table.query.relations.item(i);
		if (
			oneRel.table1 == this.table.name && oneRel.field1 == this.name
			 || 
			oneRel.table1 == this.table.name &&  oneRel.field2 == this.name
		) {
			//alert("Remove relation:"+oneRel.table1 + ":" + oneRel.table2);
			oneRel.remove();
			i--;
		}
	}

	try {
		this.table.container.firstChild.deleteRow(this.table.columns.itemsNameToIndex[this.name]+1);
	} catch(e) {}

	if (top.iframe0.delRow) {
		top.iframe0.delRow(this.name, this.table.name);
	}

	this.table.columns.remove(this.name);
	this.table.query.reindex_columns();
	return true;
}

function SQLColumn_parseSQLCondition(str) {
	if (typeof str == 'undefined') {
		str = '';
	}
	var m = null;
	var canOpen = false;
	if (str != '') {
		if (typeof this.condition != 'undefined' && this.condition != null) {
			var r = this.condition;
		} else {
			var r = new SQLCondition(top.uidgen.generate("condition"));
			r.column = this;
			this.condition = r;
		}
		r.string = str;
		for (var ct in CONDITION_TYPES) {
			if (!CONDITION_TYPES.hasOwnProperty(ct)) {
				continue;
			}
			var rgx = CONDITION_TYPES[ct];
			if (!rgx) {
				continue;
			}
			if (ct == '<>' || ct == '!=') {
				ctv = '<>';
			} else {
				ctv = ct;
			}
			if (m = str.match(rgx)) {
				r.cond_type = ct;
				var expr = '';
				if (! (r.cond_type == 'is null' || r.cond_type == 'is not null')) {
					expr = m[1];
				}
				
				if (expr != '') {	//try to get the var_type, param_name
					var tmp = decodeServerVariable(expr);
					r.var_type = tmp.var_type;
					r.param_name = tmp.param_name;
					if (r.var_type == 'Entered Value') {
						r.test_value = r.param_name;
					}
				}
				canOpen = true;
				break;
			}
		}
	} else {
		delete this.condition;
		canOpen = true;
	}
	return canOpen;
}

function SQLColumn_serialize (uniq) {
	var s = '';
	if (this.checked) {
		s += sprintf('tmp2.checked = %b;' + NEW_LINE, this.checked);
	}
	if (this.aggFunc!='') {
		s += sprintf('tmp2.aggFunc = "%s";' + NEW_LINE, this.aggFunc);
	}
	if (this.order!='') {
		s += sprintf('tmp2.order = "%s";' + NEW_LINE, this.order);
	}
	if (this.output) {
		s += sprintf('tmp2.output = true;' + NEW_LINE);
	}
	if (this.cindex>=0) {
		s += sprintf('tmp2.cindex = %s;' + NEW_LINE, this.cindex);
	}
	if (typeof this.condition != 'undefined' && typeof this.condition.serialize != 'undefined') {
		s += 'tmp3 = new SQLCondition();' + NEW_LINE;
		s += this.condition.serialize();
		s += 'tmp2.condition = tmp3;' + NEW_LINE;
	}

	return s;
}
function getNextColumnAliasName (agg_func, column_name) {
	var tmp_aggFunc = agg_func;
	if ( tmp_aggFunc != '' ) {
		tmp_aggFunc += '_';
	}
	var count = 1;
	var alias = "";
	while (true) {
		var do_break = false;
		alias = tmp_aggFunc + column_name + '_' + count;
		for ( var i=0; i < myQuery.tables.length; i++ ) {
			var zTable = myQuery.tables.item(i);
			for ( var j=0; j < zTable.columns.length; j++ ) {
				var zCol = zTable.columns.item(j);
				if ( typeof(zTable.columns.item(alias)) != "undefined" ) {
					count++;
					do_break = true;
					break;
				}
			}
			if (do_break) {
				break;
			}
		}
		if (!do_break) {
			return alias;
		}
	}

}

function select_all_checkbox_clicked(obj, checked, table_name) {
	var table = myQuery.tables.item(table_name);

	if (checked) {
		for(var i=table.columns.length-1; i>=0; i-- ) {
			var col = table.columns.item(i);
			if (col.name=="*" || col.doomed) {
				continue;
			}
			if (col.checked) {
				col.set_checked(false);
				col.remove(true);
			}
		}
	} else {
		table.columns.item("*").set_checked(true);
		return true;
	}
	table.columns.item("*").set_checked(checked);

	table.select_all = checked;
	top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
	return true;
}

function column_checkbox_clicked(obj, checked, table_name, alias_name, column_name) {
	if (click_drag_flag) {
		obj.checked = !obj.checked;
		return false;
	}

	top.ui.sqlQueryRebuild=true;
	if ( checked ){
		if (obj) {
			table_name = obj.getAttribute("table_name");
		}
		var theCol = myQuery.tables.item(table_name).columns.item(alias_name);
		if (theCol.was_deleted) {
			theCol.set_checked(false);
			return false;
		}
		//will set checked false,select_all false and remove the row from the sqlcolumns tab
		myQuery.tables.item(table_name).columns.item("*").remove(true);
		for ( var i=0; i < myQuery.tables.length; i++ ) {
			var zTable = myQuery.tables.item(i);
			for ( var j=0; j < zTable.columns.length; j++ ) {
				var zCol = zTable.columns.item(j);
				if ( zCol.checked && theCol.name!="*" && zCol.name == theCol.name ) {
					theCol.set_checked(false);
					var alias = getNextColumnAliasName(theCol.aggFunc, theCol.column_name);
					var nClmn = theCol.table.new_column(alias, theCol.column_name);
					nClmn.copy(theCol);
					nClmn.checked = true;
					nClmn.output = true;
					delete theCol.condition;
					theCol.output = false;
					nClmn.print("some", "diagram");
					nClmn.set_checked(true);
					top.ui.invalidate(true, true, 'sqlcolumns,sqlquery');
					return true;
				}
			}
		}
		o = new Object();
		o.table    = theCol.table.name;
		o.column   = theCol.column_name;
		o.alias    = theCol.name==theCol.column_name?'':theCol.name;
		o.aggFunc  = theCol.aggFunc;
		o.order    = theCol.order;
		o.output   = theCol.output = true;
		o.tdType   = theCol.data_type;
		o.tdSep    = theCol.sep;
		o.sqlCondition = theCol.sqlCondition;
		o.hasWhere = 0;

		theCol.cindex = 1000000;
		myQuery.reindex_columns();

		if(top.iframe0.insertTR) {
			top.iframe0.insertTR(column_name, o, true);
		}
		theCol.set_checked(true);

	} else {
		myQuery.tables.item(table_name).columns.item("*").remove(true);

		var theCol1 = myQuery.tables.item(table_name).columns.item(column_name);
		var theCol2 = myQuery.tables.item(table_name).columns.item(alias_name);
		if(column_name!=alias_name) {
			var ret = theCol2.remove();
			if(!ret) {
				theCol2.set_checked(true);
			}
		} else {
			theCol1.remove();
		}
	}

	top.ui.invalidate(true, true, 'sqlquery');
	return true;
}

function cbfortablecolumn_clicked() {

}

function SQLColumn_print (uniq, mode) {
	var s = "";
	switch(mode) {
	case "print":
		s += sprintf('<li>column:%s</li>', this.name);
		break;
	case "diagram":
		var new_index = this.table.columns.itemsNameToIndex[this.name]+1;
		if (new_index >= this.table.container.firstChild.rows.length) {
			new_index = -1;//this.table.container.firstChild.rows.length-1;
		}
		
		//alert(new_index);
		var zRow = this.table.container.firstChild.insertRow(new_index);
		var add_class = "";

		if (this.was_deleted) {
			add_class += " column_was_deleted";
			zRow.title = "This column is no longer present in the table on the server. You should Synchronize this table.";
		}

		if (this.name != this.column_name) {
			add_class += " column_alias";
		} else {
			add_class += "";
		}

		var zCell  = zRow.insertCell(0);
		zCell.className = "qtd" + add_class;

		//alert(this.name+":"+this.isFK());
		zCell.innerHTML = '<div class="key_base ' + (this.is_pk && this.name==this.column_name?'column_pk':(this.isFK()?'column_fk':'')) + '"></div>';

		zCell  = zRow.insertCell(1);
		zCell.className = "qtd" + add_class;
		if (this.name=="*") {
			zCell.innerHTML = '<input class="cbfortablecolumn" id="select_all_'+this.table.name+'" type="checkbox" onclick="top.canvas.undo.lock();var ret=select_all_checkbox_clicked(this, this.checked, this.getAttribute(\'table_name\'));top.canvas.undo.unlock();if(ret) top.canvas.undo.addUndo();" alias_name="*" column_name="*" table_name="'+ this.table.name+'" '+(this.table.select_all ? 'checked="true"' : '')+'/>';
		} else {
			zCell.innerHTML = '<input class="cbfortablecolumn" type="checkbox" onclick="top.canvas.undo.lock();var ret=column_checkbox_clicked(this, this.checked, this.getAttribute(\'table_name\'), this.getAttribute(\'alias_name\'), this.getAttribute(\'column_name\'));top.canvas.undo.unlock(); if(ret) top.canvas.undo.addUndo();" alias_name="' + this.name + '" column_name="' + this.column_name + '" table_name="'+ this.table.name+'"/>';
		}
		zCell.firstChild.checked = this.checked;

		zCell  = zRow.insertCell(2);
		zCell.className = "column qtd" + add_class;
		zCell.sep = this.sep;
		zCell.innerHTML = this.name;// + '&nbsp;&nbsp;';

		zCell  = zRow.insertCell(3);
		zCell.className = "qtd" + add_class;

		var col_length = '';
		if (this.column_length && this.column_length != '') {
			col_length = ' (' +  this.column_length + ')';
		}
		zCell.innerHTML = '<nobr>&nbsp;&nbsp;' + this.data_type +	col_length + '</nobr>';
		break;
	}
	return s;
}

SQLColumn.prototype.copy = SQLColumn_copy;
SQLColumn.prototype.replace = SQLColumn_replace;
SQLColumn.prototype.link = SQLColumn_link;
SQLColumn.prototype.set_checked = SQLColumn_set_checked;
SQLColumn.prototype.repaint = SQLColumn_repaint;
SQLColumn.prototype.isFK = SQLColumn_isFK;
SQLColumn.prototype.remove = SQLColumn_remove;
SQLColumn.prototype.parseSQLCondition = SQLColumn_parseSQLCondition;
SQLColumn.prototype.serialize = SQLColumn_serialize;
SQLColumn.prototype.print = SQLColumn_print;

function SQLCondition(name) {
	this.name = name;
	this.cond_type = '';
	this.var_type = '';
	this.param_name = '';
	this.test_value = '';
	this.string = '';
}
var CONDITION_TYPES = {
	'':null, 
	'=':/^=\s*'?([^']*?)'?$/,
	'>':/^>(?!=)\s*'?(.*?)'?$/,
	'>=':/^>\=\s*'?(.*?)'?$/,
	'<':/^<(?!=|>)\s*'?(.*?)'?$/,
	'<=':/^<\=\s*'?(.*?)'?$/,
	'<>':/^(?:<>|\!=)\s*'?([^']*?)'?$/,
	'begins with':/^LIKE\s*'?([^%]*?)%'?$/i,
	'contains':/^LIKE\s*'?%(.*?)%'?$/i,
	'ends with':/^LIKE\s*'?%([^\s]*?)'?$/i, 
	'is null':/is\s+null/i,
	'is not null':/is\s+not\s+null/i
}
//*/\\"'

var var_type = [
	'Form Variable', 
	'URL Variable', 
	'Session Variable', 
	'Cookie Variable', 
	'Entered Value'
];
var var_type_to_dynamic_value = {
	PHP:{
		'Form Variable'		:'$_POST["param_name"]',
		'URL Variable'		:'$_GET["param_name"]',
		'Session Variable':'$_SESSION["param_name"]',
		'Cookie Variable'	:'$_COOKIE["param_name"]',
		'Entered Value'		:'param_name'
	},
	ASP:{
		'Form Variable'		:'Request.Form("param_name")',
		'URL Variable'		:'Request.QueryString("param_name")',
		'Session Variable':'Session("param_name")',
		'Cookie Variable'	:'Request.Cookie("param_name")',
		'Entered Value'		:'param_name'
	},
	CFM:{
		'Form Variable'		:'#FORM.param_name#',
		'URL Variable'		:'#URL.param_name#',
		'Session Variable':'#SESSION.param_name#',
		'Cookie Variable'	:'#COOKIE.param_name#',
		'Entered Value'		:'param_name'
	},
	JSP:{
		'Form Variable'		:'request.getParameter("param_name")',
		'URL Variable'		:'request.getParameter("param_name")',
		'Session Variable':'session.getValue("param_name")',
		'Cookie Variable'	:'request.getParameter("param_name")',
		'Entered Value'		:'param_name'
	}
}
var dynamic_value_to_var_type = {
	PHP:{
		'Form Variable'			:/^\$_POST\["([a-zA-Z0-9_]*)"\]$/,
		'URL Variable'			:/^\$_GET\["([a-zA-Z0-9_]*)"\]$/,
		'Session Variable'	:/^\$_SESSION\["([a-zA-Z0-9_]*)"\]$/,
		'Cookie Variable'		:/^\$_COOKIE\["([a-zA-Z0-9_]*)"\]$/	
	}
	, ASP:{
		'Form Variable'			:/^Request\.Form\("([a-zA-Z0-9_]*)"\)$/,
		'URL Variable'			:/^Request\.QueryString\("([a-zA-Z0-9_]*)"\)$/,
		'Session Variable'	:/^Session\("([a-zA-Z0-9_]*)"\)$/,							
		'Cookie Variable'		:/^Request\.Cookie\("([a-zA-Z0-9_]*)"\)$/
	}
	, CFM:{
		'Form Variable'			:/^#FORM\.([a-zA-Z0-9_]*)#$/,		
		'URL Variable'			:/^#URL\.([a-zA-Z0-9_]*)#$/,			
		'Session Variable'	:/^#SESSION\.([a-zA-Z0-9_]*)#$/,
		'Cookie Variable'		:/^#COOKIE\.([a-zA-Z0-9_]*)#$/
	}
	, JSP:{
		'Form Variable'			:/^request\.getParameter\("([a-zA-Z0-9_]*)"\)$/,
		'URL Variable'			:/^request\.getParameter\("([a-zA-Z0-9_]*)"\)$/,	
		'Session Variable'	:/^session\.getValue\("([a-zA-Z0-9_]*)"\)$/,			
		'Cookie Variable'		:/^request\.getParameter\("([a-zA-Z0-9_]*)"\)$/
	}
}
function encodeServerVariable(wParType, wParam) {
	return var_type_to_dynamic_value[technology][wParType].replace("param_name", wParam);
}

function decodeServerVariable(expr) {
	var ret = {
		var_type:'Entered Value',
		param_name:''
	};
	for(var var_type in dynamic_value_to_var_type[technology]) {
		if (!dynamic_value_to_var_type[technology].hasOwnProperty(var_type)) {
			continue;
		}
		var rx = dynamic_value_to_var_type[technology][var_type];
		if (m = expr.match(rx)) {
			ret.var_type = var_type;
			ret.param_name = m[1];
			break;
		}
	}
	if (ret.var_type == 'Entered Value') {
		ret.param_name = expr;
	}
	return ret;
}

function SQLCondition_print(uniq, mode) {
	var s = "";
	switch(mode) {
		case "print":
			s += this.name + "<br>";
			break;
		case "expression":
			if (this.var_type == '') {
				return this.string;
			}
			var bop = '';
			var eop =  '';
			var ctv = (this.cond_type == '<>' || this.cond_type == '!=') ? notequals:this.cond_type;
			var wCond =  ctv;
			var wCondRT = ctv;
			var tdSep = this.column.sep;
			var sep  ='';

			switch (tdSep) {
				case '1':
					sep = this.column.aggFunc == "count"?"":"'";
					break;
				case '2':
					sep = dateseparator;
					break;
				default:
					sep = '';
			}

			switch (this.cond_type) {
				case 'contains':
					bop = "LIKE '%";
					eop = "%'";
					break;
				case 'begins with':
					bop = "LIKE '";
					eop = "%'";
					break;
				case 'ends with':
					bop = "LIKE '%";
					eop = "'";
					break;
				case 'is null':
					bop = "is null";
					eop = "";
					break;
				case 'is not null':
					bop = "is not null";
					eop = "";
					break;
				default:
					bop = wCond+sep;
					eop = sep;
			}

			wCond = bop;
			wCondRT = bop;
			if (wCond != "is null" && wCond != "is not null") {
				wCondRT += this.test_value;
				wCond += encodeServerVariable(this.var_type, this.param_name);
				wCond += eop;
				wCondRT+=eop;
			}
			s += wCond;
			break;
	}

	return s;
}

function SQLCondition_serialize(uniq, mode) {
	var s = '';
	if (this.name != '') {
		s += sprintf('tmp3.name = "%s";' + NEW_LINE, this.name);
	}
	s += 'tmp3.column = tmp2;' + NEW_LINE;
	if (this.cond_type != '') {
		s += sprintf('tmp3.cond_type = "%s";' + NEW_LINE, this.cond_type);
	}
	if (this.var_type != '') {
		s += sprintf('tmp3.var_type = "%s";' + NEW_LINE, this.var_type);
	}
	if (this.param_name != '') {
		s += sprintf('tmp3.param_name = "%s";' + NEW_LINE, this.param_name);
	}
	if (this.test_value != '') {
		s += sprintf('tmp3.test_value = "%s";' + NEW_LINE, this.test_value.replace(/"/gi, "\\\""));
	}
	if (this.string != '') {
		s += sprintf('tmp3.string = "%s";' + NEW_LINE, this.string.replace(/"/g, '\\"'));
	}
	return s;
}

SQLCondition.prototype.print = SQLCondition_print;
SQLCondition.prototype.serialize = SQLCondition_serialize;


//relation between table1.field1 to table2.field2
//t1, t2 - SQLTable s
//f1, f2 - SQLColumn s
function SQLRelation(name, t1, t2, f1, f2, card1, card2, restrict) {
	this.name = name;
	if(typeof(t1) != "undefined") {
		this.table1 = t1.name;
		this.table2 = t2.name;
	}
	this.field1 = f1;
	this.field2 = f2;
	this.card1 = card1;
	this.card2 = card2;
	this.restrict = restrict;
	
	this.ui = {relLeft:0, relTop1:0, relTop2:0};
}

function SQLRelation_remove () {
	//remove the associated HTML elements
	this.remove_uiobjects();
	this.query.relations.remove(this.name);
	try{
		var z = this.query.tables.item(this.table1).columns.item(this.field1)['doomed'];
		if (typeof z != "undefined" && z==false || typeof z=="undefined") {
			this.query.tables.item(this.table1).columns.item(this.field1).repaint();
		}
	}catch(e) {}
	try{
		var z = this.query.tables.item(this.table2).columns.item(this.field2)['doomed'];
		if (typeof z != "undefined" && z==false || typeof z=="undefined") {
			this.query.tables.item(this.table2).columns.item(this.field2).repaint();
		}
	}catch(e) {}
}
function SQLRelation_remove_uiobjects () {
	var tmp = document.getElementById(this.name);
	if (tmp)tmp.parentNode.removeChild(tmp);
	tmp = document.getElementById(this.name+"divh1");
	if (tmp)tmp.parentNode.removeChild(tmp);
	tmp  = document.getElementById(this.name+"divh2");
	if (tmp)tmp.parentNode.removeChild(tmp);
	tmp  = document.getElementById(this.name+"el1");
	if (tmp)tmp.parentNode.removeChild(tmp);
	tmp  = document.getElementById(this.name+"el2");
	if (tmp)tmp.parentNode.removeChild(tmp);
}


var gt_image = '<div class="rel_right_arrow">&gt;</div>';
var lt_image = '<div class="rel_left_arrow">&lt;</div>';

var up_image = '<div class="rel_up_arrow">^</div>';
var down_image = '<div class="rel_down_arrow">v</div>';
function SQLRelation_redim (b) {
	var selRel = this;
	var divv1 = document.getElementById(selRel.name);
	if (
		this.query.tables.item(this.table1).columns.item(this.field1).was_deleted
			||
		this.query.tables.item(this.table2).columns.item(this.field2).was_deleted
			||
		divv1 == null
	) {
		return;
	}
	var horiz1 = document.getElementById(selRel.name+"divh1");
	var horiz2 = document.getElementById(selRel.name+"divh2");
	var iel1  = document.getElementById(selRel.name+"el1");
	var iel2  = document.getElementById(selRel.name+"el2");
	var o1 = this.query.tables.item(selRel.table1).container;
	var o2 = this.query.tables.item(selRel.table2).container;

	l1 = o1.offsetLeft;
	t1 = o1.offsetTop;
	w1 = o1.offsetWidth;
	h1 = o1.offsetHeight;
	r1 = l1 + w1;
	
	l2 = o2.offsetLeft;
	t2 = o2.offsetTop;
	w2 = o2.offsetWidth;
	h2 = o2.offsetHeight;
	r2 = l2 + w2;
	
	card1 = horiz1.card;
	card2 = horiz2.card;

	x1 = horiz1.relTop;
	x2 = horiz2.relTop;
	
	if(x1<17*zoomFactor){
		x1 = 17*zoomFactor;
		horiz1.relTop = x1;
	}
	if(x1 > h1-4){
		x1 = h1-4;
		horiz1.relTop = x1;
	}
	selRel.ui.relTop1 = x1;

	if(x2<17*zoomFactor){
		x2 = 17*zoomFactor;
		horiz2.relTop = x2;
	}
	if(x2 > h2-4){
		x2 = h2-4;
		horiz2.relTop = x2;
	}
	selRel.ui.relTop2 = x2;
	horiz1.style.top = t1 + x1;
	horiz2.style.top = t2 + x2;
	
	if (b) {
		horiz1.style.height=5;
		horiz2.style.height=5;
		divv1.style.width=5;
		iel1.style.width=10;
		iel1.style.height=20;
		iel2.style.width=10;
		iel2.style.height=20;
	}

	if(divv1.relLeft>0){
		y1 = divv1.relLeft;
	} else {
		if (r1<l2) {
			y1 = parseInt((l2+r1)/2);
		} else if (r2<l1) {
			y1 = parseInt((l1+r2)/2);
		} else if (r1<=r2) {
			y1 = r2+20;
		} else {
			y1 = l2-20;
		}
	}
	selRel.ui.relLeft = y1 - 2;
	divv1.style.left = y1 - 2;
	divv1.style.height=abs1((t1+x1)-(t2+x2));
	
	var modify1 = this.card1 == 'n';
	var modify2 = this.card2 == 'n';
	var debug_rels = false;
	iel1.style.display = modify2?"none":"block";
	iel2.style.display = modify1?"none":"block";

	if (y1<l1) {
		horiz1.style.left = y1;
		horiz1.style.width = l1-y1;
		if (modify1) iel1.innerHTML = gt_image;
		iel1.style.left = l1 - iel1.offsetWidth + 2;
		iel1.style.top = t1 + x1 - iel1.offsetHeight/2 + 2;
		debug_rels && top.canvas.zdbg("in 1");
	} else if (y1>r1) {
		horiz1.style.left = r1;
		horiz1.style.width = y1-r1+1;
		if (modify1) iel1.innerHTML = lt_image;
		iel1.style.left = r1;
		iel1.style.top = t1 + x1 - iel1.offsetHeight/2 + 2;
		debug_rels && top.canvas.zdbg("in 2");
	} else {
		horiz1.style.left = y1;
		horiz1.style.width = 1;
		if (t2+x2<t1){
			if (modify1) iel1.innerHTML = down_image;
			iel1.style.left = y1 - iel1.offsetWidth/2 + 1;
			iel1.style.top = t1 - iel1.offsetHeight;
			debug_rels && top.canvas.zdbg("in 31");
		} else if (t2+x2>t1+h1) {
			iel1.style.top = t1+h1;
			if (modify1) iel1.innerHTML = up_image;
			iel1.style.left = y1 - iel1.offsetWidth/2 + 1;
			debug_rels && top.canvas.zdbg("in 32");
		} else {
			if (l1<l2) {
				iel1.style.left = r1 + 1;
				if (modify1) iel1.innerHTML = lt_image;
				debug_rels && top.canvas.zdbg("in 33");
			} else {
				if (modify1) iel1.innerHTML = gt_image;
				iel1.style.left = l1 - iel1.offsetHeight;
				debug_rels && top.canvas.zdbg("in 34");
			}
			iel1.style.top = t2 + x2 - iel1.offsetHeight/2 + 2;
		}
	}

	if (y1<l2) {
		horiz2.style.left=y1;
		horiz2.style.width=l2-y1;
		if (modify2) iel2.innerHTML = gt_image;
		iel2.style.left = l2 - iel2.offsetWidth;
		iel2.style.top = t2 + x2 - iel2.offsetHeight/2 + 2;
		debug_rels && top.canvas.zdbg("in 1", true);
	} else if (y1>r2) {
		horiz2.style.left=r2;
		horiz2.style.width=y1-r2+1;
		if (modify2) iel2.innerHTML = lt_image;
		iel2.style.left = r2 + 1;
		iel2.style.top = t2 + x2 - iel2.offsetHeight/2 + 2;
		debug_rels && top.canvas.zdbg("in 2", true);
	} else {
		horiz2.style.left=y1;
		horiz2.style.width=1;
		if (t1+x1<t2){
			if (modify2) iel2.innerHTML = down_image;
			iel2.style.top = t2 - iel2.offsetHeight;
			iel2.style.left = y1 - iel2.offsetWidth/2 + 1;
			debug_rels && top.canvas.zdbg("in 31", true);
		} else if (t1+x1>t2+h2) {
			if (modify2) iel2.innerHTML = up_image;
			iel2.style.top = t2 + h2;
			iel2.style.left = y1 - iel2.offsetWidth/2 + 1;
			debug_rels && top.canvas.zdbg("in 32", true);
		} else {
			if (l2<l1) {
				iel2.style.left= r2 + 1;
				if (modify2) iel2.innerHTML = lt_image;
				debug_rels && top.canvas.zdbg("in 33", true);
			} else {
				if (modify2) iel2.innerHTML = gt_image;
				iel2.style.left=l2 - iel2.offsetWidth;
				debug_rels && top.canvas.zdbg("in 34", true);
			}
			iel2.style.top = t1 + x1 - iel2.offsetHeight/2 + 2;
		}
	}
	var pt1 = t1 + x1;
	var pt2 = t2 + x2;
	divv1.style.top = ((pt1-pt2)<0)?pt1+2:pt2+2;
}

function SQLRelation_serialize (uniq) {
	var s = '';
	if (typeof uniq=="undefined") {
		s += sprintf('var tmp = new SQLRelation("%s");' + NEW_LINE, this.name);
	} else {
		s += sprintf('var tmp = %s.new_relation("%s");' + NEW_LINE, uniq, this.name);
	}
	s += sprintf('tmp.table1 = "%s";' + NEW_LINE, this.table1);
	s += sprintf('tmp.table2 = "%s";' + NEW_LINE, this.table2);
	s += sprintf('tmp.field1 = "%s";' + NEW_LINE, this.field1);
	s += sprintf('tmp.field2 = "%s";' + NEW_LINE, this.field2);
	s += sprintf('tmp.card1 = "%s";' + NEW_LINE, this.card1);
	s += sprintf('tmp.card2 = "%s";' + NEW_LINE, this.card2);
	s += sprintf('tmp.restrict = "%s";' + NEW_LINE, this.restrict);
	s += sprintf('tmp.ui = {relLeft:%s, relTop1:%s, relTop2:%s};' + NEW_LINE, this.ui.relLeft, this.ui.relTop1, this.ui.relTop2);
	return s;
}

function SQLRelation_select(sel_id, do_navigate) {
	if(sel_id!=null) {
		document.getElementById(this.name).firstChild.style.borderLeftStyle = "solid";
		document.getElementById(this.name + "divh1").firstChild.style.borderTopStyle = "solid";
		document.getElementById(this.name + "divh2").firstChild.style.borderTopStyle = "solid";
		document.getElementById(this.name).firstChild.style.borderLeftColor = relation_color[this.restrict];
		document.getElementById(this.name + "divh1").firstChild.style.borderTopColor = relation_color[this.restrict];
		document.getElementById(this.name + "divh2").firstChild.style.borderTopColor = relation_color[this.restrict];
		currentElement = document.getElementById(sel_id);
		currentElement.firstChild.style['border'+(this.name==sel_id?'Left':'Top')+'Style']="dashed";
		currentElement.firstChild.style['border'+(this.name==sel_id?'Left':'Top')+'Color']="black";
		top.mo.selectedRelation = this.name;
		this.selected_line = sel_id
	}
}

function SQLRelation_deselect() {
	document.getElementById(this.name).firstChild.style.borderLeftStyle = "solid";
	document.getElementById(this.name + "divh1").firstChild.style.borderTopStyle = "solid";
	document.getElementById(this.name + "divh2").firstChild.style.borderTopStyle = "solid";
	document.getElementById(this.name).firstChild.style.borderLeftColor = relation_color[this.restrict];
	document.getElementById(this.name + "divh1").firstChild.style.borderTopColor = relation_color[this.restrict];
	document.getElementById(this.name + "divh2").firstChild.style.borderTopColor = relation_color[this.restrict];

	currentElement = null;
	top.mo.selectedRelation = null;
}

function SQLRelation_print(uniq, mode) {
	var s = "";

	var t1 = this.query.tables.item(this.table1);
	var t2 = this.query.tables.item(this.table2);
	var f1 = t1.columns.item(this.field1);
	var f2 = t2.columns.item(this.field2);
	if (
		typeof t1.tDetails[f1.column_name] == "undefined" 
			|| 
		typeof t2.tDetails[f2.column_name] == "undefined" 
		) {
		this.remove();
		return;
	}

	switch(mode) {
	case "print":
		s += sprintf('<li>relation:%s</li>', this.name);
		break;
	case "diagram":
		//div1, div2, x1, x2, card1, card2, field1, field2, restrict)
		var linkObj2id = this.name;
	
		//begin linkObj1
		var linkObj1 = utility.dom.createElement("DIV", {
			id:this.name + 'divh1',
			card:this.card1,
			o:this.table1,
			style:'position:absolute;overflow:hidden; z-index:10; cursor:N-resize; top:' + this.ui.relTop1,
			className:'links1'
		}, this.query.target_wnd);
		linkObj1.innerHTML = '<div style="position:absolute; overflow:hidden; left:0px; top:2px;	width:100%; height: 1px;	z-index:10;border-style:none; border-top:1px solid '+relation_color[this.restrict]+';"></div>';
		this.query.container.appendChild(linkObj1);
		linkObj1.relTop = this.ui.relTop1;
		//end linkObj1

		//begin linkObj2
		var linkObj2 = utility.dom.createElement("DIV", {
			id: this.name,
			vert: 0,
			table1: this.table1,
			table2: this.table2,
			card1: this.card1,
			card2: this.card2,
			field1: this.field1,
			field2: this.field2,
			restrict: this.restrict,
			style: 'position:absolute; overflow:hidden; z-index:10; cursor:E-resize; top:' + this.ui.relLeft,
			className: 'links'
		}, this.query.target_wnd);
		linkObj2.innerHTML = '<div style="position:absolute; overflow:hidden; left:2px; top:0px;	width:2px; height:100%;	z-index:10;border-left:1px solid '+relation_color[this.restrict]+'"></div>';
		this.query.container.appendChild(linkObj2);
		linkObj2.relLeft = this.ui.relLeft;
		//end linkObj2
	
		//begin linkObj3
		var linkObj3 = utility.dom.createElement("DIV", {
			id: this.name + 'divh2',
			card: this.card2,
			o: this.table2,
			style: 'position:absolute;overflow:hidden; z-index:10; cursor:N-resize; top:' + this.ui.relTop2,
			className:'links1'
		}, this.query.target_wnd);
		linkObj3.innerHTML = '<div style="position:absolute; overflow:hidden; left:0px; top:2px;	width:100%; height: 1px;	z-index:10;border-top:1px solid '+relation_color[this.restrict]+'"></div>';
		this.query.container.appendChild(linkObj3);
		linkObj3.relTop = this.ui.relTop2;
		//end linkObj3
	
		//begin linkObj4
		var linkObj4 = utility.dom.createElement("DIV", {
			id: this.name + 'el1',
			style: 'position:absolute;zoverflow:hidden; z-index:9;', //;border: 1px solid green
			className: 'relname'
		}, this.query.target_wnd);
		//linkObj4.innerHTML = this.card1;
		this.query.container.appendChild(linkObj4);
		//end linkObj4
	
		//begin linkObj5
		var linkObj5 = utility.dom.createElement("DIV", {
			id:this.name + 'el2',
			style:'position:absolute;zoverflow:hidden; z-index:9;', //border: 1px solid red;
			className:'relname'
		}, this.query.target_wnd);
		//linkObj5.innerHTML = this.card2;
		this.query.container.appendChild(linkObj5);
		//end linkObj5

		if (this.restrict == 'no') {
			if (this.card1 == 'n') {
				utility.dom.classNameAdd(linkObj4, 'relname_visible');
			}
			if (this.card2 == 'n') {
				utility.dom.classNameAdd(linkObj5, 'relname_visible');
			}
			linkObj4.style.color = relation_color[this.restrict];
			linkObj5.style.color = relation_color[this.restrict];
		}

		linkObj1.onmousedown=linkObj2.onmousedown=linkObj3.onmousedown=lineMouseDown;
		linkObj1.onmouseup=linkObj2.onmouseup=linkObj3.onmouseup=lineMouseUp; 
		linkObj1.ondblclick=linkObj2.ondblclick=linkObj3.ondblclick=lineDblClick; 

		var row_column_offset_index = 0;
		var zt1 = document.getElementById(this.query.tables.item(this.table1).name).firstChild;
		var zt2 = document.getElementById(this.query.tables.item(this.table2).name).firstChild;
		var idx1 = this.query.tables.item(this.table1).columns.itemsNameToIndex[this.field1];
		var idx2 = this.query.tables.item(this.table2).columns.itemsNameToIndex[this.field2];
		var ro1 = zt1.rows[row_column_offset_index + idx1];
		var ro2 = zt2.rows[row_column_offset_index + idx2];
		
		var top1 = zt1.rows[0].offsetHeight + ro1.offsetHeight*(row_column_offset_index + idx1) + ro1.offsetHeight/2;
		var top2 = zt2.rows[0].offsetHeight + ro2.offsetHeight*(row_column_offset_index + idx2) + ro2.offsetHeight/2;
		linkObj1.orelTop = top1;
		linkObj3.orelTop = top2;

		if (this.ui.relTop1 == 0 || this.ui.relTop2 == 0) {
			linkObj1.relTop = top1;
			linkObj3.relTop = top2;
		}
		this.redim(1);
	
		break;
	}
	return s;
}

function SQLRelation_repaint () {
	this.remove_uiobjects();
	this.print("some", "diagram");
	if (top.mo.selectedRelation && top.mo.selectedRelation==this.name) {
		this.select(this.selected_line);
	}
	this.query.tables.item(this.table1).columns.item(this.field1).repaint();
	this.query.tables.item(this.table2).columns.item(this.field2).repaint();
}

SQLRelation.prototype.remove = SQLRelation_remove;
SQLRelation.prototype.remove_uiobjects = SQLRelation_remove_uiobjects;
SQLRelation.prototype.redim = SQLRelation_redim;
SQLRelation.prototype.serialize = SQLRelation_serialize;
SQLRelation.prototype.select = SQLRelation_select;
SQLRelation.prototype.deselect = SQLRelation_deselect;
SQLRelation.prototype.print = SQLRelation_print;
SQLRelation.prototype.repaint = SQLRelation_repaint;
