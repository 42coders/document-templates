<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9">
                <form method="POST" action="" @submit.prevent="save">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="name"
                               v-model="documentTemplate.name"
                               placeholder="Document name">
                    </div>
                    <div class="form-group">
                        <label for="layoutSelector">Layout</label>
                        <select @change="handleLayoutChange" class="form-control" id="layoutSelector"
                                name="layout"
                                v-model="documentTemplate.layout">
                            <option v-for="(layout, index) in layouts" :value="layout">{{layout}}</option>
                        </select>
                    </div>
                    <div v-if="documentClasses" class="form-group">
                        <label for="documentClassSelector">Class</label>
                        <select class="form-control" name="document_class"
                                id="documentClassSelector"
                                v-model="documentTemplate.document_class"
                                @change="handleClassChange"
                        >
                            <option v-for="(documentClass, index) in documentClasses" :value="documentClass">
                                {{documentClass}}
                            </option>
                        </select>
                    </div>
                    <div v-if="isRequestPending && actionPending == ACTIONS.GET_TEMPLATES"
                         class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div v-for="(template, index) in templates" class="form-group">
                        <label :for="createEditorId(index)">Template "<b>{{template.name}}</b>"</label>
                        <textarea class="form-control" ref="templateEditors" :id="createEditorId(index)"
                                  name="templateeditor" rows="3"
                                  v-model="template.content"
                        >
                            {{template.content}}
                        </textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">
                        <span v-if="isRequestPending && actionPending == ACTIONS.SAVE"
                              class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Save
                    </button>
                    <a class="btn btn-secondary mb-2" target="_blank"
                       :href="this.baseUrl + this.id()">Render</a>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import TemplateEditor from "../ckeditor/TemplateEditor";

    export default {
        props: ['initialData', 'baseUrl'],
        data() {
            return {
                ACTIONS: {
                    SAVE: 'save',
                    GET_TEMPLATES: 'getTemplates',
                    GET_PLACEHOLDERS: 'getPaceholders'
                },
                id: () => {
                    return this.documentTemplate.id ? '/' + this.documentTemplate.id : ''
                },
                documentClasses: this.initialData.documentClasses,
                placeholders: this.initialData.placeholders,
                templates: this.initialData.templates,
                layouts: this.initialData.layouts,
                documentTemplate: this.initialData.documentTemplate,
                isRequestPending: false,
                actionPending: ''
            };
        },
        mounted() {
            this.init();
        },
        watch: {
            templates: function () {
                var _this = this;

                Vue.nextTick(function() {
                    _this.initEditors();
                });
            },
            placeholders: function () {
                var _this = this;

                Vue.nextTick(function() {
                    _this.initEditors();
                });
            }
        },
        methods: {
            createEditorId(index) {
                return 'templateEditor' + index;
            },
            handleLayoutChange: function (e) {
                this.getTemplates();
            },
            handleClassChange: function (e) {
                this.getPlaceholders();
            },
            init() {
                axios.interceptors.request.use((config) => {
                    this.isRequestPending = true;
                    this.actionPending = config.action;
                    return config;
                });

                axios.interceptors.response.use((response) => {
                    this.isRequestPending = false;
                    this.actionPending = '';
                    return response;
                });

                this.initEditors();
            },
            initEditors() {
                var _this = this;

                this.templates.forEach((template, index) => {
                    let editorId = _this.createEditorId(index),
                        templateEditor = new TemplateEditor(editorId, _this.placeholders);

                    templateEditor.init();

                    let editor = CKEDITOR.instances[editorId];
                    editor.on('change', () => {
                        template.content = editor.getData();
                    });

                    // The change event doesn't work in source editing mode, must use the key event
                    // @see https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_editor.html#event-change
                    editor.on('mode', function() {
                        if (this.mode === 'source') {
                            template.content = editor.getData();
                            var editable = editor.editable();
                            editable.attachListener(editable, 'key', function() {
                                template.content = editor.getData();
                            });
                        }
                    });
                })
            },
            getTemplates() {
                console.log(this.templates);
                this.templates = [];
                axios.request({
                    url: 'templates' + this.id(),
                    method: 'post',
                    baseURL: this.baseUrl,
                    action: this.ACTIONS.GET_TEMPLATES,
                    data: {
                        layout: this.documentTemplate.layout,
                        document_class: this.documentTemplate.document_class,
                    }
                })
                    .then(({data}) => {
                        console.log(data);
                        this.templates = data;
                        this.initEditors();
                    });
            },
            getPlaceholders() {
                axios.request({
                    method: 'post',
                    url: 'placeholders' + this.id(),
                    baseURL: this.baseUrl,
                    action: this.ACTIONS.GET_PLACEHOLDERS,
                    data: {
                        layout: this.documentTemplate.layout,
                        document_class: this.documentTemplate.document_class,
                    }
                })
                    .then(({data}) => {
                        console.log(data);
                        this.placeholders = data;
                    });
            },
            save() {
                var method = this.id() ? 'put' : 'post';

                axios.request(
                    {
                        url: this.id(),
                        method: method,
                        baseURL: this.baseUrl,
                        action: this.ACTIONS.SAVE,
                        data: {
                            name: this.documentTemplate.name,
                            layout: this.documentTemplate.layout,
                            document_class: this.documentTemplate.document_class,
                            templates: this.templates
                        }
                    }
                )
                    .then(({data}) => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                            return;
                        }
                        this.documentTemplate = data.documentTemplate;
                        this.templates = data.templates
                    });
            }
        }
    }
</script>
