<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\OrderItems;
use Carbon\Carbon;
use Excel;

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

    public function todayDownload() {

      // Execute the query used to retrieve the data. In this example
      // we're joining hypothetical users and payments tables, retrieving
      // the payments table's primary key, the user's first and last name,
      // the user's e-mail address, the amount paid, and the payment
      // timestamp.

      $orders = Orders::whereDate('created_at', Carbon::today())->get();
      $sum = Orders::whereDate('created_at', Carbon::today())->sum('netPrice');

      // Initialize the array which will be passed into the Excel
      // generator.
      $ordersArray = [];

      // Define the Excel spreadsheet headers
      $ordersArray[] = ['Medicine Name', 'Quantity', 'Price per Item', 'Discounts', 'Total Price' , 'Date Time Ordered', 'Price Type'];

      // Convert each member of the returned collection into an array,
      // and append it to the payments array.
      foreach($orders as $key => $value) {
        $stocks = $value->items;
        foreach($stocks as $x => $item) {
          $stock = $item->inventory;
          $price = (!$item->isBulk ? $item->pricePerPiece : $item->bulkPrice);
          $priceType = ($item->isBulk ? "BULK PRICE" : "REGULAR PRICE");
          // $orderedDate = date($item->created_at, "M d, Y h:i a");
          $orderedDate = $item->created_at;
          $array = [$stock->medicineName, $item->quantity, $price, $item->discount, $item->totalPrice, $orderedDate, $priceType];
          $ordersArray[] = $array;
        }
        $ordersArray[] = ['TOTAL QUANTITY: ', $value->totalQuantity, 'GLOBAL DISCOUNT: ', $value->globalDiscount, 'GROSS: '.$value->grossPrice, 'NET: '.$value->netPrice, $value->created_at];
        $ordersArray[] = ['','','','','','',''];
      }

      $ordersArray[] = ['','','','','','',''];
      $ordersArray[] = ['','','','','TOTAL SALES:',$sum,''];

      // // Generate and return the spreadsheet
      $fileName = date("m-d-Y-his");
      Excel::create($fileName, function($excel) use ($ordersArray) {
          // Set the spreadsheet title, creator, and description
          $excel->setTitle('Today Sales');
          $excel->setCreator('John Perez')->setCompany('JBL Pharmacy');
          $excel->setDescription('sales today file');

          // Build the spreadsheet, passing in the payments array
          $excel->sheet('sheet1', function($sheet) use ($ordersArray) {
            $sheet->cells('A1:G1', function($cells) {
              $cells->setFontSize(16);
              $cells->setFontWeight('bold');
            });
            $sheet->fromArray($ordersArray, null, 'A1', false, false);
          });

      })->download('xlsx');
    }

    public function weeklyDownload() {
      $fromDate = Carbon::now()->subDay()->startOfWeek()->toDateString();
      $tillDate = Carbon::now()->subDay()->startOfWeek()->addDays(6)->toDateString();
      $orders   = Orders::whereBetween('created_at', [$fromDate, $tillDate])->get();
      $sum      = Orders::whereBetween('created_at', [$fromDate, $tillDate])->sum('netPrice');

      // Initialize the array which will be passed into the Excel
      // generator.
      $ordersArray = [];

      // Define the Excel spreadsheet headers
      $ordersArray[] = ['Medicine Name', 'Quantity', 'Price per Item', 'Discounts', 'Total Price' , 'Date Time Ordered', 'Price Type'];

      // Convert each member of the returned collection into an array,
      // and append it to the payments array.
      foreach($orders as $key => $value) {
        $stocks = $value->items;
        foreach($stocks as $x => $item) {
          $stock = $item->inventory;
          $price = (!$item->isBulk ? $item->pricePerPiece : $item->bulkPrice);
          $priceType = ($item->isBulk ? "BULK PRICE" : "REGULAR PRICE");
          // $orderedDate = date($item->created_at, "M d, Y h:i a");
          $orderedDate = $item->created_at;
          $array = [$stock->medicineName, $item->quantity, $price, $item->discount, $item->totalPrice, $orderedDate, $priceType];
          $ordersArray[] = $array;
        }
        $ordersArray[] = ['TOTAL QUANTITY: ', $value->totalQuantity, 'GLOBAL DISCOUNT: ', $value->globalDiscount, 'GROSS: '.$value->grossPrice, 'NET: '.$value->netPrice, $value->created_at];
        $ordersArray[] = ['','','','','','',''];
      }

      $ordersArray[] = ['','','','','','',''];
      $ordersArray[] = ['','','','','TOTAL SALES:',$sum,''];

      // // Generate and return the spreadsheet
      $fileName = date("M") . " - Weekly Sales - " . date("his");
      Excel::create($fileName, function($excel) use ($ordersArray) {
          // Set the spreadsheet title, creator, and description
          $excel->setTitle('Weekly Sales');
          $excel->setCreator('John Perez')->setCompany('JBL Pharmacy');
          $excel->setDescription('sales today file');

          // Build the spreadsheet, passing in the payments array
          $excel->sheet('sheet1', function($sheet) use ($ordersArray) {
            $sheet->cells('A1:G1', function($cells) {
              $cells->setFontSize(16);
              $cells->setFontWeight('bold');
            });
            $sheet->fromArray($ordersArray, null, 'A1', false, false);
          });

      })->download('xlsx');
    }

    public function monthlyDownload() {
      $firstDayMonth = Carbon::parse('first day of this month')->toDateString();
      $lastDayMonth = Carbon::parse('last day of this month')->toDateString();
      $orders   = Orders::whereBetween('created_at', [$firstDayMonth, $lastDayMonth])->get();
      $sum   = Orders::whereBetween('created_at', [$firstDayMonth, $lastDayMonth])->sum('netPrice');

      // Initialize the array which will be passed into the Excel
      // generator.
      $ordersArray = [];

      // Define the Excel spreadsheet headers
      $ordersArray[] = ['Medicine Name', 'Quantity', 'Price per Item', 'Discounts', 'Total Price' , 'Date Time Ordered', 'Price Type'];

      // Convert each member of the returned collection into an array,
      // and append it to the payments array.
      foreach($orders as $key => $value) {
        $stocks = $value->items;
        foreach($stocks as $x => $item) {
          $stock = $item->inventory;
          $price = (!$item->isBulk ? $item->pricePerPiece : $item->bulkPrice);
          $priceType = ($item->isBulk ? "BULK PRICE" : "REGULAR PRICE");
          // $orderedDate = date($item->created_at, "M d, Y h:i a");
          $orderedDate = $item->created_at;
          $array = [$stock->medicineName, $item->quantity, $price, $item->discount, $item->totalPrice, $orderedDate, $priceType];
          $ordersArray[] = $array;
        }
        $ordersArray[] = ['TOTAL QUANTITY: ', $value->totalQuantity, 'GLOBAL DISCOUNT: ', $value->globalDiscount, 'GROSS: '.$value->grossPrice, 'NET: '.$value->netPrice, $value->created_at];
        $ordersArray[] = ['','','','','','',''];
      }

      $ordersArray[] = ['','','','','','',''];
      $ordersArray[] = ['','','','','TOTAL SALES:',$sum,''];

      // // Generate and return the spreadsheet
      $fileName = date("M") . " - Sales - " . date("his");
      Excel::create($fileName, function($excel) use ($ordersArray) {
          // Set the spreadsheet title, creator, and description
          $excel->setTitle('Monthly Sales');
          $excel->setCreator('John Perez')->setCompany('JBL Pharmacy');
          $excel->setDescription('sales today file');

          // Build the spreadsheet, passing in the payments array
          $excel->sheet('sheet1', function($sheet) use ($ordersArray) {
            $sheet->cells('A1:G1', function($cells) {
              $cells->setFontSize(16);
              $cells->setFontWeight('bold');
            });
            $sheet->fromArray($ordersArray, null, 'A1', false, false);
          });

      })->download('xlsx');
    }

    public function generateDateRange (Request $request) {
      $fromDate = Carbon::parse($request->input('startDate'))->toDateString();
      $tillDate = Carbon::parse($request->input('endDate'))->toDateString();
      $orders   = OrderItems::whereBetween('created_at', [$fromDate." 00:00:00", $tillDate." 23:59:59"])->get();

      $sales = [];
      $sum = 0;
      $totalQty = 0;
      foreach ($orders as $key => $value) {
        $stock = $value->inventory;
        $order = $value->order;
        $totalPrice = (float) $value->totalPrice;
        if ($order->globalDiscount > 0) {
          $totalPrice = $totalPrice - ($totalPrice * ($order->globalDiscount / 100));
        }
        if (!isset($sales[$value->productId])) {
          $sales[$value->productId] = [
            'productName' => $stock->medicineName,
            'quantity'    => (int) $value->quantity,
            'totalPrice'  => (float) $totalPrice
          ];
        } else {
          $sales[$value->productId]['quantity'] += (int) $value->quantity;
          $sales[$value->productId]['totalPrice'] += (float) $totalPrice;
        }
        $sum += $totalPrice;
        $totalQty += $value->quantity;
      }
      return view('reports.tables', compact('sales', 'sum', 'totalQty', 'fromDate', 'tillDate'));
    }

    public function downloadDateRange (Request $request) {
      $fromDate = Carbon::parse($request->input('startDate'))->toDateString();
      $tillDate = Carbon::parse($request->input('endDate'))->toDateString();
      $orders   = OrderItems::whereBetween('created_at', [$fromDate." 00:00:00", $tillDate." 23:59:59"])->get();

      $sales = [];
      $sales[] = ['Product Name', 'Quantity', 'Total'];
      $sum = 0;
      $totalQty = 0;
      foreach ($orders as $key => $value) {
        $stock = $value->inventory;
        $order = $value->order;
        $totalPrice = (float) $value->totalPrice;
        if ($order->globalDiscount > 0) {
          $totalPrice = $totalPrice - ($totalPrice * ($order->globalDiscount / 100));
        }
        if (!isset($sales[$value->productId])) {
          $sales[$value->productId] = [
            $stock->medicineName,
            (int) $value->quantity,
            (float) $totalPrice
          ];
        } else {
          $sales[$value->productId][1] += (int) $value->quantity;
          $sales[$value->productId][2] += (float) $totalPrice;
        }
        $sum += $totalPrice;
        $totalQty += $value->quantity;
      }

      $sales[] = ['TOTAL: ', $totalQty, $sum];

      // // Generate and return the spreadsheet
      $fileName = "Date Range: {$fromDate} - {$tillDate}";
      Excel::create($fileName, function($excel) use ($sales) {
          // Set the spreadsheet title, creator, and description
          $excel->setTitle('Product Summary Sales');
          $excel->setCreator('John Perez')->setCompany('JBL Pharmacy');
          $excel->setDescription('group by product name sales');

          // Build the spreadsheet, passing in the payments array
          $excel->sheet('sheet1', function($sheet) use ($sales) {
            $sheet->cells('A1:C1', function($cells) {
              $cells->setFontSize(16);
              $cells->setFontWeight('bold');
            });
            $sheet->fromArray($sales, null, 'A1', false, false);
          });

      })->download('xlsx');
    }
}
