document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const idAnuncio = urlParams.get('id');

    if (idAnuncio) {
        carregarInteresses(idAnuncio);
    } else {
        document.querySelector('.interesses-container').innerHTML = '<h1>Anúncio não especificado.</h1>';
    }
});

async function carregarInteresses(id) {
    const container = document.querySelector('.interesses-container');

    try {
        const response = await fetch(`virtual_cars/controller/lista-interesses.php?id=${id}`);
        if (!response.ok) {
            const erro = await response.json();
            throw new Error(erro.message || 'Não foi possível carregar os interesses.');
        }

        const interesses = await response.json();
        const titulo = container.querySelector('.interesses-titulo');
        container.innerHTML = '';
        container.appendChild(titulo);

        if (interesses.length === 0) {
            container.insertAdjacentHTML('beforeend', '<p>Nenhuma mensagem de interesse para este anúncio.</p>');
        } else {
            interesses.forEach(interesse => {
                const cardHTML = criarCardInteresse(interesse);
                container.insertAdjacentHTML('beforeend', cardHTML);
            });
            adicionarEventListenersExcluirInteresse();
        }

    } catch (error) {
        console.error('Falha ao carregar interesses:', error);
        container.innerHTML += `<p style="color: red;">${error.message}</p>`;
    }
}

function criarCardInteresse(interesse) {
    const dataFormatada = new Date(interesse.dataHora).toLocaleString('pt-BR');

    return `
        <div class="interesse-card">
            <button class="btn-acao btn-excluir-interesse" data-id="${interesse.id}">X</button>
            <h3 class="interesse-nome">${interesse.nome}</h3>
            <p class="interesse-contato"><strong>Telefone:</strong> ${interesse.telefone}</p>
            <p class="interesse-mensagem"><strong>Mensagem:</strong> "${interesse.mensagem}"</p>
            <p class="interesse-contato" style="font-size: 0.8rem; text-align: right;">Recebido em: ${dataFormatada}</p>
        </div>
    `;
}

function adicionarEventListenersExcluirInteresse() {
    document.querySelectorAll('.btn-excluir-interesse').forEach(button => {
        button.addEventListener('click', async (event) => {
            if (confirm('Tem certeza que deseja excluir esta mensagem?')) {
                const idInteresse = event.target.dataset.id;
                const formData = new FormData();
                formData.append('id', idInteresse);

                try {
                    const response = await fetch('virtual_cars/controller/exclui-interesse.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    if (response.ok && result.success) {
                        event.target.closest('.interesse-card').remove();
                    } else {
                        alert(result.message || 'Não foi possível excluir a mensagem.');
                    }
                } catch (error) {
                    alert('Ocorreu um erro de comunicação com o servidor.');
                }
            }
        });
    });
}