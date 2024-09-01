<?php

use App\Models\User;
use App\Models\Section;
use App\Models\Etudiant;
use App\Models\Actualite;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PalmaresController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $Sections=Section::all();
    $Actualites=Actualite::query()->Orderby('id',"desc")->take(5)->get();

    //On vérifie s'il y a un utilisateur authentifié
    if(Auth::user()){
        $Etudiant=Etudiant::where("user_id",Auth()->user()->id)->first();

        $User=User::whereId(Auth()->user()->id)->first();
        return view('welcome',compact('Sections',"Actualites","Etudiant","User"));
    }

    return view('welcome',compact('Sections',"Actualites"));
});

Route::get("coupon_semestre/{clef}/{classe_id}",[CouponController::class,"imprimer"])->name("coupon_semestre");
Route::get("coupon_annuel/{clef}/{classe_id}",[CouponController::class,"imprimerAnnuel"])->name("coupon_annuel");

Route::get("palmares",[PalmaresController::class,"imprimer"])->name("palmares");
Route::get("palmares_annuel",[PalmaresController::class,"imprimerAnnuel"])->name("palmares_annuel");
