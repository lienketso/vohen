/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************************************************!*\
  !*** ./platform/plugins/marketplace/resources/assets/js/marketplace.js ***!
  \*************************************************************************/
$(document).ready(function () {
  $('#shop-url').on('keyup', function () {
    var displayURL = $(this).closest('.form-group').find('span small');
    displayURL.html(displayURL.data('base-url') + '/<strong>' + $(this).val().toLowerCase() + '</strong>');
  }).on('change', function () {
    var _this = this;

    $('.shop-url-wrapper').addClass('content-loading');
    $(this).closest('form').find('button[type=submit]').addClass('btn-disabled').prop('disabled', true);
    $.ajax({
      url: $(this).data('url'),
      type: 'POST',
      data: {
        url: $(this).val(),
        reference_id: $('input[name=reference_id]').val()
      },
      success: function success(res) {
        $('.shop-url-wrapper').removeClass('content-loading');

        if (res.error) {
          $('.shop-url-status').removeClass('text-success').addClass('text-danger').text(res.message);
        } else {
          $('.shop-url-status').removeClass('text-danger').addClass('text-success').text(res.message);
          $(_this).closest('form').find('button[type=submit]').prop('disabled', false).removeClass('btn-disabled');
        }
      },
      error: function error() {
        $('.shop-url-wrapper').removeClass('content-loading');
      }
    });
  });
  $('.custom-select-image').on('click', function (event) {
    event.preventDefault();
    $(this).closest('.image-box').find('.image_input').trigger('click');
  });

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $(input).closest('.image-box').find('.preview_image').prop('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  $('.image_input').on('change', function () {
    readURL(this);
  });
});
/******/ })()
;