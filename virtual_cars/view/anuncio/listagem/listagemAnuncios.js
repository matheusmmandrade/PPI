document.addEventListener('DOMContentLoaded', function () {
    carregarAnuncios();
});


async function carregarAnuncios() {
    const container = document.querySelector('.listagem-container');

    try {
        const response = await fetch('virtual_cars/controller/lista-anuncios.php');

        if (!response.ok) {
            throw new Error('A resposta da rede não foi OK');
        }

        const anuncios = await response.json();

        const titulo = container.querySelector('.listagem-titulo');
        container.innerHTML = ''; 
        container.appendChild(titulo);

        if (anuncios.length === 0) {

            container.insertAdjacentHTML('beforeend', '<p>Você ainda não cadastrou nenhum anúncio.</p>');
            return;
        }

        anuncios.forEach(anuncio => {
            const cardHTML = criarCardAnuncio(anuncio);
            container.insertAdjacentHTML('beforeend', cardHTML);
        });
        
        adicionarEventListenersExcluir();

    } catch (error) {
        console.error('Falha ao carregar anúncios:', error);
        container.innerHTML += '<p style="color: red;">Ocorreu um erro ao carregar seus anúncios. Tente novamente mais tarde.</p>';
    }
}

function criarCardAnuncio(anuncio) {
    return `
        <div class="anuncio-item">
            <img src="../../public/images/anuncios/${anuncio.foto}" alt="Foto de ${anuncio.modelo}" class="anuncio-foto">
            <div class="anuncio-info">
                <p><strong>Marca:</strong> ${anuncio.marca}</p>
                <p><strong>Modelo:</strong> ${anuncio.modelo}</p>
                <p><strong>Ano:</strong> ${anuncio.ano}</p>
            </div>
            <div class="anuncio-botoes">
                <a href="../anuncio.html?id=${anuncio.id}" class="btn-acao">Ver Detalhes</a>
                <a href="listagemInteresses.html?id=${anuncio.id}" class="btn-acao">Ver Interesses</a>
                <button class="btn-acao btn-excluir" data-id="${anuncio.id}">Excluir</button>
            </div>
        </div>
    `;
}


function adicionarEventListenersExcluir() {
    document.querySelectorAll('.btn-excluir').forEach(button => {
        button.addEventListener('click', async (event) => {
            if (confirm('Tem certeza que deseja excluir este anúncio? Esta ação é irreversível.')) {
                
                const idAnuncio = event.target.dataset.id;
                const formData = new FormData();
                formData.append('id', idAnuncio);

                try {
                    const response = await fetch('virtual_cars/controller/exclui-anuncio.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        event.target.closest('.anuncio-item').remove();
                        alert(result.message);
                    } else {
                        alert(result.message || 'Não foi possível excluir o anúncio.');
                    }
                } catch (error) {
                    console.error('Erro na requisição de exclusão:', error);
                    alert('Ocorreu um erro de comunicação com o servidor.');
                }
            }
        });
    });
}