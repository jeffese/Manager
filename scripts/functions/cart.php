<?php 
require_once('../../scripts/bset.php');
vetlogin();

echo $tmpt[0];
?>
<link href="/css/style001.css" rel="stylesheet" type="text/css"><?php echo $tmpt[1]; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><script type="text/javascript">
var basket = new Array()
var itno = 0
var items = <?php echo count($Tshopping) ?>;
var Gtot = 0
var qtytot = 0
var cartitems=getCookie("cartitems")
var baskcky = cartitems.split('#')
var cartqty = getCookie('cartqty')
var qty = cartqty.split('#')
</script>
      <span class="header">My Cart</span>
<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border: solid #CCC 2px">
        <tr class="boldwhite1">
          <td height="20" align="center" bgcolor="#DA261D">#</td>
          <td align="center" bgcolor="#DA261D">&nbsp;</td>
          <td align="center" bgcolor="#DA261D">Selected Option</td>
          <td align="center" bgcolor="#DA261D">Unit Price</td>
          <td align="center" bgcolor="#DA261D">Discount</td>
          <td align="center" bgcolor="#DA261D">Quantity</td>
          <td align="center" bgcolor="#DA261D">&nbsp;</td>
          <td align="center" bgcolor="#DA261D">Period</td>
          <td align="center" bgcolor="#DA261D">Total</td>
        </tr>
        <?php if ($totalRows_Tshopping > 0) {
				  for ($k=0; $k<count($Tshopping); $k++) {
					$item = ''; $j = $k+1; ?>
        <tr>
          <td align="center"><?php echo $j ?></td>
          <td><input name="lchk<?php echo $j ?>" id="lchk<?php echo $j ?>" type="checkbox" value="<?php echo $Tshopping[$k]['ex_id'] ?>" onclick="setitem(this, true); document.getElementById('chk<?php echo $j ?>').checked = this.checked; if (this.checked) document.getElementById('qty<?php echo $j ?>').value=1; else document.getElementById('qty<?php echo $j ?>').value=0; " />
            <script language="JavaScript" type="text/javascript">
if (!((cartitems==undefined)||(cartitems==false))) {
	re = new RegExp("<?php echo $Tshopping[$k]['ex_id'] ?>")
	if (cartitems.search(re) != -1)
		document.getElementById("lchk<?php echo $j ?>").checked = true;
}
	for (i = 0; i < baskcky.length; i++) {
		if (baskcky[i] == <?php echo $Tshopping[$k]['ex_id'] ?>) {
			itno = i;
			break;
		}
	}
	disc = <?php echo $Tshopping[$k]['price'] ?> * <?php echo isset($_SESSION['discount']) ? $_SESSION['discount'] : 0 ?> / 100;
	disc = trailzero(rndup(disc, 2), 2);
	taxv = (<?php echo $Tshopping[$k]['price'] ?> - disc) * <?php echo VAT ?> / 100;
	taxv = trailzero(rndup(taxv, 2), 2);
	vol = <?php echo $Tshopping[$k]['length'] ?> * <?php echo $Tshopping[$k]['width'] ?> * <?php echo $Tshopping[$k]['breadth'] ?>;
	weight = <?php echo $Tshopping[$k]['weight'] ?>;
	basket.push([<?php echo $Tshopping[$k]['ex_id'] ?>, "<?php echo addslashes($Tshopping[$k]['ex_name']) ?>", "<?php echo $Tshopping[$k]['price'] ?>", disc, parseInt(setnum(qty[itno])), '0.00', vol, weight, '<?php echo $Tshopping[$k]['symbol'] ?>', taxv, 0])
                                          </script></td>
          <td><select name="item<?php echo $j ?>" id="item<?php echo $j ?>">
          </select></td>
          <td align="right" id="itmprice<?php echo $j ?>"></td>
          <td align="right" id="disc<?php echo $j ?>"></td>
          <td align="center" nowrap="nowrap"><input id="qty<?php echo $j ?>" name="qty<?php echo $j ?>" type="text" onblur="caltot(0)" style="width:40px; text-align:center" /></td>
          <td align="right" id="tot<?php echo $j ?>">&nbsp;</td>
          <td align="right" id="tot<?php echo $j ?>">&nbsp;</td>
          <td align="right" id="tot<?php echo $j ?>"><script language="JavaScript" type="text/javascript">

	document.getElementById("itmname<?php echo $j ?>").innerHTML = basket[<?php echo $k ?>][1];
	document.getElementById("itmprice<?php echo $j ?>").innerHTML = '=N= '+setthous(basket[<?php echo $k ?>][2]);
	document.getElementById("disc<?php echo $j ?>").innerHTML = '=N= '+setthous(disc);
	//document.getElementById("tax<?php echo $j ?>").innerHTML = 'N '+setthous(taxv);
	document.getElementById("qty<?php echo $j ?>").value = setthous(qty[itno]);
                      </script></td>
        </tr>
        <?php }
                          }else { ?>
        <b class="red-normal">Your Cart is Empty!</b>
        <?php } ?>
        <tr>
          <td height="5" colspan="9" bgcolor="#5C605E"></td>
        </tr>
        <tr>
          <td colspan="4" align="right" id="numwords">&nbsp;</td>
          <td align="right" valign="top"><input type="hidden" name="itemqty" id="itemqty" />
            <input type="hidden" name="cashtot" id="cashtot" />
            <b>Total:</b></td>
          <td align="center" bgcolor="#CCDC05" id="totqty" class="red-normal">&nbsp;</td>
          <td align="center" nowrap="nowrap" bgcolor="#CCDC05" class="red-normal" id="totgrand">&nbsp;</td>
          <td align="center" nowrap="nowrap" bgcolor="#CCDC05" class="red-normal" id="totgrand">&nbsp;</td>
          <td align="center" nowrap="nowrap" bgcolor="#CCDC05" class="red-normal" id="totgrand">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="right"><?php if (count($Tshopping)>0) { ?>
      <img src="/images/checkout.png" width="130" height="40" alt="Checkout" onclick="if (qtytot==0) {alert('There are no items in your cart')} else {document.location = '/payment.php'}"  />
      <?php } ?></td>
  </tr>
</table>
<?php echo $tmpt[2]; ?>