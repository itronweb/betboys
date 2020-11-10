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

function checkRelations() {
	var delsw;
	var linklist = "";
	var rels = myQuery.relations;
	for (i=0;i<rels.length;i++) {
		delsw = false;
		if (!tableHasField(rels.item(i).table1, rels.item(i).field1)) {
			delsw = true;
		} else {
			if (!tableHasField(rels.item(i).table2, rels.item(i).field2)) {
				delsw = true;
			}
		}
		if (delsw) {
			linklist += rels.item(i).table1 + "." + rels.item(i).field1 + " - " + rels.item(i).table2 + "." + rels.item(i).field2 + "\n";
			top.ui.invalidate(true, true);
			currentElement = document.getElementById(rels.item(i).name);
			alert("checkRelations called:" + currentElement);
			delLink(true);
			i--;
		}
	}
	if (linklist != "") {
		alert("Removed links:\n"+linklist + "because of missing foreign/primary keys.");
	}
}

function unLockLink() {
	var tmp = currentElement.id;
	if(tmp.match(/divh[1-2]$/)){
		tmp = tmp.replace(/divh[1-2]$/, "", tmp);
		temp = document.getElementById(tmp);
	} else {
		temp = currentElement;
	}
	document.getElementById(temp.id+'divh1').relTop = document.getElementById(temp.id+'divh1').orelTop;
	document.getElementById(temp.id+'divh2').relTop = document.getElementById(temp.id+'divh2').orelTop;
	temp.relLeft = 0;
	myQuery.relations.item(tmp).redim();
	currentElement = null;
}

function removeLink(){
	if(currentDiv.length!=2){
	    alert(locales["Please select 2 tables."]);
	} else {
		if (confirm(locales["Are you sure you want to delete all relations\nbetween tables "]+currentDiv[0].id+locales[" and "]+currentDiv[1].id+" ?")) {
			for(var i=0;i<myQuery.relations.length; i++) {
				if(
					(myQuery.relations.item(i).table1 == currentDiv[0].id && myQuery.relations.item(i).table2== currentDiv[1].id) 
					|| (myQuery.relations.item(i).table1 == currentDiv[1].id && myQuery.relations.item(i).table2 == currentDiv[0].id)
				){
					myQuery.relations.item(i).remove();
					i--;
				}
			}
			top.ui.invalidate(true, true);
		}
	}
}

function delLink(conf){
	var i;
	if(top.mo.selectedRelation){
		if(conf || confirm(locales["Are you sure you want to delete this link?"])){
			var tmp = top.mo.selectedRelation;
			myQuery.relations.item(tmp).deselect();
			myQuery.relations.item(tmp).remove();
			top.ui.invalidate(true, true, 'sqlquery');
		}
	} else {
		if(!conf){
			alert(locales["Please select a link."]);
		}
	}
}


redimCurrentSelectionTOID = null;
function redimCurrentSelection() {
	//top.ui.invalidate(null, true);
	if(top.mo.selectedRelation) {
		myQuery.relations.item(top.mo.selectedRelation).redim();
	} else {
		if (drawRelationsAsMoving) {
			redimCurrentSelectionTO();
		} else {
			clearTimeout(redimCurrentSelectionTOID);
			redimCurrentSelectionTOID = setTimeout("redimCurrentSelectionTO()", 100);
		}
	}
}

function redimCurrentSelectionTO() {
	for(var j=0; j<myQuery.relations.length; j++) {
		if (
			myQuery.tables.item(myQuery.relations.item(j).table1).ui.selected
				||
			myQuery.tables.item(myQuery.relations.item(j).table2).ui.selected
			) {
			myQuery.relations.item(j).redim();
		}
	}
}

function newLink1(){
	if(currentDiv.length!=2){
		alert(locales["Please select 2 tables."]);
	} else {
		openIT("link.html", 500, 130, 120, 120, "_Link", "Add Relation");
	}
}

function editLink(){
	if(!top.mo.selectedRelation){
		alert(locales["Please select a link."]);
	} else {
		openIT("dlgEditRelation.html", 500, 290, 130, 130, "_table", "Edit Relation");
	}
}

function newLink(div1, div2, x1, x2, card1, card2, field1, field2, restrict, type){
	alert("newLink called!");
}
 
function redim(divv1, b) {
	var selRel = myQuery.relations.item(divv1.id);
	selRel.redim(b);
}

function max(a, b) {
		return (a<b)?b:a;
}

function min(a, b) {
		return (a>b)?b:a;
}

function abs1(a) {
		return (a<0)?-a:(a>0?a:1);
}

function removeRelationsSS() {
	openIT("removess.html", 550, 250, 120, 120, "_RemoveSS", "Remove Relations Server-Side");
}

