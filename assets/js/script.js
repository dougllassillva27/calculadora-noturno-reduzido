// --- CONFIGURAÇÃO DO FLATPICKR ---
const dateConfig = {
  altInput: true,
  altFormat: 'd/m/Y', // Formato visual amigável (o que o usuário vê)
  dateFormat: 'Y-m-d', // Formato que o sistema usa internamente
  locale: 'pt',
};

const timeConfig = {
  enableTime: true,
  noCalendar: true,
  dateFormat: 'H:i',
  time_24hr: true,
  locale: 'pt',
};

flatpickr('#inicio_data', dateConfig);
flatpickr('#fim_data', dateConfig);
flatpickr('#inicio_hora', timeConfig);
flatpickr('#fim_hora', timeConfig);

// --- FUNÇÃO E LISTENER PARA MÁSCARA DE HORA MENSAL ---
const inputHorasMes = document.getElementById('total_horas_mes');

const aplicarMascaraDeHora = (event) => {
  let valor = event.target.value;

  // 1. Remove tudo que não for dígito
  valor = valor.replace(/\D/g, '');

  // 2. Se o valor tiver 3 ou mais dígitos, insere os dois pontos
  if (valor.length >= 3) {
    const parteHoras = valor.slice(0, valor.length - 2);
    const parteMinutos = valor.slice(valor.length - 2);
    valor = `${parteHoras}:${parteMinutos}`;
  }

  // 3. Atualiza o valor do campo
  event.target.value = valor;
};

inputHorasMes.addEventListener('input', aplicarMascaraDeHora);

// --- LÓGICA DE SUBMISSÃO DO FORMULÁRIO DE JORNADA ---
document.getElementById('calculadoraForm').addEventListener('submit', function (event) {
  event.preventDefault();

  const inicioDataInput = document.getElementById('inicio_data');
  const fimDataInput = document.getElementById('fim_data');

  const inicioData = inicioDataInput._flatpickr ? inicioDataInput._flatpickr.input.value : '';
  const inicioHora = document.getElementById('inicio_hora').value;
  const fimData = fimDataInput._flatpickr ? fimDataInput._flatpickr.input.value : '';
  const fimHora = document.getElementById('fim_hora').value;

  if (!inicioData || !inicioHora || !fimData || !fimHora) {
    alert('Por favor, preencha todos os campos de data e hora da jornada.');
    return;
  }

  const inicioJornada = `${inicioData} ${inicioHora}`;
  const fimJornada = `${fimData} ${fimHora}`;

  const resultadoDiv = document.getElementById('resultado');
  const periodoInicio = document.getElementById('periodo_inicio').value;
  const periodoFim = document.getElementById('periodo_fim').value;

  const formData = new FormData();
  formData.append('inicio_jornada', inicioJornada);
  formData.append('fim_jornada', fimJornada);
  formData.append('periodo_inicio', periodoInicio);
  formData.append('periodo_fim', periodoFim);

  fetch(`${basePath}/api/calcular.php`, {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.sucesso) {
        const res = data.dados;
        document.getElementById('res-total-jornada').textContent = res.total_jornada;
        document.getElementById('res-noturno-trabalhado').textContent = res.noturno_trabalhado;
        document.getElementById('res-noturno-computado').textContent = res.noturno_computado;
        document.getElementById('res-acrescimo').textContent = res.acrescimo;
        document.getElementById('res-detalhe-calculo').innerHTML = res.detalhe_calculo;
        resultadoDiv.style.display = 'block';
      } else {
        alert('Erro: ' + data.mensagem);
        resultadoDiv.style.display = 'none';
      }
    })
    .catch((error) => {
      console.error('Ocorreu um erro na requisição da jornada:', error);
      alert('Não foi possível comunicar com o servidor. Tente novamente.');
    });
});

// --- LÓGICA DE SUBMISSÃO DO FORMULÁRIO MENSAL ---
document.getElementById('formCalculoMensal').addEventListener('submit', function (event) {
  event.preventDefault();

  const totalHoras = document.getElementById('total_horas_mes').value;
  const resultadoDiv = document.getElementById('resultadoMensal');

  if (!totalHoras) {
    alert('Por favor, informe o total de horas no mês.');
    return;
  }

  const formData = new FormData();
  formData.append('total_horas', totalHoras);

  fetch(`${basePath}/api/calcular_mensal.php`, {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.sucesso) {
        document.getElementById('res-mensal-informado').textContent = data.total_informado;
        document.getElementById('res-mensal-computado').textContent = data.total_computado;
        document.getElementById('res-mensal-acrescimo').textContent = data.acrescimo;
        document.getElementById('res-mensal-detalhe-calculo').innerHTML = data.detalhe_calculo;
        resultadoDiv.style.display = 'block';
      } else {
        alert('Erro: ' + data.mensagem);
        resultadoDiv.style.display = 'none';
      }
    })
    .catch((error) => {
      console.error('Ocorreu um erro no cálculo mensal:', error);
      alert('Não foi possível comunicar com o servidor. Tente novamente.');
    });
});
