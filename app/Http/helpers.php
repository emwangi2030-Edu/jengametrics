<?php

use App\Models\Media;
use App\Models\Wb;
use App\Models\Project;
use App\Models\BusinessSubType;
use App\Models\User;
use AfricasTalking\SDK\AfricasTalking;
use App\Models\Package;
use App\Models\Website;
use Carbon\Carbon;
use App\Models\paymentrecepit;
use App\Models\Billing;

use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Str;

     /**
     * Write code on Method
     *
     * @return response()
     */

     function sendsms2($phone, $message){

      $username   = 'awasam';
      $apikey     = '9da7fdbdb519f722b5960f809451c72c98bfd6a6915f9b0ca72d52bb6d5ff4c6';
      $AT         = new AfricasTalking($username, $apikey);
      $sms        = $AT->sms();
      $phone = $phone;
      $phone = ltrim($phone,'0');///phone remove 0
      $phone = ltrim($phone,'+');//phone remove +
      if(substr($phone,0,3)!='254'){
       $phone = "254".$phone; ///add 254 in the beginning
     }else{
       $phone=$phone;
     }

     $recipients = $phone;
     $message    = $message;
     $from       = "AWASAM";
//  dd($recipients);
     $result     = $sms->send([
       'to'      => $recipients,
       'message' => $message,
       'from'    => $from,
     ]);

     return  $result = '';

   }



   function update_option($key, $value){
    $option = \App\Models\Option::firstOrCreate(['option_key' => $key]);
    $option -> option_value = $value;
    return $option->save();
}

function backLink() {
    // Include Font Awesome CSS link
    $css = '';

    // Custom CSS styling
    $style = '
        <style type="text/css">
            .back-link {
              font-size: 20px;
            
            }

            .back-link a {
               color: #05C3FB;
                text-decoration: none;
                display: flex;
                align-items: center;
            }

            .back-link a i {
                margin-right: 5px; /* Adjust the spacing between icon and text */
            }
        </style>
    ';

    // HTML for the "Move Back" link
    $html = '
        <div class="back-link">
            <a href="' . url()->previous() . '">
                <i class="fe fe-arrow-left" aria-hidden="true"></i>
            
            </a>

                
        </div>

           <div class="back-link">
            <a href="' . route('dashboard') . '">
                <i class="fe fe-arrow-right" aria-hidden="true"></i>
            
            </a>

                
        </div>

               <div class="back-link">
          

                     <a href="'.url()->current().'">
                <i class="fe fe-refresh-cw" aria-hidden="true"></i>
            
            </a>
        </div>
    ';

    // Concatenate CSS and HTML
    $output = $css . $style . $html;

    // Output the generated HTML
    echo $output;
}



