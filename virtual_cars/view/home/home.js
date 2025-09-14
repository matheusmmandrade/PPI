// Aguarda o carregamento completo do DOM para executar o script
window.addEventListener('DOMContentLoaded', () => {

    // --- Seleção dos Elementos do DOM ---
    const selectMarca = document.getElementById('marca');
    const selectModelo = document.getElementById('modelo');
    const selectLocalizacao = document.getElementById('localizacao');
    const anunciosContainer = document.getElementById('anuncios-container');

    // --- Funções Auxiliares ---

    /**
     * Função genérica para preencher um elemento <select> com opções.
     * @param {HTMLSelectElement} selectElement O elemento select a ser preenchido.
     * @param {string[]} items Um array de strings para as opções.
     * @param {string} placeholder O texto da primeira opção (ex: "Todas as Marcas").
     */
    function preencherSelect(selectElement, items, placeholder) {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item;
            option.textContent = item;
            selectElement.appendChild(option);
        });
        selectElement.disabled = items.length === 0;
    }
    
    // --- Funções de Requisição (AJAX com Fetch API) ---

    /**
     * Carrega as marcas de veículos disponíveis e preenche o select de marcas.
     */
    async function carregarMarcas() {
        try {
            const response = await fetch('../../controller/public-api.php?action=getMarcas');
            if (!response.ok) throw new Error('Erro ao buscar marcas');
            const marcas = await response.json();
            preencherSelect(selectMarca, marcas, 'Todas as Marcas');
        } catch (error) {
            console.error(error);
        }
    }

    /**
     * Carrega os modelos com base na marca selecionada.
     */
    async function carregarModelos() {
        const marca = selectMarca.value;
        // Reseta e desabilita os selects dependentes
        preencherSelect(selectModelo, [], 'Todos os Modelos');
        preencherSelect(selectLocalizacao, [], 'Todas as Cidades');
        selectModelo.disabled = true;
        selectLocalizacao.disabled = true;

        if (marca) {
            try {
                const response = await fetch(`../../controller/public-api.php?action=getModelos&marca=${marca}`);
                if (!response.ok) throw new Error('Erro ao buscar modelos');
                const modelos = await response.json();
                preencherSelect(selectModelo, modelos, 'Todos os Modelos');
            } catch (error) {
                console.error(error);
            }
        }
    }

    /**
     * Carrega as cidades com base na marca e modelo selecionados.
     */
    async function carregarCidades() {
        const marca = selectMarca.value;
        const modelo = selectModelo.value;
        preencherSelect(selectLocalizacao, [], 'Todas as Cidades');
        selectLocalizacao.disabled = true;
        
        if (marca && modelo) {
            try {
                const response = await fetch(`../../controller/public-api.php?action=getCidades&marca=${marca}&modelo=${modelo}`);
                if (!response.ok) throw new Error('Erro ao buscar cidades');
                const cidades = await response.json();
                preencherSelect(selectLocalizacao, cidades, 'Todas as Cidades');
            } catch (error) {
                console.error(error);
            }
        }
    }

    /**
     * Busca os anúncios no servidor com base nos filtros atuais.
     */
    async function buscarAnuncios() {
        const marca = selectMarca.value;
        const modelo = selectModelo.value;
        const localizacao = selectLocalizacao.value;

        // Monta a URL com os parâmetros de filtro
        const url = new URL('../../controller/public-api.php', window.location.href);
        url.searchParams.append('action', 'getAnuncios');
        if (marca) url.searchParams.append('marca', marca);
        if (modelo) url.searchParams.append('modelo', modelo);
        if (localizacao) url.searchParams.append('localizacao', localizacao);
        
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Erro ao buscar anúncios');
            const anuncios = await response.json();
            exibirAnuncios(anuncios);
        } catch (error) {
            console.error(error);
            anunciosContainer.innerHTML = '<p class="aviso-sem-anuncios">Erro ao carregar anúncios. Tente novamente mais tarde.</p>';
        }
    }

    // --- Funções de Manipulação do DOM ---

    /**
     * Renderiza os cards de anúncio na tela de forma segura e performática.
     * @param {Array} anuncios Um array de objetos de anúncio.
     */
    function exibirAnuncios(anuncios) {
        // Limpa o container de forma segura e eficiente
        while (anunciosContainer.firstChild) {
            anunciosContainer.removeChild(anunciosContainer.firstChild);
        }

        if (anuncios.length === 0) {
            const aviso = document.createElement('p');
            aviso.className = 'aviso-sem-anuncios';
            aviso.textContent = 'Nenhum anúncio encontrado com os filtros selecionados.';
            anunciosContainer.appendChild(aviso);
            return;
        }

        const fragment = document.createDocumentFragment();

        anuncios.forEach(anuncio => {
            const link = document.createElement('a');
            link.href = `../anuncio/anuncio.html?id=${anuncio.id}`;
            link.className = 'card-link';

            const cardDiv = document.createElement('div');
            cardDiv.className = 'card';

            const modeloH1 = document.createElement('h1');
            modeloH1.className = 'modelo-carro-card';
            modeloH1.textContent = anuncio.modelo;

            const imgContainer = document.createElement('div');
            imgContainer.className = 'img-container';

            const img = document.createElement('img');
            img.className = 'imagem-carro-card';
            const fotoUrl = anuncio.foto ? `../../public/images/anuncios/${anuncio.foto}` : '../../public/images/image_placeholder.png'; // Imagem padrão caso não haja foto
            img.src = fotoUrl;
            img.alt = `Foto de ${anuncio.marca} ${anuncio.modelo}`;
            img.loading = 'lazy'; // Otimização: carrega imagens conforme o scroll

            const infoDiv = document.createElement('div');
            infoDiv.className = 'aditional-info-card';

            const pMarcaAno = document.createElement('p');
            pMarcaAno.textContent = `${anuncio.marca} ${anuncio.ano}`;

            const pCidade = document.createElement('p');
            pCidade.textContent = anuncio.cidade;

            const valorB = document.createElement('b');
            valorB.className = 'valor-carro-card';
            const valorFormatado = parseFloat(anuncio.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            valorB.textContent = valorFormatado;

            imgContainer.appendChild(img);
            infoDiv.appendChild(pMarcaAno);
            infoDiv.appendChild(pCidade);

            cardDiv.appendChild(modeloH1);
            cardDiv.appendChild(imgContainer);
            cardDiv.appendChild(infoDiv);
            cardDiv.appendChild(valorB);

            link.appendChild(cardDiv);
            fragment.appendChild(link);
        });

        anunciosContainer.appendChild(fragment);
    }

    // --- Event Listeners ---
    selectMarca.addEventListener('change', () => {
        carregarModelos();
        buscarAnuncios();
    });

    selectModelo.addEventListener('change', () => {
        carregarCidades();
        buscarAnuncios();
    });

    selectLocalizacao.addEventListener('change', buscarAnuncios);

    // --- Inicialização da Página ---
    function init() {
        carregarMarcas();
        buscarAnuncios(); // Carrega os últimos 20 anúncios ao entrar na página
    }

    init();
});