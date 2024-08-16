<script>

<?php 

	$sinirGun = 86400;

	$sinirHafta = 604800;

	$gunumuz = time();


	//Tüm Üyeleri Seçiyoruz	

	$uyeler = $database->query("SELECT * FROM uyeler");



	//Bütün Üyelere İşlem Uygulayacağız

	while ($uye = $uyeler->fetch_assoc()) {



		$uyeID = $uye['id'];

		//Banlı veya çıkmışsa
		if ($uye['status'] == "banned" OR $uye['status'] == "left") {
			$forceDrop = true;
		}
		else {
			$forceDrop = false;
		}



		//Üyenin Elindeki bölümleri Seçiyoruz

		$ceviriler = $database->query("SELECT * FROM bolumler WHERE cevirmen = '$uyeID' AND ceviri = 0"); //Çeviriler

		$redakteler = $database->query("SELECT * FROM bolumler WHERE redaktor = '$uyeID' AND redakte = 0"); //Redakteler

		$cleanler = $database->query("SELECT * FROM bolumler WHERE cleaner = '$uyeID' AND clean = 0"); //Cleanler

		$typesetler = $database->query("SELECT * FROM bolumler WHERE typesetter = '$uyeID' AND typeset = 0"); //Typesetler

		$kontroller = $database->query("SELECT * FROM bolumler WHERE kontrolcu = '$uyeID' AND kontrol = 0"); //Kontroller



		//Bölümlerin Günleri Taranacak

			//Çevirileri tara

			while($ceviri = $ceviriler->fetch_assoc()) {

				$bolumID = $ceviri['id'];



				//Hesap Yapıyoruz

				if($ceviri['ceviri_alim'] == null) {

					$sonuc = 500000;

				}

				else {

					$alim = $ceviri['ceviri_alim'];

					$sure = strtotime($alim);

					$sonuc = $gunumuz - $sure;

				}



				

				//Kontrol Ediyoruz

				if ($sonuc > $sinirGun) {



					//Kişiye Özelliği Kontrol Ediyoruz

					$seriID = $ceviri['manga_id'];

					$seri = $database->query("SELECT * FROM seriler WHERE id = '$seriID'");

					$seri = $seri->fetch_assoc();





					//Özelse Düşüşür

					if ($seri['availability'] == 0 OR $forceDrop) { 

				

						//Düşür

						$database->query("UPDATE bolumler SET cevirmen = null WHERE id = '$bolumID'");



						//Bildirim Gönder

							//Seri Ismi ve Bölüm numarası Bul

							$seriName = $seri['manga_name'];

							$bolumNumarasi = $ceviri['bolum_no'];

							$tarih = date('Y-m-d H:i:s', $sure + 86400);

						?>

							var hedef = "member";

							var id = <?php echo $uyeID ?>;

							var type = "3";

							var baslik = "Bölümün Bot Tarafından Bırakıldı!";

							var icerik = "Üzerine aldığın <?php echo $seriName ?> serisinin <?php echo $bolumNumarasi ?>. bölümünün çevirisi beş saat boyunca gönderilmediği için başkalarının yapabilmesi adına otomatik olarak bırakıldı. Eğer bir hata olduğunu düşünüyorsan veya bölüme başlamışsan bölümler sayfasına giderek alınmadıysa tekrardan alabilirsin.";

							var tarih = "<?php echo $tarih ?>"

							sendNotification(hedef,id,type,baslik,icerik,tarih);

						<?php

					}



				}

			}

			//Redakteleri tara

			while($redakte = $redakteler->fetch_assoc()) {

				$bolumID = $redakte['id'];



				//Hesap Yapıyoruz

				if($redakte['redakte_alim'] == null) {

					$sonuc = 500000;

				}

				else {

					$alim = $redakte['redakte_alim'];

					$sure = strtotime($alim);

					$sonuc = $gunumuz - $sure;

				}



				

				//Kontrol Ediyoruz

				if ($sonuc > $sinirGun) {



					//Kişiye Özelliği Kontrol Ediyoruz

					$seriID = $redakte['manga_id'];

					$seri = $database->query("SELECT * FROM seriler WHERE id = '$seriID'");

					$seri = $seri->fetch_assoc();





					//Özelse Düşüşür

					if ($seri['availability'] == 0 OR $forceDrop) { 

				

						//Düşür

						$database->query("UPDATE bolumler SET redaktor = null WHERE id = '$bolumID'");



						//Bildirim Gönder

							//Seri Ismi ve Bölüm numarası Bul

							$seriName = $seri['manga_name'];

							$bolumNumarasi = $redakte['bolum_no'];

							$tarih = date('Y-m-d H:i:s', $sure + 86400);

						?>

							var hedef = "member";

							var id = <?php echo $uyeID ?>;

							var type = "3";

							var baslik = "Bölümün Bot Tarafından Bırakıldı!";

							var icerik = "Üzerine aldığın <?php echo $seriName ?> serisinin <?php echo $bolumNumarasi ?>. bölümünün redaktesi beş saat boyunca gönderilmediği için başkalarının yapabilmesi adına otomatik olarak bırakıldı. Eğer bir hata olduğunu düşünüyorsan veya bölüme başlamışsan bölümler sayfasına giderek alınmadıysa tekrardan alabilirsin.";

							var tarih = "<?php echo $tarih ?>"

							sendNotification(hedef,id,type,baslik,icerik,tarih);

						<?php

					}



				}

			}

			

			//Cleanler tara

			while($clean = $cleanler->fetch_assoc()) {

				$bolumID = $clean['id'];



				//Hesap Yapıyoruz

				if($clean['clean_alim'] == null) {

					$sonuc = 500000;

				}

				else {

					$alim = $clean['clean_alim'];

					$sure = strtotime($alim);

					$sonuc = $gunumuz - $sure;

				}



				//Kontrol Ediyoruz

				if ($sonuc > $sinirGun) {



					//Kişiye Özelliği Kontrol Ediyoruz

					$seriID = $clean['manga_id'];

					$seri = $database->query("SELECT * FROM seriler WHERE id = '$seriID'");

					$seri = $seri->fetch_assoc();



					//Özelse Düşüşür

					if ($seri['availability'] == 0 OR $forceDrop) { 





						//Düşür

						$database->query("UPDATE bolumler SET cleaner = null WHERE id = '$bolumID'");



						//Bildirim Gönder

							//Seri Ismi ve Bölüm numarası Bul

							$seriName = $seri['manga_name']; 

							$bolumNumarasi = $clean['bolum_no'];

							$tarih = date('Y-m-d H:i:s', $sure + 86400);

						?>

							var hedef = "member";

							var id = <?php echo $uyeID ?>;

							var type = "3";

							var baslik = "Bölümün Bot Tarafından Bırakıldı!";

							var icerik = "Üzerine aldığın <?php echo $seriName ?> serisinin <?php echo $bolumNumarasi ?>. bölümünün cleani beş saat boyunca gönderilmediği için başkalarının yapabilmesi adına otomatik olarak bırakıldı. Eğer bir hata olduğunu düşünüyorsan veya bölüme başlamışsan bölümler sayfasına giderek alınmadıysa tekrardan alabilirsin.";

							var tarih = "<?php echo $tarih ?>"

							sendNotification(hedef,id,type,baslik,icerik,tarih);

						<?php

					}



				}

			}



			//Typesetleri tara

			while($typeset = $typesetler->fetch_assoc()) {

				$bolumID = $typeset['id'];



				//Hesap Yapıyoruz

				if($typeset['typeset_alim'] == null) {

					$sonuc = 500000;

				}

				else {

					$alim = $typeset['typeset_alim'];

					$sure = strtotime($alim);

					$sonuc = $gunumuz - $sure;

				}



				//Kontrol Ediyoruz

				if ($sonuc > $sinirGun) {



					//Kişiye Özelliği Kontrol Ediyoruz

					$seriID = $typeset['manga_id'];

					$seri = $database->query("SELECT * FROM seriler WHERE id = '$seriID'");

					$seri = $seri->fetch_assoc();



					//Özelse Düşüşür

					if ($seri['availability'] == 0 OR $forceDrop) { 





						//Düşür

						$database->query("UPDATE bolumler SET typesetter = null WHERE id = '$bolumID'");



						//Bildirim Gönder

							//Seri Ismi ve Bölüm numarası Bul

							$seriName = $seri['manga_name'];

							$bolumNumarasi = $typeset['bolum_no'];

							$tarih = date('Y-m-d H:i:s', $sure + 86400);

						?>

							var hedef = "member";

							var id = <?php echo $uyeID ?>;

							var type = "3";

							var baslik = "Bölümün Bot Tarafından Bırakıldı!";

							var icerik = "Üzerine aldığın <?php echo $seriName ?> serisinin <?php echo $bolumNumarasi ?>. bölümünün dizgisi beş saat boyunca gönderilmediği için başkalarının yapabilmesi adına otomatik olarak bırakıldı. Eğer bir hata olduğunu düşünüyorsan veya bölüme başlamışsan bölümler sayfasına giderek alınmadıysa tekrardan alabilirsin.";

							var tarih = "<?php echo $tarih ?>"

							sendNotification(hedef,id,type,baslik,icerik,tarih);

						<?php

					}



				}

			}



			//Kontrolleri tara

			while($kontrol = $kontroller->fetch_assoc()) {

				$bolumID = $kontrol['id'];



				//Hesap Yapıyoruz

				if($kontrol['kontrol_alim'] == null) {

					$sonuc = 500000;

				}

				else {

					$alim = $kontrol['kontrol_alim'];

					$sure = strtotime($alim);

					$sonuc = $gunumuz - $sure;

				}



				//Kontrol Ediyoruz

				if ($sonuc > $sinirGun) {



					//Kişiye Özelliği Kontrol Ediyoruz

					$seriID = $kontrol['manga_id'];

					$seri = $database->query("SELECT * FROM seriler WHERE id = '$seriID'");

					$seri = $seri->fetch_assoc();



					//Özelse Düşüşür

					if ($seri['availability'] == 0 OR $forceDrop) { 

						//Düşür

						$database->query("UPDATE bolumler SET kontrolcu = null WHERE id = '$bolumID'");



						//Bildirim Gönder

							//Seri Ismi ve Bölüm numarası Bul

							$seriName = $seri['manga_name'];

							$bolumNumarasi = $typeset['bolum_no'];

							$tarih = date('Y-m-d H:i:s', $sure + 86400);

						?>

							var hedef = "member";

							var id = <?php echo $uyeID ?>;

							var type = "3";

							var baslik = "Bölümün Bot Tarafından Bırakıldı!";

							var icerik = "Üzerine aldığın <?php echo $seriName ?> serisinin <al></al>abilirsin. <?php echo $sonuc." - ".$alim?>";

							var tarih = "<?php echo $tarih ?>"

							sendNotification(hedef,id,type,baslik,icerik,tarih);

						<?php

					}



				}

			}

	}


?>



</script>