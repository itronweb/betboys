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

String.prototype.HTMLEntities = [
	["&", "&amp;"], 
	["<", "&lt;"],
	[">", "&gt;"],
	["'", "&#039;"],
	['"', "&quot;"]
];
function String_HTMLEncode() {
	var i, a, s, re;
	s = this;
	a = "".HTMLEntities;
	for (i=0; i<a.length; i++) {
		re = new RegExp(a[i][0], "g");
		s = s.replace(re, a[i][1]);
	}
	return s;
}
function String_HTMLDecode() {
	var i, a, s, re;
	s = this;
	a = "".HTMLEntities;
	for (i=0; i<a.length; i++) {
		re = new RegExp(a[i][1], "g");
		s = s.replace(re, a[i][0]);
	}
	return s;
}
String.prototype.HTMLEncode = String_HTMLEncode;
String.prototype.HTMLDecode = String_HTMLDecode;

var dpo_debug = false;
if(dpo_debug){
	var dbgw=showModelessDialog("blank.html",window,"status:false;dialogLeft:"+(window.screen.availWidth-300)+";dialogWidth:300px;dialogHeight:500px; resizable:yes; 	");
}
	function d(obj,replace_content){
if(dpo_debug){
		var ss = "<br>";
		if(typeof(replace_content)=="undefined") {
			replace_content=false;
		}
		try{
			for(var fn in obj){
				ss+=fn+"="+obj[fn] + "<br>";
			}
		}catch(e){}
	
		if(ss=="<br>"){
			ss = obj;
		}
		dbgw.document.body.innerHTML=replace_content?ss:dbgw.document.body.innerHTML+ss;
	}
}
