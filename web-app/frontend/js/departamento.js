import { DepartamentoAPI } from "../api/apidepartamento.js";

async function crearDepartamento(nombre) {

    try {
        const res = await DepartamentoAPI.crear(nombre);

        console.log(res);
        alert(res.message);

    } catch (error) {
        console.error(error);
        alert("Error");
    }
}
async function verDepartamento() {

    try {
        const res = await DepartamentoAPI.crear(nombre);

        console.log(res);
        alert(res.message);

    } catch (error) {
        console.error(error);
        alert("Error");
    }
}
async function cargarDepartamentos() {

    try {
        const res = await DepartamentoAPI.listar();

        const lista = document.getElementById("lista");
        lista.innerHTML = "";

        res.data.forEach(dep => {

            const li = document.createElement("li");
            li.textContent = dep.nombre;

            lista.appendChild(li);
        });

    } catch (error) {
        console.error("Error cargando:", error);
    }
}

/* =========================
   EVENTO INICIAL
========================= */
document.addEventListener("DOMContentLoaded", () => {

    cargarDepartamentos();

});

document.addEventListener("DOMContentLoaded", () => {

    document.querySelector("button").addEventListener("click", () => {
        const nombre = document.getElementById("nombre").value;
        crearDepartamento(nombre);
    });

});