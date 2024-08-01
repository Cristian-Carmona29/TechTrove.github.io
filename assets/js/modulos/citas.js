const frm = document.querySelector("#frmRegistro");
const btnAccion = document.querySelector("#btnAccion");
let tblCitas;

var firstTabEl = document.querySelector("#myTab li:last-child button");
var firstTab = new bootstrap.Tab(firstTabEl);

document.addEventListener("DOMContentLoaded", function () {
  tblCitas = $("#tblCitas").DataTable({
    ajax: {
      url: base_url + "citas/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "servicio" },
      { data: "nombre_completo" },
      { data: "numero_celular" },
      { data: "fecha" },
      { data: "hora" },
      { data: "visita" },
      { data: "direccion_servicio" },
      { data: "estado" },
      { data: "accion" },
    ],
    language,
    dom,
    buttons,
  });

  // Maneja el envío del formulario de cancelación de cita
  const frmCancelarCita = document.querySelector("#frmCancelarCita");
  frmCancelarCita.addEventListener("submit", function (e) {
    e.preventDefault();
    let data = new FormData(this);
    const url = base_url + "citas/cancelar";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(data);
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        if (res.icono == "success") {
          tblCitas.ajax.reload();
          $("#cancelarCitaModal").modal("hide");
          frmCancelarCita.reset();
        }
        Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
      }
    };
  });
});

function cancelarCita(idCita) {
  document.querySelector("#citaID").value = idCita;
  $("#cancelarCitaModal").modal("show");
}

// Maneja el envío del formulario de registro de citas
frm.addEventListener("submit", function (e) {
  e.preventDefault();
  let data = new FormData(this);
  const url = base_url + "citas/registrar";
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(data);
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      if (res.icono == "success") {
        frm.reset();
        tblCitas.ajax.reload();
      }
      Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
    }
  };
});

function eliminarCita(idCita) {
  Swal.fire({
    title: "Aviso?",
    text: "¡Estás seguro de eliminar el registro!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, Eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "citas/delete/" + idCita;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          if (res.icono == "success") {
            tblCitas.ajax.reload();
          }
          Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
        }
      };
    }
  });
}

function editCita(idCita) {
  const url = base_url + "citas/edit/" + idCita;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.querySelector("#id").value = res.id;
      document.querySelector("#servicio_id").value = res.servicio_id;
      document.querySelector("#nombre_completo").value = res.nombre_completo;
      document.querySelector("#numero_celular").value = res.numero_celular;
      document.querySelector("#fecha").value = res.fecha;
      document.querySelector("#hora").value = res.hora;
      document.querySelector("#visita").value = res.visita;
      document.querySelector("#direccion_servicio").value = res.direccion_servicio;
      document.querySelector("#estado").value = res.estado;
      btnAccion.textContent = "Actualizar";
      firstTab.show();
    }
  };
}