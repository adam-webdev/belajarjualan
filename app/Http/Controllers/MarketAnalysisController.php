<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Phpml\Association\Apriori;

class MarketAnalysisController extends Controller
{
    public function aprioriAnalysis()
    {

        $transactions = (new Order())->data_transaksi();
        // 📊 Langkah 1: Siapkan Data Transaksi
        // Setiap array di dalam adalah satu keranjang belanja.
        $samples = [
            ['susu', 'roti', 'selai'],
            ['susu', 'popok', 'bir', 'telur'],
            ['susu', 'roti', 'popok', 'selai'],
            ['roti', 'kopi', 'gula'],
            ['roti', 'susu', 'selai'],
            ['kopi', 'gula', 'teh'],
            ['roti', 'susu', 'popok']
        ];

        // Label tidak wajib untuk Apriori, bisa diisi array kosong.
        $labels  = [];

        // ⚙️ Langkah 2: Konfigurasi dan Latih Model
        // $support: Seberapa sering itemset muncul dalam data. Contoh: 0.4 = 40%
        // $confidence: Seberapa sering aturan terbukti benar. Contoh: 0.6 = 60%
        $associator = new Apriori($support = 0.1, $confidence = 0.4);
        $associator->train($transactions, $labels);

        // 🚀 Langkah 3: Dapatkan Hasil Aturan Asosiasi
        $rules = $associator->getRules();
        // ✅ Ambil Frequent Itemsets dan Rules
        $frequentItemsets = $associator->apriori();
        // 💡 Langkah 4: Kirim hasil ke View
        return view('analysis_result', ['rules' => $rules, 'frequentItemsets' => $frequentItemsets]);
    }
}