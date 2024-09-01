@extends("layouts.master")
@section("contenu")


        <div class="tableau">
            <hr style="border:1px dashed black">
            <h3 class="text-center fw-bold">Relevés des matières et des cotes n°.... /20... </h3>
            <h6 class="text-start fst-italic">le chef de section {{ $queries[0]->section }} soussigné, atteste par la présente que : </h6>
            <div>
                <p>

                    L'Etudiant : <span class="fw-bold">{{ $queries[0]->nom." ".$queries[0]->postnom." ".$queries[0]->prenom }}</span> <br>
                    Promotion : <span class="fw-bold">{{ $queries[0]->promo }}</span> <br>
                    <?php
                         $date1=new DATETIME($queries[0]->mois);
                         $formatter=new IntlDateFormatter('fr-FR',IntlDateFormatter::LONG,IntlDateFormatter::NONE,'Africa/Kinshasa',IntlDateFormatter::GREGORIAN,'MMMM');
                    ?>
                    <span class="fst-italic">
                        A suivi régulièrement pendant l'année Académique {{ $queries[0]->annee."-".($queries[0]->annee+1) }} Les cours portant sur les
                        matières ci-dessous et obtenu les cotes suivantes à la @if(isset($queries[0]->sess)){{ $queries[0]->sess." ".$formatter->format($date1)." ". $queries[0]->annee  }}@else{{ "fin de l'année ".session("Annee")[0]."-".session("Annee")[0]+1 }}@endif :
                    </span>
                 </p>
            </div>
            <table class="table table-striped">
                <thead>
                    <th>N°</th>
                    <th>Intitulés des cours</th>
                    <th>Nombre d'heures</th>
                    <th>Maximum</th>
                    <th>Cotes Obtenues</th>
                    <th>%</th>

                </thead>
                <tbody>
                    <?php $total=0; $totalobtenue=0; ?>
                    @foreach ($queries as $query)

                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{ $query->cours }}</td>
                            <td class="text-end">{{ $query->Nh }}</td>
                            <td class="text-end">{{ $query->Maximum }}</td>
                            <td class="text-end">{{ $query->Cote }}</td>
                            <td>{{ $query->Pourcent }}</td>
                        </tr>
                     <?php $total +=$query->Maximum; $totalobtenue += $query->Cote;  ?>
                    @endforeach
                        <?php $pourcent=($totalobtenue*100)/$total;  ?>
                        <tr>

                            <td colspan="3" class="text-end">Total</td>
                            <td class="text-end">{{ $total }}</td>
                            <td class="text-end">{{ $totalobtenue }}</td>
                            <td class="text-start">{{ number_format($pourcent,2,',',',');  }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Pourcentage</td>
                            <td colspan="3" class="text-center">{{ number_format($pourcent,2,',',',');  }}</td>

                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Mention</td>
                            <td colspan="3" class="text-center">
                                @if($pourcent >=90 && $pourcent <100 )
                                    {{ "La plus Grande Distinction" }}
                                @elseif($pourcent <90 && $pourcent >=80)
                                    {{ "Grande Distinction" }}
                                @elseif($pourcent < 80 && $pourcent >=70)
                                    {{ "Distinction" }}
                                @elseif($pourcent < 70 && $pourcent >= 50)
                                    {{ "Satisfaction" }}
                                @elseif($pourcent < 50 && $pourcent >= 40)
                                    {{ "Ajourné" }}
                                @else
                                    {{ "NAF" }}
                                @endif
                            </td>

                        </tr>
                </tbody>

            </table>

        </div>

@endsection

