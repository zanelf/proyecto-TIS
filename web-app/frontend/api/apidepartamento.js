import { get, post } from "./api.js";

const BASE = "/consultas";

export const DepartamentoAPI = {

    listar: () => get(`${BASE}/VerDepartamento.php`),

    crear: (nombre) => post(`${BASE}/insertardepartamento.php`, { nombre }),
    
    eliminar: (id) => post(`${BASE}/eliminar.php`, { id })

};