<x-app-layout>

    <div class="relative min-h-screen md:flex">

        {{-- Sidebar --}}
        <div id="sidebar" class="z-50 bg-white w-64 absolute inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out md:relative md:translate-x-0">

            {{-- Header --}}
            <div class="w-100 flex-none bg-white border-b-2 border-b-grey-200 flex flex-row p-5 pr-0 justify-between items-center h-20 ">

                {{-- Logo --}}
                <a href="/" class="mx-auto">

                    <img class="h-16" src="{{ asset('storage/img/logo2.png') }}" alt="Logo">

                </a>

                {{-- Side Menu hide button --}}
                <button  type="button" title="Cerrar Menú" id="sidebar-menu-button" class="md:hidden mr-2 inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>

            {{-- Nav --}}
            <nav class="p-4 text-rojo">

                <a href="#usuarios" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>

                    Usuarios
                </a>

                <a href="#dependencias" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>

                    Dependencias
                </a>

                <a href="#entradas" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                      </svg>

                    Entradas
                </a>

                <a href="#seguimiento" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                      </svg>

                    Seguimiento
                </a>

                <a href="#conclusiones" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>

                    Conclusiones
                </a>

            </nav>

        </div>

        <div class="flex-1 flex-col flex max-h-screen overflow-x-auto min-h-screen">

            <div class="w-100 bg-white border-b-2 border-b-grey-200 flex-none flex flex-row p-5 justify-between items-center h-20">

                <!-- Mobile menu button-->
                <div class="flex items-center">

                    <button  type="button" title="Abrir Menú" id="mobile-menu-button" class="md:hidden inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>

                </div>

                {{-- Logo --}}
                <p class="font-semibold text-2xl text-rojo">Manual de Usuario</p>

                <div></div>

            </div>

            <div class="bg-white flex-1 overflow-y-auto py-8 md:border-l-2 border-l-grey-200">

                <div class="lg:w-2/3 mx-auto rounded-xl">

                    <div class="capitulo mb-10" id="introduccion">

                        <p class="text-2xl font-semibold text-rojo mb-5">Introducción</p>

                        <div class="  px-3">

                            <p class="mb-2">El Sistema de Gestión Documental, tiene como propósito administrar la documentación que llega a cada departamento del Instituto Registral y Catastral de Michoacán de Ocampo. Dicha administración consta de 3 etapas;</p>

                            <ul class="px-4 list-disc">
                                <li>Entrada: Se refiere a la llegada de la documentación a cada departamento, en la cual se asignan los responsables de llevar acabo la siguiente etapa.</li>
                                <li>Seguimiento: Se refiere a cada actividad que genera cada entrada, es responsable de cada seguimiento la o las personas asignadas</li>
                                <li>Conclución: Se refiere al termino del asunto, es responsable de cada conclusión la o las personas asignadas</li>
                            </ul>

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="usuarios">

                        <p class="text-2xl font-semibold text-rojo mb-5">Usuarios</p>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de usuarios lleva el control del registro de los usuarios del sistema. Los usuarios estan clasificados por roles ('Titular' y 'Usuario')
                                cada uno con atribuciones distintas. Solo los usuarios con rol de Titular pueden agregar nuevos usuarios y editarlos.
                            </p>

                            <p>
                                <strong>Busqueda de usuario:</strong>
                                puede hacer busqueda de usuarios por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nuevo usuario:</strong>
                                puede agregar un nuevo usuario haciendo click el el botón "Agregar nuevo usuario" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_modal_crear.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Editar usuario:</strong>
                                cada usuario tiene asociado dos botones de acciones, puede editar un usuario haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información del usuario para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_editar.jpg') }}" alt="Imágen buscar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_modal_editar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Borrar usuario:</strong>
                                puede borrar un usuario haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_borrar.jpg') }}" alt="Imágen buscar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="dependencias">

                        <p class="text-2xl font-semibold text-rojo mb-5">Dependencias</p>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de dependencias lleva el control del registro de las dependecias que son necesarias para indicar la procedencia de cada Entrada. Solo los usuarios con rol de Titular pueden agregar nuevas dependencias y editarlas.
                            </p>

                            <p>
                                <strong>Busqueda de dependencia:</strong>
                                puede hacer busqueda de dependencias por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nueva dependencia:</strong>
                                puede agregar una nueva dependencia haciendo click el el botón "Agregar nueva Dependencia" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p>
                                <strong>Editar dependencia:</strong>
                                cada dependencia tiene asociado dos botones de acciones, puede editar una dependencia haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la dependencia para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar dependencia:</strong>
                                puede borrar una dependencia haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="entradas">

                        <p class="text-2xl font-semibold text-rojo mb-5">Entradas</p>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de entradas lleva el control del registro de las entradas. Una entrada representa un documento que ha llegado al usuario Titular.
                                Las entradas estan compuestas de un conjunto de campos, siendo ellos:

                                <ul class="px-4 list-disc mb-4">
                                    <li>Folio: Este campo es un identificador el cual se agrega a cada registro automáticamente.</li>
                                    <li>Oficio: Este campo contiene el número de oficio del documento entrante.</li>
                                    <li>Asunto: Este campo contiene la descripción general del contenido del documento.</li>
                                    <li>Origen: Es la dependencia de la cual proviene el documento.</li>
                                    <li>Destinatario: Es la oficina a la que va dirigido el documento.</li>
                                    <li>Asignado a: Son los usuarios los cuales se les asigna el seguimiento y conclusión del documento.</li>
                                    <li>Fecha de término: Es la fecha limite para dar respuesta al documento.</li>
                                    <li>Estado: Es estado de la entrada. Nuevo; idica que la entrada no contiene aún seguimientos. Seguimiento; inidca que la entrada tiene seguimientos. Concluido; indica que la entrada ha concluido.</li>
                                    <li>Archivos: Es el documento entrante en formato PDF, puede subir varios archivos a la vez.</li>
                                </ul>

                            </p>

                            <p>
                                <strong>Busqueda de entradas:</strong>
                                puede hacer busqueda de entradas por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entradas_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nueva entrada:</strong>
                                puede agregar una nueva entrada haciendo click el el botón "Agregar nueva Entrada" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrada_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p class="mb-4">
                                En la sección de "Asignar A", puede seleccionar uno o mas usuarios manteniendo presionada la tecla ctrl.
                            </p>

                            <p>
                                <strong>Editar entrada:</strong>
                                cada entrada tiene asociado dos botones de acciones, puede editar una entrada haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la entrada para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entradas_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entradas_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p class="mb-4">
                                Los archivos de la entrada se mostraran justo despues de la seccion de "Asunto", los cuales puede eliminar haciendo click en el botón que los precede.
                                En la columna de Asignado A cada uno de los usuarios tienen un botón el cual tiene la funcion de enviar un mensaje via Whatsapp, informando que se les ha sido adignada una entrada
                            </p>

                            <p>
                                <strong>Borrar entrada:</strong>
                                puede borrar una entrada haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entradas_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="seguimiento">

                        <p class="text-2xl font-semibold text-rojo mb-5">Seguimiento</p>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de seguimiento lleva el control del registro de los seguimientos. Una seguimiento representa una eventualidad en el proceso de la entrada.
                                Los seguimientos estan compuestos de un conjunto de campos, siendo ellos:

                                <ul class="px-4 list-disc mb-4">
                                    <li>Oficio de respuesta: Este campo contiene el número de oficio con el que se respunde.</li>
                                    <li>Comentario: Este campo contiene la descripción general del contenido del documento.</li>
                                    <li>Entrada: La entrada a la cual pertenece el seguimiento, mostrando el folio de la entrada.</li>
                                    <li>Fecha de respuesta: Es la fecha en la cual se genera el documento.</li>
                                    <li>Archivos: Es el documento del seguimiento en formato PDF, puede subir varios archivos a la vez</li>
                                </ul>

                            </p>

                            <p>
                                <strong>Busqueda de seguimientos:</strong>
                                puede hacer busqueda de seguimientos por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/seguimientos_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nuevo seguimiento:</strong>
                                puede agregar un nuevo seguimiento haciendo click el el botón "Agregar nuevo Seguimiento" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/seguimiento_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p>
                                <strong>Editar seguimiento:</strong>
                                cada seguimiento tiene asociado dos botones de acciones, puede editar un seguimiento haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información del seguimiento para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/seguimiento_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/seguimiento_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p class="mb-4">
                                Los archivos del seguimiento se mostraran justo despues de la seccion de "Comentario", los cuales puede eliminar haciendo click en el botón que los precede.
                            </p>

                            <p>
                                <strong>Borrar seguimiento:</strong>
                                puede borrar un seguimiento haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/seguimiento_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="conclusiones">

                        <p class="text-2xl font-semibold text-rojo mb-5">Conclusiones</p>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de conclusiones lleva el control del registro de las conclusiones. Una conclusión representa el fín en el proceso de la entrada, puede haber varias resoluciones
                                para concluir una entrada.
                                Las conclusiones estan compuestas de un conjunto de campos, siendo ellos:

                                <ul class="px-4 list-disc mb-4">
                                    <li>Entrada: La entrada a la cual pertenece el seguimiento, mostrando el folio de la entrada.</li>
                                    <li>Comentario: Este campo contiene la descripción general del contenido del documento.</li>
                                    <li>Archivos: Es el documento del seguimiento en formato PDF, puede subir varios archivos a la vez</li>
                                </ul>

                            </p>

                            <p>
                                <strong>Busqueda de conclusiones:</strong>
                                puede hacer busqueda de conclusiones por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/conclusiones_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nueva conclusión:</strong>
                                puede agregar una nueva conclusión haciendo click el el botón "Agregar nueva Conclusión" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/conclusion_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p>
                                <strong>Editar conclusión:</strong>
                                cada conclusión tiene asociado dos botones de acciones, puede editar una conclusión haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información del conclusión para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/conclusion_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/conclusion_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p class="mb-4">
                                Los archivos de la conclusión se mostraran justo despues de la seccion de "Comentario", los cuales puede eliminar haciendo click en el botón que los precede.
                            </p>

                            <p>
                                <strong>Borrar conclusión:</strong>
                                puede borrar una conclusión haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/conclusion_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        const btn_close = document.getElementById('sidebar-menu-button');
        const btn_open = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');

        btn_open.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        btn_close.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        /* Change nav profile image */
        window.addEventListener('nav-profile-img', event => {

            document.getElementById('nav-profile').setAttribute('src', event.detail.img);

        });

    </script>

</x-app-layout>
