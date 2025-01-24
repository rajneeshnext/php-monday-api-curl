function mondayInsertContacts($query, $token){               
    echo "<br/>--Running Monday for records--<br/>";
    //echo $query;
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token, 'API-version : 2023-04'];
    $data = @file_get_contents($apiUrl, false, stream_context_create([
          'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
          ]
    ]));
    //print_r($http_response_header);
    $code=getHttpCode($http_response_header);
    
    //echo "<pre>";
    $responseContent = json_decode($data, true);
    //print_r($responseContent);
    if($code == 422  || !isset($responseContent) || isset($responseContent['errors'])){
        print_r($responseContent['errors']);
        echo "API error";
        exit();
    }else{
        return $responseContent;
    }
}
function getHttpCode($http_response_header)
{
    if(is_array($http_response_header))
    {
        $parts=explode(' ',$http_response_header[0]);
        if(count($parts)>1) //HTTP/1.0 <code> <text>
            return intval($parts[1]); //Get code
    }
    return 0;
}
function getPdfFromMonday($item_id, $token){
    $board_id = "111111";
    $column_id = "files6";
    $query= '{ 
                items(ids: ["'.$item_id.'"]) {
                  column_values(ids: "'.$column_id.'"){
                    id
                    value
                    text
                  }
                }
              }';
    $assetID = array();
    $pdfs = mondayCheckContacts($query, $token);
    if(isset($pdfs['data']['items'][0]['column_values'][0]['value'])){
        $pdfs1 = $pdfs['data']['items'][0]['column_values'][0]['value'];
        $responseContent = json_decode($pdfs1, true);
        //print_r($responseContent);
        foreach($responseContent['files'] as $file){
            $assetID[] = $file['assetId'];
        }
    }      
    return $assetID;
}            
function getPublicURL($asset_id, $token){
    $query= '{ 
                 assets(ids: '.$asset_id.') {
                    public_url
                }
              }';
    $api_response = mondayCheckContacts($query, $token);
    //echo "<pre>";print_r($api_response);
    $public_url = $api_response['data']['assets'][0]['public_url'] ?? null;
    if ($public_url) {
        return $public_url;
    } else {
        return "";
    }
}
