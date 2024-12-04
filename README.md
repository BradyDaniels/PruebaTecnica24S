## Descripcion general

Este proyecto es para la prueba tecnica 24siete. A nivel funcional consiste en una vistia home inicial la cual te pedira seleccionar el rol, si quieres ser paciente o doctor.

- El doctor podra agregar un nuevo servicio o administrar los ya existente. Podra tambien re agendar o confirmar las citas solicitadas.
- El paciente podra visualizar las citas que a agendado y podrias buscar doctores dentro una ubicacion y especialidad en especifico para agendar una nueva cita. 

## Descripcion tecnica
 El backend de este proyecto fue realizado mediante la herramienta xampp, para el levantamiento rapido de una base de datos, usando PHP principalmente para la creacion de un CRUD basico y rutas API que se conectan al frotnend realizado en nextjs y estilizado con tailwindcss. 

## Instalacion

### Backend-Xampp

#### Instalacion de XAMPP

  Para la ejecucion del backend de este proyecto junto con la administracion de la base de datos se requiere la instalacion y ejecucion de xampp teniendo habilitado Apache y Mysql en su panel de control. Aqui su link de descarga: https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.0.30/xampp-windows-x64-8.0.30-0-VS16-installer.exe/download

#### Importar DB

- Una vez instalado XAMPP es vital que se importe la base de datos encontrando en el documento "db-export" ubicado en la raiz de este repositorio.
- Luego de la importancion se debe de crear el usuario para la base de datos importada mediante el siquiente query:

  GRANT ALL PRIVILEGES ON *.* TO `CRUD`@`localhost` IDENTIFIED BY PASSWORD '*23C85CE7B732736570B43A505CE609BF83DFFAFC' WITH GRANT OPTION;
  GRANT ALL PRIVILEGES ON `prueba tecnica 24siete`.* TO `CRUD`@`localhost`;

- Una vez importada el usuario y la bd, se debe de copiar la carpeta "htdocs" ubicada en la raiz de este repositorio y pegarla en la carpeta raiz de donde se tiene instalado el XAMPP. Pedira reemplazar y hay que darle a aceptar, esto es con la finalidad de colocar los scripts php necesarios tanto para el CRUD de la base de datos como para las llamadas api.

Este servidor correra en localhost siempre y cuando se tenga xampp ejecutado.  
  
  

### Frontend-Nextjs

Para la instalacion del front de este proyecto, sencillamente se debe de clonar el repositorio y en una terminal ubicada en la raiz de este ejecutar "npm i" para la descarga de node_modules. El proyecto se ejecuta con el comando "npm run dev".



