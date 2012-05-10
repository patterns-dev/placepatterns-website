function rpx_effects() {
  if (typeof jQuery != 'undefined') {
    $(document).ready(function() {
      /*
       * Show counts on hover.
       */
      $('.rpx_ct_total').hover(
        function() {
          $(this).parent().parent().find('.rpx_counter').fadeIn('slow');
        },
        function() {
          $(this).parent().parent().find('.rpx_counter').fadeOut('slow');
        }
      );
    });//document ready
  }//jquery available
}
function rpx_showhide(element) {
  if (typeof jQuery != 'undefined') {
    /*
     * Briefly shows count element.
     */
    $(element).fadeIn('fast').delay(5000).fadeOut('slow');
  }
}
