const apiKey = 'TU_CLAVE_API'; // Reemplaza con tu clave API de Bing News Search
const url = `https://api.bing.microsoft.com/v7.0/news/search?q=Chile&mkt=es-CL&count=10`;

const options = {
    method: 'GET',
    headers: {
        'Ocp-Apim-Subscription-Key': apiKey
    }
};

async function fetchNews() {
    try {
        const response = await fetch(url, options);
        const data = await response.json();
        return data.value;
    } catch (error) {
        console.error('Error al obtener las noticias:', error);
        return [];
    }
}

function displayNews(articles) {
    const newsContainer = document.getElementById('news-container');

    articles.forEach(article => {
        const newsItem = document.createElement('div');
        newsItem.classList.add('news-item');

        const title = document.createElement('h2');
        title.textContent = article.name;

        const description = document.createElement('p');
        description.textContent = article.description;

        const link = document.createElement('a');
        link.href = article.url;
        link.textContent = 'Leer más';
        link.target = '_blank';

        newsItem.appendChild(title);
        newsItem.appendChild(description);
        newsItem.appendChild(link);

        newsContainer.appendChild(newsItem);
    });
}

async function initNews() {
    const articles = await fetchNews();
    displayNews(articles);
}

document.addEventListener('DOMContentLoaded', initNews);
