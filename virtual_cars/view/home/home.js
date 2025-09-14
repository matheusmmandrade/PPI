document.addEventListener('DOMContentLoaded', function () {
    // Seletores dos elementos do DOM
    const selectMarca = document.getElementById('marca');
    const selectModelo = document.getElementById('modelo');
    const selectLocalizacao = document.getElementById('localizacao');
    const anunciosContainer = document.getElementById('anuncios-container');

// CORRETO para a sua estrutura
    const API_URL = '../../controller/public_controller.php';
    // Função para popular um <select> com opções
    function populateSelect(selectElement, items, defaultOptionText) {
        selectElement.innerHTML = `<option value="">${defaultOptionText}</option>`;
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item;
            option.textContent = item;
            selectElement.appendChild(option);
        });
        selectElement.disabled = items.length === 0;
    }

    // Função para renderizar os cards de anúncios
    function renderAnuncios(anuncios) {
        anunciosContainer.innerHTML = '';
        if (anuncios.length === 0) {
            anunciosContainer.innerHTML = '<p class="nenhum-anuncio">Nenhum anúncio encontrado com os filtros selecionados.</p>';
            return;
        }

        anuncios.forEach(anuncio => {
            const valorFormatado = parseFloat(anuncio.valor).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

            // Caminho para a pasta de imagens dos anúncios
            const imagePath = `../../public/images/anuncios/${anuncio.fotoCapa}`;
            
            const cardHTML = `
                <a href="../anuncio/anuncio.html?id=${anuncio.id}" class="card-link">
                    <div class="card">
                        <h1 class="modelo-carro-card">${anuncio.modelo}</h1>
                        <div class="img-container">
                            <img class="imagem-carro-card" src="${imagePath}" alt="Foto de ${anuncio.marca} ${anuncio.modelo}">
                        </div>
                        <div class="aditional-info-card">
                            <p>${anuncio.marca} ${anuncio.ano}</p>
                            <p>${anuncio.cidade}</p>
                        </div>
                        <b class="valor-carro-card">${valorFormatado}</b>
                    </div>
                </a>
            `;
            anunciosContainer.insertAdjacentHTML('beforeend', cardHTML);
        });
    }

    // Função para buscar e exibir os anúncios com base nos filtros
    async function buscarAnuncios() {
        const marca = selectMarca.value;
        const modelo = selectModelo.value;
        const cidade = selectLocalizacao.value;

        try {
            const response = await fetch(`${API_URL}?acao=buscar&marca=${marca}&modelo=${modelo}&cidade=${cidade}`);
            if (!response.ok) throw new Error('Falha na busca de anúncios');
            const anuncios = await response.json();
            renderAnuncios(anuncios);
        } catch (error) {
            console.error('Erro ao buscar anúncios:', error);
            anunciosContainer.innerHTML = '<p class="erro">Ocorreu um erro ao carregar os anúncios. Tente novamente.</p>';
        }
    }

    // Carregar Marcas
    async function carregarMarcas() {
        try {
            const response = await fetch(`${API_URL}?acao=marcas`);
            if (!response.ok) throw new Error('Falha ao carregar marcas');
            const marcas = await response.json();
            populateSelect(selectMarca, marcas, 'Marca');
        } catch (error) {
            console.error('Erro ao carregar marcas:', error);
        }
    }

    // Carregar Modelos
    async function carregarModelos() {
        const marcaSelecionada = selectMarca.value;
        populateSelect(selectModelo, [], 'Modelo');
        populateSelect(selectLocalizacao, [], 'Cidade');
        selectModelo.disabled = true;
        selectLocalizacao.disabled = true;

        if (marcaSelecionada) {
            try {
                const response = await fetch(`${API_URL}?acao=modelos&marca=${marcaSelecionada}`);
                if (!response.ok) throw new Error('Falha ao carregar modelos');
                const modelos = await response.json();
                populateSelect(selectModelo, modelos, 'Modelo');
            } catch (error) {
                console.error('Erro ao carregar modelos:', error);
            }
        }
        buscarAnuncios();
    }

    // Carregar Cidades
    async function carregarCidades() {
        const marcaSelecionada = selectMarca.value;
        const modeloSelecionado = selectModelo.value;
        populateSelect(selectLocalizacao, [], 'Cidade');
        selectLocalizacao.disabled = true;

        if (marcaSelecionada && modeloSelecionado) {
             try {
                const response = await fetch(`${API_URL}?acao=cidades&marca=${marcaSelecionada}&modelo=${modeloSelecionado}`);
                if (!response.ok) throw new Error('Falha ao carregar cidades');
                const cidades = await response.json();
                populateSelect(selectLocalizacao, cidades, 'Cidade');
            } catch (error) {
                console.error('Erro ao carregar cidades:', error);
            }
        }
        buscarAnuncios();
    }

    // Adiciona os Event Listeners
    selectMarca.addEventListener('change', carregarModelos);
    selectModelo.addEventListener('change', carregarCidades);
    selectLocalizacao.addEventListener('change', buscarAnuncios);

    // Carga Inicial
    carregarMarcas();
    buscarAnuncios();
});