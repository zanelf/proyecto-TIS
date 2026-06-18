# proyecto-TIS (SGISC)

Repositorio de trabajo para el proyecto semestral del ramo de Taller de Ingenieria Informatica, dentro del contexto 

## tabla de contenido 
- [Descripción](#Descripción)
- [Instalación](#instalacíon)
- [Instrucciones de uso]("#Instrucciones-de-uso")
- [Contribución](#Contribución)
- [Estructura de carpetas](#Estructura-de-carpetas)
- [Equipo](#Equipo)

# Descripción
En vista de la problematica de falta de unificacion de las solicitudes ciudadanas, se creo el proyecto *SGISC* ( sistema de gestion integral de solicitudes ciudadanas).
Este proyecto busca centralizar y gestionar el envio y recibo de las multiples solicitudes que se recibe la municipalidad con el fin de asegurar que estas llegen a los remitentes correspondientes y el ciudadano que emitio la solicitud pueda saber que esta sucediendo con su consulta. 

Este repositorio esta separado en 2 partes principales
- `web-app` Esta carpeta tendra el proyecto de web que actuara a modo de interfaz para gestionar todos lo requerimientos del proyecto 
- `otros` Esta  carpeta tendra informacion de uso del grupo de trabajo y codigos que queden de legado, se usara principalmente para dar orden al proyecto.

# Instalación

- clonar proyecto en carpeta de servidor  
- en php crear una base de datos para el proyexto
- acceder a la carpeta `web-app/base_de_datos/`
- correr el respaldo `proyectodb2.sql` en la base de datos creada 
- abrir desde el localhost

# Instrucciones-de-uso
  - encender apache server
  - encender mysql server
  - acceder al link local del servidor `localhost:80/xampp/proyecto-TIS/web-app/` desde un buscador web
  - utilizar la pagina
    
# Contribución
| Nombre | Módulos |
| --- | --- | 
| Leonardo Acuña | |
| Vicente Bastidas | | 
| Cristian  Urrutia | |
| Beatriz  Vidal | |
| Franco  Videla | |

# Estructura-de-carpetas
```
otros/                   # carpeta para ayudar al orden guardando cosas que competen al proyecto pero no a la pagina web
└── legacy/              #
    └── Mantenedores/    # version original de las interfases para los mantenedores 

web-app/                 # direccion del proyecto web
├── base-de-datos/       # scripts con el backup de inicializacion back-end del proyeccto 
├── consultas/           # alojamiento de las consultas a realizas del front-end al back-end 
├── recursos/            # 
│    ├── componentes/    # codigos genericos reutilizables 
│    ├── css/            # ubicacion de los styles  
│    ├── img/            # 
│    └── js/             # 
└── ventanas/            # alojamiento del front-end 

```
# Equipo 
| Nombre | github | rol |
| --- | --- | --- |
| Leonardo Acuña | [@Aesedefe1](https://github.com/Aesedefe1) ||
| Vicente Bastidas| [@zanelf](https://github.com/zanelf) ||
| Cristian  Urrutia| [@Currutiad](https://github.com/Currutiad) ||
| Beatriz  Vidal| [@BeatrizVidalA](https://github.com/BeatrizVidalA) ||
| Franco  Videla| [@Jayaquelo](https://github.com/Jayaquelo) ||
