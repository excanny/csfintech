<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapricornDataPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        // MTN Plans
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "20MB for 1day for Daily",
            "allowance" => "20MB for 1day",
            "price" => "25",
            "validity" => "Daily",
            "datacode" => "25"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "50MB for 7days for Weekly",
            "allowance" => "50MB for 7days",
            "price" => "50",
            "validity" => "Weekly",
            "datacode" => "50"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "100MB Daily for Daily",
            "allowance" => "100MB Daily",
            "price" => "100",
            "validity" => "Daily",
            "datacode" => "100"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "160MB for 30days for Monthly",
            "allowance" => "160MB for 30days",
            "price" => "150",
            "validity" => "Monthly",
            "datacode" => "150"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "200MB 2-Day Plan for Daily",
            "allowance" => "200MB 2-Day Plan",
            "price" => "200",
            "validity" => "Daily",
            "datacode" => "200"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "350MB for Weekly",
            "allowance" => "350MB",
            "price" => "300",
            "validity" => "Weekly",
            "datacode" => "300"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "750MB 2-Week Plan for Weekly",
            "allowance" => "750MB 2-Week Plan",
            "price" => "500",
            "validity" => "Weekly",
            "datacode" => "500"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "1.5GB 1-Month Mobile for Monthly",
            "allowance" => "1.5GB 1-Month Mobile",
            "price" => "1000",
            "validity" => "Monthly",
            "datacode" => "1000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "2GB Monthly for Monthly",
            "allowance" => "2GB Monthly",
            "price" => "1200",
            "validity" => "Monthly",
            "datacode" => "1200"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "6GB for Weekly",
            "allowance" => "6GB",
            "price" => "1500",
            "validity" => "Weekly",
            "datacode" => "1500"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "4.5GB 1-Month All Day plan for Monthly",
            "allowance" => "4.5GB 1-Month All Day plan",
            "price" => "2000",
            "validity" => "Monthly",
            "datacode" => "2000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "6GB for Monthly",
            "allowance" => "6GB",
            "price" => "2500",
            "validity" => "Monthly",
            "datacode" => "2500"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "8GB for Monthly",
            "allowance" => "8GB",
            "price" => "3000",
            "validity" => "Monthly",
            "datacode" => "3000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "10GB for Monthly",
            "allowance" => "10GB",
            "price" => "3500",
            "validity" => "Monthly",
            "datacode" => "3500"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "15GB for Monthly",
            "allowance" => "15GB",
            "price" => "5000",
            "validity" => "Monthly",
            "datacode" => "5000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "30GB for 60Days",
            "allowance" => "30GB",
            "price" => "8000",
            "validity" => "60Days",
            "datacode" => "8000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "40GB for Monthly",
            "allowance" => "40GB",
            "price" => "10000",
            "validity" => "Monthly",
            "datacode" => "10000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "75GB for Monthly",
            "allowance" => "75GB",
            "price" => "15000",
            "validity" => "Monthly",
            "datacode" => "15000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "75GB for 60Days",
            "allowance" => "75GB",
            "price" => "20000",
            "validity" => "60Days",
            "datacode" => "20000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "120GB for 60Days",
            "allowance" => "120GB",
            "price" => "30000",
            "validity" => "60Days",
            "datacode" => "30000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "150GB for 90Days",
            "allowance" => "150GB",
            "price" => "50000",
            "validity" => "90Days",
            "datacode" => "50000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "250GB for 90Days",
            "allowance" => "250GB",
            "price" => "75000",
            "validity" => "90Days",
            "datacode" => "75000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "1TB for 1-Year",
            "allowance" => "1TB",
            "price" => "100000",
            "validity" => "1-Year",
            "datacode" => "100000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "400GB for 1-Year",
            "allowance" => "400GB",
            "price" => "120000",
            "validity" => "1-Year",
            "datacode" => "120000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "1000GB for 1-Year",
            "allowance" => "1000GB",
            "price" => "250000",
            "validity" => "1-Year",
            "datacode" => "250000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$mtn,
            "name" => "2000GB for 1-Year",
            "allowance" => "2000GB",
            "price" => "450000",
            "validity" => "1-Year",
            "datacode" => "450000"
        ]);



        // Airtel Plans
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "40MB for 1 Day",
            "allowance" => "40MB",
            "price" => "50",
            "validity" => "1 Day",
            "datacode" => "49.99"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "100MB for 1 Day",
            "allowance" => "100MB",
            "price" => "100",
            "validity" => "1 Day",
            "datacode" => "99"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "200MB for 3 Days",
            "allowance" => "200MB",
            "price" => "200",
            "validity" => "3 Days",
            "datacode" => "199.03"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "350MB for 7 Days",
            "allowance" => "350MB",
            "price" => "300",
            "validity" => "7 Days",
            "datacode" => "299.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "1GB for 1 Day",
            "allowance" => "1GB",
            "price" => "300",
            "validity" => "1 Day",
            "datacode" => "299.03"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "750MB for 14 Days",
            "allowance" => "750MB",
            "price" => "500",
            "validity" => "14 Days",
            "datacode" => "499"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "2GB for 2 Days",
            "allowance" => "2GB",
            "price" => "500",
            "validity" => "2 Days",
            "datacode" => "499.03"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "1.5GB for 30 Days",
            "allowance" => "1.5GB",
            "price" => "1000",
            "validity" => "30 Days",
            "datacode" => "999"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "2GB for 30 Days",
            "allowance" => "2GB",
            "price" => "1200",
            "validity" => "30 Days",
            "datacode" => "1199"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "6GB for 7 Days",
            "allowance" => "6GB",
            "price" => "1500",
            "validity" => "7 Days",
            "datacode" => "1499.03"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "3GB for 30 Days",
            "allowance" => "3GB",
            "price" => "1500",
            "validity" => "30 Days",
            "datacode" => "1499.01"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "4.5GB for 30 Days",
            "allowance" => "4.5GB",
            "price" => "2000",
            "validity" => "30 Days",
            "datacode" => "1999"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "6GB for 30 Days",
            "allowance" => "6GB",
            "price" => "2500",
            "validity" => "30 Days",
            "datacode" => "2499.01"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "8GB for 30 Days",
            "allowance" => "8GB",
            "price" => "3000",
            "validity" => "30 Days",
            "datacode" => "2999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "11GB for 30 Days",
            "allowance" => "11GB",
            "price" => "4000",
            "validity" => "30 Days",
            "datacode" => "3999.01"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "15GB for 30 Days",
            "allowance" => "15GB",
            "price" => "5000",
            "validity" => "30 Days",
            "datacode" => "4999"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "25GB for 30 Days",
            "allowance" => "25GB",
            "price" => "8000",
            "validity" => "30 Days",
            "datacode" => "7999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "40GB for 30 Days",
            "allowance" => "40GB",
            "price" => "10000",
            "validity" => "30 Days",
            "datacode" => "9999"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "75GB for 30 Days",
            "allowance" => "75GB",
            "price" => "15000",
            "validity" => "30 Days",
            "datacode" => "14999"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "120GB for 30 Days",
            "allowance" => "120GB",
            "price" => "20000",
            "validity" => "30 Days",
            "datacode" => "19999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "200GB for 30 Days",
            "allowance" => "200GB",
            "price" => "30000",
            "validity" => "30 Days",
            "datacode" => "29999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "280GB for 30 Days",
            "allowance" => "280GB",
            "price" => "36000",
            "validity" => "30 Days",
            "datacode" => "35999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "400GB for 90 Days",
            "allowance" => "400GB",
            "price" => "50000",
            "validity" => "90 Days",
            "datacode" => "49999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "500GB for 120 Days",
            "allowance" => "500GB",
            "price" => "60000",
            "validity" => "120 Days",
            "datacode" => "59999.02"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$airtel,
            "name" => "1TB for 365 Days",
            "allowance" => "1TB",
            "price" => "100000",
            "validity" => "365 Days",
            "datacode" => "99999.02"
        ]);


        // GLO Bundles
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "10MB for 1 day",
            "allowance" => "10MB",
            "price" => "25",
            "validity" => "1 day",
            "datacode" => "DATA-32"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "250MB for 1 day",
            "allowance" => "250MB",
            "price" => "25",
            "validity" => "1 day",
            "datacode" => "DATA-15"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "15MB for 1 day",
            "allowance" => "15MB",
            "price" => "50",
            "validity" => "1 day",
            "datacode" => "DATA-18"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "500MB for 1 day",
            "allowance" => "500MB",
            "price" => "50",
            "validity" => "1 day",
            "datacode" => "DATA-30"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "35MB for 1 day",
            "allowance" => "35MB",
            "price" => "100",
            "validity" => "1 day",
            "datacode" => "DATA-21"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "1GB for 5 days",
            "allowance" => "1GB",
            "price" => "100",
            "validity" => "5 days",
            "datacode" => "DATA-31"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "350MB for 5 days",
            "allowance" => "350MB",
            "price" => "200",
            "validity" => "5 days",
            "datacode" => "DATA-28"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "1.25GB for 1 day",
            "allowance" => "1.25GB",
            "price" => "200",
            "validity" => "1 day",
            "datacode" => "DATA-37"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "800MB for 7 days",
            "allowance" => "800MB",
            "price" => "500",
            "validity" => "7 days",
            "datacode" => "DATA-27"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "1.6GB for 30 days",
            "allowance" => "1.6GB",
            "price" => "1000",
            "validity" => "30 days",
            "datacode" => "DATA-2"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "7GB for 7 days",
            "allowance" => "7GB",
            "price" => "1500",
            "validity" => "7 days",
            "datacode" => "DATA-24"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "5.8GB for 30 days",
            "allowance" => "5.8GB",
            "price" => "2000",
            "validity" => "30 days",
            "datacode" => "DATA-25"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "7.7GB for 30 days",
            "allowance" => "7.7GB",
            "price" => "2500",
            "validity" => "30 days",
            "datacode" => "DATA-19"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "10GB for 30 days",
            "allowance" => "10GB",
            "price" => "3000",
            "validity" => "30 days",
            "datacode" => "DATA-23"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "13.25GB for 30 days",
            "allowance" => "13.25GB",
            "price" => "4000",
            "validity" => "30 days",
            "datacode" => "DATA-12"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "18.25GB for 30 days",
            "allowance" => "18.25GB",
            "price" => "5000",
            "validity" => "30 days",
            "datacode" => "DATA-5"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "29.5GB for 30 days",
            "allowance" => "29.5GB",
            "price" => "8000",
            "validity" => "30 days",
            "datacode" => "DATA-4"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "50GB for 30 days",
            "allowance" => "50GB",
            "price" => "10000",
            "validity" => "30 days",
            "datacode" => "DATA-10"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "93GB for 30 days",
            "allowance" => "93GB",
            "price" => "15000",
            "validity" => "30 days",
            "datacode" => "DATA-11"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "119GB for 30 days",
            "allowance" => "119GB",
            "price" => "18000",
            "validity" => "30 days",
            "datacode" => "DATA-20"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "138GB for 30 days",
            "allowance" => "138GB",
            "price" => "20000",
            "validity" => "30 days",
            "datacode" => "DATA-33"
        ]);
