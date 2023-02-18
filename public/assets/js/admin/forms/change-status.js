crud.field('status_id').onChange(function(field) {
  let status_select = crud.field('user_id').input;
  let selected_index = field.input.selectedIndex;

  console.log(123123)
  // let user_select = crud.field('user_id').input;
  // let selected_user_index = user_select.selectedIndex;


  //   if (selected_index == 0) {
      

  //     user_select[selected_user_index].removeAttribute('selected')

  //     // user_select[0].setAttribute('selected', 'selected');
  //     console.log(user_select[0])
  //   }
  // if (!field.value) {
  //   status_select[selected_index].removeAttribute('selected')
  //   status_select.forEach(element => {
  //     if (element.dataset.short_name == 'new') {
  //       element.setAttribute('selected', 'selected');
  //     }
  //   });
  // } else {
  //   if (selected_index == 0) {
  //     status_select[selected_index].removeAttribute('selected')
  //     status_select.forEach(element => {
  //       if (element.dataset.short_name == 'in_process') {
  //         element.setAttribute('selected', 'selected');
  //       }
  //     });
  //   }

  // }
}).change();