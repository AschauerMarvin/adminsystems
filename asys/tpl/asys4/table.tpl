<!-- START BLOCK : table -->
{table_text}
<table id="{table_id}" class="{table_class}" border="0">
<thead>
<tr>
<!-- START BLOCK : tablehead -->
<th align="left">{table_header}</th>
<!-- END BLOCK : tablehead -->
</tr> 
</thead>
<!-- START BLOCK : tablerow -->
<tr align="center">
<!-- START BLOCK : tablecontent-->
<td{colspan}>{table_content}</td>
<!-- END BLOCK : tablecontent -->
<!-- START BLOCK : dobutton-->                                                                          
<td><a href="{dobutton_href}"><img alt="{dobutton_alt}" src="{layout_img}{button_file}{dobutton_src}" />{doobutton_desc}</a></td>
<!-- END BLOCK : dobutton -->
<!-- START BLOCK : usr_content-->
<td>{table_content}</td>
<!-- END BLOCK : usr_content -->
</tr>
<!-- END BLOCK : tablerow -->
</table>
<!-- END BLOCK : table -->