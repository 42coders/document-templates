# 0.2.0 (2019-04-08)

## Enhancements
- Added twig sandbox to the user editable templates, and created own security policy

## Minor fixes
- Renamed the groups in the service provider

## Migration guide
This release contains incompatible changes in the config file, you'd need to re-publish the configuration.

**IMPORTANT:** Please note that the command below will overwrite the existing config file with the default values. If you made changes to the configuration, create a backup of the file before running the command:

```sh
php artisan vendor:publish --provider="BWF\DocumentTemplates\DocumentTemplatesServiceProvider" --tag=config --force
``` 

# 0.3.0 (2019-04-24)
 ## Enhancements
 - Added CKEditor
 - Created placeholder plugin for CKEditor
 
 ## Migration guide
 This release contains changes in the Vue component, you'd need to re-publish the components and public.
 
 **IMPORTANT:** Please note that the command below will overwrite the existing Vue component. If you made changes to the component, create a backup of the file before running the command:
 
 ```sh
 php artisan vendor:publish --provider="BWF\DocumentTemplates\DocumentTemplatesServiceProvider" --tag=components --force
 ```
 
 Publish the Ckeditor to the public:
 ```sh
  php artisan vendor:publish --provider="BWF\DocumentTemplates\DocumentTemplatesServiceProvider" --tag=public --force
```

# 0.3.1 (2019-06-12)
 ## Enhancements
 - Added support for rendering single editable template
 
 # 0.3.2 (2019-06-12)
  ## Enhancements
  - Created new MailTemplate trait, to support subject line rendering
  
# 0.3.3 (2019-06-17)
 ## Enhancements
   - Add more toolbars to the ckeditor, including the source editing
   - Use RMT (https://github.com/liip/RMT) for release management 
 ## Fixes
   - Change the editable templates content field to text
   - Fix the ckeditor save issue when editing in source mode
   - Fix the multiplication of the span element after save

# 0.3.5 (2019-06-20)
 ## Enhancements
   - Added document-templates.js it does all the necessary initialisations (ckeditor/component/etc)
   - ServiceProvider publishes ckeditor and document-templates.js
