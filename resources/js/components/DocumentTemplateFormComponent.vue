<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="name"
                               placeholder="Document name" value="Document name">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Layout</label>
                        <select @change="handleLayoutChange" class="form-control" id="exampleFormControlSelect1" name="layout"
                                v-model="layout">
                            <option v-for="(layout, index) in layouts" :value="layout">{{layout}}</option>
                        </select>
                    </div>
                    <div v-for="(template, index) in templates" class="form-group">
                        <label for="exampleFormControlTextarea1">Template "<b>{{template.name}}</b>"</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="" rows="3">
                            {{template.content}}
                        </textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mb-2">Save</button>
                    <a class="btn btn-secondary mb-2" target="_blank" href="">Render</a>
                </form>
            </div>
            <div class="col-3">
                <h4>Placeholders</h4>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['initialData'],
        data() {
            return {
                placeholders: this.initialData.placeholders,
                templates: this.initialData.templates,
                layouts: this.initialData.layouts,
                layout: this.initialData.documentTemplate.layout || null,
                documentClass: this.initialData.documentTemplate.document_class || null,
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
            getTemplates() {
                axios.post('/document-templates/templates', {
                    layout: this.layout,
                    document_class: this.documentClass,
                })
                    .then(({data}) => {
                        this.templates = data;
                    });
            },
            getPlaceholders() {
                axios.post('/document-templates/placeholders', {
                    layout: this.layout,
                    document_class: this.documentClass,
                })
                .then(({data}) => {
                    this.placeholders = data;
                });
            }
        }
    }
</script>
