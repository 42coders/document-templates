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
        requires: ['richcombo'],
        toolbar: 'placeholder_select',
        onLoad: function () {
            // Register styles for placeholder widget frame.
            CKEDITOR.addCss('.cke_placeholder{background-color:#ff0}');
        },
        init: function (editor) {
            //  array of placeholders to choose from that'll be inserted into the editor
            var placeholderGroups = [];

            // init the default config - empty placeholders
            var defaultConfig = {
                placeholderGroups: []
            };

            // merge defaults with the passed in items
            var config = CKEDITOR.tools.extend(defaultConfig, editor.config.placeholder_select || {}, true);

            // run through an create the set of items to use
            config.placeholders.forEach((placeholderData) => {
                var placeholderGroup = new PlaceholderGroup(placeholderData);
                placeholderGroups.push(placeholderGroup);
            });

            placeholderGroups.forEach((placeholderGroup, index) => {
                // add the menu to the editor
                editor.ui.addRichCombo('placeholder_select' + index,
                    {
                        label: placeholderGroup.getName(),
                        title: 'Insert placeholder: ' + placeholderGroup.getName(),
                        voiceLabel: 'Insert placeholder',
                        className: 'cke_format',
                        multiSelect: false,
                        toolbar: 'placeholder_select',
                        panel:
                            {
                                css: [editor.config.contentsCss, CKEDITOR.skin.getPath('editor')],
                                voiceLabel: editor.lang.panelVoiceLabel
                            },

                        init: function () {
                            this.startGroup("Insert placeholder");
                                placeholderGroup.getPlaceholders().forEach((placeholder) => {
                                    this.add(placeholder.getPlaceholder(), placeholder.getLabel(), placeholder.getLabel());
                                })
                        },

                        onClick: function (value) {
                            editor.focus();
                            editor.fire('saveSnapshot');
                            editor.insertHtml(value);
                            editor.fire('saveSnapshot');
                        }
                    });
            });

            var lang = editor.lang.placeholder;

            // Put ur init code here.
            editor.widgets.add('placeholder', {
                // Widget code.
            });
        },

        afterInit: function (editor) {
            var placeholderReplaceRegex = /{{([^{}])+}}|{%([^{}])+%}/g;

            editor.dataProcessor.dataFilter.addRules({
                text: function (text, node) {
                    var dtd = node.parent && CKEDITOR.dtd[node.parent.name];

                    // Skip the case when placeholder is in elements like <title> or <textarea>
                    // but upcast placeholder in custom elements (no DTD).
                    if (dtd && !dtd.span)
                        return;

                    return text.replace(placeholderReplaceRegex, function (match) {
                        // Creating widget code.
                        var widgetWrapper = null,
                            innerElement = new CKEDITOR.htmlParser.element('span', {
                                'class': 'cke_placeholder'
                            });

                        // Adds placeholder identifier as innertext.
                        innerElement.add(new CKEDITOR.htmlParser.text(match));
                        widgetWrapper = editor.widgets.wrapElement(innerElement, 'placeholder');

                        // Return outerhtml of widget wrapper so it will be placed
                        // as replacement.
                        return widgetWrapper.getOuterHtml();
                    });
                }
            });
        }
    });