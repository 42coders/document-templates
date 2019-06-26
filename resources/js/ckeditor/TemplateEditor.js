export default class TemplateEditor {
    constructor(id, placeholders) {
        this.id = id;
        this.placeholders = placeholders;
    }

    init() {
        if(CKEDITOR.instances.hasOwnProperty(this.id)){
            CKEDITOR.instances[this.id].destroy()
        }

        CKEDITOR.replace(this.id, {
            customConfig: '',
            allowedContent: true,
            extraPlugins: 'richcombo,placeholder_select',
            toolbarGroups:[
                { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'forms' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'links' },
                { name: 'insert' },
                '/',
                { name: 'styles' },
                { name: 'colors' },
                { name: 'tools' },
                { name: 'others' },
                { name: 'about' },
                '/',
                { name: 'placeholder_select'}
            ],
            placeholder_select: {
                placeholders: this.placeholders,
            },
            protectedSource: [/{%([^{}])+%}/g],
        });
    }
}