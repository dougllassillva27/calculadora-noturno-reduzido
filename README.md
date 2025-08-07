# Calculadora de Hora Noturna Reduzida

Uma aplicação web completa para calcular a hora noturna reduzida (NotRed) com base nas leis da CLT. A ferramenta é flexível, permitindo o cálculo por jornada de trabalho (início e fim) e também por um total de horas mensais, com um período noturno configurável.

![Screenshot da Aplicação](https://i.imgur.com/gK2T3i2.png)

---

## ✨ Funcionalidades Principais

- **Calculadora por Jornada:** Insira a data e a hora de início e fim do turno para obter uma análise detalhada.
- **Calculadora por Total de Horas:** Ideal para fechamentos mensais, insira um total de horas noturnas (ex: `176:30`) e veja o resultado consolidado.
- **Período Noturno Configurável:** Altere dinamicamente o período noturno padrão (22:00 - 05:00) para se adequar a diferentes convenções coletivas.
- **Interface Amigável:** Utiliza a biblioteca [Flatpickr.js](https://flatpickr.js.org/) para fornecer seletores de data e hora modernos e fáceis de usar.
- **Máscara de Input Inteligente:** O campo de total de horas mensais formata automaticamente a entrada do usuário, inserindo os dois pontos (`:`) para separar horas e minutos.
- **Transparência no Cálculo:** Os resultados são acompanhados da fórmula matemática utilizada, mostrando claramente como os valores foram obtidos.
- **Arquitetura Desacoplada:** O backend em PHP é modularizado, com a lógica de negócio separada da camada de API, facilitando a manutenção e a escalabilidade.

---

## 🚀 Tecnologias Utilizadas

- **Backend:** PHP 7.4+
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Bibliotecas:** [Flatpickr.js](https://flatpickr.js.org/) para os seletores de data e hora.

---

## 📂 Estrutura do Projeto

A estrutura de pastas foi organizada para promover a separação de responsabilidades (Separation of Concerns).

```
/calculadora-noturno-reduzido/
├── api/
│   ├── calcular.php
│   └── calcular_mensal.php
├── inc/
│   └── versao.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css
│   │   └── js/
│   │       └── script.js
│   └── index.php
├── src/
│   ├── CalculadoraJornadaNoturna.php
│   └── CalculadoraMensal.php
└── README.md
```

- **`/public`**: Única pasta que deveria ser publicamente acessível em um ambiente de produção ideal. Contém todos os _assets_ (CSS, JS) e o `index.php`.
- **`/src`**: Contém as classes PHP com a lógica de negócio principal da aplicação.
- **`/api`**: Contém os scripts que servem como endpoints, recebendo requisições e conectando o frontend com a lógica do `/src`.
- **`/inc`**: Contém scripts utilitários incluídos em outras partes do projeto, como o versionador de assets.

---

## 🛠️ Instalação e Configuração

Para executar este projeto em um ambiente de desenvolvimento local (como XAMPP, WAMP ou Laragon):

1.  **Clone o Repositório**

    ```bash
    git clone [https://github.com/seu-usuario/calculadora-noturno-reduzido.git](https://github.com/seu-usuario/calculadora-noturno-reduzido.git)
    ```

2.  **Mova para o Servidor Web**
    Copie a pasta `calculadora-noturno-reduzido` para o diretório raiz do seu servidor web (geralmente `htdocs`, `www` ou `public_html`).

3.  **Ajuste o Caminho Base**
    Abra o arquivo `public/index.php` e certifique-se de que a variável `$base` corresponde ao caminho do seu projeto no servidor.

    ```php
    // Exemplo: se você acessar o projeto via http://localhost/calculadora/
    $base = "/calculadora";
    ```

4.  **Acesse a Aplicação**
    Abra seu navegador e acesse a URL correspondente, apontando para a pasta `/public`.

    - `http://localhost/caminho/do/projeto/public/`

    **Opcional (Recomendado):** Para URLs mais limpas (ex: `http://localhost/caminho/do/projeto/`), você pode configurar um Virtual Host no seu Apache para que o `DocumentRoot` aponte diretamente para a pasta `/public` do projeto.

---

## ⚖️ License

Este projeto está licenciado sob a **MIT License**.
