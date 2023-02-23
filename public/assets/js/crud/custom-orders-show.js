jQuery(document).ready(function($) {
  $('.image-to-preview').click(function() {
    var modal = $('#image-preview-modal');
    var img = $(this).attr('src');
    var modalImg = $('#img01');
    modal.css('display', 'block');
    modalImg.attr('src', img);
  });

  $('.preview-close, .modal-preview-img').click(function() {
    $('#image-preview-modal').css('display', 'none');
  });
});
