import { httpClient } from "../http";

const baseUrl = "/api/pessoas";

function getPessoas(query) {
    return httpClient.get(`${baseUrl}`, { params: query });
}

function getPessoa(id) {
    return httpClient.get(`${baseUrl}/${id}`);
}

function createPessoa(data) {
    return httpClient.post(`${baseUrl}`, data);
}

function updatePessoa(id, data) {
    return httpClient.put(`${baseUrl}/${id}`, data)
}

export default {
    getPessoas,
    getPessoa,
    createPessoa,
    updatePessoa
}