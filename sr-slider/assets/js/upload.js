jQuery(document).ready(function($){

    slider = {

        processing : function( widget_id, widget_id_string ) {

            var frame = wp.media({
                title: 'Upload Image',
                multiple: false
            });

            frame.on('close',function( ) {
                var attachments = frame.state().get('selection').toJSON();
                slider.inserting( widget_id, widget_id_string, attachments[0] );
            });

            frame.open();
            return false;
        },

        inserting : function( widget_id, widget_id_string, attachment ) {

            $("#" + widget_id_string ).val(attachment.id);
            $("#" + widget_id_string + 'sr_slider_image').attr( "src", attachment.url );

        },

    };
});
