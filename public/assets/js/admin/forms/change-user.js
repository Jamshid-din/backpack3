crud.field('user_id').onChange(function(field) {
  let status_select = crud.field('status_id').input;
  let selected_index = status_select.selectedIndex;
  if (!field.value) {
    console.log('User removed')
    status_select.forEach(element => {
      element.removeAttribute('selected')
    });
    status_select[0].setAttribute('selected','selected')
  } else {

    if (selected_index == 0) {
      status_select[selected_index].removeAttribute('selected')
      status_select[1].setAttribute('selected','selected')
    }

  }
}).change();