<?php
    require 'vendor/autoload.php';

    $context = stream_context_create(array('ssl'=>array(
        'verify_peer' => false, 
        "verify_peer_name"=>false
        )));
    
    libxml_set_streams_context($context);

    if(isset($_POST['query'])){
        $inputText = $_POST['query'];
        $query = "http://lookup.dbpedia.org/api/search?QueryClass=VideoGame&query=" . $inputText . "&MaxHits=5";
        $xml_data = simplexml_load_file($query) or 
        die("Error: Object Creation failure");

        $i = 0;
        $uri = "";
        foreach ($xml_data->children() as $data)
        {
            //Better Search + Terminate
            if(($data->Label == "League of Legends") && ($i > 0)){
                break;
            }
            if($data->Label != ""){
                $i++;
                $uri = str_replace("http://dbpedia.org/resource/", "", $data->URI);
                echo "<li class='dropdown-item' id='data_list_item_".$i."' uri='".$uri."'>".$data->Label."</li>";
    
                echo "<script>
                $('#data_list_item_".$i."').on('click', function(){
                    if($('#data_list_item_".$i."').attr('URI') != ''){
                        $.redirect('result.php', { 'search_uri' : $('#data_list_item_".$i."').attr('uri') });
                    }
                });
                </script>";
            }
        }
    }
?>