//        array_push($data, [
//            "service_type" => \App\Model\CapricornDataPlan::$glo,
//            "name" => "225GB for 30 days",
//            "allowance" => "225GB",
//            "price" => "30000",
//            "validity" => "30 days",
//            "datacode" => "DATA-64"
//        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "225GB for 30 days",
            "allowance" => "225GB",
            "price" => "30000",
            "validity" => "30 days",
            "datacode" => "DATA-434"
        ]);
//        array_push($data, [
//            "service_type" => \App\Model\CapricornDataPlan::$glo,
//            "name" => "300GB for 30 days",
//            "allowance" => "300GB",
//            "price" => "36000",
//            "validity" => "30 days",
//            "datacode" => "DATA-65"
//        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "300GB for 30 days",
            "allowance" => "300GB",
            "price" => "36000",
            "validity" => "30 days",
            "datacode" => "DATA-435"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "425GB for 90 days",
            "allowance" => "425GB",
            "price" => "50000",
            "validity" => "90 days",
            "datacode" => "DATA-66"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "425GB for 90 days",
            "allowance" => "425GB",
            "price" => "50000",
            "validity" => "90 days",
            "datacode" => "DATA-436"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "525GB for 120 days",
            "allowance" => "525GB",
            "price" => "60000",
            "validity" => "120 days",
            "datacode" => "DATA-67"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "525GB for 120 days",
            "allowance" => "525GB",
            "price" => "60000",
            "validity" => "120 days",
            "datacode" => "DATA-437"
        ]);
