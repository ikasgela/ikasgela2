https://gitea.ikasgela.{{ config('app.debug') ? 'test' : 'com' }}/{{ $markdown_text->repositorio }}/src/branch/{{ isset($markdown_text->rama) ? $markdown_text->rama : 'master' }}/{{ $markdown_text->archivo }}