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

var logdebug = false;
var SHOW_TABLE_TIMEOUT = 300;

var DRAG_DELTA = 4;
var CLICK_DRAG_KEY = 32;//17
var click_drag_flag = false;
	eventbutton = 0;

function setEventButton (e) {
	if (!window.event) {
		var event = e;
		eventbutton = event.button + 1;
		if (eventbutton == 3) eventbutton = 2;
		if (event.detail==0) {
			eventbutton = 2;
		}
		if (is.mac && event.ctrlKey) {
			eventbutton = 2;
		}
	} else {
		var event = window.event;
		eventbutton = event.button;
	}
}

function MovingObjects() {
	this.moving=0;
	this.selectedTable = "";
	this.selectedRelation = "";
	this.editTableShowTimeout = null;
	this.dropTargetTable = null;
	this.dropTargetTableColumn = null;
	this.clickedTD = null;
	this.dropTargetRowIndex = null;
}

	top.mo = new MovingObjects();

var currentDiv=new Array();
var startX=-1, startY=-1, exZindex;
var rgt=40;
var currentElement = null;
var selectRegionObj = null;
function scrol(e){
//	var o = utility.dom.setEventVars(e);
//	utility.dom.stopEvent(o.e);
	var selectRegion = document.getElementById('selectRegion');
	if (selectRegion) {
		selectRegion.style.display = "none";
	}
	return false;
}

window.onscroll = scrol;

function hidemenu(mnu) {
	var tmp = eval(mnu);
	if (tmp != 'undefined') {
		hideFii(tmp.fii);
		hide(tmp);
	}
}

function closeAllMenus(now) {
	if (typeof now=='undefined') {
		now = false;
	}
	if (now) {
		hidemenu('tcm');
	} else if (!window.firstTimeRC) {
		window.tcmTO = setTimeout("hidemenu('tcm');", 500);	
	}

	if (now) {
		hidemenu('lcm');
	} else if (!window.firstTimeRC) {
		window.lcmTO = setTimeout("hidemenu('lcm');", 500);	
	}

	if (now) {
		hidemenu('rcm');
	} else if (!window.firstTimeRC) {
		window.rcmTO = setTimeout("hidemenu('rcm');", 500);	
	}

	window.parent.timeout();

	window.firstTimeRC = false;
}
function bodySelect() {
	if (window.getSelection) {
		var selObj = window.getSelection();
		if (selObj.toString()) {
			selObj.removeAllRanges();
		}
	}
}

function bodyKeyDown(e) {
	var o = utility.dom.setEventVars(e);
  var keyCode = 	e.keyCode ? e.keyCode :
									e.charCode ? e.charCode :
									e.which ? e.which : void 0;
	if (keyCode == CLICK_DRAG_KEY) {
		utility.dom.stopEvent(o.e);
		if(!click_drag_flag) {
			click_drag_flag = true;
			document.body.style.cursor = "move";
			top.status = "Click and drag to scroll the query diagram area.";
			if (top.mo.moving == 1) {
				unSelectTables(1);
				if (top.mo.selectedRelation) {
					myQuery.relations.item(top.mo.selectedRelation).deselect();
				}
			} else if(top.mo.moving == 10) {
				zDragLink.style.display = "none";
				zDragHelper.style.display = "none";
				document.body.style.cursor = "";
			}
			top.mo.moving = 0;
		}
		return false;
	}
}
function bodyKeyUp(e) {
	var o = utility.dom.setEventVars(e);
	if (click_drag_flag && o.e.keyCode == CLICK_DRAG_KEY) {
		window.status = "Done";
		click_drag_flag = false;
		document.body.style.cursor = "";
		utility.dom.stopEvent(o.e);
		return false;
	}
	//window.status = o.e.keyCode;
	if (o.e.keyCode == 65) {
		myQuery.selectAll();
		return false;
	}

	for(i=0;i<currentDiv.length;i++){
		switch (o.e.keyCode) {
			case 37:
				newX = parseInt(currentDiv[i].style.left) - 1;
				if (newX >=0) {
					currentDiv[i].style.left = newX;
					myQuery.tables.item(currentDiv[i].id).ui.x = newX;
					redimCurrentSelection();
				}
				break;
			case 39:
				newX = parseInt(currentDiv[i].style.left) + 1;
				currentDiv[i].style.left = newX;
				myQuery.tables.item(currentDiv[i].id).ui.x = newX;
				redimCurrentSelection();
				break;
			case 38:
				newY = parseInt(currentDiv[i].style.top) - 1;
				if (newY >=0) {
					currentDiv[i].style.top = newY;
					myQuery.tables.item(currentDiv[i].id).ui.y = newY;
					redimCurrentSelection();
				}
				break;
			case 40:
				newY = parseInt(currentDiv[i].style.top) + 1;
				currentDiv[i].style.top = newY;
				myQuery.tables.item(currentDiv[i].id).ui.y = newY;
				redimCurrentSelection();
				break;
			default:
				break;
		}

	}
	switch (o.e.keyCode) {
		case 76:
				editLink();
			break;
		//case 117:
		case 107:
				zoomIn();
			break;
		//case 118:
		case 109:
				zoomOut();
			break;
		case 46:
			if (currentDiv.length) {
				delTables();
			} else if (top.mo.selectedRelation) {
				delLink();
			}
			break;
		case 83:
			parent.showPleaseWait('Please wait while saving', '160px', ''); 
			setTimeout('parent.save(1)', 20);
			break;
		case 88:
		case 81:
			parent.save(1);
			parent.closeQUB();
		case 27:
			closeAllMenus();
			break;
		case 90:
			undo.undo();
			break;
		case 89:
			undo.redo();
			break;
	}
	return true;
}

