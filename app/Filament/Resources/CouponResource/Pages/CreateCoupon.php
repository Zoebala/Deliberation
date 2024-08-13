<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Models\Jury;
use App\Models\Annee;
use Filament\Actions;
use App\Models\Classe;
use App\Filament\Resources\CouponResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ? string
    {
        return "Coupon enregistré avec succès!";
    }

    protected function mutateFormDataBeforeCreate(array $data):array
    {

        $data["semestre_id"]=(int)session("semestre_id")[0] ?? 1;

        $data["classe_id"]=(int)session("classe_id")[0] ?? 1;

        return $data;
    }
}
