<!-- Version PHP -->

<?php
echo "<p>Niveau 1: Créer un algorithme qui génére un tableau de 100 chiffres entre 0 et 1000 (random).</p>
<p>Niveau 2 : Trier ce tableau par ordre de grandeur.</p>";

$number_of_items = 101;
for ($i = 1; $i < $number_of_items; $i++){
    $arrayTab[$i] = rand(0,1000); 
}
asort($arrayTab);
echo '<pre>';
print_r($arrayTab);
?>

<?php 
echo "Niveau 3 : Trier le tableau de la manière suivante ([le chiffre le plus petit, 
le chiffre le plus grand, le deuxième plus petit, le deuxième plus grand, ..., ...,])";

$number_of_items = 100/2;
$number_of_items_tab = 100;

for ($i = 0; $i < $number_of_items_tab; $i++){
    $arrayTab[$i] = rand(0,1000); 
}

rsort($arrayTab);

for ($i = 0; $i < $number_of_items; $i++){
    $tab_1[$i] = $arrayTab[$i];
}

sort($arrayTab);

for ($i = 0; $i < $number_of_items; $i++){
    $tab_2[$i] = $arrayTab[$i];
}

$tab_3 = [];

for ($j = 0; $j < $number_of_items; $j++){
    $tab_1[$j];
    $tab_2[$j];
    array_push($tab_3, $tab_1[$j], $tab_2[$j]);
}
echo '<pre>';
print_r($tab_3);
?>

<!-- Version JS -->

<script>
let arr = [];
let sortedArr = [];

for(let i=0; i<100; i++){
    let number = Math.floor(Math.random()*1000);
    arr.push(number);
}

console.log(arr);

function sortNumber(a, b){
    return a-b;
}

arr.sort(sortNumber);

while(arr.length>0){
    sortedArr.push(arr[0]);
    arr.splice(0,1);
    sortedArr.push(arr[arr.length-1]);
    arr.splice(arr.length-1, 1);
}

console.log(sortedArr);
</script>

<!-- Version MySQL -->
<?php
    
    try{
    $db=new PDO('mysql:host=mysqldb;dbname=dailychallenges;charset=utf8', 'root', 'root');
    //Declare variables
    $longueur=100; //nombre d'éléments dans la DB
    $maxValue=1000;//Valeur maximale des nombres à générer
    $arrayA=[];
    $arrayB=[];

    //Clean up Db at start (pour éviter de surcharger la DB)
    function resetOnLoad(){
        global $db;
        $trasher=$db->prepare("DELETE FROM randomNumbers");
        $trasher->execute();
        $trasher->closeCursor();
    }

    //Generate the numbers and push them into a DB
    function randomGenerator ($iterations, $limit){
        global $db;
        for ($i=0; $i<$iterations;$i++){
                $random=rand(0,$limit); 
                //echo $random;                  
                $stmnt=$db->prepare("INSERT INTO randomNumbers(random) VALUES(:random)");
                $stmnt->bindParam(':random', $random);
                $stmnt->execute();
                $stmnt->closeCursor();
        }
    }

    //Calling the functions. At this stage Db is populated
    resetOnLoad();
    randomGenerator($longueur, $maxValue);

    // Get the numbers and order them
    $order=$db->prepare("SELECT * FROM randomNumbers ORDER BY random ASC");
    $order->execute();
    $ordered=$order->fetchAll(PDO::FETCH_COLUMN,0);
    function displayAsc(){
        global $arrayA;
        global $ordered;
        foreach($ordered as $o){
            echo '<tr><td>'.$o.'</td></tr>';
            array_push($arrayA,$o);
        }
    }

    //Second sorting algorithm
    $reverse=$db->prepare("SELECT * FROM randomNumbers ORDER BY random DESC");
    $reverse->execute();
    $reversed=$reverse->fetchAll(PDO::FETCH_COLUMN,0);
    function sorter(){
        global $arrayB;
        global $ordered;
        global $reversed;
        global $longueur;
        for($i=0;$i<$longueur/2;$i++){
            echo '<tr><td>'.$ordered[$i].'</td></tr>';
            echo '<tr><td>'.$reversed[$i].'</td></tr>';
            array_push($arrayB,$ordered[$i],$reversed[$i]);
        }
    }
}
//This is part of the PDO creation (see Parcours)
catch(Exception $e)
{
    die('Erreur:'.$e->getMessage());
}

?>