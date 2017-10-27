# laravel-parameters
Under Construction, Laravel Package for Managing App Parameters/Settings.

This package is under development.

## Goals:

- This package should reduce the Configurability headache for developers, by providing a dedicated UI to edit and manage Parameters, and using it simply in the app as `param('parameter_name')`.

## Dev Goals:

- Build reusable admin interface for editing and adding Parameters. The interface should be completely customizable.

- Parameters can be used in the app as `param('parameter_name')`.

- each param has a `type` should be casted to it, and the editor corresponds to this type. e.g: `boolean` type should be a `input[type=checkbox]`  and `param('some_boolean_parameter')` should return `true` or `false`.

- ....