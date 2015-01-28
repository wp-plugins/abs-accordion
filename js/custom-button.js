(function() {
    tinymce.create('tinymce.plugins.Wptuts', {
        init : function(ed, url) {
            ed.addButton('dropcap', {
                title : 'DropCap',
                cmd : 'dropcap',
                image : url + '/dropcap.jpg'
            });
 
            ed.addButton('youtube', {
                title : 'Add recent posts shortcode',
                cmd : 'youtube',
                image : url + '/youtube.png'
            });
 
            ed.addCommand('dropcap', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '<span class="dropcap">' + selected_text + '</span>';
                ed.execCommand('mceInsertContent', 0, return_text);
            });
 
            ed.addCommand('youtube', function() {
                var vedioid = prompt("How many posts you want to show ? "),
                    shortcode;
                        shortcode = '[youtube]' + vedioid + '[/youtube]';
                        ed.execCommand('mceInsertContent', 0, shortcode);
                 
                    
            });
        },
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add( 'wptuts', tinymce.plugins.Wptuts );
})();