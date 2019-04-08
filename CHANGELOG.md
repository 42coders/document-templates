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

 