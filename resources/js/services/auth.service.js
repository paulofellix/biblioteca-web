import { httpClient } from "../http";

function getUserData () {
  return httpClient.get("/api/user")
}

function sanctumCheck () {
    return httpClient.get('/sanctum/csrf-cookie')
}

function login (authData) {
    return httpClient.post('/login', authData)
}

export default { getUserData, sanctumCheck, login }