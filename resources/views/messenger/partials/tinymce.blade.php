<script src="{{ url('/tinymce/tinymce.min.js?apiKey=8krh70d56tqoqk6d2q7vpauy2oiss2rvh5k3fh3hund2ck3x') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea#message',
        language: 'es_ES',
        plugins: "link image autolink emoticons lists hr codesample",
        default_link_target: "_blank",
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample | image link emoticons hr',
        link_assume_external_targets: true,
        codesample_languages: [
            {text: 'Java', value: 'java'},
            {text: 'HTML/XML', value: 'markup'},
            {text: 'CSS', value: 'css'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'PHP', value: 'php'},
            {text: 'Python', value: 'python'},
            {text: 'C#', value: 'csharp'},
        ],
    });
</script>
