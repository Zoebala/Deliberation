<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CouponController extends Controller
{
    //
    public function imprimer($coupon_id,$classe_id){
      
        $queries=DB::select("SELECT Sem.lib as sess,Cpon.created_at as mois ,An.lib as annee, Cl.lib as promo,nom,postnom,prenom,c.lib as cours,(C.ponderation*15) as Nh,(C.ponderation * 20) as Maximum,(C.ponderation*(tj+examenS1)) as Cote,
                (((C.ponderation*(tj+examenS1))*100)/(C.ponderation*20)) as Pourcent,Sec.lib as section,Sec.chef_section as chsection,Sec.grade as grade
                            FROM cours as C
                            JOIN semestres as Sem ON Sem.id=C.semestre_id
                            JOIN elementcoupons as Elcp ON Elcp.cours_id=C.id
                            JOIN coupons Cpon ON Elcp.coupon_id=Cpon.id
                            JOIN etudiants as Etud ON Etud.id=Cpon.etudiant_id
                            JOIN classes as Cl ON Cl.id=Etud.classe_id
                            JOIN juries as J ON J.id=Cl.jury_id
                            JOIN sections as Sec ON Sec.id=J.section_id
                            JOIN annees as An ON An.id=J.annee_id
                            WHERE Cpon.id=$coupon_id AND Cpon.classe_id=$classe_id");
        // dd($queries);



        if(count($queries) > 0){
            $data=[
                // "title" => 'Etudiants de '.$queries[0]->classe." - ".$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            $pdf = Pdf::loadView('Etats/coupon',$data);
            return $pdf->download('coupon'.$queries[0]->nom."_".$queries[0]->postnom.date("d/m/Y H:i:s").'.pdf');
        }else{
            Notification::make()
            ->title('Aucune donnée trouvée!')
            ->danger()
           ->duration(5000)
            ->send();
            return redirect()->back();

        }
    }
    public function imprimerAnnuel($coupon_id,$classe_id){

        $queries=DB::select("SELECT Cpon.created_at as mois ,An.lib as annee, Cl.lib as promo,nom,postnom,prenom,c.lib as cours,(C.ponderation*15) as Nh,(C.ponderation * 20) as Maximum,(C.ponderation*(tj+examenS1)) as Cote,
                (((C.ponderation*(tj+examenS1))*100)/(C.ponderation*20)) as Pourcent,Sec.lib as section,Sec.chef_section as chsection,Sec.grade as grade
                            FROM cours as C
                            JOIN elementcoupons as Elcp ON Elcp.cours_id=C.id
                            JOIN coupons Cpon ON Elcp.coupon_id=Cpon.id
                            JOIN etudiants as Etud ON Etud.id=Cpon.etudiant_id
                            JOIN classes as Cl ON Cl.id=Etud.classe_id
                            JOIN juries as J ON J.id=Cl.jury_id
                            JOIN sections as Sec ON Sec.id=J.section_id
                            JOIN annees as An ON An.id=J.annee_id
                            WHERE Cpon.id=$coupon_id AND Cpon.classe_id=$classe_id");




        if(count($queries) > 0){
            $data=[
                // "title" => 'Etudiants de '.$queries[0]->classe." - ".$queries[0]->Annee,
                "date" => date("d/m/Y"),
                "queries"=> $queries
            ];

            $pdf = Pdf::loadView('Etats/coupon',$data);
            return $pdf->download('coupon_annuel'.$queries[0]->nom."_".$queries[0]->postnom.date("d/m/Y H:i:s").'.pdf');
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
