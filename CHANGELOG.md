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