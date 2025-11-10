const meses = [
  "Enero",
  "Febrero",
  "Marzo",
  "Abril",
  "Mayo",
  "Junio",
  "Julio",
  "Agosto",
  "Septiembre",
  "Octubre",
  "Noviembre",
  "Diciembre",
];

const nombreMeses = lluviasResult.mes.map((lluvia) => {
  return meses[lluvia - 1];
});

// Esperar a que cargue el DOM
document.addEventListener("DOMContentLoaded", function () {
  // Utilización de Chart.JS
  // Busca el 'id' que pusimos en el HTML
  const ctx = document.getElementById("miGraficoDeLluvias");

  if (ctx) {
    new Chart(ctx, {
      type: "bar", // Tipo de gráfico
      data: {
        labels: nombreMeses, // ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio"],
        datasets: [
          {
            label: "Lluvia (mm)",
            data: lluviasResult.cantidad, // Datos de ejemplo
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }
});

Swal.fire({
  title: "Do you want to save the changes?",
  showDenyButton: true,
  showCancelButton: true,
  confirmButtonText: "Save",
  denyButtonText: `Don't save`
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
    Swal.fire("Saved!", "", "success");
  } else if (result.isDenied) {
    Swal.fire("Changes are not saved", "", "info");
  }
});