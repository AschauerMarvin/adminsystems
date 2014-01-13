CKEDITOR.plugins.add( 'asyslink',
{
	init: function( editor )
	{
		editor.addCommand( 'simpleLinkDialog', new CKEDITOR.dialogCommand( 'simpleLinkDialog' ) );
 
		editor.ui.addButton( 'Asyslink',
		{
			label: 'Insert Adminsystems internal Link',
			command: 'simpleLinkDialog',
			icon: this.path + 'images/asys.png'
		} );
 
		CKEDITOR.dialog.add( 'simpleLinkDialog', function( editor )
		{
			return {
				title : 'Adminsystems Internal Link',
				minWidth : 400,
				minHeight : 200,
				contents :
				[
					{
						// Definition of the Settings dialog window tab (page) with its id, label and contents.
						// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.contentDefinition.html
						id : 'general',
						label : 'Settings',
						elements :
						[
							// Dialog window UI element: HTML code field.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html
							{
								type : 'html',
								// HTML code to be shown inside the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.html.html#constructor
								html : 'You can add an Link to an internal Adminsystems Page. Just type in the Alias of the Page you want to link.'
							},
							// Dialog window UI element: a textarea field for the link text.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textarea.html
							{
								type : 'text',
								id : 'contents',
								// Text that labels the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.labeledElement.html#constructor
								label : 'Displayed Name',
								// Validation checking whether the field is not empty.
								validate : CKEDITOR.dialog.validate.notEmpty( 'The Displayed Name field cannot be empty.' ),
								// This field is required.
								required : true,
								// Function to be run when the commitContent method of the parent dialog window is called.
								// Get the value of this field and save it in the data object attribute.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#getValue
								commit : function( data )
								{
									data.contents = this.getValue();
								}
							},
							// Dialog window UI element: a text input field for the link URL.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textInput.html
							{
								type : 'text',
								id : 'url',
								label : 'Page Alias',
								validate : CKEDITOR.dialog.validate.notEmpty( 'You must set the Page Alias' ),
								required : true,
								commit : function( data )
								{
									data.url = this.getValue();
								}
							},
							// Dialog window UI element: a selection field with link styles.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.select.html
							{
								type : 'select',
								id : 'style',
								label : 'Style',
								// Items that will appear inside the selection field, in pairs of displayed text and value.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.select.html#constructor
								items : 
								[
									[ '<none>', '' ],
									[ 'Bold', 'b' ],
									[ 'Underline', 'u' ],
									[ 'Italics', 'i' ]
								],
								commit : function( data )
								{
									data.style = this.getValue();
								}
							},
							// Dialog window UI element: a checkbox for opening in a new page.
							// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.checkbox.html
							{
								type : 'checkbox',
								id : 'newPage',
								label : 'Opens in a new page',
								// Default value.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.checkbox.html#constructor
								'default' : false,
								commit : function( data )
								{
									data.newPage = this.getValue();
								}
							}
						]
					}
				],
				onOk : function()
				{
					// Create a link element and an object that will store the data entered in the dialog window.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.document.html#createElement
					var dialog = this,
						data = {},
						link = editor.document.createElement( 'a' );
					// Populate the data object with data entered in the dialog window.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#commitContent
					this.commitContent( data );

					// Set the URL (href attribute) of the link element.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setAttribute
					link.setAttribute( 'href', 'index.php?page=' + data.url );

					// In case the "newPage" checkbox was checked, set target=_blank for the link element.
					if ( data.newPage )
						link.setAttribute( 'target', '_blank' );

					// Set the style selected for the link, if applied.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setStyle
					switch( data.style )
					{
						case 'b' :
							link.setStyle( 'font-weight', 'bold' );
						break;
						case 'u' :
							link.setStyle( 'text-decoration', 'underline' );
						break;
						case 'i' :
							link.setStyle( 'font-style', 'italic' );
						break;
					}

					// Insert the Displayed Text entered in the dialog window into the link element.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dom.element.html#setHtml
					link.setHtml( data.contents );

					// Insert the link element into the current cursor position in the editor.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#insertElement
					editor.insertElement( link );
				}
			};
		} );
	}
} );