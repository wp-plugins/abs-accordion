(function() {
    tinymce.create('tinymce.plugins.Absaccdion', {
        init : function(ed, url) {
        
            ed.addButton('abs_accordion', {
                title : 'ABS Accordion Shortcode',
                cmd : 'abs_accordion',
                image : url + '/abs_accordion.png'
            });
 
             
            ed.addCommand('abs_accordion', function() {
                var categoryname = prompt("Put the category name"),
                    shortcode;
                        shortcode = '[abs_accordion category="'+ categoryname +'"]' ;
                        ed.execCommand('mceInsertContent', 0, shortcode);
                 
                    
            });
        },
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add( 'absaccordion', tinymce.plugins.Absaccdion );
})();