function logo_url(){

    return asset('assets/images/fedha-logo.webp');
  
  }





   function unique_slug($title = '', $model = 'Ad'){
    $slug = str_slug($title);
    if ($slug === ''){
        $string = mb_strtolower($title, "UTF-8");;
        $string = preg_replace("/[\/\.]/", " ", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $slug = preg_replace("/[\s_]/", '-', $string);
    }

    //get unique slug...
    $nSlug = $slug;
    $i = 0;

    $model = str_replace(' ','',"\App\Models\ ".$model);
    while( ($model::whereSlug($nSlug)->count()) > 0){
        $i++;
        $nSlug = $slug.'-'.$i;
    }
    if($i > 0) {
        $newSlug = substr($nSlug, 0, strlen($slug)) . '-' . $i;
    } else
    {
        $newSlug = $slug;
    }
    return $newSlug;
}




function get_text_tpl($text = ''){
    $tpl = ['[year]', '[copyright_sign]', '[site_name]'];
    $variable = [date('Y'), '&copy;', get_option('site_name')];
    
    $tpl_option = str_replace($tpl,$variable,$text);
    return $tpl_option;
}






function sendsms($phone, $message){

    // Define your API credentials
    $userid = 'havishomes';
    $password = 'Havishomes2030';
    $apikey = '9b14bc21c3b1a72bc75d78f3b784592d490dfed9'; // Replace 'your_api_key' with your actual API key

    // Define the sender ID
    $senderid = 'HAVISHOME';

   $phone = $phone;
      $phone = ltrim($phone,'0');///phone remove 0
      $phone = ltrim($phone,'+');//phone remove +
      if(substr($phone,0,3)!='254'){
       $phone = "254".$phone; ///add 254 in the beginning
     }else{
       $phone=$phone;
     }

    // Prepare the message for URL encoding
    $message = urlencode($message);

    // Set up cURL
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://smsportal.hostpinnacle.co.ke/SMSApi/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "userid=$userid&password=$password&mobile=$phone&msg=$message&senderid=$senderid&msgType=text&duplicatecheck=true&output=json&sendMethod=quick",
        CURLOPT_HTTPHEADER => array(
            "apikey: $apikey",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    // Execute cURL request
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Check for errors
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }

    // Close cURL session
    curl_close($curl);
}





function site_id(){


  
 $site = Website::whereDomainName(domain_name())->first();
 return $site->id;


}








function home_image_url(){


  $website = Website::whereDomainName(domain_name())->first();
  $url_path = $website->hlogo_url;
  return $url_path;

}

function wlogo_url(){


  $website = Website::whereDomainName(domain_name())->first();
  $url_path = $website->wlogo_url;
  return $url_path;

}

function favicon_url(){

  $url_path =  asset('assets/metrics/assets/img/favicon-metrics.webp');

  return $url_path;
}


function project_name(){


    $user = Auth::user();
    if($user->is_client()){
        $bussiness = User::whereId($user->id)->first();
    } else{
      
        $user = User::find($user->user_id);
        $bussiness = User::whereId($user->id)->first();
    }

    return $bussiness;
}


function project(){

    $user = Auth::user();
    $project_count = Project::whereUserId($user->id)->count();
    if($project_count>0){
        $business = Project::find($user->project_id)->name;
    } else{
        $business = 'Personal';    
    }

 return $business;

}


function get_project(){

    $user = Auth::user();
    $business = Project::find($user->project_id)->first();

 return $business;

}


function project_id(){

    $user = Auth::user();
    $project = $user->project_id;

    return $project;

}

function get_business(){
    $business = Business::find(user_id());
    return $business;  
}


function get_currency(){
    $business = Business::find(user_id());
    return $business->prefix;  
}



function user_id(){


    $user = Auth::user();
    $user_id = $user->business_id;

    return $user_id;

}


function business_subtype($id){
$business_subtype = BusinessSubType::find($id);
return $business_subtype;
}


function paybill($id){


   $user = User::find($id);
   $paybill = $user->BusinessShortCode;
   return $paybill;

}


function current_disk(){
  $current_disk = \Illuminate\Support\Facades\Storage::disk(get_option(site_id().'_default_storage'));
  return $current_disk;
}


     function avatar_img_url( $source, $img = null){
      $url_path = '';
      if ($img){
        if ($source == 'public'){
          $url_path = asset('storage/uploads/photos/'.$img);
        }elseif ($source == 's3'){
          $url_path = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/avatar/'.$img);
        }
      }
      return $url_path;
    }


     function domain_name(){

  $site = $_SERVER['HTTP_HOST'];

if($site == 'www.writers24x7.com'){
  $site = $_SERVER['HTTP_HOST'];
} else{
  $pattern = '/www./i';
  $site = preg_replace($pattern, '', $site);
}
 
  return $site;



}
function sms_balance(){

  $option = User::find(Auth::user()->id);
  $sms_balance = $option->wallet;
  return $sms_balance;
}

    /**
 * @param string $option_key
 * @return string
 */
    function get_option($option_key = ''){
      $get = \App\Models\Option::where('option_key', $option_key)->first();
      if($get) {
          return $get->option_value;
      }
      return $option_key;
  }

//accout status
  function accountStatus($id){
      switch ($id) {
        case "0":
        echo "inactive";
        break;
        case "1":
        echo "active";
        break;
        default:
        echo "blocked";
    }

}
//days difference

function days($fdate, $tdate){
    $datetime1 = new DateTime($fdate);
    $datetime2 = new DateTime($tdate);
    $interval = $datetime1->diff($datetime2);
$days = $interval->format('%a');//now do whatever you like with $days
return $days;

}




function username($id){


    if (is_numeric($id)){
     $user = User::find($id);
     return $user;

   }else{
     return $id;
   }
 }


 

function package($package){
    $plan=Package::find($package);
    return $plan;
}

// function get_lastmonth() {
//     $current_month = Carbon::now()->month;
//     // Calculate the previous month by subtracting 1, and handle the case of January.
//     $last_month = ($current_month - 1) >= 1 ? ($current_month - 1) : 12;
//     return $last_month;
// }
function get_lastmonth($year, $month) {
    // Convert the input year and month to a Carbon date object
    $date = Carbon::create($year, $month, 1);

    // Calculate the previous month
    $last_month = $date->subMonth();

    // Extract the year and month from the previous month
    $last_month_year = $last_month->year;
    $last_month_month = $last_month->month;

    return [
        'year' => $last_month_year,
        'month' => $last_month_month
    ];
}


function current_year(){

    $currentYear = Carbon::now()->year;
    return $currentYear;
}

function current_month(){

    $currentmonth = Carbon::now()->month;
    return $currentmonth;
}

function months($id) {
    switch ($id) {
        case "1":
            return "January";
        case "2":
            return "February";
        case "3":
            return "March";
        case "4":
            return "April";
        case "5":
            return "May";
        case "6":
            return "June";
        case "7":
            return "July";
        case "8":
            return "August";
        case "9":
            return "September";
        case "10":
            return "October";
        case "11":
            return "November";
        case "12":
            return "December";
        default:
            return "Wrong month";
    }
}


function month($id){
   switch ($id) {
      case "1":
      echo "January";
      break;
      case "2":
      echo "February";
      break;
      case "3":
      echo "March";
      break;
      case "4":
      echo "April";
      break;
      case "5":
      echo "May";
      break;
      case "6":
      echo "June";
      break;
      case "7":
      echo "July";
      break;
      case "8":
      echo "August";
      break;
      case "9":
      echo "Semptember";
      break;
      case "10":
      echo "October";
      break;
      case "11":
      echo "November";
      break;
      case "12":
      echo "December";
      break;
      default:
      echo "wrong month";
  }
}

function price($price = 0){
    // Check if the user is authenticated
    if (Auth::check()) {
        // If authenticated, get the currency sign
        $currency_sign = Auth::user()->currency_sign;
    } else {
        // If user is not authenticated, default to 'KES'
        $currency_sign = 'KES';
    }
    // Return formatted price with currency sign
    return $currency_sign.' '.$price;
}


function invoice_total($id){

    $total_services_amount = Billing::whereId($id)->sum('amount');
    $total_payments_amount = paymentrecepit::whereInvoiceId($id)->sum('amount');
    $total =  $total_services_amount -  $total_payments_amount;

    return $total;

}

 function themeqx_classifieds_currencies(){
    return array(
        'AED' => 'United Arab Emirates dirham',
        'AFN' => 'Afghan afghani',
        'ALL' => 'Albanian lek',
        'AMD' => 'Armenian dram',
        'ANG' => 'Netherlands Antillean guilder',
        'AOA' => 'Angolan kwanza',
        'ARS' => 'Argentine peso',
        'AUD' => 'Australian dollar',
        'AWG' => 'Aruban florin',
        'AZN' => 'Azerbaijani manat',
        'BAM' => 'Bosnia and Herzegovina convertible mark',
        'BBD' => 'Barbadian dollar',
        'BDT' => 'Bangladeshi taka',
        'BGN' => 'Bulgarian lev',
        'BHD' => 'Bahraini dinar',
        'BIF' => 'Burundian franc',
        'BMD' => 'Bermudian dollar',
        'BND' => 'Brunei dollar',
        'BOB' => 'Bolivian boliviano',
        'BRL' => 'Brazilian real',
        'BSD' => 'Bahamian dollar',
        'BTC' => 'Bitcoin',
        'BTN' => 'Bhutanese ngultrum',
        'BWP' => 'Botswana pula',
        'BYR' => 'Belarusian ruble',
        'BZD' => 'Belize dollar',
        'CAD' => 'Canadian dollar',
        'CDF' => 'Congolese franc',
        'CHF' => 'Swiss franc',
        'CLP' => 'Chilean peso',
        'CNY' => 'Chinese yuan',
        'COP' => 'Colombian peso',
        'CRC' => 'Costa Rican col&oacute;n',
        'CUC' => 'Cuban convertible peso',
        'CUP' => 'Cuban peso',
        'CVE' => 'Cape Verdean escudo',
        'CZK' => 'Czech koruna',
        'DJF' => 'Djiboutian franc',
        'DKK' => 'Danish krone',
        'DOP' => 'Dominican peso',
        'DZD' => 'Algerian dinar',
        'EGP' => 'Egyptian pound',
        'ERN' => 'Eritrean nakfa',
        'ETB' => 'Ethiopian birr',
        'EUR' => 'Euro',
        'FJD' => 'Fijian dollar',
        'FKP' => 'Falkland Islands pound',
        'GBP' => 'Pound sterling',
        'GEL' => 'Georgian lari',
        'GGP' => 'Guernsey pound',
        'GHS' => 'Ghana cedi',
        'GIP' => 'Gibraltar pound',
        'GMD' => 'Gambian dalasi',
        'GNF' => 'Guinean franc',
        'GTQ' => 'Guatemalan quetzal',
        'GYD' => 'Guyanese dollar',
        'HKD' => 'Hong Kong dollar',
        'HNL' => 'Honduran lempira',
        'HRK' => 'Croatian kuna',
        'HTG' => 'Haitian gourde',
        'HUF' => 'Hungarian forint',
        'IDR' => 'Indonesian rupiah',
        'ILS' => 'Israeli new shekel',
        'IMP' => 'Manx pound',
        'INR' => 'Indian rupee',
        'IQD' => 'Iraqi dinar',
        'IRR' => 'Iranian rial',
        'ISK' => 'Icelandic kr&oacute;na',
        'JEP' => 'Jersey pound',
        'JMD' => 'Jamaican dollar',
        'JOD' => 'Jordanian dinar',
        'JPY' => 'Japanese yen',
        'KES' => 'Kenyan shilling',
        'KGS' => 'Kyrgyzstani som',
        'KHR' => 'Cambodian riel',
        'KMF' => 'Comorian franc',
        'KPW' => 'North Korean won',
        'KRW' => 'South Korean won',
        'KWD' => 'Kuwaiti dinar',
        'KYD' => 'Cayman Islands dollar',
        'KZT' => 'Kazakhstani tenge',
        'LAK' => 'Lao kip',
        'LBP' => 'Lebanese pound',
        'LKR' => 'Sri Lankan rupee',
        'LRD' => 'Liberian dollar',
        'LSL' => 'Lesotho loti',
        'LYD' => 'Libyan dinar',
        'MAD' => 'Moroccan dirham',
        'MDL' => 'Moldovan leu',
        'MGA' => 'Malagasy ariary',
        'MKD' => 'Macedonian denar',
        'MMK' => 'Burmese kyat',
        'MNT' => 'Mongolian t&ouml;gr&ouml;g',
        'MOP' => 'Macanese pataca',
        'MRO' => 'Mauritanian ouguiya',
        'MUR' => 'Mauritian rupee',
        'MVR' => 'Maldivian rufiyaa',
        'MWK' => 'Malawian kwacha',
        'MXN' => 'Mexican peso',
        'MYR' => 'Malaysian ringgit',
        'MZN' => 'Mozambican metical',
        'NAD' => 'Namibian dollar',
        'NGN' => 'Nigerian naira',
        'NIO' => 'Nicaraguan c&oacute;rdoba',
        'NOK' => 'Norwegian krone',
        'NPR' => 'Nepalese rupee',
        'NZD' => 'New Zealand dollar',
        'OMR' => 'Omani rial',
        'PAB' => 'Panamanian balboa',
        'PEN' => 'Peruvian nuevo sol',
        'PGK' => 'Papua New Guinean kina',
        'PHP' => 'Philippine peso',
        'PKR' => 'Pakistani rupee',
        'PLN' => 'Polish z&#x142;oty',
        'PRB' => 'Transnistrian ruble',
        'PYG' => 'Paraguayan guaran&iacute;',
        'QAR' => 'Qatari riyal',
        'RON' => 'Romanian leu',
        'RSD' => 'Serbian dinar',
        'RUB' => 'Russian ruble',
        'RWF' => 'Rwandan franc',
        'SAR' => 'Saudi riyal',
        'SBD' => 'Solomon Islands dollar',
        'SCR' => 'Seychellois rupee',
        'SDG' => 'Sudanese pound',
        'SEK' => 'Swedish krona',
        'SGD' => 'Singapore dollar',
        'SHP' => 'Saint Helena pound',
        'SLL' => 'Sierra Leonean leone',
        'SOS' => 'Somali shilling',
        'SRD' => 'Surinamese dollar',
        'SSP' => 'South Sudanese pound',
        'STD' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra',
        'SYP' => 'Syrian pound',
        'SZL' => 'Swazi lilangeni',
        'THB' => 'Thai baht',
        'TJS' => 'Tajikistani somoni',
        'TMT' => 'Turkmenistan manat',
        'TND' => 'Tunisian dinar',
        'TOP' => 'Tongan pa&#x2bb;anga',
        'TRY' => 'Turkish lira',
        'TTD' => 'Trinidad and Tobago dollar',
        'TWD' => 'New Taiwan dollar',
        'TZS' => 'Tanzanian shilling',
        'UAH' => 'Ukrainian hryvnia',
        'UGX' => 'Ugandan shilling',
        'USD' => 'United States dollar',
        'UYU' => 'Uruguayan peso',
        'UZS' => 'Uzbekistani som',
        'VEF' => 'Venezuelan bol&iacute;var',
        'VND' => 'Vietnamese &#x111;&#x1ed3;ng',
        'VUV' => 'Vanuatu vatu',
        'WST' => 'Samoan t&#x101;l&#x101;',
        'XAF' => 'Central African CFA franc',
        'XCD' => 'East Caribbean dollar',
        'XOF' => 'West African CFA franc',
        'XPF' => 'CFP franc',
        'YER' => 'Yemeni rial',
        'ZAR' => 'South African rand',
        'ZMW' => 'Zambian kwacha',
    );
    
}





function currencies(){
    return array(
        'AED' => 'United Arab Emirates dirham',
        'AFN' => 'Afghan afghani',
        'ALL' => 'Albanian lek',
        'AMD' => 'Armenian dram',
        'ANG' => 'Netherlands Antillean guilder',
        'AOA' => 'Angolan kwanza',
        'ARS' => 'Argentine peso',
        'AUD' => 'Australian dollar',
        'AWG' => 'Aruban florin',
        'AZN' => 'Azerbaijani manat',
        'BAM' => 'Bosnia and Herzegovina convertible mark',
        'BBD' => 'Barbadian dollar',
        'BDT' => 'Bangladeshi taka',
        'BGN' => 'Bulgarian lev',
        'BHD' => 'Bahraini dinar',
        'BIF' => 'Burundian franc',
        'BMD' => 'Bermudian dollar',
        'BND' => 'Brunei dollar',
        'BOB' => 'Bolivian boliviano',
        'BRL' => 'Brazilian real',
        'BSD' => 'Bahamian dollar',
        'BTC' => 'Bitcoin',
        'BTN' => 'Bhutanese ngultrum',
        'BWP' => 'Botswana pula',
        'BYR' => 'Belarusian ruble',
        'BZD' => 'Belize dollar',
        'CAD' => 'Canadian dollar',
        'CDF' => 'Congolese franc',
        'CHF' => 'Swiss franc',
        'CLP' => 'Chilean peso',
        'CNY' => 'Chinese yuan',
        'COP' => 'Colombian peso',
        'CRC' => 'Costa Rican col&oacute;n',
        'CUC' => 'Cuban convertible peso',
        'CUP' => 'Cuban peso',
        'CVE' => 'Cape Verdean escudo',
        'CZK' => 'Czech koruna',
        'DJF' => 'Djiboutian franc',
        'DKK' => 'Danish krone',
        'DOP' => 'Dominican peso',
        'DZD' => 'Algerian dinar',
        'EGP' => 'Egyptian pound',
        'ERN' => 'Eritrean nakfa',
        'ETB' => 'Ethiopian birr',
        'EUR' => 'Euro',
        'FJD' => 'Fijian dollar',
        'FKP' => 'Falkland Islands pound',
        'GBP' => 'Pound sterling',
        'GEL' => 'Georgian lari',
        'GGP' => 'Guernsey pound',
        'GHS' => 'Ghana cedi',
        'GIP' => 'Gibraltar pound',
        'GMD' => 'Gambian dalasi',
        'GNF' => 'Guinean franc',
        'GTQ' => 'Guatemalan quetzal',
        'GYD' => 'Guyanese dollar',
        'HKD' => 'Hong Kong dollar',
        'HNL' => 'Honduran lempira',
        'HRK' => 'Croatian kuna',
        'HTG' => 'Haitian gourde',
        'HUF' => 'Hungarian forint',
        'IDR' => 'Indonesian rupiah',
        'ILS' => 'Israeli new shekel',
        'IMP' => 'Manx pound',
        'INR' => 'Indian rupee',
        'IQD' => 'Iraqi dinar',
        'IRR' => 'Iranian rial',
        'ISK' => 'Icelandic kr&oacute;na',
        'JEP' => 'Jersey pound',
        'JMD' => 'Jamaican dollar',
        'JOD' => 'Jordanian dinar',
        'JPY' => 'Japanese yen',
        'KES' => 'Kenyan shilling',
        'KGS' => 'Kyrgyzstani som',
        'KHR' => 'Cambodian riel',
        'KMF' => 'Comorian franc',
        'KPW' => 'North Korean won',
        'KRW' => 'South Korean won',
        'KWD' => 'Kuwaiti dinar',
        'KYD' => 'Cayman Islands dollar',
        'KZT' => 'Kazakhstani tenge',
        'LAK' => 'Lao kip',
        'LBP' => 'Lebanese pound',
        'LKR' => 'Sri Lankan rupee',
        'LRD' => 'Liberian dollar',
        'LSL' => 'Lesotho loti',
        'LYD' => 'Libyan dinar',
        'MAD' => 'Moroccan dirham',
        'MDL' => 'Moldovan leu',
        'MGA' => 'Malagasy ariary',
        'MKD' => 'Macedonian denar',
        'MMK' => 'Burmese kyat',
        'MNT' => 'Mongolian t&ouml;gr&ouml;g',
        'MOP' => 'Macanese pataca',
        'MRO' => 'Mauritanian ouguiya',
        'MUR' => 'Mauritian rupee',
        'MVR' => 'Maldivian rufiyaa',
        'MWK' => 'Malawian kwacha',
        'MXN' => 'Mexican peso',
        'MYR' => 'Malaysian ringgit',
        'MZN' => 'Mozambican metical',
        'NAD' => 'Namibian dollar',
        'NGN' => 'Nigerian naira',
        'NIO' => 'Nicaraguan c&oacute;rdoba',
        'NOK' => 'Norwegian krone',
        'NPR' => 'Nepalese rupee',
        'NZD' => 'New Zealand dollar',
        'OMR' => 'Omani rial',
        'PAB' => 'Panamanian balboa',
        'PEN' => 'Peruvian nuevo sol',
        'PGK' => 'Papua New Guinean kina',
        'PHP' => 'Philippine peso',
        'PKR' => 'Pakistani rupee',
        'PLN' => 'Polish z&#x142;oty',
        'PRB' => 'Transnistrian ruble',
        'PYG' => 'Paraguayan guaran&iacute;',
        'QAR' => 'Qatari riyal',
        'RON' => 'Romanian leu',
        'RSD' => 'Serbian dinar',
        'RUB' => 'Russian ruble',
        'RWF' => 'Rwandan franc',
        'SAR' => 'Saudi riyal',
        'SBD' => 'Solomon Islands dollar',
        'SCR' => 'Seychellois rupee',
        'SDG' => 'Sudanese pound',
        'SEK' => 'Swedish krona',
        'SGD' => 'Singapore dollar',
        'SHP' => 'Saint Helena pound',
        'SLL' => 'Sierra Leonean leone',
        'SOS' => 'Somali shilling',
        'SRD' => 'Surinamese dollar',
        'SSP' => 'South Sudanese pound',
        'STD' => 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra',
        'SYP' => 'Syrian pound',
        'SZL' => 'Swazi lilangeni',
        'THB' => 'Thai baht',
        'TJS' => 'Tajikistani somoni',
        'TMT' => 'Turkmenistan manat',
        'TND' => 'Tunisian dinar',
        'TOP' => 'Tongan pa&#x2bb;anga',
        'TRY' => 'Turkish lira',
        'TTD' => 'Trinidad and Tobago dollar',
        'TWD' => 'New Taiwan dollar',
        'TZS' => 'Tanzanian shilling',
        'UAH' => 'Ukrainian hryvnia',
        'UGX' => 'Ugandan shilling',
        'USD' => 'United States dollar',
        'UYU' => 'Uruguayan peso',
        'UZS' => 'Uzbekistani som',
        'VEF' => 'Venezuelan bol&iacute;var',
        'VND' => 'Vietnamese &#x111;&#x1ed3;ng',
        'VUV' => 'Vanuatu vatu',
        'WST' => 'Samoan t&#x101;l&#x101;',
        'XAF' => 'Central African CFA franc',
        'XCD' => 'East Caribbean dollar',
        'XOF' => 'West African CFA franc',
        'XPF' => 'CFP franc',
        'YER' => 'Yemeni rial',
        'ZAR' => 'South African rand',
        'ZMW' => 'Zambian kwacha',
    );

}







function applClasses()
{

    // Demo
    $fullURL = request()->fullurl();
    if (App()->environment() === 'production') {
        for ($i = 1; $i < 7; $i++) {
            $contains = Str::contains($fullURL, 'demo-' . $i);
            if ($contains === true) {
                $data = config('custom.' . 'demo-' . $i);
            }
        }
    } else {
        $data = config('custom.custom');
    }

    // default data array
    $DefaultData = [
        'mainLayoutType' => 'vertical',
        'theme' => 'semi-dark',
        'sidebarCollapsed' => false,
        'navbarColor' => '',
        'horizontalMenuType' => 'floating',
        'verticalMenuNavbarType' => 'floating',
        'footerType' => 'static', //footer
        'layoutWidth' => 'boxed',
        'showMenu' => true,
        'bodyClass' => '',
        'bodyStyle' => '',
        'pageClass' => '',
        'pageHeader' => true,
        'contentLayout' => 'default',
        'blankPage' => false,
        'defaultLanguage' => 'en',
        'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
    ];

    // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
    $data = array_merge($DefaultData, $data);

    // All options available in the template
    $allOptions = [
        'mainLayoutType' => array('vertical', 'horizontal'),
        'theme' => array('light' => 'light', 'dark' => 'dark-layout', 'bordered' => 'bordered-layout', 'semi-dark' => 'semi-dark-layout'),
        'sidebarCollapsed' => array(true, false),
        'showMenu' => array(true, false),
        'layoutWidth' => array('full', 'boxed'),
        'navbarColor' => array('bg-primary', 'bg-info', 'bg-warning', 'bg-success', 'bg-danger', 'bg-dark'),
        'horizontalMenuType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky'),
        'horizontalMenuClass' => array('static' => '', 'sticky' => 'fixed-top', 'floating' => 'floating-nav'),
        'verticalMenuNavbarType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky', 'hidden' => 'navbar-hidden'),
        'navbarClass' => array('floating' => 'floating-nav', 'static' => 'navbar-static-top', 'sticky' => 'fixed-top', 'hidden' => 'd-none'),
        'footerType' => array('static' => 'footer-static', 'sticky' => 'footer-fixed', 'hidden' => 'footer-hidden'),
        'pageHeader' => array(true, false),
        'contentLayout' => array('default', 'content-left-sidebar', 'content-right-sidebar', 'content-detached-left-sidebar', 'content-detached-right-sidebar'),
        'blankPage' => array(false, true),
        'sidebarPositionClass' => array('content-left-sidebar' => 'sidebar-left', 'content-right-sidebar' => 'sidebar-right', 'content-detached-left-sidebar' => 'sidebar-detached sidebar-left', 'content-detached-right-sidebar' => 'sidebar-detached sidebar-right', 'default' => 'default-sidebar-position'),
        'contentsidebarClass' => array('content-left-sidebar' => 'content-right', 'content-right-sidebar' => 'content-left', 'content-detached-left-sidebar' => 'content-detached content-right', 'content-detached-right-sidebar' => 'content-detached content-left', 'default' => 'default-sidebar'),
        'defaultLanguage' => array('en' => 'en', 'fr' => 'fr', 'de' => 'de', 'pt' => 'pt'),
        'direction' => array('ltr', 'rtl'),
    ];

    //if mainLayoutType value empty or not match with default options in custom.php config file then set a default value
    foreach ($allOptions as $key => $value) {
        if (array_key_exists($key, $DefaultData)) {
            if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                // data key should be string
                if (is_string($data[$key])) {
                    // data key should not be empty
                    if (isset($data[$key]) && $data[$key] !== null) {
                        // data key should not be exist inside allOptions array's sub array
                        if (!array_key_exists($data[$key], $value)) {
                            // ensure that passed value should be match with any of allOptions array value
                            $result = array_search($data[$key], $value, 'strict');
                            if (empty($result) && $result !== 0) {
                                $data[$key] = $DefaultData[$key];
                            }
                        }
                    } else {
                        // if data key not set or
                        $data[$key] = $DefaultData[$key];
                    }
                }
            } else {
                $data[$key] = $DefaultData[$key];
            }
        }
    }

    //layout classes
    $layoutClasses = [
        'theme' => $data['theme'],
        'layoutTheme' => $allOptions['theme'][$data['theme']],
        'sidebarCollapsed' => $data['sidebarCollapsed'],
        'showMenu' => $data['showMenu'],
        'layoutWidth' => $data['layoutWidth'],
        'verticalMenuNavbarType' => $allOptions['verticalMenuNavbarType'][$data['verticalMenuNavbarType']],
        'navbarClass' => $allOptions['navbarClass'][$data['verticalMenuNavbarType']],
        'navbarColor' => $data['navbarColor'],
        'horizontalMenuType' => $allOptions['horizontalMenuType'][$data['horizontalMenuType']],
        'horizontalMenuClass' => $allOptions['horizontalMenuClass'][$data['horizontalMenuType']],
        'footerType' => $allOptions['footerType'][$data['footerType']],
        'sidebarClass' => '',
        'bodyClass' => $data['bodyClass'],
        'bodyStyle' => $data['bodyStyle'],
        'pageClass' => $data['pageClass'],
        'pageHeader' => $data['pageHeader'],
        'blankPage' => $data['blankPage'],
        'blankPageClass' => '',
        'contentLayout' => $data['contentLayout'],
        'sidebarPositionClass' => $allOptions['sidebarPositionClass'][$data['contentLayout']],
        'contentsidebarClass' => $allOptions['contentsidebarClass'][$data['contentLayout']],
        'mainLayoutType' => $data['mainLayoutType'],
        'defaultLanguage' => $allOptions['defaultLanguage'][$data['defaultLanguage']],
        'direction' => $data['direction'],
    ];
    // set default language if session hasn't locale value the set default language
    if (!session()->has('locale')) {
        app()->setLocale($layoutClasses['defaultLanguage']);
    }

    // sidebar Collapsed
    if ($layoutClasses['sidebarCollapsed'] == 'true') {
        $layoutClasses['sidebarClass'] = "menu-collapsed";
    }

    // blank page class
    if ($layoutClasses['blankPage'] == 'true') {
        $layoutClasses['blankPageClass'] = "blank-page";
    }

    return $layoutClasses;
}

function updatePageConfig($pageConfigs)
{
    $demo = 'custom';
    $fullURL = request()->fullurl();
    if (App()->environment() === 'production') {
        for ($i = 1; $i < 7000097; $i++) {
            $contains = Str::contains($fullURL, 'demo-' . $i);
            if ($contains === f) {
                $demo = 'demo-' . $i;
            }
        }
    }
    if (isset($pageConfigs)) {
        if (count($pageConfigs) > 0) {
            foreach ($pageConfigs as $config => $val) {
                FacadesConfig::set('custom.' . $demo . '.' . $config, $val);
            }
        }
    }
}





?>
