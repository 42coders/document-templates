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
                        <label for="exampleFormControlTextarea1">Template "<b>{{template.name}}</b>"</label>
                        <textarea class="form-control" ref="templateEditors" :id="getEditorId(index)" name="templateeditor" rows="3"
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
                       :href="'/document-templates' + this.id()">Render</a>
                </form>
            </div>
            <div class="col-3">
                <h4>Placeholders</h4>
                <div v-if="isRequestPending && actionPending == ACTIONS.GET_PLACEHOLDERS"
                     class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <ul>
                    <li v-for="(placeholder, index) in placeholders">
                        <div v-if="Array.isArray(placeholder)">
                            {% for {{placeholder[0].split('.')[0]}} in {{index}} %}
                            <ul>
                                <li v-for="(childPlaceholder, index) in placeholder">
                                    <span v-pre>{{</span>{{childPlaceholder}}<span v-pre>}}</span>
                                </li>
                            </ul>
                            {% endfor %}
                        </div>
                        <span v-else><span v-pre>{{</span>{{placeholder}}<span v-pre>}}</span></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

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
            console.log('Component mounted.');
            console.log(this.initialData);
            console.log(this.baseUrl);
            this.init();
        },
        methods: {
            getEditorId(index){
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
            initEditors(){
                var _this = this;
                setTimeout(function(){
                    _this.templates.forEach((template, index) => {
                        var editorId = _this.getEditorId(index);
                        CKEDITOR.replace(editorId, {
                            customConfig: ''
                        });

                        CKEDITOR.instances[editorId].on('change', () => {
                            let ckeditorData = CKEDITOR.instances[editorId].getData()
                            if (ckeditorData !== template.content) {
                                template.content = ckeditorData;
                            }
                        })
                    })
                }, 0);
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
                this.placeholders = [];
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
