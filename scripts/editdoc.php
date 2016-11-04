<?php
$docs = getDBData($dbh, "SELECT `documentfiles`.*, $vendor_sql 
        FROM {$_SESSION['DBCoy']}.`documentfiles` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`  ON `documentfiles`.EmployeeID=`vendors`.VendorID 
        WHERE `shelf`='$doc_shelf' AND `OwnerID`=$doc_id");
?>
<table border="0" cellspacing="4" cellpadding="4">
    <tr valign="baseline">
        <td><input name="docnt" type="hidden" id="docnt" value="<?php echo count($docs) ?>" />
            <script type="text/javascript">
                var docs=<?php echo count($docs) + 1 ?>;
          
                function addDoc() {
                    var content = '<table border="0" cellpadding="2" cellspacing="2" id="docs'+docs+'">\n\
      <tr><td class="red-normal"><strong>'+docs+'.</strong></td>\n\
      <td><input type="file" name="doc'+docs+'" id="doc'+docs+'" value="" \n\
      onchange="$(\'#docname'+docs+'\').html(this.value)" /></td>\n\
      <td class="black-normal" id="docname'+docs+'"></td>\n\
      <td><textarea name="doc_info'+docs+'" rows="2" style="width:200px"></textarea></td></tr></table>'
              $('#modoc').before(content); 
              $('#docnt').val(docs); 
              docs++;
          }
            </script>

            <?php $i = 1;
            foreach ($docs as $doc) { ?>
                <table border="0" cellpadding="2" cellspacing="2" style="float:left; padding:4px;">
                    <tr>
                        <td width="10" class="red-normal"><img src="/images/b_drop.png" width="16" height="16" border="0" onclick="$('#icdoc<?php echo $i ?>').attr('src', '/images/noimage2.jpg'); $('#d<?php echo $i ?>').attr('value', '0');" />
                            <input type="hidden" name="d<?php echo $i ?>" id="d<?php echo $i ?>" />
                            <input type="hidden" name="f<?php echo $i ?>" value="<?php echo $doc['DocID'] ?>" />
                            <input type="hidden" name="fn<?php echo $i ?>" value="<?php echo $doc['fname'] ?>" /></td>
                        <td><img src="/images/docs.png" id="icdoc<?php echo $i ?>" border="0" /></td>
                        <td><table border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td id="docname<?php echo $i ?>"><a href="/documents/archive/getfile.php?id=<?php echo $doc['DocID'] ?>" target="new"><?php echo $doc['FileName'] ?></a></td>
                                </tr>
                                <tr>
                                    <td><?php echo $doc['VendorName'] ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $doc['DocDate'] ?></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td><span class="red-normal"><strong><?php echo $i ?></strong></span></td>
                        <td colspan="2"><?php echo $doc['Description'] ?></td>
                    </tr>
                </table>
    <?php $i++; } ?>
            <div id="modoc"><a href="Javascript: void(0)" onclick="addDoc()" class="red-normal">Add Document</a></div>
        </td>
    </tr>
</table>
