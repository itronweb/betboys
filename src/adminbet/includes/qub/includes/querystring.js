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

// split a string similar to an url and extract the parameters by default
// return a hash with the left side as key and an array containing all values

// delimiters can be modified, default values are:
// sep1 = ?
// sep2 = &
// sep3 = =

function splitQS(str, sep1, sep2, sep3) {
	var qs, idx, aux, re;
	qs = new Object();
	if (!str) {
		str = window.location.href.toString();
	} else {
		str = str.toString();
	}
	if (str.indexOf('#') >= 0) {
		str = str.replace(/#.*$/, '');
	}
	if (!sep1) sep1 = "?";
	if (!sep2) sep2 = "&";
	if (!sep3) sep3 = "=";
	idx = str.indexOf(sep1);
	if (idx != -1) {
		str = str.substr(idx+1);
	}
	if (str.length == 0) return;
	params = str.split(sep2);
	for (i=0; i<params.length; i++) {
		if (params[i].length<0) continue; // skip empty elements &&
		if (params[i].split(sep3).length == 2) {
			aux = params[i].split(sep3);
			if (typeof(qs[aux[0]]) != "object") {
				qs[aux[0]] = new Array();
			}
			qs[unescape(aux[0])].push(unescape(aux[1]));
		}
	}
	return qs;
}