function bodyMouseDown(e){
	var o = utility.dom.setEventVars(e);
	var targetEl = o.targ;
	if (!targetEl) {
		eventbutton = 0;
		top.mo.moving = 0;
		return true;
	}
	setEventButton(e);
	var tag = (targetEl.tagName) ? targetEl.tagName : '[unknown tag]';
	if (tag == "scrollbar") {
		return true;
	}
	if (is.ie && tag == "BODY") {
		if (targetEl.offsetHeight<targetEl.scrollHeight && event.clientX>(targetEl.offsetWidth-scrollbars_width)) {
			return;
		}
		if (targetEl.offsetWidth<targetEl.scrollWidth && event.clientY>(targetEl.offsetHeight-scrollbars_width)) {
			return;
		}
	}
	closeAllMenus();
	zDragLink.style.display = "none";

	if(targetEl.tagName.toLowerCase()=="input") {
		return;
	}

	startX = o.posx;
	startY = o.posy;
	if (top.mo.moving!=1 && eventbutton==1 && top.mo.selectedRelation) {
		if(tcm != 'undefined' && lcm != 'undefined' && rcm != 'undefined' && tcm.style.display != "block" && lcm.style.display != "block" && rcm.style.display != "block") {
			myQuery.relations.item(top.mo.selectedRelation).deselect();
		}
	}

	if(top.mo.moving==0) {
		if (tcm != 'undefined' && lcm != 'undefined' && rcm != 'undefined' && tcm.style.display != "block" && lcm.style.display != "block" && rcm.style.display != "block" && o.leftclick) {
			unSelectTables();
			var selectRegion = document.getElementById('selectRegion');
			selectRegion.startLeft = startX;
			selectRegion.startTop  = startY;
			selectRegion.style.left = startX;
			selectRegion.style.top = startY;
			selectRegion.style.width = 0;
			selectRegion.style.height = 0;
			selectRegion.style.display="block";
		}
	}
}

