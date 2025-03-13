$(document).ready(function(){
  $("body").on('click', 'button', function () {
      // Si el boton no tiene el atributo ajax no hacemos nada
      if ($(this).data('ajax') === undefined)
          return;

      // El metodo .data identifica la entrada y la castea al valor más correcto
      if ($(this).data('ajax') !== true)
          return;

      var form = $(this).closest("form");
      var button = $(this);
      var url = form.attr('action');

      // Alert container
      var alertContainer = form.find('.alert-container');
      alertContainer.html('');

      // Escondomes los errores
      form.find(".form-validation-failed").html('');

      form.ajaxSubmit({
          dataType: 'JSON',
          type: 'POST',
          url: url,
          success: function (r) {

              // Mostrar mensaje
              if (r.message !== null) {
                  if (r.message.length > 0) {
                      var tipo  = "";
                      if (r.response) {
                          tipo = "success";
                      } else {
                          tipo = "error";
                      }
                      $('#modal_editadd').modal('hide');
                      if(dt != null){
                        dt.page( 'last' ).draw( 'page' );
                        dt.ajax.reload(null, false);
                      }
                      setTimeout(function(){
                        Swal.fire({
                          position: 'top-end',
                          heightAuto: false,
                          type: tipo,
                          text: r.message,
                          showConfirmButton: false,
                          timer: 2000
                        });
                      }, 1000);
                  }
              }

              // Validaciones
              if(r.validations !== null) {
                for(var k in r.validations) {
                    var vmessage = typeof(r.validations[k]) === 'array' ? r.validations[k][0] : r.validations[k];
                   form.find("[data-key='" + k + "']").html(vmessage);
                }
              }

              // Ejecutar funciones que son especificadas por el servidor
              if (r.function !== null) {
                  setTimeout(r.function, 0);
              }

              // Ejecutar funciones que son especificadas por el cliente
              if (button.data('success') !== undefined && r.response) {
                  setTimeout('{0}()'.format(button.data('success')), 0);
              }

              // Redireccionar
              if (r.href !== null) {
                  if (r.href === 'self') window.location.reload(true);
                  else redirect(r.href);
              }

              // Si el servidor retorno algo
              if (r.result !== null && button.data('result') !== undefined && r.response) {
                  var resultFunction = button.data('result') + '({0})';
                  resultFunction = resultFunction.format(JSON.stringify(r.result));
                  setTimeout(resultFunction, 0);
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              if (jqXHR.status === 422) {
                  for (var k in jqXHR.responseJSON) {
                      var control = form.find('.validation-message[data-target="' + k + '"]');
                      control.text(jqXHR.responseJSON[k][0]);
                      control.css('display', 'block');
                  }
              } else {
                  var message = '<div class="alert alert-warning alert-dismissable response-message"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + errorThrown + ' | <b>' + textStatus + '</b></div>';

                  if(alertContainer.length > 0){
                      alertContainer.html(message);
                  }
              }
          }
      });

      return false;
  })
})

if (!Number.prototype.toMonth) {
    Number.prototype.toMonth = function () {
        var m = '';

        switch (parseInt(this)) {
            case 1:
                m = 'Enero';
                break;
            case 2:
                m = 'Febrero';
                break;
            case 3:
                m = 'Marzo';
                break;
            case 4:
                m = 'Abril';
                break;
            case 5:
                m = 'Mayo';
                break;
            case 6:
                m = 'Junio';
                break;
            case 7:
                m = 'Julio';
                break;
            case 8:
                m = 'Agosto';
                break;
            case 9:
                m = 'Setiembre';
                break;
            case 10:
                m = 'Octubre';
                break;
            case 11:
                m = 'Noviembre';
                break;
            case 12:
                m = 'Diciembre';
                break;
        }

        return m;
    };
}
