function mondayCheckContacts($query, $count=0){   
            
    //echo "<br/>--Running Monday for $count records--<br/>";
    //echo $query;
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjM0NDQxODEyNywiYWFpIjoxMSwidWlkIjo1ODgwNTA3NywiaWFkIjoiMjAyNC0wNC0wOVQxMTowODozMC4wMDBaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6MTkwMzQxODQsInJnbiI6InVzZTEifQ.VkjkaVS134d-CRdM535aIVNbaXKLu_4VXb-hTQTV1nI';
    $apiUrl = 'https://api.monday.com/v2';
    $headers = ['Content-Type: application/json', 'Authorization: ' . $token, 'API-version : 2023-10'];
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
    exit();
}
function mondayInsertUpdateContacts($query, $count=0){   
            
    echo "<br/>--Running Monday for $count records--<br/>";
    //echo $query;
    $token = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjM0NDQxODEyNywiYWFpIjoxMSwidWlkIjo1ODgwNTA3NywiaWFkIjoiMjAyNC0wNC0wOVQxMTowODozMC4wMDBaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6MTkwMzQxODQsInJnbiI6InVzZTEifQ.VkjkaVS134d-CRdM535aIVNbaXKLu_4VXb-hTQTV1nI';
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
    
    $responseContent = json_decode($data, true);
    echo "<pre>";
    //print_r($responseContent);
    if($code == 422  || !isset($responseContent) || isset($responseContent['errors'])){
        print_r($responseContent['errors']);
        echo "API error";
        exit();
    }else{
        return $responseContent;
    }
    exit();
}
function contactFieldToMap($group = "1715837953_contactf__1", $datas, $item_checked) {
    //Contact field board
    
    $array_field = array();
    
    foreach($item_checked['data'] as $data_item){
       foreach($data_item['items'] as $item){
            //print_r($item);
            //continue;
            $group_id = $item['group']['id'];
            $column_values = $item['column_values'];
            if( $group_id == $group){
                //echo $group;
                $i=0;
                foreach($column_values as $column_value){
                    //print_r($column_value); app_in_date__1
                    //continue; 
                    if($column_value['id'] == "dup__of_text__1" && isset($column_value['value'])){
                        $ct_value = str_replace('"', '', $column_value['value']);
                        $i++;
                    }
                    if($column_value['id'] == "text2__1" && isset($column_value['value'])){
                        $monday_value = str_replace('"', '', $column_value['value']);
                        $i++;
                    }
                    if($column_value['id'] == "tags__1" && isset($column_value['value'])){
                        $monday_value = str_replace('"', '', $column_value['value']);
                        $i++;
                    }
                    if($i==2){
                        $array_field[$monday_value] = $ct_value;
                        break;
                    }
                }
            }
       } 
    }
    //echo "<pre>";
    //print_r($array_field);
    //print_r($datas);
    $column_values = array();
    $field_to_update = array();
    foreach($array_field as $key => $value){  
        if($key!="" && $datas[$value]!=""){
            if($key == "lead_email"){
                $field_to_update[$key] = $datas[$value];
                $column_values[] = '\"'.$key.'\": {\"email\": \"'.$datas[$value].'\", \"text\":\"'.$datas[$value].'\"}';
            }else if($key == "app_sent__1" || $key == "app_complete__1" || $key == "deal_closed__1" || $key == "spanish" || $key == "dup__of_check__1" || $key == "check4__1" || $key == "check0__1" || $key == "check8__1"){
                $field_to_update[$key] = $datas[$value];
                $column_values[] = '\"'.$key.'\": { \"checked\":\"true\"}';
            }elseif($key == "alt_name__1"){
               $datas[$value] = preg_replace('/[^A-Za-z0-9\s]/', '',  $datas[$value]);
               $field_to_update[$key] = $datas[$value];
               $datas[$value] = substr($datas[$value], 0, 20).'...';
               $datas[$value] = str_replace(PHP_EOL, '', $datas[$value]);
               $column_values[] = '\"'.$key.'\": \"'.$datas[$value].'\"';
            }elseif($key == "link__1"){
                $column_values[] = '\"'.$key.'\": { \"url\":\"'.$datas[$value].'\", \"text\":\"'.$datas[$value].'\"}';
            }elseif($key == "ct_last_call_disp__1" || $key == "status_138__1"){
                // type=status
                $column_values[] = '\"'.$key.'\": { \"label\":\"'.$datas[$value].'\"}';
            }elseif($key == "date__1" || $key == "business_start_date__1" || $key == "date3__1" || $key == "last_inbound_call__1" || $key == "last_sms_sent__1" || $key == "last_sms_received__1" || $key == "last_email_sent__1" || $key == "modified_on__1" || $key == "suppress_until__1" || $key == "last_outbound_call__1" || $key == "created_on__1"){
                // type=date
                //\"date__1\": \"2024-06-10T08:33:22Z\", \"date3__1\": \"2024-06-05T08:33:39Z\", \"last_inbound_call__1\": \"2024-06-03T08:33:26Z\", \"last_sms_sent__1\": \"2024-06-01T08:33:43Z\", \"last_sms_received__1\": \"2024-06-02T08:33:30Z\", \"last_email_sent__1\": \"2024-06-07T08:33:49Z\"
                //date_default_timezone_set( 'America/Los_Angeles' );
                $datetime = date('Y-m-d h:i:s A', strtotime("+0 minutes", strtotime($datas[$value])));
                $date  = date('Y-m-d', strtotime($datetime));
                $time  = date('H:i:s', strtotime($datetime));
                $column_values[] = ' \"'.$key.'\": {\"date\" : \"'.$date.'\" , \"time\" : \"'.$time.'\"}';
            }elseif($key == "world_clock__1"){
                // type=status
                $column_values[] = '\"'.$key.'\": { \"timezone\":\"'.$datas[$value].'\"}';
            }elseif($key == "ct_owned_by_id__1" || $key == "modified_by__1" || $key == "app1_email__1" || $key == "app2_email__1" || $key == "website__1"){
                // this if avoid removing dash or special charcters 
                $column_values[] = '\"'.$key.'\": \"'.$datas[$value].'\"';
            }elseif($key == "business_desc__1" || $key == "use_of_funds__1"){
                $field_to_update[$key] = $datas[$value];
                $datas[$value] = substr($datas[$value], 0, 20).'...';
                $datas[$value] = str_replace(PHP_EOL, '', $datas[$value]);
                $column_values[] = '\"'.$key.'\": \"'.$datas[$value].'\"';
            }
            else{
                $datas[$value] = preg_replace('/[^A-Za-z0-9\s]/', '',  $datas[$value]);
                $field_to_update[$key] = $datas[$value];
                $column_values[] = '\"'.$key.'\": \"'.$datas[$value].'\"';
            }
        }else{
            if($key == "app_sent__1" || $key == "app_complete__1" || $key == "deal_closed__1" || $key == "spanish" || $key == "dup__of_check__1"){
                $field_to_update[$key] = $datas[$value];
                $column_values[] = '\"'.$key.'\": { \"checked\":\"false\"}';
            }
        }
    }
    return $column_values;
}
function getContactTagNames($arr){
    // Get row by multiple names in column
    $tags_idss =  implode(',', array_map('add_quotes', $arr));
    $query_check = 'query {
                          items_page_by_column_values (limit: 150, board_id: "6686734687", columns: [{column_id: "id__1", column_values: ['.$tags_idss.']}]) {
                            cursor
                            items {
                              id
                              name
                            }
                          }
                        }';
                                            
    $item_checked = mondayCheckContacts($query_check); 
    foreach($item_checked['data']['items_page_by_column_values']['items'] as $item){
            $item_names[] = $item['name'];
    }
    if($item_names){
        $tag_name = implode(',', $item_names);
    }
    return $tag_name;
}
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
