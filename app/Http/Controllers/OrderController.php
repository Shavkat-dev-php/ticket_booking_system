<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'event_date' => 'required|date',
            'ticket_adult_price' => 'required|integer',
            'ticket_adult_quantity' => 'required|integer',
            'ticket_kid_price' => 'required|integer',
            'ticket_kid_quantity' => 'required|integer',
        ]);

        try {

            $barcode = $this->generateUniqueBarcode();
            $response = Http::post('https://api.site.com/book', $request->all() + ['barcode' => $barcode]);

            $attempts = 0;
            while ($response->json()['error'] == 'barcode already exists' && $attempts < 5) {
                $barcode = $this->generateUniqueBarcode();
                $response = Http::post('https://api.site.com/book', $request->all() + ['barcode' => $barcode]);
                $attempts++;
            }

            if ($attempts >= 5) {
                return response()->json(['error' => 'Failed to generate a unique barcode'], 500);
            }

            $approvalResponse = Http::post('https://api.site.com/approve', ['barcode' => $barcode]);

            if ($approvalResponse->json()['message'] == 'order successfully approved') {
                $equal_price = ($request->ticket_adult_price * $request->ticket_adult_quantity) +
                    ($request->ticket_kid_price * $request->ticket_kid_quantity);

                $order = Order::create([
                    'event_id' => $request->event_id,
                    'event_date' => $request->event_date,
                    'ticket_adult_price' => $request->ticket_adult_price,
                    'ticket_adult_quantity' => $request->ticket_adult_quantity,
                    'ticket_kid_price' => $request->ticket_kid_price,
                    'ticket_kid_quantity' => $request->ticket_kid_quantity,
                    'barcode' => $barcode,
                    'equal_price' => $equal_price,
                ]);

                $this->createTickets($order->id, $request->ticket_adult_price, $request->ticket_adult_quantity, $request->ticket_kid_price, $request->ticket_kid_quantity);
            } else {
                return response()->json(['error' => $approvalResponse->json()['error']], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Order successfully created'], 201);
    }

    private function generateUniqueBarcode()
    {
        do {
            $barcode = substr(str_shuffle("0123456789"), 0, 8);
        } while (Order::where('barcode', $barcode)->exists() || Ticket::where('barcode', $barcode)->exists());

        return $barcode;
    }

    private function createTickets($order_id, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity)
    {
        $tickets = [];

        foreach (['adult' => $ticket_adult_quantity, 'kid' => $ticket_kid_quantity] as $type => $quantity) {
            for ($i = 0; $i < $quantity; $i++) {
                $tickets[] = [
                    'order_id' => $order_id,
                    'ticket_type' => $type,
                    'ticket_price' => $type == 'adult' ? $ticket_adult_price : $ticket_kid_price,
                    'barcode' => $this->generateUniqueBarcode(),
                    'ticket_quantity' => 1,
                ];
            }
        }

        Ticket::insert($tickets);
    }
}
