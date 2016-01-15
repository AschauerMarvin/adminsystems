<!-- START BLOCK : form -->
<fieldset>
<br />
<!-- START BLOCK : form_top_title -->
<h4>{form_title}</h4>
<!-- END BLOCK : form_top_title -->
<!-- START BLOCK : form_text_top -->
{text}
<!-- END BLOCK : form_text_top -->
<form action="{form_action}" method="{form_method}">
<!-- START BLOCK : selectform -->
<!-- START BLOCK : form_text_select -->
{text}
<!-- END BLOCK : form_text_select -->

<select name="{form_name}" size="{form_size}">
<!-- START BLOCK : selectformoption -->
	<option value="{form_value}" {selected}>{form_value}</option> 
<!-- END BLOCK : selectformoption -->
</select> {form_desc}<br />
<!-- END BLOCK : selectform -->
<!-- START BLOCK : formtext -->
<!-- START BLOCK : form_title -->
<h5>{form_title}</h5>
<!-- END BLOCK : form_title -->
<!-- START BLOCK : form_text_form -->
{text}
<!-- END BLOCK : form_text_form -->
<input type="{form_type}" name="{form_name}" value="{form_value}" /> {form_desc}
<br />
<!-- END BLOCK : formtext -->
<!-- START BLOCK : nobrformtext -->
<input type="{form_type}" name="{form_name}" value="{form_value}" /> {form_desc}
<!-- END BLOCK : nobrformtext -->
<!-- START BLOCK : formcheck -->
<!-- START BLOCK : form_text_check -->
{text}
<!-- END BLOCK : form_text_check -->
<input type="{form_type}" name="{form_name}" value="{form_value}" {form_checked} /> {form_desc}
{br}
<!-- END BLOCK : formcheck -->
<!-- START BLOCK : textarea -->
<!-- START BLOCK : form_text_text -->
{text}
<!-- END BLOCK : form_text_text -->
<textarea id="{textarea_id}" class="{textarea_class}" cols="{textarea_colls}" rows="{textarea_rows}" name="{form_name}">{form_desc}</textarea>
<!-- END BLOCK : textarea -->
<input type="submit" value="{form_submit}" />
</form>
</fieldset>
<br />
<!-- END BLOCK : form -->