//        array_push($data, [
//            "service_type" => \App\Model\CapricornDataPlan::$glo,
//            "name" => "675GB for 120 days",
//            "allowance" => "675GB",
//            "price" => "75000",
//            "validity" => "120 days",
//            "datacode" => "DATA-68"
//        ]);
//        array_push($data, [
//            "service_type" => \App\Model\CapricornDataPlan::$glo,
//            "name" => "1TB for 365 days",
//            "allowance" => "1TB",
//            "price" => "100000",
//            "validity" => "365 days",
//            "datacode" => "DATA-69"
//        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$glo,
            "name" => "1TB for 365 days",
            "allowance" => "1TB",
            "price" => "100000",
            "validity" => "365 days",
            "datacode" => "DATA-439"
        ]);


        // 9mobile bundles
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "Daily 25MB for 24 hours",
            "allowance" => "Daily 25MB",
            "price" => "50",
            "validity" => "24 hours",
            "datacode" => "50"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "Daily 100MB for 24 hours",
            "allowance" => "Daily 100MB",
            "price" => "100",
            "validity" => "24 hours",
            "datacode" => "100"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "150MB for 7 days",
            "allowance" => "150MB",
            "price" => "200",
            "validity" => "7 days",
            "datacode" => "200"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "500MB for 30 days",
            "allowance" => "500MB",
            "price" => "500",
            "validity" => "30 days",
            "datacode" => "500"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "1GB for 30 days",
            "allowance" => "1GB",
            "price" => "1000",
            "validity" => "30 days",
            "datacode" => "1000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "1.5GB for 30 days",
            "allowance" => "1.5GB",
            "price" => "1200",
            "validity" => "30 days",
            "datacode" => "1200"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "2.5GB for 30 days",
            "allowance" => "2.5GB",
            "price" => "2000",
            "validity" => "30 days",
            "datacode" => "2000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "4GB for 30 days",
            "allowance" => "4GB",
            "price" => "3000",
            "validity" => "30 days",
            "datacode" => "3000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "5.5GB for 30 days",
            "allowance" => "5.5GB",
            "price" => "4000",
            "validity" => "30 days",
            "datacode" => "4000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "11.5GB for 30 days",
            "allowance" => "11.5GB",
            "price" => "8000",
            "validity" => "30 days",
            "datacode" => "8000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "15GB for 30 days",
            "allowance" => "15GB",
            "price" => "10000",
            "validity" => "30 days",
            "datacode" => "10000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "27.5GB for 30 days",
            "allowance" => "27.5GB",
            "price" => "18000",
            "validity" => "30 days",
            "datacode" => "18000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "30GB for 90 days",
            "allowance" => "30GB",
            "price" => "27500",
            "validity" => "90 days",
            "datacode" => "27500"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "60GB for 180 days",
            "allowance" => "60GB",
            "price" => "55000",
            "validity" => "180 days",
            "datacode" => "55000"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "100GB for 100 days",
            "allowance" => "100GB",
            "price" => "84992",
            "validity" => "100 days",
            "datacode" => "84992"
        ]);
        array_push($data, [
            "service_type" => \App\Model\CapricornDataPlan::$etisalat,
            "name" => "120GB for 365 days",
            "allowance" => "120GB",
            "price" => "110000",
            "validity" => "365 days",
            "datacode" => "110000"
        ]);

        DB::table('capricorn_data_plans')->truncate();
        DB::table('capricorn_data_plans')->insert($data);
    }
}
