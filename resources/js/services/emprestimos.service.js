import { httpClient } from "../http";

const baseUrl = "/api/emprestimos";

function getEmprestimos(query) {
    return httpClient.get(`${baseUrl}`, { params: query });
}

function getEmprestimo(id) {
    return httpClient.get(`${baseUrl}/${id}`);
}

function createEmprestimo(data) {
    return httpClient.post(`${baseUrl}`, data);
}

function updateEmprestimo(id, data) {
    return httpClient.put(`${baseUrl}/${id}`, data)
}

export default {
    getEmprestimos,
    getEmprestimo,
    createEmprestimo,
    updateEmprestimo
}