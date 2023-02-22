

jQuery(document).ready(function($) {

  let csrf_token = $('meta[name="csrf-token"]').attr('content');

  $("body").on("change", ".custom-status-column", function(){
    let row_id = $(this).data('id');
    let selected_option_value = $(this).val();

    let selected_color =  $(this).find(":selected").data('color');

    $('.status-color'+row_id).each(function (index,element) {
      element.style.backgroundColor = selected_color;
    })
    $('.custom-status-column'+row_id).each(function (index,element) {
      element.value = selected_option_value;
    })
    console.log('changing from list table...')

    let new_data =  {
      'id': row_id,
      'user_id': $('.custom-users-column'+row_id)[0].value,
      'status_id': parseInt($(this).val()),
    };
    sendUpdateRequest(row_id, new_data);
  });


  $('body').on('click', '.dtr-control', function () {
    let row_id = $(this).text().replace(/^\s+|\s+$/g, '');

    // Updating changes status in modal
    $('.status-color'+row_id)[1].style.backgroundColor = $('.status-color'+row_id)[0].style.backgroundColor;
    $('.custom-status-column'+row_id)[1].value = $('.custom-status-column'+row_id)[0].value;

    $('.custom-users-column'+row_id)[1].value = $('.custom-users-column'+row_id)[0].value;

    console.log('Changing from modal...')
  })

  $("body").on("change", ".custom-users-column", function(){
    console.log('Store changed user_id to db')
    let row_id = $(this).data('id');
    let selected_option_value = $(this).val();

    let status_id = $('.custom-status-column'+row_id)[0].value;
    $('.custom-users-column'+row_id).each(function (index,element) {
      element.value = selected_option_value;
      if ( $('.custom-users-column'+row_id +' option:first').is(':selected') ) {
        $('.status-color'+row_id).each(function (index,element) {
          let status_select = $('.custom-status-column'+row_id)[0]
          let default_status_color = status_select.options[0].getAttribute('data-color');

          element.style.backgroundColor = default_status_color
        })

        $('.custom-status-column'+row_id).each(function (index,element) {
          element.selectedIndex = 0;
          status_id = element.value

          element.querySelector('option').removeAttribute('disabled');
        })
      }
      else {
        if ( $('.custom-status-column'+row_id +' option:first').is(':selected') ) {
          $('.status-color'+row_id).each(function (index,element) {
            let status_select = $('.custom-status-column'+row_id)[0]
            let default_status_color = status_select.options[1].getAttribute('data-color');
  
            element.style.backgroundColor = default_status_color
          })
          $('.custom-status-column'+row_id).each(function (index,element) {
            element.selectedIndex = 1;
            status_id = element.value
            element.options[0].disabled = true
          })
        }
      }
    })

    let new_data =  {
      'id': row_id,
      'user_id': selected_option_value,
      'status_id': parseInt(status_id),
    };

    sendUpdateRequest(row_id, new_data);
    
  })

  function sendUpdateRequest(row_id, new_data) {
    $.ajax({
      type: 'PUT',
      url: '/editable-column/' + row_id,
      data: {new_data},
      success: function(data) {
        // console.log(data);
      }
    });
  }
});

