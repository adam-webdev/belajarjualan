<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use App\Models\AssociationRule;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

// Asumsikan Anda memiliki kelas AprioriEngine atau library di sini
// use Apriori\Apriori; // Contoh: jika Anda menggunakan library Apriori PHP

class AnalyzeAndStoreApriori extends Command
{
    protected $signature = 'apriori:run-and-store';
    protected $description = 'Runs Apriori analysis on order data and stores association rules.';

    public function handle()
    {
        $this->info('Starting Apriori analysis and storage...');

        // 1. Ambil data transaksi dalam format yang dibutuhkan Apriori (array of arrays of product names)
        $orders = Order::with('orderDetails.productCombination.product')->get();

        $transactions = [];
        // Map product name to product ID for efficient lookup when storing
        $productNameToIdMap = Product::pluck('id', 'name')->toArray();

        foreach ($orders as $order) {
            $itemsInTransaction = [];
            foreach ($order->orderDetails as $detail) {
                if ($detail->productCombination && $detail->productCombination->product) {
                    $productName = $detail->productCombination->product->name;
                    $itemsInTransaction[] = $productName;
                }
            }
            if (!empty($itemsInTransaction)) {
                $transactions[] = $itemsInTransaction;
            }
        }

        if (empty($transactions)) {
            $this->warn('No transactions found to analyze. Exiting.');
            return Command::SUCCESS;
        }

        // 2. Jalankan Algoritma Apriori Anda
        // GANTI BAGIAN INI dengan panggilan ke library Apriori Anda
        // Contoh: $apriori = new Apriori(); $apriori->train($transactions, 0.01, 0.5); $rawRules = $apriori->getRules();
        $rawRules = $this->getDummyAprioriRules($transactions); // Menggunakan fungsi dummy untuk demo

        if (empty($rawRules)) {
            $this->info('No association rules found with current thresholds. Exiting.');
            return Command::SUCCESS;
        }

        // 3. Simpan Aturan ke Database dalam sebuah Transaksi
        DB::beginTransaction();
        try {
            // Hapus semua aturan lama sebelum menyimpan yang baru
            AssociationRule::truncate();

            foreach ($rawRules as $rule) {
                // Konversi nama produk di antecedent ke ID produk
                $antecedentProductIds = [];
                foreach ($rule['antecedent'] as $name) {
                    if (isset($productNameToIdMap[$name])) {
                        $antecedentProductIds[] = $productNameToIdMap[$name];
                    }
                }
                // Konversi nama produk di consequent ke ID produk
                $consequentProductIds = [];
                foreach ($rule['consequent'] as $name) {
                    if (isset($productNameToIdMap[$name])) {
                        $consequentProductIds[] = $productNameToIdMap[$name];
                    }
                }

                // Hanya simpan aturan jika antecedent dan consequent memiliki ID produk yang valid
                if (!empty($antecedentProductIds) && !empty($consequentProductIds)) {
                    AssociationRule::create([
                        'antecedent_product_ids' => $antecedentProductIds,
                        'antecedent_names'       => $rule['antecedent'],
                        'consequent_product_ids' => $consequentProductIds,
                        'consequent_names'       => $rule['consequent'],
                        'support'                => $rule['support'],
                        'confidence'             => $rule['confidence'],
                        'lift'                   => $rule['lift'] ?? 0.0, // Pastikan lift ada
                    ]);
                }
            }

            DB::commit(); // Commit transaksi jika berhasil
            $this->info('Apriori analysis completed and ' . count($rawRules) . ' rules stored successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            $this->error('An error occurred during Apriori analysis or storage: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Dummy function to simulate Apriori output.
     * In a real scenario, you would integrate your Apriori algorithm here.
     *
     * @param array $transactions
     * @return array
     */
    private function getDummyAprioriRules(array $transactions): array
    {
        // PENTING: Ganti ini dengan implementasi Apriori Anda yang sebenarnya!
        // Ini hanya untuk tujuan demonstrasi.
        return [
            [
                'antecedent' => ['Roti Gandum'],
                'consequent' => ['Telur Ayam'],
                'support' => 0.6,
                'confidence' => 0.8,
                'lift' => 1.5,
            ],
            [
                'antecedent' => ['Susu Full Cream'],
                'consequent' => ['Roti Gandum'],
                'support' => 0.5,
                'confidence' => 0.7,
                'lift' => 1.2,
            ],
            [
                'antecedent' => ['Baju Kaos'],
                'consequent' => ['Celana Jeans'],
                'support' => 0.4,
                'confidence' => 0.75,
                'lift' => 1.8,
            ],
            [
                'antecedent' => ['Telur Ayam'],
                'consequent' => ['Gula Pasir'],
                'support' => 0.3,
                'confidence' => 0.6,
                'lift' => 1.1,
            ],
            [
                'antecedent' => ['Laptop'],
                'consequent' => ['Mouse'],
                'support' => 0.3,
                'confidence' => 0.9,
                'lift' => 2.0,
            ],
        ];
    }
}