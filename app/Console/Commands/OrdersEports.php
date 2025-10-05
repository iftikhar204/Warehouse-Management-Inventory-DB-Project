<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportOrders extends Command
{
    protected $signature = 'export:orders {filename=orders.xlsx}';
    protected $description = 'Export all orders to Excel file';

    public function handle()
    {
        $file = $this->argument('filename');

        Excel::store(new OrdersExport, $file, 'local');

        $this->info("Orders exported successfully to storage/app/$file");
    }
}
