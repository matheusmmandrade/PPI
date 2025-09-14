window.addEventListener('DOMContentLoaded', () => {

    const selectMarca = document.getElementById('marca');
    const selectModelo = document.getElementById('modelo');
    const selectLocalizacao = document.getElementById('localizacao');
    const anunciosContainer = document.getElementById('anuncios-container');

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

   
    async function carregarModelos() {
        const marca = selectMarca.value;
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

   
    async function buscarAnuncios() {
        const marca = selectMarca.value;
        const modelo = selectModelo.value;
        const localizacao = selectLocalizacao.value;

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


    
    function exibirAnuncios(anuncios) {
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
            img.loading = 'lazy'; 

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

    selectMarca.addEventListener('change', () => {
        carregarModelos();
        buscarAnuncios();
    });

    selectModelo.addEventListener('change', () => {
        carregarCidades();
        buscarAnuncios();
    });

    selectLocalizacao.addEventListener('change', buscarAnuncios);

    function init() {
        carregarMarcas();
        buscarAnuncios(); 
    }

    init();
});