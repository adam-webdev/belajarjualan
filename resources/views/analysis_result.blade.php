<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis Apriori</title>
    <style>
        body { font-family: sans-serif; margin: 20px 40px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2 { color: #333; }
        .badge { background-color: #007bff; color: white; padding: 4px 8px; border-radius: 4px; margin-right: 5px; font-size: 0.9em; display: inline-block; margin-bottom: 4px;}
    </style>
</head>
<body>

    <h1>🚀 Hasil Analisis Keranjang Belanja (Apriori)</h1>

    <h2>Aturan Asosiasi (Rules)</h2>
    @if(empty($rules))
        <p>Tidak ada aturan asosiasi yang ditemukan.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Aturan (Jika Beli Ini...)</th>
                    <th>Maka Kemungkinan Beli Ini</th>
                    <th>Support</th>
                    <th>Confidence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rules as $rule)
                    <tr>
                        <td>
                            @if(is_array($rule['antecedent']))
                                @foreach($rule['antecedent'] as $item)
                                    <span class="badge">{{ is_string($item) ? $item : json_encode($item) }}</span>
                                @endforeach
                            @else
                                <span class="badge">{{ is_string($rule['antecedent']) ? $rule['antecedent'] : json_encode($rule['antecedent']) }}</span>
                            @endif
                        </td>
                        <td>
                            @if(is_array($rule['consequent']))
                                @foreach($rule['consequent'] as $item)
                                    <span class="badge" style="background-color: #28a745;">{{ is_string($item) ? $item : json_encode($item) }}</span>
                                @endforeach
                            @else
                                <span class="badge" style="background-color: #28a745;">{{ is_string($rule['consequent']) ? $rule['consequent'] : json_encode($rule['consequent']) }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($rule['support'], 2) }}</td>
                        <td>{{ number_format($rule['confidence'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <h2>📊 Daftar Frequent Itemsets</h2>
    <p>Kombinasi item yang sering muncul bersama.</p>

    @if(empty($frequentItemsets))
        <p>Tidak ada frequent itemsets yang ditemukan.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Itemset</th>
                </tr>
            </thead>
            <tbody>
                @foreach($frequentItemsets as $itemset)
                    <tr>
                        <td>
                            @if(is_array($itemset))
                                @foreach($itemset as $item)
                                    <span class="badge">{{ is_string($item) ? $item : json_encode($item) }}</span>
                                @endforeach
                            @else
                                <span class="badge">{{ is_string($itemset) ? $itemset : json_encode($itemset) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>