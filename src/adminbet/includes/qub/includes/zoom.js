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
start_zoom_factor = 1
zoomFactor = start_zoom_factor;

function zoomIn(){
	zoomFactor*=1.1;
	zoom(1.1);
}

function zoomOut(){
	zoomFactor/=1.1;
	zoom(1/1.1);
}

function zoom(zf){
	var tables = myQuery.tables;

	for(i=0;i<tables.length;i++){
		tables.item(i).ui.move(tables.item(i).ui.x * zf, tables.item(i).ui.y * zf);
	}

	var rulesList = document.styleSheets[1].rules;
	if (!rulesList) {
		rulesList = document.styleSheets[1].cssRules;
	}

//	rulesList[0].style.height   = 12*zf;
//	rulesList[1].style.height   = 12*zf;
//	rulesList[2].style.height   = 14*zf;

	rulesList[0].style.fontSize = 10*zoomFactor;
	rulesList[1].style.fontSize = 10*zoomFactor;
	rulesList[2].style.fontSize = 12*zoomFactor;
	rulesList[3].style.fontSize = 10*zoomFactor;
	rulesList[4].style.fontSize = 10*zoomFactor;

	var rels = myQuery.relations;
	for(i=0;i<rels.length;i++){
		var div1 = document.getElementById(rels.item(i).name);
		var divh1 = document.getElementById(rels.item(i).name+"divh1");
		var divh2 = document.getElementById(rels.item(i).name+"divh2");
		div1.relLeft=div1.relLeft*zf;
		divh1.relTop=divh1.relTop*zf;
		divh2.relTop=divh2.relTop*zf;
		rels.item(i).redim();
	}
}
