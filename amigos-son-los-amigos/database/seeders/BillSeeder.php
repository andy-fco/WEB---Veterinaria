<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Bill;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Client::all();

        if ($clientes->isEmpty()) {
            $this->command->error('No hay clientes para crear facturas.');
            return;
        }

        foreach ($clientes as $cliente) {
            Bill::factory()->count(4)->create([
                'id_cliente' => $cliente->id,
            ]);
        }
    }
}
