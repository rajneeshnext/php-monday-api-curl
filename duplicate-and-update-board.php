<?php
include "common-functions.php";
$update_monday_query = 'mutation {
                      duplicate_board(board_id: 7321410389, board_name: "Court Cases - '.$monday_row_item_id.'", duplicate_type: duplicate_board_with_structure) {
                        board {
                          id
                        }
                      }
                    }';
$responseContent = mondayInsertContacts($update_monday_query, $token); 

if(isset($responseContent['data']['duplicate_board']['board']['id'])){
     echo $board_id = $responseContent['data']['duplicate_board']['board']['id'];
     $url = "www.com/anyserver/$board_id";
     $column_values_s = "{";
                        $column_values_s .= '\"link4__1\": { \"url\":\"'.$url.'\", \"text\":\"'.$url.'\"}';
                        $column_values_s .= "}";  
    
    $update_monday_query = 'mutation {
                                          change_multiple_column_values(
                                            board_id: 4498206985
                                            item_id: '.$monday_row_item_id.'
                                            column_values: "'.$column_values_s.'"
                                           ) {
                                            id
                                          }
                                        }';
    mondayInsertContacts($update_monday_query, $token);                                     
}
