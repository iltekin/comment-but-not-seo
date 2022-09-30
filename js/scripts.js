jQuery(document).ready(function($) {
    let cbns_target = $("p.comment-form-url > input[name='url']");
    var cbns_shown = false;

    $(cbns_target).on('input',function(){
        var value = $(cbns_target).val();
        if ( value.length > 0 && cbns_shown === false) {
            $("p.comment-form-url").append(cbns_message);
            cbns_shown = true;
        } else if ( value.length === 0 ) {
            $("p.comment-form-url .cbns_notice").remove();
            cbns_shown = false;
        }

    });
});