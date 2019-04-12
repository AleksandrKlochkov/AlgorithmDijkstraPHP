<?php
function print_arr($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

$result=null;
$jobj = file_get_contents( __DIR__ . DIRECTORY_SEPARATOR . 'graph.json' ); // в примере все файлы в корне
$data = json_decode($jobj); 
$start = 1;
$end = 1;

if($_GET['start'] && $_GET['end']){
    $start = $_GET['start'];
    $end = $_GET['end'];

    $graf = $data;
    $pointer = $graf->graph;
    $arrlen = count($pointer);
    //массив контенер
        for ($xi=0; $xi<$arrlen; $xi++){
            $v[$xi] = [];
                for ($yj=0; $yj<$arrlen; $yj++){
                    $v[$xi][$yj] = 0;
             }
        }
    //визиты
    $visite = [];
    //дистанции
    $dist = [];
    //установка начальных значений визитов и дистанцей
    for($i = 0; $i< $arrlen ;$i++){
        $a = $pointer[$i]->vertex1;  //вершина 1
        $b = $pointer[$i]->vertex2; //вершина 2
        $d = $pointer[$i]->distance; //дистанция между вершинами
        
        $v[$a][$b] = $v[$b][$a]  = $d;
        $visite[$i] = false; //для каждой вершины визит устанавливаем false
        $dist[$i] = 10000000;//изначально дистанции равны бесконечности
    }

    //очередь прохождения по вершинам
    $VertexName = [];
    $vStart = $start;//начальная вешина
    $vEnd = $end; //конечная вешина
   
    $queue = [];
    array_push($queue, $vStart);

    $dist[$vStart] = 0; //дистанция первой вершины равна 0
    $visite[$vStart] = true ;//визит первой вершины true так как мы изначально находимся в ней

    $allVerticesRes = []; //вывод результата кратчайшего пути от стартовой вершины до всех имеющихся вершин
    $shortestArowing = [];
    while(!empty($queue)){
        $vertex = array_shift($queue);
        for($j=1; $j<count($v[$vertex]); $j++){

            if(!$visite[$j] && $v[$vertex][$j] && $v[$vertex][$j]+$dist[$vertex]<$dist[$j]){//если вершина еще не посищена и имеется ребро от этой вершины и растоение до вершины меньше чем бесконечность
                $dist[$j] = $v[$vertex][$j] + $dist[$vertex];//записываем дистанцию
                $VertexName[$j-1] =$j;// имя вершины в которую зашли
                array_push($queue,$j); 
            }
        }
     }

     $queueEnd = [];
     array_push($queueEnd, $vEnd);
     $end = $vEnd;
     $weight = $dist[$vEnd];
     $ver = []; // массив посещенных вершин
     $ver[0] = $end; // начальный элемент - конечная вершина
     $k = 1; // индекс предыдущей вершины

     while(!empty($queueEnd)){
        $vert = array_shift($queueEnd);
        for($i1=1; $i1<$arrlen; $i1++) // просматриваем все вершины
            if ($v[$end][$i1] != 0)// если связь есть
                {
                    $tempVert = $weight - $v[$end][$i1]; // определяем вес пути из предыдущей вершины
                    if ($tempVert == $dist[$i1]) // если вес совпал с рассчитанным
                        {                 // значит из этой вершины и был переход
                          //  console.log(i1)
                            $weight = $tempVert; // сохраняем новый вес
                            $end = $i1;//заменяем вершину
                            array_push($queueEnd,$i1);
                            $ver[$k] = $i1; // и записываем ее в массив
                            $k++;
                        }
                }
     }

     $ver = array_reverse($ver);
     $res ='';
     foreach($ver as $v){
       $res .=  $v.' ';

     }
     
     $result = ' Кратчайший путь от вершины ' . $vStart .' до '. $vEnd .': ' . $res.' Общий вес пути:' . $dist[$vEnd];
    

}


$temp = [];

foreach($data->graph as $valueObj)
{
    foreach ($valueObj as $key => $value) 
    {
        if(($key != 'vertex1') && ($key != 'vertex2'))
        {
            continue;
        }
        $temp[$value] = $value;
    }
}
asort($temp);
$result = $result == null ? 'Здесь будет выведен результат' : $result;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--fonts-->
     <link rel="stylesheet" href="fonts/Roboto/stylesheet.css">
    <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
   
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body>
<div class="wrapper">
            <div class="content">
                <div class="container">
                    <div class="select-box">
                        <form action="">
                            <ul class="select-list">

                                <li>
                                    <select name="start" class="select">
                                        <?foreach($temp as $key):  ?>
                                            <option><?=$temp[$key]?></option>
                                        <?endforeach?>
                                    </select>
                                </li>

                                <li>
                                    <select name="end" class="select">
                                        <?foreach($temp as $key):  ?>
                                            <option><?=$temp[$key]?></option>
                                        <?endforeach?>
                                    </select>
                                </li>

                                <li><button class="button" type="submit">Расчитать</button></li>
                        </ul>
                        </form>
                    </div>

                    <div class="result-box">
                        <h1>Результат</h1>
                        <p><?=$result?></p>
                    </div>    


                </div>  
            </div>

            <div class="footer">
                <div class="container">
                    <div class="copy">
                         <p>&copy 2019 Footer</p>
                    </div>
                </div>
                    
            </div>

      </div>
</body>
</html>
