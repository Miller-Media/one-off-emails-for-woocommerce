jQuery(document).ready(function(){

    // Send email
    jQuery("#wooe_send_email").on('click', function(e){

        e.preventDefault();

        var to = jQuery('#wooe_to').val();
        var subject = jQuery('#wooe_subject').val();
        var heading = jQuery('#wooe_heading').val();
        var iFrameDOM = jQuery('iframe#wooe_message_ifr').contents();
        var message = iFrameDOM.find('#tinymce.wooe_message').html();

        var $response = jQuery("#wooe_ajax_res_send_email");
        $response.fadeIn();
        $response.text('Sending one-off email...');

        var data = {
            action: 'wooe_sendemail',
            to: to,
            subject: subject,
            heading: heading,
            message: message
        };
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            success: function( data ){
                data = JSON.parse(data);

                // If the response contains an error.
                if( data.error && data.error.length > 0 ) {
                    $response.text("There was an error: " + data.error);
                    return;
                }

                // Clear fields on front end.
                jQuery('#wooe_to').val('');
                jQuery('#wooe_subject').val('');
                jQuery('#wooe_heading').val('');
                iFrameDOM.find('#tinymce.wooe_message').html('');
                jQuery('#wooe_preview_window').html('');

                $response.text("Email sent successfully!");
                setTimeout(function(){
                    $response.fadeOut();
                }, 5000);
            },
            error: function(errorThrown){
                console.log('There was an error');
                console.log(errorThrown);
            }
        });
    });

    // Preview email
    jQuery("#wooe_preview_email").on('click', function(e){

        e.preventDefault();

        var heading = jQuery('#wooe_heading').val();
        var iFrameDOM = jQuery('iframe#wooe_message_ifr').contents();
        var message = iFrameDOM.find('#tinymce.wooe_message').html();

        jQuery('#tinymce').fadeOut();
        setTimeout(function(){
            jQuery('#tinymce').fadeIn();
        }, 2000);

        var $response = jQuery("#wooe_preview_window");
        $response.fadeIn();
        $response.text('Generating email preview...');

        var data = {
            action: 'wooe_previewemail',
            heading: heading,
            message: message
        };
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: data,
            success: function( data ){
                data = JSON.parse(data);
                console.log(data);

                // If the response contains an error.
                if( data.error && data.error.length > 0 ) {
                    $response.text("There was an error: " + data.error);
                    return;
                }

                $response.html(data.result);
            },
            error: function(errorThrown){
                console.log('There was an error');
                console.log(errorThrown);
            }
        });
    });

});