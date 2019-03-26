<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9">
                <form method="POST" action="" @submit.prevent="save">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="name"
                               v-model="documentTemplate.name"
                               placeholder="Document name" value="Document name">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Layout</label>
                        <select @change="handleLayoutChange" class="form-control" id="exampleFormControlSelect1"
                                name="layout"
                                v-model="documentTemplate.layout">
                            <option v-for="(layout, index) in layouts" :value="layout">{{layout}}</option>
                        </select>
                    </div>
                    <div v-if="documentClasses" class="form-group">
                        <label for="exampleFormControlSelect1">Class</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="document_class"
                                v-model="documentTemplate.document_class"
                                @change="handleClassChange"
                                >
                            <option v-for="(documentClass, index) in documentClasses" :value="documentClass">{{documentClass}}</option>
                        </select>
                    </div>
                    <div v-for="(template, index) in templates" class="form-group">
                        <label for="exampleFormControlTextarea1">Template "<b>{{template.name}}</b>"</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="" rows="3"
                                  v-model="template.content"
                        >
                            {{template.content}}
                        </textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">Save</button>
                    <a class="btn btn-secondary mb-2" target="_blank" :href="'/document-templates' + this.id()">Render</a>
                </form>
            </div>
            <div class="col-3">
                <h4>Placeholders</h4>
                <ul>
                    <li v-for="(placeholder, index) in placeholders">
                        <div v-if="Array.isArray(placeholder)">
                            {% for {{placeholder[0]}} in {{placeholder[0].split('.')[0]}} %}
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
        props: ['initialData'],
        data() {
            return {
                id: () => {return this.documentTemplate.id ? '/' + this.documentTemplate.id : ''},
                documentClasses: this.initialData.documentClasses,
                placeholders: this.initialData.placeholders,
                templates: this.initialData.templates,
                layouts: this.initialData.layouts,
                documentTemplate: this.initialData.documentTemplate
            };
        },
        mounted() {
            console.log('Component mounted.');
            console.log(this.initialData);
        },
        methods: {
            handleLayoutChange: function (e) {
                this.getTemplates();
            },
            handleClassChange: function (e) {
                this.getPlaceholders();
            },
            getTemplates() {
                console.log(this.templates);
                axios.post('/document-templates/templates' + this.id(), {
                    layout: this.documentTemplate.layout,
                    document_class: this.documentTemplate.document_class,
                })
                    .then(({data}) => {
                        console.log(data);
                        this.templates = data;
                    });
            },
            getPlaceholders() {
                axios.post('/document-templates/placeholders' + this.id(), {
                    layout: this.documentTemplate.layout,
                    document_class: this.documentTemplate.document_class,
                })
                    .then(({data}) => {
                        console.log(data);
                        this.placeholders = data;
                    });
            },
            save() {
                var method = this.id() ? axios.put : axios.post;

                method('/document-templates' + this.id(), {
                    name: this.documentTemplate.name,
                    layout: this.documentTemplate.layout,
                    document_class: this.documentTemplate.document_class,
                    templates: this.templates
                })
                    .then(({data}) => {
                        this.documentTemplate = data.documentTemplate,
                        this.templates = data.templates
                    });
            }
        }
    }
</script>
