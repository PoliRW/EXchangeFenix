To work with the tinymce editor and file manager, i'm use the primakurzy (https://github.com/PrimaKurzy-cz/responsivefilemanager) base, which works with version 5x.

Help command  composer in terminal
 
 1 composer show --all tinymce/tinymce,
 
 2 composer require tinymce/tinymce,

3 composer require primakurzy/responsivefilemanager

Help to script config to tinymce :

Note: tinymce's language settings are in czech language

<script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
            <script type="text/javascript">
                tinymce.init({
                    selector: '#obsah',
                    language: 'cs',
                    language_url: '<?php echo dirname($_SERVER["PHP_SELF"]); ?>/vendor/tweeb/tinymce-i18n/langs/cs.js',
                    height: '50vh',
                    entity_encoding: "raw",
                    verify_html: false,
                    content_css: [
                        'css/reset.css',
                        'css/section.css',
                        'css/style.css',
                        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
                        'https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap',
                    ],
                    body_id: "content",
                    plugins: 'advlist anchor autolink charmap code colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists nonbreaking noneditable pagebreak paste preview print save searchreplace tabfocus table textcolor textpattern visualchars',
                    toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor",
                    toolbar2: "link unlink anchor | fontawesome | image media | responsivefilemanager | preview code",
                });
            </script>


   Note 2:
    
   Application administration is not yet complete. 
            
   I am looking for the best solution to send the new information from a table in the administration to the DB and initialize it in the user page
