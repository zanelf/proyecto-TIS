const BASE_URL = "http://api.miproyecto.local";

async function request(endpoint, options = {}) {

    const response = await fetch(`${BASE_URL}${endpoint}`, {
        method: options.method || "GET",
        headers: {
            "Content-Type": "application/json",
            ...(options.headers || {})
        },
        body: options.body || undefined
    });

    const text = await response.text();

    try {
        return JSON.parse(text);
    } catch (e) {
        console.error("Respuesta no JSON:", text);
        throw new Error("Error en servidor");
    }
}

// métodos base
export async function get(endpoint) {
    return request(endpoint, { method: "GET" });
}

export async function post(endpoint, data) {
    return request(endpoint, {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export async function put(endpoint, data) {
    return request(endpoint, {
        method: "PUT",
        body: JSON.stringify(data)
    });
}

export async function del(endpoint) {
    return request(endpoint, { method: "DELETE" });
}