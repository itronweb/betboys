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

function openIT(u,W,H,X,Y,n, tW) {
	if (window.showModalDialog) {
		var wnd = showModalDialog(u, window, "edge:sunken; status:off; unadorned:off; help:no; font-family:Verdana; font-size:10; dialogWidth:" + W + "px; dialogHeight:" + (H+30) + "px");
	} else {
		try{
			//Mozilla 1.7 sometimes gives an error if the URL does not contain the domain (BASE of the opener)
			var s = window.location.href;
			s = s.replace(/\?.*$/, '');
			s = s.replace(/\/[^\/]*$/, '/');
			u = s + u;
			var wnd = window.open(u, "_blank", "dependent=1,left=300,top=300,width="+(W+20)+",height="+(H+20));
			wnd.focus();
		}catch(e) {
			alert("There was an error while trying to open an window! Requested URL:" + u + "\r\nError message:" +e.message);
		}
	}
	return wnd;
}
var helpWindow = null;
function openHelpWindowIT(u,W,H) {
	helpWindow = window.open(u, "_qubHelpWindow", "resizable=yes,status=yes,toolbar=yes,menubar=yes,location=yes,scrollbars=yes,toolbar=yes,dependent=1,left=" + (screen.availWidth - W - 20 - 40) + ",top=60,width="+(W+20)+",height="+(H+20));
	helpWindow.focus();
}
