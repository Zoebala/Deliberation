@extends("layouts.master")
@section("contenu")
<style>

    table{
            max-width:98%;
            margin-left:-10;
            margin-bottom: 10px;
        }
    ul li{
        list-style-type: none;
    }
    td{
        text-align: center;
    }
</style>


        <div class="tableau">
            <hr style="border:1px dashed black">
            <h3 class="text-center fw-bold mb-3" style="text-decoration:underline;">Palmarès de Résultats {{ session("classe")[0]." ".$queries[0]->annee."-".($queries[0]->annee+1) }} </h3>

            <div>
                <ol>
                    <li><h3>On Réussi avec la plus grande distinction</h3>
                        <ul>
                            @foreach ($queries as $item)
                                @if ((($item->coteObtenue*100)/$item->total)<=100 && (($item->coteObtenue*100)/$item->total)>=90 )
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                                @foreach ($queries as $item)
                                                @if ((($item->coteObtenue*100)/$item->total)<=100 && (($item->coteObtenue*100)/$item->total)>=90 )
                                                    <tr>
                                                        <td>{{ $i++; }}</td>
                                                        <td>{{ $item->nom }}</td>
                                                        <td>{{ $item->postnom }}</td>
                                                        <td>{{ $item->prenom }}</td>
                                                        <td>{{ $item->genre }}</td>
                                                        <td>{{ ($item->coteObtenue*100)/$item->total }}</td>
                                                    </tr>
                                                @endif
                                                @endforeach
                                        </tbody>
                                    </table>
                                    </li>
                                @else
                                    @if ($loop->last)

                                        <li class="mb-2 fw-bold">Néant</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li> <h3>On Réussi avec grande distinction</h3>
                        <ul>
                            @foreach ($queries as $item)
                                @if ((($item->coteObtenue*100)/$item->total)<90 && (($item->coteObtenue*100)/$item->total)>=80 )
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                              @foreach ($queries as $item)
                                                @if ((($item->coteObtenue*100)/$item->total)<90 && (($item->coteObtenue*100)/$item->total)>=80 )
                                                    <tr>
                                                        <td>{{ $i++; }}</td>
                                                        <td>{{ $item->nom }}</td>
                                                        <td>{{ $item->postnom }}</td>
                                                        <td>{{ $item->prenom }}</td>
                                                        <td>{{ $item->genre }}</td>
                                                        <td>{{ number_format(($item->coteObtenue*100)/$item->total,2,",",",") }}</td>
                                                    </tr>
                                                @endif
                                             @endforeach
                                           </tbody>
                                        </table>
                                    </li>
                                 @else
                                    @if ($loop->last)

                                        <li class="mb-2 fw-bold">Néant</li>
                                    @endif
                                @endif
                            @endforeach

                        </ul>
                    </li>
                    <li><h3>On Réussi avec distinction</h3>
                        <ul>
                            @foreach ($queries as $item)
                                @if ((($item->coteObtenue*100)/$item->total)< 80 && (($item->coteObtenue*100)/$item->total)>=70 )
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                                @foreach ($queries as $item)
                                                    @if ((($item->coteObtenue*100)/$item->total)<80 && (($item->coteObtenue*100)/$item->total)>=70 )
                                                        <tr>
                                                            <td>{{ $i++; }}</td>
                                                            <td>{{ $item->nom }}</td>
                                                            <td>{{ $item->postnom}}</td>
                                                            <td>{{ $item->prenom }}</td>
                                                            <td>{{ $item->genre }}</td>
                                                            <td>{{ number_format(($item->coteObtenue*100)/$item->total,2,",",",") }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                        </tbody>
                                        </table>
                                    </li>
                                @else
                                    @if ($loop->last)

                                        <li class="mb-2 fw-bold">Néant</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    <li><h3>On Réussi avec satifaction</h3>
                        <ul>
                            @foreach ($queries as $item)
                                @if ((($item->coteObtenue*100)/$item->total)< 70 && (($item->coteObtenue*100)/$item->total)>=50 )
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                                @foreach ($queries as $item)
                                                    @if ((($item->coteObtenue*100)/$item->total)<70 && (($item->coteObtenue*100)/$item->total)>=50 )
                                                        <tr>
                                                            <td>{{ $i++; }}</td>
                                                            <td>{{ $item->nom }}</td>
                                                            <td>{{ $item->postnom }}</td>
                                                            <td>{{ $item->prenom }}</td>
                                                            <td>{{ $item->genre }}</td>
                                                            <td>{{ number_format(($item->coteObtenue*100)/$item->total,2,",",",") }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                        </tbody>
                                        </table>
                                    </li>
                                @else
                                    @if ($loop->last)
                                        <li class="mb-2 fw-bold">Néant</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    <li><h3>Sont ajournés</h3>
                        <ul>
                            @foreach ($queries as $item)
                                @if ((($item->coteObtenue*100)/$item->total)< 50 && (($item->coteObtenue*100)/$item->total)>=40 )
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                                @foreach ($queries as $item)
                                                    @if ((($item->coteObtenue*100)/$item->total)<50 && (($item->coteObtenue*100)/$item->total)>=40 )
                                                        <tr>
                                                            <td>{{ $i++; }}</td>
                                                            <td>{{ $item->nom}}</td>
                                                            <td>{{ $item->postnom }}</td>
                                                            <td>{{ $item->prenom }}</td>
                                                            <td>{{ $item->genre }}</td>
                                                            <td>{{ ($item->coteObtenue*100)/$item->total }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                        </tbody>
                                        </table>
                                    </li>
                                @else
                                    @if ($loop->last)

                                        <li class="mb-2 fw-bold">Néant</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    <li><h3>Sont assimilés aux ajournés</h3>
                        <ul>

                                @if (count($AAA)>1)
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    {{-- <th>Pourcentage</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($AAA as $item)

                                                        <tr>
                                                            <td>{{ $loop->index+1 }}</td>
                                                            <td>{{ $item->nom}}</td>
                                                            <td>{{ $item->postnom }}</td>
                                                            <td>{{ $item->prenom }}</td>
                                                            <td>{{ $item->genre }}</td>
                                                            {{-- <td></td> --}}
                                                        </tr>

                                                @endforeach
                                        </tbody>
                                        </table>
                                    </li>
                                @else
                                    <li class="mb-2 fw-bold">Néant</li>

                                @endif

                        </ul>
                    </li>
                    <li><h3>Sont non admis à la même filière</h3>
                        <ul>
                            @foreach ($queries as $item)
                                @if ((($item->coteObtenue*100)/$item->total)< 40)
                                    <li>
                                        <table>
                                            <thead>

                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom</th>
                                                    <th>Postnom</th>
                                                    <th>Prénom</th>
                                                    <th>Genre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($queries as $item)
                                                    @if ((($item->coteObtenue*100)/$item->total)<40 )
                                                        <tr>
                                                            <td>#</td>
                                                            <td>{{ $item->nom}}</td>
                                                            <td>{{ $item->postnom }}</td>
                                                            <td>{{ $item->prenom }}</td>
                                                            <td>{{ $item->genre }}</td>
                                                            <td>{{ ($item->coteObtenue*100)/$item->total }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                        </tbody>
                                        </table>
                                    </li>
                                @else
                                    @if ($loop->last)

                                        <li class="mb-2 fw-bold">Néant</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>

                </ol>
            </div>


        </div>

@endsection

