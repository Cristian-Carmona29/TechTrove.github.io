const frm = document.querySelector("#frmRegistro");
const btnAccion = document.querySelector("#btnAccion");
let tblServicios;

var firstTabEl = document.querySelector("#myTab li:last-child button");
var firstTab = new bootstrap.Tab(firstTabEl);

document.addEventListener("DOMContentLoaded", function() {
    tblServicios = $("#tblServicios").DataTable({
        ajax: {
            url: base_url + "servicios/listar",
            dataSrc: "",
        },
        columns: [
            { data: "id" },
            { data: "nombre" },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm" onclick="editServicio(${row.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarServicio(${row.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: base_url + 'assets/js/spanish.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'desc']],
    });

    //submit servicios
    frm.addEventListener("submit", function(e) {
        e.preventDefault();
        let data = new FormData(this);
        const url = base_url + "servicios/registrar";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(data);
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res.icono == "success") {
                    frm.reset();
                    tblServicios.ajax.reload();
                    btnAccion.textContent = "Registrar";
                    document.querySelector("#id").value = "";
                }
                Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
            }
        };
    });
});

function eliminarServicio(idServicio) {
    Swal.fire({
        title: "Aviso?",
        text: "Esta seguro de eliminar el servicio!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "servicios/delete/" + idServicio;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res.icono == "success") {
                        tblServicios.ajax.reload();
                    }
                    Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
                }
            };
        }
    });
}

function editServicio(idServicio) {
    const url = base_url + "servicios/edit/" + idServicio;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            document.querySelector("#id").value = res.id;
            document.querySelector("#nombre").value = res.nombre;
            btnAccion.textContent = "Actualizar";
            firstTab.show();
        }
    };
}