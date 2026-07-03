<?php
    // include the database connection file
    include('connection.php');

    $sql = "SELECT * FROM `tbl_waton_product`";

    $result = mysqli_query($conn, $sql);

    if ( $result ) {


        //Create the XML structure
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
     
     
        $root = $xml->createElement('productList');
        $xml->appendChild($root);
     
     
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $entry = $xml->createElement('product');
     
     
                foreach ($row as $key => $value) {
                    $node = $xml->createElement($key, htmlspecialchars($value));
                    $entry->appendChild($node);
                }
     
     
                $root->appendChild($entry);
            }
        } else {
            echo 'No data found.';
            exit;
        }
     
     
        //Output or save the XML
        // To save the XML as a file
        $xml->save('export-data-'.date('His-dmY').'.xml');
     
     
        // To force download as file
        header('Content-disposition: attachment; filename=export-data-'.date('His-dmY').'.xml');
        header('Content-type: text/xml');
        echo $xml->saveXML();
     
     
     } else {
     
     
        echo 'Error:'. $sql . "<br>" . mysqli_error($conn);
     
     
     }
     
     
     
     
     // Close the database connection
     $conn->close();
     
?>