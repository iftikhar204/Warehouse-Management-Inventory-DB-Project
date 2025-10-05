<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facades\Pdf;

class DashboardController extends Controller
{
    // public function home(){
    //     return view('home');
    // }
    public function index(Request $request)
    {
        $period = $request->query('period', 'all_time');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::now();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = null;
                $endDate = null;
                break;
        }

        $totalWarehouses = Warehouse::count();

        // Calculate total maximum capacity from all warehouses
        $totalMaxCapacity = Warehouse::sum('Max_Capacity');

        // Calculate total occupied capacity from all stock quantities
        // Assuming 'stocks' is the table name and 'quantity' is the column in the Stock model
        $totalOccupiedCapacity = Stock::sum('quantity');

        // Calculate available capacity
        $availableCapacity = $totalMaxCapacity - $totalOccupiedCapacity;
        // Ensure available capacity doesn't go below zero if total occupied exceeds max capacity
        if ($availableCapacity < 0) {
            $availableCapacity = 0;
        }

        $activeProductsQuery = Product::query()->where('status', 'active');
        if ($startDate && $endDate) {
            $activeProductsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $activeProducts = $activeProductsQuery->count();

        $pendingOrdersQuery = Order::query()->where('status', 'pending');
        if ($startDate && $endDate) {
            $pendingOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $pendingOrders = $pendingOrdersQuery->count(); // This now directly serves as pending shipments

        $monthlyData = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')->pluck('count', 'month')->toArray();

        $monthlyOrderLabels = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->format('M');
        });

        $monthlyOrderCounts = $monthlyOrderLabels->map(function ($label, $index) use ($monthlyData) {
            return $monthlyData[$index + 1] ?? 0;
        });

        $inventoryAlertsQuery = Product::with(['stocks.subsection.warehouseSections.warehouses'])
            ->withCount(['stocks as stock_quantity' => function ($query) {
                $query->select(DB::raw("SUM(quantity)"));
            }]);

        $inventoryAlerts = $inventoryAlertsQuery
            ->having('stock_quantity', '<', 10)
            ->get();
        $lowStockItems = $inventoryAlerts->count();

        $recentOrdersQuery = Order::query();
        if ($startDate && $endDate) {
            $recentOrdersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $recentOrders = $recentOrdersQuery->with([
            'incomingOrderItems.supplier',
            'outgoingOrderItems.distributor'
        ])
            ->latest()
            ->take(5)
            ->get();

        if ($request->has('export')) {
            $exportType = $request->query('export');

            $ordersForExport = Order::with([
                'incomingOrderItems.supplier',
                'outgoingOrderItems.distributor'
            ]);
            if ($startDate && $endDate) {
                $ordersForExport->whereBetween('created_at', [$startDate, $endDate]);
            }

            $ordersData = $ordersForExport->get();

            if ($exportType === 'excel') {
                return Excel::download(new OrdersExport($ordersData), 'orders.xlsx');
            }
        }

        return view('home', compact(
            'totalWarehouses',
            'availableCapacity', // Pass the newly calculated availableCapacity
            'activeProducts',
            'pendingOrders', // pendingOrders is used for pendingShipments in the blade
            'lowStockItems',
            'inventoryAlerts',
            'recentOrders',
            'monthlyOrderLabels',
            'monthlyOrderCounts',
            'period'
        ));
    }
}
