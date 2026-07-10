# proyecto-TIS (SGISC)

Repositorio de trabajo para el proyecto semestral del ramo de Taller de Ingeniería Informática.

## Tabla de contenido

- [proyecto-TIS (SGISC)](#proyecto-tis-sgisc)
  - [Tabla de contenido](#tabla-de-contenido)
- [Descripción](#descripción)
- [Instalación](#instalación)
- [Instrucciones de uso](#instrucciones-de-uso)
- [Contribución](#contribución)
- [Estructura de carpetas](#estructura-de-carpetas)
- [Equipo](#equipo)

# Descripción

Debido a la problemática de la falta de unificación de las solicitudes ciudadanas, se creó el proyecto **SGISC** (Sistema de Gestión Integral de Solicitudes Ciudadanas).

Este proyecto busca centralizar y gestionar el envío y la recepción de las múltiples solicitudes que recibe la municipalidad, con el fin de asegurar que estas lleguen a los remitentes correspondientes y que el ciudadano que emitió la solicitud pueda conocer el estado de su consulta.

Este repositorio está separado en dos partes principales:

- `web-app`: esta carpeta contiene el proyecto web, que actúa como interfaz para gestionar todos los requerimientos del proyecto.
- `otros`: esta carpeta contiene información de uso del grupo de trabajo y código de legado. Se utiliza principalmente para mantener el orden del proyecto.

# Instalación

- Clonar el proyecto en la carpeta del servidor (`xampp/htdocs/xampp/`).
- Abrir XAMPP o el stack de preferencia.
- Abrir el gestor de bases de datos de preferencia (por defecto, MySQL).
- Crear una nueva base de datos llamada `proyectodb`.
- Acceder a la carpeta `./web-app/base_de_datos/`.
- Importar el respaldo de la base de datos ubicado en dicha carpeta con el nombre `backup base de datos`.
- Verificar la configuración del archivo `conexion.php` para que las credenciales coincidan con la base de datos creada.
- Acceder a la carpeta `./web-app/config/`.
- Crear el archivo `mail_config.php`.
- Copiar el contenido de `mail_config_example.php`.
- Reemplazar el valor de `MAIL_PASS` por la contraseña del correo.
- Abrir el proyecto desde `localhost`.

# Instrucciones de uso

- Encender el servidor Apache.
- Encender el servidor MySQL.
- Acceder a `localhost:80/xampp/proyecto-TIS/web-app/` desde un navegador web.
- Utilizar la aplicación.

# Contribución

| Nombre | Módulos |
| --- | --- |
| Leonardo Acuña | |
| Vicente Bastidas | |
| Cristian Urrutia | |
| Beatriz Vidal | |
| Franco Videla | |

# Estructura de carpetas

```text
otros/                   # Carpeta para mantener el orden del proyecto, almacenando elementos que no pertenecen directamente a la aplicación web.
└── legacy/
    └── Mantenedores/    # Versiones originales de las interfaces de los mantenedores.

web-app/                 # Proyecto principal de la aplicación web.
├── base_de_datos/       # Scripts con el respaldo e inicialización de la base de datos.
├── consultas/           # Consultas realizadas desde el front-end hacia el back-end.
├── config/              # Archivos de configuración del proyecto.
├── libs/                # Librerías utilizadas, por ejemplo, para el envío de correos.
├── recursos/
│   ├── componentes/     # Componentes reutilizables.
│   ├── css/             # Hojas de estilo.
│   ├── img/             # Recursos gráficos.
│   └── js/              # Scripts JavaScript.
└── ventanas/            # Interfaces del front-end.
```

# Equipo

| Nombre | GitHub | Rol |
| --- | --- | --- |
| Leonardo Acuña | [@Aesedefe1](https://github.com/Aesedefe1) | Backend |
| Vicente Bastidas | [@zanelf](https://github.com/zanelf) | Analista |
| Cristian Urrutia | [@Currutiad](https://github.com/Currutiad) | Frontend y Backend |
| Beatriz Vidal | [@BeatrizVidalA](https://github.com/BeatrizVidalA) | Frontend |
| Franco Videla | [@Jayaquelo](https://github.com/Jayaquelo) | Líder de grupo |
