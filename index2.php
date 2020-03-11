<?php 


$data = array_map('str_getcsv', file('data2.csv'));
//print_r($data);

//$core = randomcok($data);

$pusat = array();
$kel = array();
$sudah = array();
$hasilfix = array();

$hasilfix = dbscan(4,5,$data,$pusat,$kel,$sudah);
$asek = dbscanlanjut($data,$hasilfix);




function dbscanlanjut(array $data, array $hasilfix){
	$belum = array();
	foreach ($data as $key => $value) {
		$cek = true;
		for ($i=0; $i <sizeof($hasilfix); $i++) { 
			for ($j=0; $j < sizeof($hasilfix[$i]) ; $j++) { 
				if($data[$key][0] == $hasilfix[$i][$j]['xp'] && $data[$key][1] == $hasilfix[$i][$j]['yp']){					
				 	$cek = false;
				}
			}	
		}
		if($cek === true)
		{
			$belum[] = $data[$key];
		}
	}

	if ($belum == null) {
		echo "Iterasi Selesai";
	}
	else
	{	
		echo "<br><br><b> Cluster : </b>";
		$pusat = array();
		$kel = array();
		$sudah = array();
		$lanjutfix = dbscan(4,5,$belum,$pusat,$kel,$sudah);
		print_r($lanjutfix);
		echo "<br><br>";
		for ($i=0; $i <sizeof($lanjutfix); $i++) { 
			$hasilfix[] = $lanjutfix[$i];
		}
		dbscanlanjut($data,$hasilfix);
	}

}




function dbscan($eps,$min,array $data,array $core,array $kelompok, array $sudah){	
	$lihat = true;
	if(!$core){
		$core = array();
		$random = array_rand($data,1);
		$XP = $data[$random][0];
		$YP = $data[$random][1];
		$core['xp'] = $XP;
		$core['yp'] = $YP;
		$kelompok = array();
		$hasil = euclidian($data,$core);
		$cek[] = array();
		$iterasi1=kelompok($eps,$min,$hasil);
		$kel = $iterasi1;
		$baaaa= array();
		$inti= mecaricore($iterasi1,$baaaa);
		$result['kelompok'] = $kel;
		$result['core'] = $inti;
		$sudah[] = $result['kelompok'];
	}
	else{
		$hasil= array();
		$hasil = euclidian($data,$core);
	    $iterasi1[] = null;
		$iterasi1=kelompok($eps,$min,$hasil);
		$kel = array();
		$inti = array();
		$baaaa= array();
		$inti = mecaricore($iterasi1, $kelompok);
		if($inti == 'Habis'){
			$lihat = false;
		}
		else
		{
		   	$kel = $iterasi1;
			$result['kelompok'] = $iterasi1;
			$result['core'] = $inti;
			$sudah[] = $result['kelompok'];	
		}
		
	}
	if($lihat == true){
		$braaa = dbscan($eps,$min,$data,$inti,$kel,$sudah);
		$sudah = $braaa;
	}
	return $sudah;			
}


$hahaha="";


function mecaricore(array $dbscan, array $kelompok){	
	$hasil = array();
	$maxim = array();
	$xp ="";
	$yp="";
	if($kelompok == null){
		foreach ($dbscan as $key => $value) {
				$maxim[] = $dbscan[$key]['hasil'];
		}	
	}
	else{
		foreach ($dbscan as $key => $value) {
			$cek = false;
			foreach ($kelompok as $key1 => $value1) {
				if( $dbscan[$key]['xp'] == $kelompok[$key1]['xp'] && $dbscan[$key]['yp'] == $kelompok[$key1]['yp']){
						$cek = true;
						break;
				}
			 }
			 if($cek === false)
			 {
			 	$maxim[] = $dbscan[$key]['hasil'];
			 	
			 }
			}
			if($maxim == null){

			 		return $hasil = "Habis";
			 	}
		}
	$maksimal = max($maxim);
	foreach ($dbscan as $key => $value) {
		if($maksimal == $dbscan[$key]['hasil']){
			$xp = $dbscan[$key]['xp'];
			$yp = $dbscan[$key]['yp'];
		};
	}

	$hasil['xp'] = $xp;
	$hasil['yp'] = $yp;
	$hasil['hasil'] = $maksimal;
	return $hasil;
}
	
function euclidian(array $data, array $isi)
{
	    $result = array();
	    $hasil = array();
		$iterasi =0;
		for($i=0; $i<sizeof($data);$i++)
		{
			$result['xp'] = $data[$i][0];
			$result['yp'] = $data[$i][1];
			$iterasi = sqrt(pow($data[$i][0] - $isi['xp'],2) + pow($data[$i][1] - $isi['yp'],2));
			$result['hasil'] = $iterasi;
			$hasil[] = $result;
			
		}
		return $hasil;
}


function kelompok($epsilon, $min, array $data)
{
	$iterasi = array();
	$hasil = array();
	for ($i=0; $i < sizeof($data); $i++) { 
			if($data[$i]['hasil'] < $epsilon){
				$iterasi[] = $data[$i];
			}
		}
	return $iterasi;
}

?>
