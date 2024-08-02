<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*----------------------------------------------------------------
                Définition des rôles
        -----------------------------------------------------------------*/
        DB::table("roles")->insert(
            [
                [
                    "name"=>"Admin",
                    "guard_name"=>"web"

                ],
                [
                    "name"=>"Jury",
                    "guard_name"=>"web"

                ],
                [
                    "name"=>"Etudiant",
                    "guard_name"=>"web"

                ],
            ]);

        // /*----------------------------------------------------------------
        //         Définition de l'administrateur
        // -----------------------------------------------------------------*/
        $User=User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);


        $User->assignRole("Admin");
        /*----------------------------------------------------------------
            Définition des permissions
        -----------------------------------------------------------------*/
          DB::table("permissions")->insert(
            [
                [
                    "name"=>"Create Annees",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Sections",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Semestres",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Juries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Membrejuries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Classes",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Cours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Etudiants",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Coupons",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Recours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Create Users",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Annees",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Sections",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Semestres",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Juries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Membrejuries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Classes",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Cours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Etudiants",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Coupons",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Recours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"ViewAny Users",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Annees",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Sections",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Semestres",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Juries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Membrejuries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Classes",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Cours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Etudiants",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Coupons",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Recours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"View Users",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Annees",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Sections",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Semestres",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Juries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Membrejuries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Classes",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Cours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Etudiants",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Coupons",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Recours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Update Users",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Annees",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Sections",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Semestres",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Juries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Membrejuries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Classes",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Cours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Etudiants",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Coupons",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Recours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"Delete Users",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Annees",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Sections",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Semestres",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Juries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Membrejuries",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Classes",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Cours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Etudiants",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Coupons",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Recours",
                    "guard_name"=>"web",
                ],
                [
                    "name"=>"DeleteAny Users",
                    "guard_name"=>"web",
                ],
            ]
          );
         /*----------------------------------------------------------------
                Attribution des permissions à des rôles
         -----------------------------------------------------------------*/

         $Jury=Role::findByName("Jury");
         $Jury->givePermissionTo([
                 "ViewAny Annees",
                 "ViewAny Semestres",
                 "ViewAny Classes",
                 "ViewAny Cours",
                 "ViewAny Etudiants",
                 "ViewAny Coupons",
                 "ViewAny Recours",
                 "Create Semestres",
                 "Create Classes",
                 "Create Cours",
                 "Create Etudiants",
                 "Create Coupons",
                 "Delete Semestres",
                 "Delete Classes",
                 "Delete Cours",
                 "Delete Etudiants",
                 "Delete Coupons",
                 "Update Semestres",
                 "Update Classes",
                 "Update Cours",
                 "Update Etudiants",
                 "Update Coupons",
                 "DeleteAny Semestres",
                 "DeleteAny Classes",
                 "DeleteAny Cours",
                 "DeleteAny Etudiants",
                 "DeleteAny Coupons",
                 "ViewAny Users",
                 "Update Users",

         ]);

         $Etudiant=Role::findByName('Etudiant');
         $Etudiant->givePermissionTo([
            "ViewAny Annees",
            "ViewAny Coupons",
            "ViewAny Recours",
            "Create Recours",
            "Delete Recours",
            "Update Recours",
            "ViewAny Users",
            "Update Users",
         ]);
    }
}
