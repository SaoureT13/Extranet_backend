<?php

class OneSignal {

    public function sendMessage($included_segments, $include_player_ids, $content, $data) {
        /*$content = array(
            "en" => 'English Message'
        );
        $hashes_array = array();
        array_push($hashes_array, array(
            "id" => "like-button",
            "text" => "Like",
            "icon" => "http://i.imgur.com/N8SN8ZS.png",
            "url" => "https://yoursite.com"
        ));
        array_push($hashes_array, array(
            "id" => "like-button-2",
            "text" => "Like2",
            "icon" => "http://i.imgur.com/N8SN8ZS.png",
            "url" => "https://yoursite.com"
        ));
        $fields = array(
            'app_id' => Parameters::$onesignal_appid, //id of the app on Onesignal plateform
            'included_segments' => array(//all users
                'All'
            ),
            'data' => array(
                "foo" => "bar"
            ),
            'contents' => $content,
            'web_buttons' => $hashes_array
        );*/

        $fields = array( //a décommenter en cas de probleme
            'app_id' => Parameters::$onesignal_appid, //id of the app on Onesignal plateform
            'included_segments' => $included_segments,
            'include_player_ids' => $include_player_ids,
            'data' => $data,
            'contents' => $content
        );
        //echo json_encode($fields);
        $fields = json_encode($fields); //a décommenter en cas de problemen

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Parameters::$urlOnesignal);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . Parameters::$onesignal_rest_apikey
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        //print_r($response);
        curl_close($ch);


        return $response;
    }

    public function callOneSignal($included_segments, $include_player_ids, $content, $dataSend) {
        $data = null;
        try {
            /* var_dump($included_segments);
              var_dump($include_player_ids);
              var_dump($content);
              var_dump($dataSend); */
            $response = $this->sendMessage($included_segments, $include_player_ids, $content, $dataSend);
            $return["allresponses"] = json_encode($response);
            $return = json_encode($return);

            // Parameters::writeLog($fileLog, $return, FILE_APPEND);

            $data = json_decode($response, true);

            $id = $data['id'];


//            print("\n\nJSON received:\n");
//            print("\n");
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $data;
    }

}
