crud.field('user_id').onChange(function(field) {
  let status_select = crud.field('status_id').input;
  let selected_index = status_select.selectedIndex;
  if (!field.value) {
    crud.field('status_id').input[0].removeAttribute('disabled')
    status_select[0].selected = 'selected';
    status_select.forEach(element => {
      element.removeAttribute('selected')
    });
  } else {
    crud.field('status_id').input[0].disabled = true
    if (selected_index == 0) {
      crud.field('status_id').input[1].selected = 'selected';
      crud.field('status_id').input[selected_index].removeAttribute('selected');
      status_select.forEach(element => {
        element.removeAttribute('selected');
      });
      crud.field('status_id').input[1].selected = 'selected';
    }

  }
}).change();

crud.field('status_id').onChange(function(field) {
  let user_select = crud.field('user_id').input;
  let user_selected_index = user_select.selectedIndex;
  let selected_status = field.input.selectedIndex;
  if (selected_status == 0 && user_selected_index != 0) {
    // user_select[0].selected = 'selected';

    user_select.forEach(element => {
      element.removeAttribute('selected')
    });
    user_select[0].selected = 'selected';

  }
}).change();