function bodyMouseMove(e) {
	if (!eventbutton) {
		return;
	}
	if(eventbutton == 0){
		if (top.mo.moving == 0) {
			return;
		}
		top.mo.moving = 0;
		var selectRegion = document.getElementById('selectRegion');
		selectRegion.style.display = "none";
		selectRegion.style.left = 0;
		selectRegion.style.top = 0;
		selectRegion.style.width = 0;
		selectRegion.style.height = 0;
		if (!click_drag_flag) {
			window.document.body.style.cursor = "default";
		}
		return;
	}
	bodySelect();
	var o = utility.dom.setEventVars(e);

	//theType, theTargets, theMouse, theKeys
	//zdbg(top.mo.moving+"\r\n"+printInfo(o.e, "theTargets"));

	currentX = o.posx;
	currentY = o.posy;

	var selectRegion = document.getElementById('selectRegion');
	var hoverElement = o.targ;
	if(eventbutton == 1 && click_drag_flag){
		if (tcm != 'undefined' && lcm != 'undefined' && rcm != 'undefined' && tcm.style.display != "block" && lcm.style.display!= "block" && rcm.style.display!= "block") {
			window.document.body.style.cursor = "move";
			selectRegion.style.display = "none";
			tmp1 = startX - currentX;
			tmp2 = startY - currentY;
			window.scrollBy(tmp1, tmp2);
		}
		return;
	}

	if (top.mo.moving==1) {
		if (currentDiv.length>0 && Math.abs(currentX-startX)<DRAG_DELTA && Math.abs(currentY-startY)<DRAG_DELTA) {
			return;
		}
		var sw = false;
		for (i=0;i<currentDiv.length;i++) {
			newX = currentX - startX + myQuery.tables.item(currentDiv[i].id).ui.cacheX;
			newY = currentY - startY + myQuery.tables.item(currentDiv[i].id).ui.cacheY;
			if(newX<0 && myQuery.tables.item(currentDiv[i].id).ui.x>=0){
				newX = 0;
			}
			if(newY<0 && myQuery.tables.item(currentDiv[i].id).ui.y>=0){
				newY = 0;
			}
			myQuery.tables.item(currentDiv[i].id).ui.move(newX, newY);
			sw = true;
		}
		if (i==0) {
			if (currentElement) {
				if(currentElement.vert == 0){
					newX = currentX - startX + currentElement.style.cacheLeft;
					currentElement.relLeft = newX;
				} else {
					newY = currentY - startY + currentElement.style.cacheTop||0;
					currentElement.relTop = newY;
				}
				sw = true;
			}
		}
		if(sw){
			redimCurrentSelection();
		}
	} else if (top.mo.moving==10) {
		top.mo.dropTargetTableColumn = null;
		top.mo.dropTargetTable = null;
		var el = hoverElement;
		var show_dragger = false;
		var srcCol = myQuery.tables.item(top.mo.dropSourceTable).columns.item(top.mo.dropSourceTableColumn);
		while (srcCol.name!="*" && srcCol.name==srcCol.column_name && el) {
			if (el.tagName == "HTML") {
				zDragHelper.innerHTML = locales["cant link here"];
				document.body.style.cursor = parent.is.ns?"move":"no-drop";
				show_dragger = true;
				break;
			}
			if(el.tagName == "TR") {
				if (el.cells.length>2) {
					if(/^column/.test(el.cells[2].className)) {
						var cur_table_id = el.parentNode.parentNode.parentNode.getAttribute("tableName");
						var field_name = el.cells[2].innerHTML.replace(/&nbsp;/gi, "");

						if (cur_table_id != top.mo.dropSourceTable) {
							var zCol = myQuery.tables.item(cur_table_id).columns.item(field_name);
							if (!zCol.was_deleted && zCol.name!="*" && zCol.name==zCol.column_name) {
								top.mo.dropTargetTableColumn = field_name;
								top.mo.dropTargetRowIndex = el.rowIndex - 1;
								top.mo.dropTargetTable = cur_table_id+"";
	
								if(top.mo.dropTargetTable == top.mo.dropSourceTable) {
									zDragHelper.innerHTML = locales["cant link here"];
									show_dragger = false;
									break;
								} else {
									zDragHelper.innerHTML = locales["link here:"] + "<b>" + top.mo.dropSourceTable+"."+top.mo.dropSourceTableColumn + ":" + top.mo.dropTargetTable+"."+top.mo.dropTargetTableColumn +"</b>";
									show_dragger = true;
									break;
								}
							} else {
								zDragHelper.innerHTML = locales["cant link here"];
								show_dragger = false;
								break;
							}
						} else {
							show_dragger = false;
							break;
						}
					} else {
						zDragHelper.innerHTML = locales["cant link here"];
						show_dragger = false;
					}
				}
				break;
			}
			el = el.parentNode;
		}
		zDragHelper.style.left = (Math.max(0, currentX - parseInt(zDragHelper.offsetWidth/2)) ) + "px";
		zDragHelper.style.top = utility.dom.getPageScroll().y + "px";

		if (show_dragger) {
			if(zDragLink.style.display != "") {
				zDragLink.style.display = "block";
			}
			zDragHelper.style.top = utility.dom.getPageScroll().y + "px";
			zDragLink.style.left = (currentX +10 ) + "px";
			zDragLink.style.top = (currentY + 10)  + "px";
		} else if (zDragLink.style.display != "none") {
			zDragLink.style.display = "none";
		}
	} else {
		if (selectRegion.style.display=="block") {
			if ( currentX < selectRegion.startLeft) {
				selectRegion.style.width = selectRegion.startLeft - currentX;
				selectRegion.style.left  = currentX;
			} else {
				selectRegion.style.width = currentX - selectRegion.startLeft;
				selectRegion.style.left  = selectRegion.startLeft;
			}

			if ( currentY < selectRegion.startTop) {
				selectRegion.style.height = max(1, selectRegion.startTop - currentY);
				selectRegion.style.top  = currentY;
			} else {
				selectRegion.style.height = max(1, currentY - selectRegion.startTop);
				selectRegion.style.top  = selectRegion.startTop;
			}
		}
	}
}
	
