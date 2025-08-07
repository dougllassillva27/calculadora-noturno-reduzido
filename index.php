<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/inc/versao.php";
$base = "/Secullum/calculadora-noturno-reduzido";

// Lógica para gerar as URLs dinamicamente
$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$url_base_site = $protocolo . '://' . $host;

// Constrói a URL absoluta para a imagem de compartilhamento
$url_imagem_social = $url_base_site . $base . '/assets/img/logo-social-share.webp';

// Gera a URL canônica da página atual
$url_canonica = $url_base_site . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    
    <title>Calculadora de Hora Noturna Reduzida</title>

    <meta name="description" content="Calcule facilmente a hora noturna reduzida (NotRed) com base na CLT. Ferramenta para cálculo por jornada de trabalho ou total de horas mensais.">
    <meta name="keywords" content="calculadora, adicional noturno, hora noturna, hora reduzida, clt, cálculo trabalhista, rh">
    <meta name="author" content="Douglas Silva">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="<?= htmlspecialchars($url_canonica) ?>">

    <link rel="icon" type="image/webp" sizes="32x32" href="<?= versao($base . '/assets/img/logo-social-share.webp') ?>">

    <meta property="og:title" content="Calculadora de Hora Noturna Reduzida">
    <meta property="og:description" content="Calcule facilmente a hora noturna reduzida (NotRed) com base na CLT.">
    <meta property="og:url" content="<?= htmlspecialchars($url_canonica) ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= htmlspecialchars($url_imagem_social) ?>">
    <meta property="og:image:width" content="1200"> <meta property="og:image:height" content="630"> <meta property="og:site_name" content="Calculadora de Hora Noturna">
    <meta property="og:locale" content="pt_BR">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Calculadora de Hora Noturna Reduzida">
    <meta name="twitter:description" content="Calcule facilmente a hora noturna (NotRed) com base na CLT.">
    <meta name="twitter:image" content="<?= htmlspecialchars($url_imagem_social) ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?= versao($base . '/assets/css/style.css') ?>">
</head>
<body>
    <main class="container">
        <h1>Calculadora de Jornada Noturna</h1>
        <p>Insira o início e o fim da jornada para calcular as horas noturnas.</p>
        
        <form id="calculadoraForm">
            <fieldset class="periodo-noturno-fieldset">
                <legend>Configuração do Período Noturno</legend>
                <div class="form-group">
                    <label for="periodo_inicio">Início Período:</label>
                    <input type="time" id="periodo_inicio" name="periodo_inicio" value="22:00" required>
                </div>
                <div class="form-group">
                    <label for="periodo_fim">Fim Período:</label>
                    <input type="time" id="periodo_fim" name="periodo_fim" value="05:00" required>
                </div>
            </fieldset>

            <fieldset class="jornada-fieldset">
                <div class="jornada-input-group">
                    <label for="inicio_data">Início da Jornada:</label>
                    <div class="input-pair">
                        <input type="text" id="inicio_data" placeholder="Selecione a data" required>
                        <input type="text" id="inicio_hora" placeholder="Selecione a hora" required>
                    </div>
                </div>
                <div class="jornada-input-group">
                    <label for="fim_data">Fim da Jornada:</label>
                    <div class="input-pair">
                        <input type="text" id="fim_data" placeholder="Selecione a data" required>
                        <input type="text" id="fim_hora" placeholder="Selecione a hora" required>
                    </div>
                </div>
                <button type="submit">Calcular</button>
            </fieldset>
        </form>

        <div id="resultado" class="resultado-container" style="display: none;">
            <div class="resultado-header">
                <h2>Resultado do Cálculo da Jornada</h2>
            </div>
            <div class="resultado-body">
                <div class="info-item">
                    <p>Duração Total da Jornada</p>
                    <span id="res-total-jornada"></span>
                </div>
                <div class="info-item">
                    <p>Horas Noturnas Trabalhadas</p>
                    <span id="res-noturno-trabalhado"></span>
                </div>
                <div class="info-item">
                    <p>Total de Horas Computadas (Noturnas + NotRed)</p>
                    <span id="res-noturno-computado"></span>
                </div>
                <div class="info-item">
                    <p>Acréscimo de Tempo (Hora Reduzida)</p>
                    <span id="res-acrescimo"></span>
                </div>
                <div class="calculo-detalhe">
                    <p id="res-detalhe-calculo"></p>
                </div>
            </div>
        </div>

        <hr class="form-separator">

        <form id="formCalculoMensal">
            <fieldset>
                <legend>Cálculo reduzido do mês</legend>
                <div class="jornada-input-group">
                    <label for="total_horas_mes">Total de horas noturnas no mês (HHH:MM):</label>
                    <input type="text" id="total_horas_mes" name="total_horas" placeholder="Ex: 176:30" required>
                </div>
                <button type="submit">Calcular Total Mensal</button>
            </fieldset>
        </form>

        <div id="resultadoMensal" class="resultado-container" style="display: none;">
            <div class="resultado-header">
                <h2>Resultado do Cálculo Mensal</h2>
            </div>
            <div class="resultado-body">
                <div class="info-item">
                    <p>Total de Horas Informadas</p>
                    <span id="res-mensal-informado"></span>
                </div>
                <div class="info-item">
                    <p>Total de Horas Computadas (Noturnas + NotRed)</p>
                    <span id="res-mensal-computado"></span>
                </div>
                <div class="info-item">
                    <p>Acréscimo de Tempo (Hora Reduzida)</p>
                    <span id="res-mensal-acrescimo"></span>
                </div>
                <div class="calculo-detalhe">
                    <p id="res-mensal-detalhe-calculo"></p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    <script>
        const basePath = '<?= $base ?>';
    </script>
    <script src="<?= versao($base."/assets/js/script.js") ?>"></script>
    
    <footer class="footer">
      <p>Desenvolvido por <a href="https://www.linkedin.com/in/dougllassillva27/" target="_blank" rel="noopener">Douglas Silva</a></p>
    </footer>
</body>
</html>