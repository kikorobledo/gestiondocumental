@extends('layouts.admin')

@section('content')

    <div class="bg-white rounded-lg shadow-2xl w-full p-4 mb-5">

        <h1 class="text-2xl font-thin tracking-widest mb-4">Entrada</h1>

        <p class="text-lg mb-2">Folio: <span class="text-gray-600">{{ $entrie->folio }}</span></p>

        <p class="text-lg mb-2">Número de oficio: <span class="text-gray-600">{{ $entrie->numero_oficio }}</span></p>

        <p class="text-lg mb-2">Asunto:</p>

        <div>

            <p class="text-gray-600">{{ $entrie->asunto }}</p>

        </div>

    </div>

    <div class="bg-white rounded-lg shadow-2xl w-full p-4 mb-5">

        <h1 class="text-2xl font-thin tracking-widest mb-4">Seguimiento</h1>

        @foreach ($trackings as $tracking)

            <div class="p-4 border border-gray-300 rounded-lg mb-4">

                <div class="flex justify-between">

                    <div>

                        <p class="text-lg mb-2">Oficio de respuesta: <span class="text-gray-600">{{ $tracking->oficio_respuesta }}</span></p>

                        <p class="text-lg mb-2">Fecha de respuesta: <span class="text-gray-600">{{ $tracking->fecha_respuesta }}</span></p>

                        <p class="text-lg mb-2">Comentario:</p>

                    </div>

                    <div>

                        <p class="text-lg mb-2">Registrado por: <span class="text-gray-600">{{ $tracking->createdBy->name }}</span></p>
                        <p class="text-lg mb-2">Fecha de registro: <span class="text-gray-600">{{ $tracking->created_at }}</span></p>


                    </div>

                </div>

                <div>

                    <p class="text-gray-600">{{ $tracking->comentario }}</p>

                </div>

                <div class=" space-x-3 mt-3 text-right">

                    @foreach ($tracking->files as $file )

                        <a
                            href="{{ Storage::disk('pdfs')->url($file->url)}}"
                            target="_blank"
                            class="bg-red-400 hover:shadow-lg text-white text-xs md:text-sm px-3 py-1 rounded-full hover:bg-red-700 focus:outline-none mr-2 md:mr-0"
                        >
                        PDF {{ $loop->iteration }}
                        </a>

                    @endforeach

                </div>

            </div>

        @endforeach

    </div>

    <div class="bg-white rounded-lg shadow-2xl w-full p-4 mb-5">

        <h1 class="text-2xl font-thin tracking-widest mb-4">Conclusión</h1>

        @foreach ($conclusions as $conclusion)

        <div class="p-4 border border-gray-300 rounded-lg mb-4">

            <div class="flex justify-between">

                <div>

                    <p class="text-lg mb-2">Oficio de respuesta: <span class="text-gray-600">{{ $conclusion->oficio_respuesta }}</span></p>

                    <p class="text-lg mb-2">Fecha de respuesta: <span class="text-gray-600">{{ $conclusion->fecha_respuesta }}</span></p>

                    <p class="text-lg mb-2">Comentario:</p>

                </div>

                <div>

                    <p class="text-lg mb-2">Registrado por: <span class="text-gray-600">{{ $conclusion->createdBy->name }}</span></p>
                    <p class="text-lg mb-2">Fecha de registro: <span class="text-gray-600">{{ $conclusion->created_at }}</span></p>


                </div>

            </div>

            <div>

                <p class="text-gray-600">{{ $conclusion->comentario }}</p>

            </div>

            <div class=" space-x-3 mt-3 text-right">

                @foreach ($conclusion->files as $file )

                    <a
                        href="{{ Storage::disk('pdfs')->url($file->url)}}"
                        target="_blank"
                        class="bg-red-400 hover:shadow-lg text-white text-xs md:text-sm px-3 py-1 rounded-full hover:bg-red-700 focus:outline-none mr-2 md:mr-0"
                    >
                    PDF {{ $loop->iteration }}
                    </a>

                @endforeach

            </div>

        </div>

    @endforeach

    </div>

@stop