function bodyMouseUp(e) {
	var s;
	var selectRegion = document.getElementById('selectRegion');

	if(top.mo.selectedRelation) {
		myQuery.relations.item(top.mo.selectedRelation).deselect();
	}

	if (top.mo.moving == 1) { //drag table
		top.mo.moving=0;
		for (i=0;i<currentDiv.length;i++) {
			myQuery.tables.item(currentDiv[i].id).ui.x = parseInt(currentDiv[i].style.left);
			myQuery.tables.item(currentDiv[i].id).ui.y = parseInt(currentDiv[i].style.top);
		}
		undo.addUndo("Move table " + top.mo.selectedTable);
		top.ui.invalidate(null, true);
	} else if (top.mo.moving == 10) { // drag field to make relation
		zDragLink.style.display = "none";
		zDragHelper.style.display = "none";
		document.body.style.cursor = "";
		if (top.mo.dropTargetTable) {
			if(top.mo.dropSourceTable != top.mo.dropTargetTable) {
				undo.lock();
				var rel = myQuery.tables.item(top.mo.dropSourceTable).columns.item(top.mo.dropSourceTableColumn).link(
					myQuery.tables.item(top.mo.dropTargetTable).columns.item(top.mo.dropTargetTableColumn)
				);
				undo.unlock();
				top.canvas.undo.addUndo("Add relation " + top.mo.dropSourceTable +"." + top.mo.dropSourceTableColumn + " -> " + top.mo.dropTargetTable + "." + top.mo.dropTargetTableColumn);
				top.ui.invalidate(true, true, 'sqlquery');
			}
		} else {
			var o = utility.dom.setEventVars(e);
			currentX = o.posx;
			currentY = o.posy;

			if (Math.abs(currentX-startX)<DRAG_DELTA && Math.abs(currentY-startY)<DRAG_DELTA && top.mo.clickedTD) {
				top.mo.clickedTD.previousSibling.firstChild.click();
				top.mo.clickedTD = null;
				top.ui.invalidate(true, true, 'sqlquery');
			}
		}
		top.mo.dropTargetTable = null;
		top.mo.moving = 0;
	} else {
		if(selectRegion.style.display == "block") {
			x11 = parseInt(selectRegion.style.left);
			x12 = x11 + parseInt(selectRegion.style.width);
			y11 = parseInt(selectRegion.style.top);
			y12 = y11 + parseInt(selectRegion.style.height);
			var arr = myQuery.tables;
			var last = "";
			var j=0;
			for(i=0;i<arr.length;i++){
				xa11 = parseInt(arr.item(i).container.style.left);
				xa12 = parseInt(arr.item(i).container.style.left) + arr.item(i).container.offsetWidth;
				ya11 = parseInt(arr.item(i).container.style.top)
				ya12 = parseInt(arr.item(i).container.style.top) + arr.item(i).container.offsetHeight;

				s = 0;
				
				if (xa11 > x11 && xa11 < x12 && ya11 > y11 && ya11 < y12) {
					//ul
					s = 1;
				} else if (xa12 > x11 && xa12 < x12 && ya11 > y11 && ya11 < y12) {
					//ur
					s = 1;
				} else if (xa11 > x11 && xa11 < x12 && ya12 > y11 && ya12 < y12) {
					//dl
					s = 1;
				} else if (xa12 > x11 && xa12 < x12 && ya12 > y11 && ya12 < y12) {
					//dr
					s = 1;
				}
				if(s){
					myQuery.tables.item(arr.item(i).name).select(true);
					last = arr.item(i).name;
					j++;
				}
			}
			if (j==1) {
				myQuery.tables.item(last).select(false);
			}
			selectRegion.style.display="none";
/*
			if (parent.ui.is.ns) {
				//this code cleans the ugly dirty pixels left behind by mozilla
				var scrl = utility.dom.getPageScroll();
				showHideIframe('none');
				showHideIframe('block');
				document.body.scrollLeft = scrl.x;
				document.body.scrollTop = scrl.y;
			}
*/
		}
		var o = utility.dom.setEventVars(e);
		if (o.rightclick) {
			currentElement = this;
			if (rcm != 'undefined') {
				rcm.style.left = o.posx;
				rcm.style.top = o.posy;
				hidemenu('tcm');
				hidemenu('lcm');
				if (window.rcmTO != -1) {
					clearTimeout(window.rcmTO);
					window.rcmTO = -1;
				}
				showMenu(rcm);
			}
		}

	}
	eventbutton = 0;
}

