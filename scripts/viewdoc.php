<?php
$docs = getDBData($dbh, "SELECT `documentfiles`.*, $vendor_sql 
        FROM {$_SESSION['DBCoy']}.`documentfiles` 
        INNER JOIN `{$_SESSION['DBCoy']}`.`vendors`  ON `documentfiles`.EmployeeID=`vendors`.VendorID 
        WHERE `shelf`='$doc_shelf' AND `OwnerID`=$doc_id");
?>
<table border="0" cellspacing="4" cellpadding="4">
    <tr valign="baseline">
        <td>
            <?php $i= 1; foreach ($docs as $doc) { ?>
                <table border="0" cellpadding="2" cellspacing="2" style="float:left; padding:4px;"">
                    <tr>
                        <td class="red-normal"><strong><?php echo $i ?></strong></td>
                        <td><img src="/images/docs.png" border="0" /></td>
                        <td><table border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td><a href="/documents/archive/getfile.php?id=<?php echo $doc['DocID'] ?>" target="new"><?php echo $doc['FileName'] ?></a></td>
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
                        <td width="10">&nbsp;</td>
                        <td colspan="2"><?php echo $doc['Description'] ?></td>
                    </tr>
                </table>
            <?php $i++; } ?>
        </td>
    </tr>
</table>