<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // ← Add this
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StockHistory;

class StockController extends Controller implements ShouldQueue
{   
    use Queueable, SerializesModels;

    public function stock(Request $request){
        $query = $request->query('q');

        if (!$query) {
            return response()->json([
                'error' => 'Missing required query parameter: q'
            ], 400);
        }   

        $stock = $this->fetchStockInfo($query);

        StockHistory::create([
            'symbol' => $stock['symbol'],
            'name' => $stock['name'] ?? null,
            'open' => $stock['open'] ?? null,
            'high' => $stock['high'] ?? null,
            'low' => $stock['low'] ?? null,
            'close' => $stock['close'] ?? null,
        ]);

        $stockData = [
            "name" => $stock['name'],
            "symbol" => $stock['symbol'],
            "open" => $stock['open'],
            "high" => $stock['high'],
            "low" => $stock['low'],
            "close" => $stock['close']
        ];
        
       $this->sendStockMail($stockData);
        
        return $stockData;
    }

    private function sendStockMail($stockData){
        Mail::raw(json_encode($stockData, JSON_PRETTY_PRINT), function ($message) {
            $message->to('mateuspieri@gmail.com')
                    ->subject('📈 Stock Data: AAPL.US')
                    ->from('hello@example.com', 'StockBot');
        });
    }

    private function fetchStockInfo($symbol){

        $url = "https://api.twelvedata.com/quote";

        try {
            $client = new Client();
            $response = $client->get($url, [
                'query' => [
                    'symbol' => $symbol,
                    'apikey' => env('STOCKS_API_KEY'),
                ]
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            if (isset($data['code']) || isset($data['status']) && $data['status'] === 'error') {
                return [
                    'error' => $data['message'] ?? 'Error fetching stock data',
                ];
            }
    
            return $data;
        } catch (\Exception $e) {
            return [
                'error' => 'Failed to fetch data from Twelve Data.',
                'details' => $e->getMessage(),
            ];
        }
    }

    public function history()
    {
        return response()->json(StockHistory::latest()->get());
    }
}
