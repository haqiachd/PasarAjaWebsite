<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Informasi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home() {
    // Mengambil data dari model Informasi
    $dataInformasi = Informasi::get();
    
    // Mengambil data dari model Event
    $dataEvent = Event::get();
    
    // Mengambil data promosi menggunakan method getPromos
    $listPromo = $this->getPromos();
    
    // Mendapatkan data dari listPromo
    $data = $listPromo->getData()->data;
    
    // Mengirimkan data ke view 'home' menggunakan compact
    return view('home', compact('dataInformasi', 'dataEvent', 'data'));
}
public function getPromos()
    {
        $allPromos = [];

        $allShop = DB::table('0shops')->select('id_shop')->get();

        foreach ($allShop as $shop) {
            $idShop = $shop->id_shop;
            $tableProd = 'sp_' . $idShop . '_prod';
            $tablePromo = 'sp_' . $idShop . '_promo';

            // Ambil tanggal saat ini
            $currentDate = Carbon::now()->toDateString();

            $promos = DB::table(DB::raw("$tablePromo as prm"))
                ->join(DB::raw("$tableProd as prod"), "prod.id_product", "prm.id_product")
                ->join('0product_categories as ctg', 'ctg.id_cp_prod', 'prod.id_cp_prod')
                ->select("prm.*", "prod.id_shop", "prod.product_name", "prod.id_cp_prod", "ctg.category_name", "prod.price", "prod.photo")
                ->where('prm.start_date', '<=', $currentDate)
                ->where('prm.end_date', '>=', $currentDate)
                ->orderByDesc('prm.end_date')
                ->get();

            // cek apakah $promos atau tidak
            if (!$promos->isEmpty()) {
                // add photo path
                foreach ($promos as $prm) {
                    $prm->product_name = ucwords($prm->product_name);
                    $prm->photo = asset('prods/' . $prm->photo);
                }
            }

            // tambahkan $promo ke $allPromos
            $allPromos = array_merge($allPromos, $promos->toArray());
        }

        return response()->json(['status' => 'success', 'message' => 'Data didapatkan', 'data' => $allPromos], 200);
    }

   
}