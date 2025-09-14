let fotosDoAnuncio = [];
let indiceFotoAtual = 0;

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const idAnuncio = urlParams.get('id');

    if (idAnuncio) {
        buscarDetalhesDoAnuncio(idAnuncio);
    } else {
        document.querySelector('main').innerHTML = '<h1>Anúncio não encontrado.</h1>';
    }
});


async function buscarDetalhesDoAnuncio(id) {
    try {
        const response = await fetch(`virtual_cars/controller/detalhes-anuncio.php?id=${id}`);

        if (!response.ok) {
            const erro = await response.json();
            throw new Error(erro.message || 'Não foi possível carregar os detalhes do anúncio.');
        }

        const anuncio = await response.json();

        preencherPagina(anuncio);

        fotosDoAnuncio = anuncio.fotos;
        inicializarGaleria();

    } catch (error) {
        console.error('Erro ao buscar detalhes do anúncio:', error);
        document.querySelector('.anuncio-container').innerHTML = `<p style="color: red;">${error.message}</p>`;
    }
}

function preencherPagina(anuncio) {
    document.title = `${anuncio.marca} ${anuncio.modelo}`; 

    document.querySelector('.anuncio-titulo').textContent = `${anuncio.marca} ${anuncio.modelo}`;
    document.querySelector('.anuncio-localizacao').textContent = `${anuncio.cidade}, ${anuncio.estado}`;
    document.querySelector('.anuncio-descricao').textContent = anuncio.descricao;
    
    const precoFormatado = parseFloat(anuncio.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.querySelector('.anuncio-preco').textContent = precoFormatado;
    
    // Preenche a lista de informações
    const listaInfo = document.querySelector('.anuncio-info-lista');
    listaInfo.innerHTML = `
        <li><strong>Ano:</strong> ${anuncio.ano}</li>
        <li><strong>Quilometragem:</strong> ${anuncio.quilometragem.toLocaleString('pt-BR')} km</li>
        <li><strong>Cor:</strong> ${anuncio.cor}</li>
    `;

    const linkInteresse = document.querySelector('.btn-login');
    linkInteresse.href = `../anuncio/interesse.html?id=${anuncio.id}`;
}

function inicializarGaleria() {
    const imgElement = document.querySelector('.imagem-carro');
    const btnEsquerda = document.querySelector('.btn-img.left');
    const btnDireita = document.querySelector('.btn-img.right');
    
    if (fotosDoAnuncio.length === 0) {
        imgElement.src = '../../public/images/sem-foto.png'; 
        btnEsquerda.style.display = 'none';
        btnDireita.style.display = 'none';
        return;
    }

    atualizarImagem();

    if (fotosDoAnuncio.length <= 1) {
        btnEsquerda.style.display = 'none';
        btnDireita.style.display = 'none';
    } else {
        btnEsquerda.onclick = imagemAnterior;
        btnDireita.onclick = proximaImagem;
    }
}

function atualizarImagem() {
    const imgElement = document.querySelector('.imagem-carro');
    imgElement.src = `../../public/images/anuncios/${fotosDoAnuncio[indiceFotoAtual]}`;
}

function proximaImagem() {
    indiceFotoAtual = (indiceFotoAtual + 1) % fotosDoAnuncio.length;
    atualizarImagem();
}

function imagemAnterior() {
    indiceFotoAtual = (indiceFotoAtual - 1 + fotosDoAnuncio.length) % fotosDoAnuncio.length;
    atualizarImagem();
}