function showHideIframe(wh) {
	var ifr = parent.document.getElementById('canvas');
	ifr.style.display = wh;
}

function lineMouseDown(e) {
	if (click_drag_flag) {
		return false;
	}
	setEventButton(e);
	var o = utility.dom.setEventVars(e);
	startX = o.posx;
	startY = o.posy;

	while(currentDiv.length){
		temp = currentDiv.pop();
		myQuery.tables.item(temp.id).deselect();
	}
	closeAllMenus(true);

	if(o.rightclick){
		window.firstTimeRC = true;
	}
	var new_rel_id = this.id.replace(/divh\d$/, "");
	if (top.mo.selectedRelation && top.mo.selectedRelation != new_rel_id) {
		myQuery.relations.item(top.mo.selectedRelation).deselect();
	}

	if (o.leftclick) {
		if(!top.mo.moving){
			top.mo.moving = 1;
			currentElement = this;
			if(currentElement.relLeft>0){
				currentElement.style.cacheLeft = currentElement.relLeft;
			} else {
				currentElement.style.cacheLeft = parseInt(currentElement.style.left);
			}
			if (currentElement.vert != 0) {
				currentElement.style.cacheTop  = currentElement.relTop;
			}
			myQuery.relations.item(new_rel_id).select(this.id);
		}
	} else if (o.rightclick) {
		currentElement = this;
		myQuery.relations.item(new_rel_id).select(this.id);
	}
}
	
function lineMouseUp(e) {
	var o = utility.dom.setEventVars(e);
	while(currentDiv.length){
		temp = currentDiv.pop();
		temp.style.borderStyle="none";
	}

	//setEventButton(e);
	if (o.rightclick) {
		if (lcm != 'undefined') {
			lcm.style.left = o.posx;
			lcm.style.top = o.posy;
			hidemenu('tcm');
			hidemenu('rcm');
			if (window.lcmTO != -1) {
				clearTimeout(window.lcmTO);
				window.lcmTO = -1;
			}
			utility.dom.stopEvent(o.e);
			myQuery.relations.item(this.id.replace(/divh\d$/, "")).select(this.id);
			showMenu(lcm);
		}
	} else if(o.leftclick) {
		utility.dom.stopEvent(o.e);
		top.mo.moving = 0;
	}
}

	function divMouseUp(e) {
		var oe = utility.dom.setEventVars(e);
		setEventButton(e);
		if (this) {
			var o = this;
		}
		if (typeof o.style == 'undefined') {
			return;
		}

		if(oe.rightclick){
			if(!myQuery.tables.item(o.id).ui.selected){
				if (!oe.e.ctrlKey && !oe.e.shiftKey) {
					top.mo.moving = 0;
					unSelectTables(1);
				}
				myQuery.tables.item(o.id).select();
				top.mo.selectedTable = o.id;
			}

			if (tcm != 'undefined') {
				tcm.style.left = oe.posx;
				tcm.style.top = oe.posy;
				hidemenu('lcm');
				hidemenu('rcm');
				if (window.tcmTO != -1) {
					clearTimeout(window.tcmTO);
					window.tcmTO = -1;
				}
				showMenu(tcm);
				utility.dom.stopEvent(oe.e);
			}
		} else if (oe.leftclick) {
//				top.mo.moving = 0;
				if (top.mo.selectedTable) {
					myQuery.tables.item(top.mo.selectedTable).select(null);
				}
		}
		return true;
	}

