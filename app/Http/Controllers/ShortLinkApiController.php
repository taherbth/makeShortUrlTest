<?php
namespace App\Http\Controllers;
use App\ShortLink;
use DB;
use Illuminate\Http\Request;

class ShortLinkApiController extends Controller
{
    public function makeShortLink(Request $request){
        $longUrl = $request->url;
        $short_code = "";
        if(empty($longUrl)) {
            return response()->json([
                'message' => "Url can't be empty"
            ]);
        }
        if(strlen($longUrl) >= 1500) {
           return response()->json([
                'message' => "URL is too long"
            ]); 
        }
        if(!$this->isValidUrlFormat($longUrl)) {            
            return response()->json([
                'message' => "URL structure is not valid"
            ]);
        }
        if($this->isSiteBlocked($longUrl, 'domain')) {           
            return response()->json([
                'message' => "This ShortLink has been blocked"
            ]);
        }
        if(!empty($longUrl)) {
            $short_code = $this->makeUrlToShortCode( $longUrl );
        }        
        //return config('constants.options.kw_blacklist');
        return response()->json([
            'message' => 'Successfully created short code',
            'short_code' => $short_code
        ], 201);
    }
    public function getShortLink($short_code){
        if(substr($short_code, -11) == '/index.html') {
            $short_code = substr($short_code, 0, -11);
        }
        if(substr($short_code, -10) == '/index.htm') {
            $short_code = substr($short_code, 0, -10);
        }
        if(substr($short_code, -10) == '/index.php') {
            $short_code = substr($short_code, 0, -10);
        }
        if(substr($short_code, -11) == '/index.aspx') {
            $short_code = substr($short_code, 0, -11);
        }
        if(substr($short_code, -10) == '/index.asp') {
            $short_code = substr($short_code, 0, -10);
        }

        $url = $this->getShortCodeToUrl($short_code);
        return response()->json([
            'message' => "Long Url >> ".$url
        ]);
    }
    /**
   * Retrieve a long URL from a short code.
   *
   * Deligates validating the supplied short code, getting the long URL from
   * the database, and optionally incrementing the URL's access counter.
   *
   * @param string $code the short code associated with a long URL
   * @param boolean $increment whether to increment the record's counter
   *
   * @return string the long URL
   * @throws Exception if an error occurs
   */
    public function getShortCodeToUrl($short_code, $increment = true) {
        if (empty($short_code)) {
            return response()->json([
                'message' => "No short code was supplied"
            ]);
        }
        $urlRow = $this->getGeneratedUrlFromDb($short_code);
        if ( $urlRow == false) {
            return response()->json([
                'message' => "Short code does not appear to exist"
            ]);
        }
        if ($increment == true) {
          $this->incrementNumberOfHit($urlRow->id);
        }
        return $urlRow->long_url;
    }
    /**
    * Increment the record's access count.
    *
    * Increment the number of times a short code has been looked up to retrieve
    * its URL.
    *
    * @param integer $id the ID of the row to increment
    * @throws PDOException if an error occurs
    */
    protected function incrementNumberOfHit($id) {
        ShortLink::where('id',$id)->update(array(
            'number_of_hit'=> DB::raw('number_of_hit + 1' )));

       // update(array('stock_quantity' => DB::raw('stock_quantity + ' .$requested_quantity)));
    }
    /**
    * Get the long URL from the database.
    *
    * Retrieve the URL associated with the short code from the database. If
    * there is an error, an exception is thrown.
    *
    * @param string $code the short code to look for in the database
    * @return string|boolean the long URL or false if it does not exist
    * @throws PDOException if an error occurs
    */
    protected function getGeneratedUrlFromDb($short_code) {
        $url = ShortLink::where('short_code',$short_code)->first();
        if($url){
            return $url;
        }else{
            return false;
        }
    }
    /**
     * Check the input url for valid url or not
     * @param string $longUrl contains input url
     * @return boolean whether URL is a valid format
    */    
    public function isValidUrlFormat($longUrl) {
        if (filter_var($longUrl, FILTER_VALIDATE_URL)  === false ) {
          $host = parse_url($longUrl, PHP_URL_HOST);
          $ipv6 = trim($host, '[]');
          if (filter_var($ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            return false;
          } else {
            $newUrl = str_replace($host, 'www.example.com', $longUrl);
            if ( filter_var($newUrl, FILTER_VALIDATE_URL) === false ) {
              return false;
            }
          }
        }
        return true;
    }
    /**
     * Check the input url to block the site macthed with blacklist
     * @param string $object contains input url
     * @param string $object_type contains 'domain' or 'keyword'
     * @return boolean whether URL is blocked or not
    */   
    public function isSiteBlocked($object, $object_type) {
        if($object_type != 'keyword' && $object_type != 'domain') {
            return response()->json([
                'message' => "Invalid Argument: Object type is unknown"
            ]);
        }
        $profane = false;
        if($object_type == 'keyword') {
          $kw_blacklist = config('constants.options.kw_blacklist');
          if (isset($kw_blacklist)) {
            foreach ($kw_blacklist as $kw) {
              if(!empty($kw) && !is_null($kw)) {
                if (fnmatch(strtolower($kw), strtolower($object)) !== FALSE) {
                  $profane = true;
                }
              }
            }
          }
        }
        if($object_type == 'domain') {
          $object = parse_url($object)['host'];
          $dom_blacklist = config('constants.options.dom_blacklist');
          if (isset($dom_blacklist)) {
            foreach ($dom_blacklist as $dom) {
              if(!empty($dom) && !is_null($dom)) {
                if (fnmatch(strtolower($dom), strtolower($object)) !== FALSE) {
                  $profane = true;
                }
              }
            }
          }
        }
        return $profane;
    }
    /**
   * Create a short code from a long URL.
   *
   * Delegates validating the URLs format, validating it, optionally
   * connecting to the URL to make sure it exists, and checking the database
   * to see if the URL is already there. If so, the cooresponding short code
   * is returned. Otherwise, createShortCode() is called to handle the tasks.
   *
   * @param string $url the long URL to be shortened
   * @return string the short code on success
   * @throws Exception if an error occurs
   */
    public function makeUrlToShortCode($longUrl) {
        if (empty($longUrl)) {
            return response()->json([
                'message' => "No URL was supplied"
            ]);
        }
        if ($this->isValidUrlFormat($longUrl) == false) {
            return response()->json([
                'message' => "URL structure is not valid"
            ]);
        }
        $codeExists = $this->shortCodeExistsInDb($longUrl);
        if ($codeExists != false ) {
            return response()->json([
                'message' => "ShortCode is already in database"
            ]);
        }
        if ($codeExists == false) {
          $shortCode = $this->generateShortCode($longUrl);
        }
        return $shortCode;
    }
    public function shortCodeExistsInDb($longUrl) {
       $existing_short_url = ShortLink::where('long_url',$longUrl)->first();
       if($existing_short_url){
            return $existing_short_url->short_code;
       }else{
        return false;
       }
    }
    /**
   * Generate a short code from a long URL.
   *
   * inserting the URL into the database, converting the integer
   * of the row's ID column into a short code, and updating the database with
   * the code. If successful, it returns the short code. If there is an error,
   * an exception is thrown.
   *
   * @param string $longUrl the long URL
   * @return string the created short code
   * @throws Exception if an error occurs
   */
    protected function generateShortCode($longUrl) {    
        $shortLink = new ShortLink([
            'long_url' => $longUrl,
            'number_of_hit' => '0'
        ]);
        $shortLink->save();
        $id = $shortLink->id;

        $shortCode = 'r'.$this->convertIdToShortCode($id);
        
        ShortLink::where('id',$id)->update(array(
            'short_code'=>$shortCode,
        ));
        return $shortCode;        
    }
     /**
   * Convert an integer ID to a short code.
   *
   * This method does the actual conversion of the ID integer to a short code.
   * If successful, it returns the created code. If there is an error, an
   * exception is thrown.
   *
   * @param int $id the integer to be converted
   * @return string the created short code
   * @throws Exception if an error occurs
   */
  protected function convertIdToShortCode($id) {
    $id = intval($id);
    if ($id < 1) {
        return response()->json([
            'message' => "The ID is not a valid integer"
        ]);
    }
    $chars = config('constants.options.SHORTLINK_CODE_CHARS');
    $length = strlen($chars);
    // make sure length of available characters is at
    // least a reasonable minimum - there should be at
    // least 10 characters
    if ($length < 10) {
        return response()->json([
            'message' => "Length of chars is too small"
        ]);
    }

    $code = "";
    while ($id > $length - 1) {
      // determine the value of the next higher character
      // in the short code should be and prepend
      $code = $chars[fmod($id, $length)] .
        $code;
      // reset $id to remaining value to be converted
      $id = floor($id / $length);
    }

    // remaining value of $id is less than the length of
    // self::$chars
    $code = $chars[$id] . $code;

    return $code;
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
