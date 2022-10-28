import { httpClient } from "../http";

const baseUrl = "/api/livros";

function getLivros (query) {
    return httpClient.get(`${baseUrl}`, { params: query });
}

function getLivro (id) {
    return httpClient.get(`${baseUrl}/${id}`);
}

function createLivro (data) {
    return httpClient.post(`${baseUrl}`, data);
}

function updateLivro (id, data) {
    return httpClient.put(`${baseUrl}/${id}`, data)
}

export default {
    getLivros,
    getLivro,
    createLivro,
    updateLivro
}