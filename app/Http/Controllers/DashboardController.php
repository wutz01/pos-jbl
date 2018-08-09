<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct () {
    }

    public function index () {
      // today
      $todaySales = Orders::whereDate('created_at', Carbon::today())->sum('netPrice');
      //  weekly
      $fromDate = Carbon::now()->subDay()->startOfWeek()->toDateString();
      $tillDate = Carbon::now()->subDay()->startOfWeek()->addDays(6)->toDateString();
      // monthly
      $firstDayMonth = Carbon::parse('first day of this month')->toDateString();
      $lastDayMonth = Carbon::parse('last day of this month')->toDateString();

      $weeklySales = Orders::whereBetween('created_at', [$fromDate, $tillDate])->sum('netPrice');
      $monthlySales = Orders::whereBetween('created_at', [$firstDayMonth, $lastDayMonth])->sum('netPrice');

      // yearly
      $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

      $yearlySales = [];
      $grossSales = [];
      foreach ($months as $key => $value) {
        $yearToday = date('Y');
        $start = Carbon::parse("first day of {$value} {$yearToday}")->toDateString();
        $end = Carbon::parse("last day of {$value} {$yearToday}")->toDateString();
        $sales = Orders::whereBetween('created_at', [$start, $end])->sum('netPrice');
        $gsales = Orders::whereBetween('created_at', [$start, $end])->sum('grossPrice');
        $yearlySales[] = (float) $sales;
        $grossSales[] = (float) $gsales;
      }
      $yearlySales = json_encode($yearlySales);
      $grossSales = json_encode($grossSales);
      return view('dashboard', compact('todaySales', 'weeklySales', 'monthlySales', 'yearlySales', 'grossSales'));
    }

    public function reportsIndex () {
      // today
      $todaySales = Orders::whereDate('created_at', Carbon::today())->sum('netPrice');
      //  weekly
      $fromDate = Carbon::now()->subDay()->startOfWeek()->toDateString();
      $tillDate = Carbon::now()->subDay()->startOfWeek()->addDays(6)->toDateString();
      // monthly
      $firstDayMonth = Carbon::parse('first day of this month')->toDateString();
      $lastDayMonth = Carbon::parse('last day of this month')->toDateString();

      $weeklySales = Orders::whereBetween('created_at', [$fromDate, $tillDate])->sum('netPrice');
      $monthlySales = Orders::whereBetween('created_at', [$firstDayMonth, $lastDayMonth])->sum('netPrice');

      return view('reports.index', compact('todaySales', 'weeklySales', 'monthlySales'));
    }
}
