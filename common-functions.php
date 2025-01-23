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
