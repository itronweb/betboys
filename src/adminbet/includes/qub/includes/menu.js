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

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                    //
    // Copyright www.interakt.ro 2000-2002                                                                //
    // Menus.js v2.0                                                                                      //
    //                                                                                                    //
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
//3c4070 : dark blue
//3c426c : dark blue ( lighter)
//ffffcc : help yellow	
//d4d0c8 : grey

   itemOnColor = "highlight";
   itemOffColor = "#d4d0c8";

		itemOffTextColor = "MenuText";
		itemOnTextColor = "highlighttext";

   menuNext = "<img src=images/next.gif>";
   newMenuXDepl = -2;
   newMenuYDepl = 2;
   menuBgColor="#808080";
   menuFgColor="#efefef";

   // preload images

   im1 = new Image();
   im1.src = "images/nimic.gif";
   im2 = new Image();
   im2.src = "images/next.gif";

   function loaded(){

    status = "Menu: Loading ...";
    myStatus = 0;

    l1=addMenu(1, "Query", "", 100, 55, 2, 2); 
    
    //l1.addMenuItem("&nbsp;Open",    "openQuery();");
    //l1.addMenuItem("&nbsp;New",     "save(0);");
    //l1.addMenuSeparator(1);
    l1.addMenuItem("&nbsp;Save",    "showPleaseWait('Please wait while saving', '160px', '');setTimeout('save(1)', 60);");
//    l1.addMenuSeparator(1);
    l1.addMenuItem("&nbsp;Save&nbsp;As", "saveAs();");
    l1.addMenuSeparator(1);
    l1.addMenuItem("&nbsp;Settings",   "canvas.settings();");
    l1.addMenuSeparator(1);
    l1.addMenuItem("&nbsp;Print",   "printIt();");
    l1.addMenuSeparator(1);
    l1.addMenuItem("&nbsp;Close",   "closeQUB();");

    l4 = addMenu(1, "Help", "openHelp();", 125, 55);

    myStatus = 1;
    status = "Menu: Loaded";
   }
   parentWindow = this;
   lastElement = this;

   NS = !(document.all);
   DOM = !!(document.getElementById);
   if (DOM) {
   	 NS = false;
   }
   
   fii = new Array();
   layerN = 0;


   function KT_parseInt(i){
   	 i+="px";
     return parseInt(i.replace(/px/g, ""));
   }

   function show(l){
    if(NS && !DOM){
     l.visibility = "show";
    }
    else{
     l.style.display = "block";
    }
   }

   function hide(l){
   	if(NS && !DOM){
      l.visibility = "hide";
    } else {
		if (l.style) {
      l.style.display = "none";
    }
   }
   }

   function scrie(l, text){
    if(DOM || !NS){
     l.innerHTML = text;
    } else {
     l.document.open();
     l.document.write(text);
     l.document.close();
    }
   }

   function Leiar(x, tata){
    var temp, el1;
    if(DOM || !NS){
     if(tata == parentWindow){
      el1 =  document.body;
     } else {
      el1 = tata;
     }
     temp =  document.createElement('div');
     if(!temp){
     	src = "<div id=leiar"+parentWindow.layerN+" class=ieLeiar/>";
     	el1.innerHTML+=src;
     	temp = el1.getElementById("leiar"+parentWindow.layerN);
     } else {
     	temp.id = "leiar"+parentWindow.layerN;
     	temp.className = "ieLeiar";
     	el1.appendChild(temp);
     }
     parentWindow.layerN++;
     return(temp);
    }else{
     temp = new Layer(x, tata);
     return temp;
    }
   }

   function arrayPush(a, l){
   	if(DOM || !NS){
     a[a.length]=l;
    }
    else{
     a.push(l);
    }
   }

   function addMenu(eSusParam, text, exe, w, mw, x, y){
    if(!w){
     w = 60;
    }
    if(!mw){
     mw = 40;
    }
    if(this.fiu){
     return this.fiu.addMenu(0, text, exe, w, mw, x, y);
    }
    var aux;
    if (this == parentWindow) {
     if(!x){
      if(this.fii.length != 0){
       aux = this.fii[this.fii.length-1];
       if (eSusParam) {
       	x = (NS)?(aux.pageX + aux.clip.right):(KT_parseInt(aux.style.left) + KT_parseInt(aux.style.width));
       	y = (NS)?(aux.pageY):(KT_parseInt(aux.style.top));
       } else {
       	x = (NS)?(aux.pageX):(KT_parseInt(aux.style.left));
       	y = (NS)?(aux.pageY + aux.clip.bottom):(KT_parseInt(aux.style.top) + aux.offsetHeight);
       }
      }
      else{
       x=0;
       y=0;
      }
     }
     aux = new MenuButton(text, x, y, mw, this, exe);
     aux.eSus = eSusParam;
     show(aux);
     if(NS){
      auxx = new Menu(w, this);
     }
     else{
      auxx = new Menu(w, this)
     }
     aux.fiu = auxx;
     aux.addMenuItem = addMenuItem;
     aux.addMenu = addMenu;
     aux.addMenuSeparator = addMenuSeparator;
     auxx.tata = aux;
     arrayPush(this.fii, aux);
     show(aux);
    } else {
     aux = this.addMenuItem("<table border=0 cellspacing=0 cellpadding=0 class=item><tr><td width=100% class=item>"+text+"</td><td valign=center class=item> "+menuNext+" </td></table>", exe);
     auxx = new Menu(w, parentWindow);
     aux.fiu = auxx;
     auxx.tata = aux;
     aux.addMenuItem = addMenuItem;
     aux.addMenu = addMenu;
     aux.addMenuSeparator = addMenuSeparator;
    }
    return aux;
   }

   function addMenuItem(text, exe){
    if(this.fiu){
     return this.fiu.addMenuItem(text, exe);
    }
    var aux;
    aux = new MenuItem(text, this, exe);
    arrayPush(this.fii, aux);
    return aux;
   }

   function addMenuSeparator(ceFel){
     var aux = new MenuSeparator(this.fiu, this.fiu.latsime-2, ceFel);
     if(NS){
       arrayPush(this.fiu.fii, aux);
     }
   }

   function showMenu(l){
    show(l);
    for(i=0;i<l.fii.length;i++){
     show(l.fii[i]);
    }
   }

   function hideFii(fii){
     for(i=0;i<fii.length;i++){
      hide(fii[i]);
     }
   }

   function Menu(w, tata){
    var aux;
    aux = new Leiar(w, tata);
    aux.fii = new Array();
    if(NS){
     aux.clip.bottom=2;
     aux.clip.right=w;
     txt = "<table border=0 cellspacing=0 cellpadding=0 width=100% height=100% class=item><tr><td class=item colspan=2 bgcolor="+parentWindow.menuFgColor+" height=1><img src=images/nimic.gif height=1></td></tr><tr><td bgcolor="+parentWindow.menuFgColor+" height=100% width=1 class=item><img src=images/nimic.gif width=1></td><td bgcolor="+parentWindow.menuBgColor+" width=100% height=1 class=item><img src=images/nimic.gif height=1></td></tr><tr><td colspan=2 bgcolor="+parentWindow.menuBgColor+" height=1 class=item><img src=images/nimic.gif height=1></td></tr></table>";
    } else {
     aux.style.width=w;
     txt = "<table border=0 cellspacing=0 cellpadding=0 bgcolor='"+parentWindow.menuFgColor+"' id=tablou width="+w+" class=item><tr><td colspan=3 height=1 class=item><img src=images/nimic.gif height=1></td></tr><tr><td width=1 height=1 class=item><img src=images/nimic.gif height=1></td><td colspan=2 bgcolor="+parentWindow.menuBgColor+" height=1 class=item><img src=images/nimic.gif height=1></td></tr></table>";
    }
    scrie(aux, txt);
    aux.addMenuItem = parentWindow.addMenuItem;
    aux.addMenu = parentWindow.addMenu;
    aux.addMenuSeparator = addMenuSeparator;
    aux.onmouseover = menuMouseOver;
    aux.onmouseout = menuMouseOut;
    aux.tata = tata;
    aux.tip = "Menu";
    aux.latsime=w;
    return aux;
   }

   function MenuItem(text, tata, exe){
    var aux;
    var w;
    text = "<a class=item>"+text+"</a>";
    if(NS){
     w = tata.latsime - 2;
     aux = new Leiar(w, tata);
     aux.bgColor=parentWindow.itemOffColor;
     aux.clip.right = w;
     aux.moveTo(1, tata.clip.bottom - 1);
     scrie(aux, text);
     tata.clip.bottom += aux.clip.bottom;
    } else {
     if(DOM){
     	var tt = tata.getElementsByTagName('table');
     	for(i=0;i<tt.length;i++) {
     		if (tt[i].id=='tablou') {
     			var nr = tt[i].rows.length;
     			var tr = tt[i].insertRow(nr-1);
     		}
     	}
     } else {
     	var nr = tata.all.tablou.rows.length;
     	var tr = tata.all.tablou.insertRow(nr-1);
     }
     var cel = tr.insertCell(0);
     cel.innerHTML = "<img src=images/nimic.gif width=1 height=1>";
     cel.style.width="1";
     cel.style.height="1";
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     var cel1 = tr.insertCell(1);
     cel1.innerHTML = text;
     cel1.style.backgroundColor = parentWindow.itemOffColor;
     cel1.style.width="100%";
     cel1.style.borderLeftWidth = "0px";
     cel1.style.borderRightWidth = "0px";
     cel1.style.borderTopWidth = "0px";
     cel1.style.borderBottomWidth = "0px";
     cel = tr.insertCell(2);
     cel.innerHTML = "<img src=images/nimic.gif width=1 height=1>";
     cel.style.backgroundColor = parentWindow.menuBgColor;
     cel.style.width="1";
     cel.style.height="1";
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     if(!(tata.ien)){
      tata.ien = 0;
     }
     cel1.ien = tata.ien;
     tata.ien++;
     aux = cel1;
    }
    aux.onmouseover=menuItemMouseOver;
    aux.onmouseout=menuItemMouseOut;
    if (NS) {
     aux.captureEvents(Event.MOUSEDOWN);
     aux.onmousedown=click;
    } else {
     aux.onmouseup=click;
    }
    if(exe){
     aux.exe = exe;
    }
    aux.tata = tata;
    aux.tip = "MenuItem";
    return aux;
   }

   function MenuSeparator(tata, x, ceFel){
    if(NS){
     var aux = new Leiar(x, tata);
     aux.clip.right = x;
     aux.moveTo(1, tata.clip.bottom - 1);
     if(ceFel==1){
     	scrie(aux, "<table border=0 cellspacing=0 cellpadding=0 width=100% class=item><tr><td bgcolor="+parentWindow.menuBgColor+" width=1 height=1 class=item><img src=images/nimic.gif height=1></td></tr><tr><td bgcolor="+parentWindow.menuFgColor+" width=1 height=1 class=item><img src=images/nimic.gif height=1></td></tr></table>");
     }else{
     	scrie(aux, "<table border=0 cellspacing=0 cellpadding=0 width=100% class=item><tr><td bgcolor="+parentWindow.itemOffColor+" width=1 height=1 class=item><img src=images/nimic.gif height=1></td></tr><tr><td bgcolor="+parentWindow.itemOffColor+" width=1 height=1 class=item><img src=images/nimic.gif height=1></td></tr></table>");
     }
     tata.clip.bottom += aux.clip.bottom;
     return aux;
    }
    else{
     if(DOM){
     	var tt = tata.getElementsByTagName('table');
     	for(i=0;i<tt.length;i++) {
     		if (tt[i].id=='tablou') {
	     		var nr = tt[i].rows.length;
    	 		var tr = tt[i].insertRow(nr-1);
    	 	}
     	}
     } else {
     	var nr = tata.all.tablou.rows.length;
     	var tr = tata.all.tablou.insertRow(nr-1);
     }
     var cel = tr.insertCell(0);
     cel.innerHTML = "<img src=images/nimic.gif height=1 width=1>";
     cel.style.width = "1";
     cel.style.height = "1";
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     cel = tr.insertCell(1);
     cel.innerHTML = "<img src=images/nimic.gif height=1 width=1>";
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     if(ceFel!=0){
     	cel.style.backgroundColor = parentWindow.menuBgColor;
     }else{
     	cel.style.backgroundColor = parentWindow.itemOffColor;
     }
     cel.style.width="100%";
     cel = tr.insertCell(2);
     cel.innerHTML = "<img src=images/nimic.gif height=1 width=1>";
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     cel.style.backgroundColor = parentWindow.menuBgColor;
     cel.style.width = "1";
     cel.style.height = "1";

     if(DOM){
     	var tt = tata.getElementsByTagName('table');
     	for(i=0;i<tt.length;i++) {
     		if (tt[i].id=='tablou') {
	     		tr = tt[i].insertRow(nr);
	     	}
     	}
     } else {
	     tr = tata.all.tablou.insertRow(nr);
     }
     cel = tr.insertCell(0);
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     cel.innerHTML = "<img src=images/nimic.gif height=1 width=1>";
     cel.style.width = "1";
     cel.style.height = "1"; 
     cel = tr.insertCell(1);
     cel.innerHTML = "<img src=images/nimic.gif height=1 width=1>";
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     if(ceFel==0){
     	cel.style.backgroundColor = parentWindow.itemOffColor;
     }
     cel.style.width="100%";
     cel = tr.insertCell(2);
     cel.style.borderLeftWidth = "0px";
     cel.style.borderRightWidth = "0px";
     cel.style.borderTopWidth = "0px";
     cel.style.borderBottomWidth = "0px";
     cel.innerHTML = "<img src=images/nimic.gif height=1 width=1>";
     cel.style.backgroundColor = parentWindow.menuBgColor;
     cel.style.width = "1";
     cel.style.height = "1";
    }
   }

   function MenuButton(text, x, y, w, tata, exe){
    var aux;
    aux = new Leiar(w, tata);
    if(NS && !DOM){
     aux.bgColor=parentWindow.itemOffColor;
     aux.moveTo(x, y);
    }
    else{
     aux.style.backgroundColor=parentWindow.itemOffColor;
     aux.style.top = y;
     aux.style.left = x;
    }
    if(w){
     if(NS){
      aux.clip.right = w;
     }
     else{
      aux.style.width = w;
     }
    }
    scrie(aux, "<table border=0 cellspacing=0 cellpadding=0 width="+w+" class=item><tr><td colspan=3 bgcolor="+parentWindow.menuFgColor+" height=1 class=item><img src=images/nimic.gif height=1></td></tr><tr><td bgcolor="+parentWindow.menuFgColor+" height=100% width=1 class=item><img src=images/nimic.gif width=1></td><td width=100% align=center class=item oncontextmenu='return false;' onselectstart='return false;'>"+text+"</td><td bgcolor="+parentWindow.menuBgColor+" width=1 height=1 class=item><img src=images/nimic.gif width=1 height=1></td></tr><tr><td colspan=3 bgcolor="+parentWindow.menuBgColor+" height=1 class=item><img src=images/nimic.gif height=1></td></tr></table>");
    aux.onmouseover=menuButtonMouseOver;
    if (NS) {
     aux.captureEvents(Event.MOUSEDOWN);
     aux.onmousedown=click;
    } else {
     aux.onmouseup=click;
    }
    aux.onmouseout=menuButtonMouseOut;
    aux.menuItemMouseOut = menuItemMouseOut;
    aux.menuItemMouseOver = menuItemMouseOver;
    aux.menuMouseOut = menuMouseOut;
    aux.menuMouseOver = menuMouseOver;
    aux.exe=exe;
    aux.tip = "MenuButton";
    return aux;
   }

   function hideRec(ce, panaUnde){
    if(ce.tip != "MenuButton"){
     if(ce.tata != panaUnde){
      if(ce.fii){
       hideFii(ce.fii);
      }
      hide(ce);
      hideRec(ce.tata, panaUnde);
     }
    }
   }

   function menuItemMouseOver(e){
		 if (typeof canvas.tcm != 'undefined') {
			 canvas.hidemenu('tcm');
		 }
		 if (typeof canvas.lcm != 'undefined') {
			 canvas.hidemenu('lcm');
		 }
		 if (typeof canvas.rcm != 'undefined') {
			 canvas.hidemenu('rcm');
		 }

    if(this.fiu && this.tip=="MenuItem"){
     if(NS && !DOM){
      this.fiu.pageX=this.pageX + this.clip.right + 1 + parentWindow.newMenuXDepl;
      this.fiu.pageY=this.pageY - 1 + parentWindow.newMenuYDepl;
     }
     else{
      this.fiu.style.left = KT_parseInt(this.tata.style.left) + KT_parseInt(this.tata.offsetWidth) + parentWindow.newMenuXDepl;
      var a = this.offsetTop;
      if(a==0){
       a=this.tata.clientHeight/(this.tata.ien)*this.ien + 1;
      }
      this.fiu.style.top = KT_parseInt(this.tata.style.top) + a - 1 + parentWindow.newMenuYDepl;
     }
    }

    if(NS && !DOM){
     this.bgColor=parentWindow.itemOnColor;
    }
    else{
     this.style.backgroundColor=parentWindow.itemOnColor;
     this.style.color=parentWindow.itemOnTextColor;
    }
    if(parentWindow.lastElement != parentWindow){
     if(this.tip == "MenuButton"){
      if(parentWindow.lastElement != this){
       if(parentWindow.lastElement.fiu){
        hideFii(parentWindow.lastElement.fiu.fii);
        hide(parentWindow.lastElement.fiu);
       }
       hideRec(parentWindow.lastElement, this);
      }
     } else {
      if(this.tata.tata != parentWindow.lastElement){
       if(parentWindow.lastElement.fiu){
        hideFii(parentWindow.lastElement.fiu.fii);
        hide(parentWindow.lastElement.fiu);
       }
       if(parentWindow.lastElement.tata != this.tata){
        hideRec(parentWindow.lastElement, this.tata);
       }
      }
     }
     parentWindow.lastElement = this;
    }
    if(this.fiu){
     if(this.fiu.fii.length != 0){
      showMenu(this.fiu);
     }
    }
   }

   function menuItemMouseOut(e){
    if(NS && !DOM){
     this.bgColor=parentWindow.itemOffColor;
    }
    else{
     this.style.backgroundColor=parentWindow.itemOffColor;
     this.style.color=parentWindow.itemOffTextColor;
    }
    parentWindow.lastElement = this;
   }

   function menuMouseOver(){
		 if (this.fiu) {
	    this.firstChild.rows[1].style.color=parentWindow.itemOnTextColor;
		 }
    if(parentWindow.toID != -1){
     clearTimeout(parentWindow.toID);
     parentWindow.toID = -1;
    }
   }

   function menuMouseOut(){
		if (this.fiu) {
    	this.firstChild.rows[1].style.color=parentWindow.itemOffTextColor;
		}
    parentWindow.toID = setTimeout("timeout();", 1000);
   }

   function timeout(){
    if(parentWindow.lastElement != parentWindow){
     if(parentWindow.lastElement.fiu){
      hideRec(parentWindow.lastElement.fiu, parentWindow);
     }
     else{
      hideRec(parentWindow.lastElement, parentWindow);
     }
    }
    parentWindow.toID = -1;
   }

   function menuButtonMouseOut(){
    if(myStatus == 1){
     this.menuItemMouseOut();
     this.menuMouseOut();
    }
   }

   function menuButtonMouseOver(){
    if(myStatus == 1){
     if(NS && !DOM){
      if(this.eSus){
	      this.fiu.pageX = this.pageX;
    	  this.fiu.pageY = this.pageY + this.clip.bottom;
      }else{
	      this.fiu.pageX = this.pageX + this.clip.right;
    	  this.fiu.pageY = this.pageY;
      }
     }
     else{
      if(this.eSus){
      	this.fiu.style.left = this.style.left;
      	this.fiu.style.top  = KT_parseInt(this.style.top) + this.offsetHeight;
      }else{
      	this.fiu.style.left = KT_parseInt(this.style.left) + this.offsetWidth;
      	this.fiu.style.top  = KT_parseInt(this.style.top);
      }
     }
     this.menuMouseOver();
     this.menuItemMouseOver();
    }
   }

   function click(e) {
		if (!window.event) {
			var event = e;
			eventbutton = event.button + 1;
			if (eventbutton == 3) eventbutton = 2;
			
			if (event.detail==0) {
				eventbutton = 2;
			}

		} else {
			var event = window.event;
			eventbutton = event.button;
		}
		if (eventbutton != 1) {
			return false;
		}
		var o = this;
		o.onmouseout();
		while (o.tata && o.tata.tata) {
			o = o.tata;
		}
		if (o.tip != 'MenuButton') {
			hide(o);
		}
		hideFii(o);
		
    if(this.exe){
     timeout();
     eval(this.exe);
    }
    return false;
   }
