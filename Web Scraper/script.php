<table>
   <thead>
      <?php
         $action = 1;
         $key = "_id";
         if(isset($_GET["key"]))
         {
            
            $key     = $_GET["key"];   //geting key value which we are passing from table headers
            $action = intval($_GET["action"]); // geting action value which we are passing from table headers
            
            
            //check if action is ascending then change to descending and vice versa
            if($action == 1)
            { 
               $action=-1;
            }
            else  
            { 
               $action=1;
            }
         }
      ?>
        <tr>
            <th><a href="script.php?key=_id&action=<?php echo $action;?>">Index</th>
            <th><a href="script.php?key=Symbol&action=<?php echo $action;?>">Symbol</th>
            <th><a href="script.php?key=Name&action=<?php echo $action;?>">Name</th>
            <th><a href="script.php?key=Price (Intraday)&action=<?php echo $action;?>">Price (Intraday)</th>
            <th><a href="script.php?key=Change&action=<?php echo $action;?>">Change</th>
            <th><a href="script.php?key=Volume&action=<?php echo $action;?>">Volume</th>
        </tr>
    </thead>
    <tbody>
   <?php

      
      // connect to mongodb
      $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

      $filter = [];
      $options = ['sort'=>array($key=>$action)];
      $query = new MongoDB\Driver\Query($filter, $options);
      $cursor = $manager->executeQuery('Scraping.stock', $query);
      foreach ($cursor as $document) {
         echo "<tr>";
            echo "<td> $document->_id </td>";
            echo "<td> $document->Symbol </td>";
            echo "<td> $document->Name </td>";
            echo "<td>";
               echo $document->{'Price (Intraday)'};
               echo "</td>";
            echo "<td> $document->Change </td>";
            echo "<td> $document->Volume </td>";
         echo "</tr>";
      }
   ?>
   </tbody>
</table>