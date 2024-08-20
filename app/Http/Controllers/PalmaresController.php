<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class PalmaresController extends Controller
{
    //

    public function imprimer(){
        $classe_id=(int)session("classe_id")[0];
        $semestre_id=(int)session("semestre_id")[0];
        //recherche du total général cours
        $maxima=DB::select("SELECT sum(ponderation*20) as maxima
                            FROM cours
                            Where classe_id=$classe_id AND semestre_id=$semestre_id");
        $max=$maxima[0]->maxima;
        //les étudiants ayant suivis tous les cours
        $queries=DB::select("SELECT nom,postnom,prenom,genre,Sem.lib as semestre,chef_section as chsection,grade as grade,
                sum(C.ponderation*(tj+examenS1)) as coteObtenue,sum(C.ponderation*20) as total,An.lib as annee,Sec.lib as section
                            FROM cours as C
                            JOIN semestres as Sem ON Sem.id=C.semestre_id
                            JOIN elementcoupons as Elcp ON Elcp.cours_id=C.id
                            JOIN coupons Cpon ON Elcp.coupon_id=Cpon.id
                            JOIN etudiants as Etud ON Etud.id=Cpon.etudiant_id
                            JOIN classes as Cl ON Cl.id=Etud.classe_id
                            JOIN juries as J ON J.id=Cl.jury_id
                            JOIN sections as Sec ON Sec.id=J.section_id
                            JOIN annees as An ON An.id=J.annee_id
                            WHERE Cpon.classe_id=$classe_id AND C.semestre_id=$semestre_id
                            Group by nom,postnom,prenom,chef_section,grade,genre,An.lib,Sec.lib
                            Having total=$max");
        // dd($queries);
        //les assimilés aux ajournés
        $AAA=DB::select("SELECT nom,postnom,prenom,genre,etudiant_id
                            FROM etudiants as Etud
                            LEFT JOIN coupons Cpon ON Cpon.etudiant_id=Etud.id
                            JOIN classes as Cl ON Cl.id=Etud.classe_id
                            WHERE Cl.id=$classe_id AND semestre_id IS NULL");

        // dd($AAA);

        if(count($queries) > 0){
            $data=[
                // "title" => 'Etudiants de '.$queries[0]->classe." - ".$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries,
                "AAA"=>$AAA,
            ];

            $pdf = Pdf::loadView('Etats/palmares',$data);
            return $pdf->download('pamares_'.session("classe")[0]."_".$queries[0]->annee."-".($queries[0]->annee+1).'.pdf');
        }else{
            Notification::make()
            ->title('Aucune donnée trouvée!')
            ->danger()
           ->duration(5000)
            ->send();
            return redirect()->back();

        }

    }
}
