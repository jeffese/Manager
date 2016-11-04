// JavaScript Document

function setitem(itm,stype) { 
	switch (stype) {
		case 1:	dir = "/shopping/"; break;
		case 2:	dir = "/autos/"; break;
		case 3:	dir = "/realestate/"; break;
		default: dir = "/";
	}
	carts =  false;
	if (itm.checked == true) {
		additem(itm, dir, carts);
	} else {
		remitem(itm, dir);
	}
}

function additem(itm, dir, cart) {
	// Basket script
	cartitems = getCookie('cartitems');
	if ((cartitems == undefined) || (cartitems == false))
		cartitems = '';
	itemval=itm.value;
	itemarray=itemval.split('%');
	cartitems += '#' + itemarray[0];
	cartitems = cartitems.replace(/^#/, "");
	setCookie("cartitems", cartitems, 0, dir, ".exood.com", 0);
	//Cart Shelf script
	cartshelf=getCookie('cartshelf');
	if ((cartshelf==undefined)||(cartshelf==false))
		cartshelf='';
	cartshelf += '#' + itemarray[1];
	cartshelf = cartshelf.replace(/^#/,"");
	setCookie("cartshelf", cartshelf, 0, dir, ".exood.com", 0);
	// Item Quantities
	cartqty = getCookie('cartqty');
	if ((cartqty == undefined) || (cartqty == false))
		cartqty = '';
	if (cart)
		cartqty += '#' + basket[itno][4];
	else
		cartqty += '#' + 1;
	cartqty = cartqty.replace(/^#/, "");
	setCookie("cartqty", cartqty, 0, dir, ".exood.com", 0);
	// Basket Count script
	baskcnt = document.getElementById('basket');
	if (document.getElementById('addpix'+setnum(itm.name)))
		document.getElementById('addpix'+setnum(itm.name)).src = '/images/remvcart.png';
	bcnt = cartitems.split('#');
	baskcnt.value = bcnt.length;
}

function remitem(itm, dir) {
	// Basket script
	itemval=itm.value;
	itemarray=itemval.split('%');
	
	cartitems = getCookie('cartitems')
	baskcky = cartitems.split('#')
	for (i = 0; i < baskcky.length; i++) {
		if (baskcky[i] == itemarray[0])
			break
	}
	// items
	baskcky[i] = ''
	cartitems = baskcky.join('#');
	cartitems = cartitems.replace(/^#/, "");
	cartitems = cartitems.replace(/#$/, "");
	cartitems = cartitems.replace(/##/g, "#");
	setCookie("cartitems", cartitems, 0, dir, ".exood.com", 0);
	// shelf
	baskcky[i] = ''
	cartshelf = baskcky.join('#');
	cartshelf = cartshelf.replace(/^#/, "");
	cartshelf = cartshelf.replace(/#$/, "");
	cartshelf = cartshelf.replace(/##/g, "#");
	setCookie("cartshelf", cartshelf, 0, dir, ".exood.com", 0);
	// Item Quantities
	cartqty = getCookie('cartqty')
	qty = cartqty.split('#')
	qty[i] = ''
	cartqty = qty.join('#')
	cartqty = cartqty.replace(/^#/, "")
	cartqty = cartqty.replace(/#$/, "")
	cartqty = cartqty.replace(/##/g, "#")
	setCookie("cartqty", cartqty, 0, dir, ".exood.com", 0)
	// Basket Count script
	baskcnt = document.getElementById('basket')
	if (document.getElementById('addpix'+setnum(itm.name)))
	document.getElementById('addpix'+setnum(itm.name)).src = '/images/addcart.png'
	bcnt = baskcnt.value
	bcnt = cartitems.split('#')
	baskcnt.value = (cartitems=='')? 0 : bcnt.length
}

function baskout(stype) {
	switch (stype) {
		case 1:	dir = "/shopping/"; break;
		case 2:	dir = "/autos/"; break;
		case 3:	dir = "/realestate/"; break;
		default: dir = "/";
	}
	//Basket Cookie script
	setCookie("cartitems", '', 0, dir, ".exood.com", 0);
	setCookie("cartshelf", '', 0, dir, ".exood.com", 0);
	//Basket Count script
	document.getElementById('basket').value=0	
}

function caltot(ckk) {
	cartqty = getCookie('cartqty')
	if ((cartqty == undefined) || (cartqty == false))
		cartqty = '';
	var qtys = cartqty.split('#')
	qtytot = 0
	Gtax = 0
	Gtot = 0
	Gdlv = 0
	tdlv = 0
	cartqty = ''
	for (tot=0; tot<items; tot++) {
		if (ckk==0){ 
			basket[tot][4] = parseInt(setnum(document.getElementById("qty"+(tot+1)).value), 10)
			if (basket[tot][4]<1) {
			basket[tot][4] = 0
			document.getElementById('chk'+(tot+1)).checked = false
			document.getElementById('lchk'+(tot+1)).checked = false
			setitem(document.getElementById('lchk'+(tot+1)), true)
			} else if (document.getElementById('lchk'+(tot+1)).checked == false) {
						document.getElementById('chk'+(tot+1)).checked = true
						document.getElementById('lchk'+(tot+1)).checked = true
						setitem(document.getElementById('lchk'+(tot+1)),true)
					}
		//set qty cookie
			cartitems = getCookie('cartitems')
			baskcky = cartitems.split('#')
			for (i = 0; i < baskcky.length; i++) {
				if (baskcky[i] == basket[tot][0])
					break
			}
			qtys[i] = basket[tot][4]
		}
		qtytot += basket[tot][4]
		//end
		document.getElementById("qty"+(tot+1)).value = basket[tot][4]
		basket[tot][5] = (parseFloat(basket[tot][2])-parseFloat(basket[tot][3])/*+parseFloat(basket[tot][9])*/)*basket[tot][4]
		basket[tot][5] = trailzero(rndup(basket[tot][5], 2), 2)
		document.getElementById("tot"+(tot+1)).innerHTML = '=N= '+setthous(basket[tot][5])
		if (document.getElementById('dlv'+(tot+1))) {
			rt = document.getElementById('city').selectedIndex
			setCookie("dlvcity", rt, 0, '/', ".exood.com", 0)
			if (rt>0) {
				if (routes[rt-1][4]==1) {
					tdlv = 0;
					Gdlv = routes[rt-1][6];
				} else {
					wgt = (routes[rt-1][12]==0) ? basket[tot][7] : Math.max(basket[tot][6]*routes[rt-1][13], basket[tot][7]);					
					tdlv = Math.max(wgt * routes[rt-1][6], routes[rt-1][5]);					
				}
				tdlv = trailzero(rndup(tdlv, 2), 2)
			} else {
				tdlv = '0.00'
			}
			basket[tot][10] = tdlv;
			document.getElementById('dlv'+(tot+1)).innerHTML = '=N= '+tdlv;
		}
		Gtax += parseFloat(basket[tot][9])
		Gtot += parseFloat(basket[tot][5])
		Gdlv += parseFloat(tdlv)
	}
	Gtotal = trailzero(rndup(parseFloat(Gtot)+parseFloat(Gdlv), 2), 2)
	Gtax = trailzero(rndup(Gtax, 2), 2)
	Gtot = trailzero(rndup(Gtot, 2), 2)
	Gdlv = trailzero(rndup(Gdlv, 2), 2)
	nwords = NumToWords(Gtotal, "Naira", "Kobo")
	if (ckk==0){ 
		cartqty = qtys.join('#')
		cartqty = cartqty.replace(/^#/, "")
		cartqty = cartqty.replace(/#$/, "")
		cartqty = cartqty.replace(/##/g, "#")
		setCookie("cartqty", cartqty, 0, "", ".exood.com", 0)
	}
	document.getElementById("totqty").innerHTML = setthous(qtytot);
	document.getElementById("totgrand").innerHTML = '=N= '+setthous(Gtot);
	if (document.getElementById('totdlv'))
		document.getElementById("totdlv").innerHTML = '=N= '+setthous(Gdlv);
	if (document.getElementById('totbill'))
	document.getElementById('totbill').innerHTML = '<b>=N= '+setthous(Gtotal)+'</b>';
	document.getElementById("itemqty").value = setthous(qtytot);
	document.getElementById("cashtot").value = Gtot;
	document.getElementById("numwords").innerHTML = nwords;
	setCookie("qty", setthous(qtytot), 0, '/', ".exood.com", 0)
	setCookie("tax", Gtax, 0, '/', ".exood.com", 0)
	setCookie("tot", Gtot, 0, '/', ".exood.com", 0)
	setCookie("dlv", Gdlv, 0, '/', ".exood.com", 0)
	setCookie("cash", Gtotal, 0, '/', ".exood.com", 0)
	setCookie("cashwords", nwords, 0, '/', ".exood.com", 0)
	setCookie("baskvals", serialize(basket), 0, '/', ".exood.com", 0)
}