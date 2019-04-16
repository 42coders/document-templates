/**
 * A plugin to enable placeholder tokens to be inserted into the CKEditor message. Use on its own or with teh placeholder plugin. 
 * The default format is compatible with the placeholders syntex
 *
 * @version 0.1 
 * @Author Troy Lutton
 * @license MIT 
 * 
 * This is a pure modification for the placeholders plugin. All credit goes to Stuart Sillitoe for creating the original (stuartsillitoe.co.uk)
 *
 */

import PlaceholderGroup from "./PlaceholderGroup";

CKEDITOR.plugins.add('placeholder_select',
{
	requires : ['richcombo'],
	onLoad: function() {
		// Register styles for placeholder widget frame.
		CKEDITOR.addCss( '.cke_placeholder{background-color:#ff0}' );
	},
	init : function( editor )
	{
		//  array of placeholders to choose from that'll be inserted into the editor
		var placeholders = [];
		
		// init the default config - empty placeholders
		var defaultConfig = {
			format: '[[%placeholder%]]',
			placeholders : []
		};

		// merge defaults with the passed in items		
		var config = CKEDITOR.tools.extend(defaultConfig, editor.config.placeholder_select || {}, true);

		// run through an create the set of items to use
		for (var i in config.placeholders) {
			var placeholderData = config.placeholders.hasOwnProperty(i) ? config.placeholders[i] : '';
			// get our potentially custom placeholder format
			// if(typeof placeholderData === 'string') {
				var placeholderGroup = new PlaceholderGroup(placeholderData);
				//var placeholder = config.format.replace('%placeholder%', placeholder);
				placeholders.push(placeholderGroup);
			// }
		}

		// add the menu to the editor
		editor.ui.addRichCombo('placeholder_select',
		{
			label: 		'Insert placeholder',
			title: 		'Insert placeholder',
			voiceLabel: 'Insert placeholder',
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				this.startGroup( "Insert placeholder" );
				for (var i in placeholders)
				{
					placeholders[i].getPlaceholders().forEach((placeholder, index) => {
						this.add(placeholder.getPlaceholder(), placeholder.getLabel(), placeholder.getLabel());
					})

				}
			},

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});

		var lang = editor.lang.placeholder;

		// Put ur init code here.
		editor.widgets.add( 'placeholder', {
			// Widget code.
		} );
	},

	afterInit: function( editor ) {
		var placeholderReplaceRegex = /\{\{([^\{\}])+\}\}/g;

		editor.dataProcessor.dataFilter.addRules( {
			text: function( text, node ) {
				var dtd = node.parent && CKEDITOR.dtd[ node.parent.name ];

				// Skip the case when placeholder is in elements like <title> or <textarea>
				// but upcast placeholder in custom elements (no DTD).
				if ( dtd && !dtd.span )
					return;

				return text.replace( placeholderReplaceRegex, function( match ) {
					// Creating widget code.
					var widgetWrapper = null,
						innerElement = new CKEDITOR.htmlParser.element( 'span', {
							'class': 'cke_placeholder'
						} );

					// Adds placeholder identifier as innertext.
					innerElement.add( new CKEDITOR.htmlParser.text( match ) );
					widgetWrapper = editor.widgets.wrapElement( innerElement, 'placeholder' );

					// Return outerhtml of widget wrapper so it will be placed
					// as replacement.
					return widgetWrapper.getOuterHtml();
				} );
			}
		} );
	}
});