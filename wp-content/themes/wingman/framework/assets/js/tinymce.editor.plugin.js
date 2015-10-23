(function($){
    
    var $magnificPopup = $.magnificPopup.instance;
    
    /*=============================================
    * Add button shortcode
    ===============================================**/
    
	tinymce.create('tinymce.plugins.kt_shortcodes', {
		init : function(ed, url) {
            ed.addButton('kt_shortcodes', {
				title : 'Kitetheme Shortcodes',
				cmd : 'kt_shortcodes',
				image : url + '/shortcodes-button.png'
			});           
			ed.addCommand('kt_shortcodes', function(a, params) {
                $magnificPopup.open({
                    items: {src: '#kt_list_shortcodes', type: 'inline'}
                }, 0);
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'kt_shortcodes',
					author 	  : 'kiteThemes',
					version   : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('kt_shortcodes', tinymce.plugins.kt_shortcodes);
    
    /*=============================================
    * List of shortcode click  
    ===============================================**/
    
    
    $(document).on('click', '.kt-layout-wrapper a', function(event){
        event.preventDefault();
        
        window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, $(this).next('.kt-shorcode-template').html());
        $magnificPopup.close();
        
    });
    
    /*=============================================
    * Button shortcode click ( Save, Cancel ) 
    ===============================================**/
    
    $(document).on('click', '.popup-modal-dismiss', function (e) {
        e.preventDefault();
        $magnificPopup.close();
    });
    
})(jQuery);