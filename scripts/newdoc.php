<table border="0" cellspacing="4" cellpadding="4">
  <tr valign="baseline">
    <td id="docswin"><input name="docnt" type="hidden" id="docnt" value="0" />
      <script type="text/javascript">
          var docs=<?php echo isset($docs) ? count($docs) + 1 : 1 ?>;
          
          function addDoc() {
              var content = '<table border="0" cellpadding="2" cellspacing="2" id="docs'+docs+'">\n\
<tr><td class="red-normal"><strong>'+docs+'.</strong></td>\n\
<td><input type="file" name="doc'+docs+'" id="doc'+docs+'" value="" \n\
onchange="$(\'#docname'+docs+'\').html(this.value)" /></td>\n\
<td class="black-normal" id="docname'+docs+'"></td></tr><tr><td>&nbsp;</td>\n\
<td><textarea name="doc_info'+docs+'" rows="2" style="width:200px"></textarea></td>\n\
<td class="black-normal"></td></tr></table>'
              $('#modoc').before(content); 
              $('#docnt').val(docs); 
              docs++;
          }
      </script>
    <div id="modoc"><a href="Javascript: void(0)" onclick="addDoc()" class="red-normal">Add Document</a></div></td>
  </tr>
</table>