function divMouseDown(e){
	setEventButton(e);
	if (click_drag_flag) {
		return false;
	}
	if (this) {
		var o = this;
	}
	if (typeof o.style == 'undefined') {
		return;
	}
	var oe = utility.dom.setEventVars(e);
	startX = oe.posx;
	startY = oe.posy;

	var targetEl = oe.targ;
	if(targetEl.tagName.toLowerCase()=="input") {
		return;
	}
	if (top.mo.selectedRelation) {
		myQuery.relations.item(top.mo.selectedRelation).deselect();
	}

	if (oe.rightclick) {
		window.firstTimeRC = true;
	}
	if(!oe.leftclick) {
		return;
	}

	if (/^column/.test(targetEl.className)) {
		top.mo.clickedTD = targetEl;
		var obj = targetEl;
		while (obj && (obj.tagName+"").toLowerCase() != "tr") {
			obj = obj.parentNode;
		}
		if (!obj) {
			return;
		}
		var field_id = obj.rowIndex-1;
		var field_name = "";
		var obc = obj.cells;
		for (j=0; j<obc.length; j++) {
			if (/^column/.test(obc[j].className)) {
				field_id = i-1;
				field_name = obc[j].innerHTML.replace(/&nbsp;/gi, "");
				break;
			}
		}
	
		divToPush = obj.parentNode.parentNode.parentNode;
		var zCol = myQuery.tables.item(divToPush.id).columns.item(field_name);
		if (!zCol.was_deleted) {// && zCol.name==zCol.column_name
			zDragLink.innerHTML = divToPush.id + "." + field_name;
			if (zCol.name==zCol.column_name && zCol.name!="*") {
				zDragHelper.style.display = "block";
			}
			zDragHelper.innerHTML = locales["drag to create a relation"];
			zDragLink.startLeft = startX;
			zDragLink.startTop  = startY;
			zDragHelper.style.left = (Math.max(0, startX - parseInt(zDragHelper.offsetWidth/2)) ) + "px";
			zDragHelper.style.top = utility.dom.getPageScroll().y + "px";
			zDragLink.style.left = startX + "px";
			zDragLink.style.top = startY + "px";
	
			top.mo.dropSourceTableColumn = field_name;
			top.mo.dropSourceTable = divToPush.id;
			top.mo.dropSourceRowIndex = field_id;
	
			utility.dom.stopEvent(oe.e);
			top.mo.moving = 10;
		} else {
			top.mo.moving = 0;
		}
	} else if(!myQuery.tables.item(o.id).ui.selected){
		if (!oe.e.ctrlKey && !oe.e.shiftKey) {
			top.mo.moving = 0;
			unSelectTables(1);
		}
		myQuery.tables.item(o.id).select();
		top.mo.moving = 1;
	} else {
		if (oe.e.ctrlKey || oe.e.shiftKey) {
			myQuery.tables.item(o.id).deselect();
		}
		top.mo.moving=1;
	}
	if (top.mo.moving == 1) {
		utility.dom.stopEvent(oe.e);
		if (top.ui.is.ns) {
			//if you stop the mouse event on this element, then the iframe window does not receive the focus in mozilla; logic, isn't it?
			//no focus, so no keyboard events on canvas
			top.canvas.focus();
		}
		for (var i=0;i<currentDiv.length;i++) {
			myQuery.tables.item(currentDiv[i].id).ui.cacheX = myQuery.tables.item(currentDiv[i].id).ui.x;
			myQuery.tables.item(currentDiv[i].id).ui.cacheY = myQuery.tables.item(currentDiv[i].id).ui.y;
		}
	}
	return true;
}

function tableDblClick(){
	if(currentDiv && currentDiv.length) {
		var tmp = currentDiv[0].id;
		top.tabset('sqlresults', 'fromui', 'select * from '+myQuery.tables.item(tmp).table_name);
	}
}

function lineDblClick(){
	currentElement = this;
	editLink();
}

function bodyMouseOut(){
}

function mylog(msg) {
	if (window.logdebug) {
		if (window.logWindow.document.body.innerHTML == '') {
			window.logWindow.document.body.innerHTML = '<textarea id="aaa"></textarea>';
		}
		var tmp = window.logWindow.document.getElementById('aaa');
		tmp.value = msg;
	}
}

if (logdebug) {
	window.logWindow = window.open("about:blank");
}
