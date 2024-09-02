<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}
        @if(session("Annee"))
            <div >
                <h1 style="display: inline-block;">Année Académique</h1>
                <p style="font-style:italic; display: inline-block; margin-left:35%;"><a href='/' style='color:white;  font-weight:bold;'>Accueil ?</a></p>

            </div>
            <h3>{{ session("Annee")[0]." - ".session("Annee")[0]+1 }}</h3>
        @else
            <h1 style="color:rgb(136, 60, 60); font-style:italic; text-align:center;">Veuillez choisir une année Académique, <a href="{{ route("filament.admin.resources.annees.index") }}" style="color:white; text-decoration:underline;">Ici !</a></h1>
            <p style="text-align:center; font-style:italic;"><a href='/' style='color:white;  font-weight:bold;'>Accueil ?</a></p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
