<?php

$csv = array_map('str_getcsv', file('data.csv'));

KMeans($csv,2);

#Initial Cluster dilakukan secara terurut
#isiArray -> jumlah data pada masing-masing cluster
#CLuster -> berisi beberapa cluster(tergantung permintaan) dan data-data pada cluster tersebut
#perintah unset untuk menghapus index pada array yang telah dimasukkan kedalam cluster
function KMeans($array, $jumlahCluster)
{
    $duplicateArray = $array;
    $Cluster = array();
    $isiArray = ceil(sizeof($array) / $jumlahCluster);
    $increase = 0;
    for($i=0; $i < $jumlahCluster; $i++)
    {
        $j =0;
        $Cluster[$i] = array();
        while(sizeof($array)!=0 && sizeof($Cluster[$i])<$isiArray)
        {
            $Cluster[$i][$j] = $array[$increase];
            unset($array[$increase]);
            $j++;
            $increase++;
        }
    }
    echo "<h3>Cluster Awal</h3>";
    for($i=0; $i<sizeof($Cluster); $i++)
    {
        echo "<br>Cluster ".$i." :<br>";
        print_r($Cluster[$i]);
    }

     $optimize = false;
     $iteration =1;
     while($optimize == false && $iteration <= 1000)
     {
        $centroid = Centroid($Cluster);
        $ClusterBaru = Training($duplicateArray,$centroid);
        echo "<br><h3>Iterasi ".$iteration."</h3><br> Centroid : ";
        print_r($centroid);
        echo "<br>";
        for($i=0; $i<sizeof($ClusterBaru); $i++)
        {
            echo "<br>Cluster ".$i." :<br>";
            print_r($ClusterBaru[$i]);
            echo"<br>";
        }
        
         #for dibawah berfungsi untuk mengecek apakah cluster baru sama dengan cluster sebelumnya
        for($i=0; $i<sizeof($Cluster); $i++)
        {
            if($Cluster[$i] != $ClusterBaru[$i])
            {
                $Cluster = $ClusterBaru;
                break;
            }
            #Break apda if diatas digunakan jika ada salah satu cluster yang tidak sama
            #jika semua sama maka dia akan menjalankan perintah $optimize = true dibawah ini dan while akan berhenti
            $optimize = true;
        }
        $iteration++;
    }

}
function Training($array, $centroid)
{
    $ClusterBaru = array();
    #for dibawah ini untuk inisialisasi aja,supaya jumlah cluster tetap sesuai dengan permintaan meskipun nantinya cluster tersebut kosong
    #biar gk muncul error, karen kalo gk pakek ini kalo ada salah satu cluster yang kosong maka error
    for($i=0; $i<sizeof($centroid); $i++)
    {
        $ClusterBaru[$i] = array();
    }

    $euclidiean = null;
    $indeksCluster = 0;
    for($i=0; $i < sizeof($array); $i++)
    {
        for($j=0; $j < sizeof($centroid); $j++)
        {
            $euclidieanBaru = 0;
            if(sizeof($centroid[$j]) != 0)
            {
                for($k=0; $k < sizeof($array[$j]); $k++)
                {
                    $euclidieanBaru += pow($centroid[$j][$k]-$array[$i][$k],2);
                }
                $euclidieanBaru = sqrt($euclidieanBaru);
                if($euclidiean != null)
                {
                    if(min($euclidiean,$euclidieanBaru) == $euclidieanBaru)
                    {
                        $indeksCluster = $j;
                    }
                }
                $euclidiean = $euclidieanBaru;
            }
            
        }
        //echo "<br>".$indeksCluster."<br>";
        $ClusterBaru[$indeksCluster][] = $array[$i];
    }
    return $ClusterBaru;
}
function Centroid($Cluster)
{
    echo "Size Cluster ".sizeof($Cluster);
    //echo "<br> ini nilainya : ".$Cluster[0][0][1];
    $centroid = array();
    for($i=0; $i<sizeof($Cluster); $i++)
    {//echo "<br>Centroid ".$i."<br>";
        $centroid[$i] =array();
        if(sizeof($Cluster[$i]) !=0)
        {
            for($j=0; $j< sizeof($Cluster[$i]); $j++)
            {
                for($k=0; $k<sizeof($Cluster[$i][$j]); $k++)
                {
                    #if digunakan untuk initialize (memastikan bahwa column centroid dengan column cluster memiliki jumlah yang sama)
                    if(sizeof($centroid[$i]) != sizeof($Cluster[$i][$j]))
                    {
                        $centroid[$i][$k] = $Cluster[$i][$j][$k]/sizeof($Cluster);
                    }
                    else
                    {
                        $centroid[$i][$k] += $Cluster[$i][$j][$k]/sizeof($Cluster);
                    }
                    
                }
            }
        }
        
    }
    return $centroid;
}

?>
