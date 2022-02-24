### Hexlet tests and linter status:
[![Actions Status](https://github.com/pavelkond/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/pavelkond/php-project-lvl2/actions)
[![Maintainability](https://api.codeclimate.com/v1/badges/19ab5aa474c054426eb8/maintainability)](https://codeclimate.com/github/pavelkond/php-project-lvl2/maintainability)
[![phpcs](https://github.com/pavelkond/php-project-lvl2/actions/workflows/workflow.yml/badge.svg?branch=main)](https://github.com/pavelkond/php-project-lvl2/actions/workflows/workflow.yml)

***

**Gendiff** is a tool to help you find differences between two json or yaml files.

Use the
``php bin/gendiff [--format <fmt>] <firstFile> <secondFile>`` to call.

Use the ``--help`` or ``-h`` command for help and ``-v`` or ``--version`` command to display the current package version

Gendiff supports three type of formats:  
1. [Stylish](https://asciinema.org/a/9UPyum2CulhLwM9cGq8DC8JH4) (use ``--format stylish``) - show difference information like git
2. [Plain](https://asciinema.org/a/TFpKTM2ogTqPKLy19d1COlZO8) (use ``--format plain``) - shows the difference in string form for each changed property
3. [Json]() (use ``--format json``) - shows difference in json format