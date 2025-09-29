<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reportes Superhéroes</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
    body { font-family: Arial; margin: 16px; }
    .container { display:flex; gap:20px; }
    .panel { flex:1; }
    table { border-collapse: collapse; width:100%; }
    th,td { border:1px solid #ccc; padding:5px; }
  </style>
</head>
<body>
  <h2>Ejercicio 1: Formulario → PDF</h2>
  <form id="filterForm">
    Título: <input type="text" id="title"><br>
    Géneros:
    <label><input type="checkbox" value="Male" name="gender">Masculino</label>
    <label><input type="checkbox" value="Female" name="gender">Femenino</label>
    <label><input type="checkbox" value="N/A" name="gender">N/A</label><br>
    Límite: <input type="number" id="limit" value="50" min="10" max="200"><br>
    Publishers:<br>
    <?php foreach($publishers as $p): ?>
      <label><input type="checkbox" name="publisher" value="<?= $p['publisher'] ?>"> <?= $p['publisher'] ?></label><br>
    <?php endforeach; ?>
    <button type="submit">Aplicar</button>
    <button type="button" id="exportPdf">Exportar PDF</button>
  </form>
  <div id="reportArea"></div>

  <h2>Ejercicio 2: Gráfico dinámico</h2>
  Métrica:
  <select id="metric">
    <option value="count">Cantidad</option>
    <option value="avg_weight">Promedio Peso</option>
  </select>
  Tipo:
  <select id="chartType"><option>bar</option><option>line</option><option>pie</option></select>
  <button id="buildChart">Generar</button>
  <canvas id="dynamicChart" width="400" height="200"></canvas>

  <h2>Ejercicio 3: Promedio de peso por publisher</h2>
  <button id="buildWeightLine">Generar gráfico</button>
  <canvas id="weightLineChart" width="400" height="200"></canvas>

<script>
let dynamicChart, weightChart;

document.getElementById('filterForm').addEventListener('submit', async e=>{
  e.preventDefault();
  const title = document.getElementById('title').value;
  const limit = document.getElementById('limit').value;
  const genders = Array.from(document.querySelectorAll('input[name="gender"]:checked')).map(i=>i.value);
  const publishers = Array.from(document.querySelectorAll('input[name="publisher"]:checked')).map(i=>i.value);

  const params = new URLSearchParams();
  if(title) params.set('title', title);
  if(limit) params.set('limit', limit);
  if(genders.length) params.set('genders', genders.join(','));
  if(publishers.length) params.set('publishers', publishers.join(','));

  const res = await fetch('/api/superheroes?'+params);
  const data = await res.json();

  let html = '<table id="reportTable"><tr><th>ID</th><th>Nombre</th><th>Género</th><th>Peso</th><th>Publisher</th></tr>';
  data.forEach(r=>{
    html+=`<tr><td>${r.id}</td><td>${r.name}</td><td>${r.gender}</td><td>${r.weight_kg}</td><td>${r.publisher}</td></tr>`;
  });
  html+='</table>';
  document.getElementById('reportArea').innerHTML = html;
});

document.getElementById('exportPdf').addEventListener('click', async ()=>{
  const el = document.getElementById('reportTable');
  if(!el) return alert('No hay reporte');
  const canvas = await html2canvas(el);
  const imgData = canvas.toDataURL('image/png');
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF();
  pdf.addImage(imgData,'PNG',10,10,190,0);
  pdf.save('reporte.pdf');
});

// Ejercicio 2
document.getElementById('buildChart').addEventListener('click', async ()=>{
  const metric = document.getElementById('metric').value;
  const chartType = document.getElementById('chartType').value;
  const res = await fetch('/api/aggregate?metric='+metric);
  const rows = await res.json();
  const labels = rows.map(r=>r.publisher);
  const values = rows.map(r=>r.value);

  if(dynamicChart) dynamicChart.destroy();
  dynamicChart = new Chart(document.getElementById('dynamicChart'), {
    type: chartType,
    data: { labels, datasets:[{label:metric, data:values}] }
  });
});

// Ejercicio 3
document.getElementById('buildWeightLine').addEventListener('click', async ()=>{
  const res = await fetch('/api/aggregate?metric=avg_weight');
  const rows = await res.json();
  const labels = rows.map(r=>r.publisher);
  const values = rows.map(r=>r.value);

  if(weightChart) weightChart.destroy();
  weightChart = new Chart(document.getElementById('weightLineChart'), {
    type: 'line',
    data: { labels, datasets:[{label:'Promedio peso', data:values}] }
  });
});
</script>
</body>
</html>
