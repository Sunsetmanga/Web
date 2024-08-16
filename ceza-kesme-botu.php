<?php 

?>

<script>
	<?php 



		$hafta = 604800;

		$gunumuz = time();

		$tarih = date("Y-m-d H:i:s");


		/**************************/





		//İzinli üyeleri bul
		$izinler = $database->query("SELECT * FROM izinler ORDER BY bitis_tarihi DESC "); //izinleri geriye doğru seçtik


		$izinliler = [];

		while ($izinli = $izinler->fetch_assoc()) {
			$izin = strtotime($izinli['bitis_tarihi']);
			$sonuc = $gunumuz - $izin; //eğer sonuç 1 haftadan yüksek bir sayı çıkar ise iznin bittiği anlamına gelir, daha düşükse henüz izin devam etmektedir.

			if ($sonuc < $hafta) { //izin varsa
				array_push($izinliler, $izinli['user_id']); //izin var o halde izinliye ekleyelim
			}

			else {
				break;
			}

		}


		/**************************/



		//Yeni Üyeleri Bul

		$yeniUyeler = $database->query("SELECT * FROM uyeler ORDER BY account_year DESC");

		$yeniler = [];


		while ($yeni = $yeniUyeler->fetch_assoc()) {

			$yas = strtotime($yeni['account_year']);

			$sonuc = $gunumuz - $yas; //eğer sonuç 1 haftadan yüksek bir sayı çıkar ise eski bir üye olduğu anlamına gelir, daha düşükse yeni gelmiştir.

			if ($sonuc < $hafta) { //yeniyse
				array_push($yeniler, $yeni['id']); //izin var o halde izinliye ekleyelim
			}

			else {
				break;
			}

		}







		/**************************/







		//Üyeleri Tara ve Ceza Kesilecekleri Tespit et

		$uyeler = $database->query("SELECT * FROM uyeler");

		$sorunlu = [];

		$sorunsuz = [];

		while ($uye = $uyeler->fetch_assoc()) {

			if ($uye['status'] == "left" OR $uye['status'] == "banned" OR $uye['additional_role'] == "mod" OR $uye['additional_role'] == "admin") continue;

			$uyeID = $uye['id'];



			//Hesap yeni veya izinli değil, işlemlere başlayalım.

			if (!in_array($uyeID, $izinliler) AND !in_array($uyeID, $yeniler)) {



				//Haftalık bölümlerini sayalım

				if (true) { //Kod bloğunu küçültebilmek için if(true) ekledim, her halükarda burası çalışacak ve haftalık bölümleri sayacak.

					$ceviriler = $database->query("SELECT * FROM bolumler WHERE cevirmen = '$uyeID'");

					$redakteler = $database->query("SELECT * FROM bolumler WHERE redaktor = '$uyeID'");
					
					$cleanler = $database->query("SELECT * FROM bolumler WHERE cleaner = '$uyeID'");

					$typesetler = $database->query("SELECT * FROM bolumler WHERE typesetter = '$uyeID'");

					$kontroller = $database->query("SELECT * FROM bolumler WHERE kontrolcu = '$uyeID'");

					$hCev = $ceviriler;

					$hRed = $redakteler;

					$hCle = $cleanler;

					$hTyp = $typesetler;

					$hKon = $kontroller;

					$hCount = 0;

					while ($hBol = $hCev->fetch_assoc()) {

						if($hBol['ceviri'] == "0") continue;

						$sure = strtotime($hBol['ceviri_teslim']);

						$sonuc = $gunumuz - $sure;

						if($hafta > $sonuc) {

							$hCount++;

						}

					}

					while ($hBol = $hRed->fetch_assoc()) {

						if($hBol['redakte'] == "0") continue;

						$sure = strtotime($hBol['redakte_teslim']);

						$sonuc = $gunumuz - $sure;

						if($hafta > $sonuc) {

							$hCount++;

						}

					}

					while ($hBol = $hCle->fetch_assoc()) {

						if($hBol['clean'] == "0") continue;

						$sure = strtotime($hBol['clean_teslim']);

						$sonuc = $gunumuz - $sure;

						if($hafta > $sonuc) {

							$hCount++;

						}

					}

					while ($hBol = $hTyp->fetch_assoc()) {

						if($hBol['typeset'] == "0") continue;

						$sure = strtotime($hBol['typeset_teslim']);

						$sonuc = $gunumuz - $sure;

						if($hafta > $sonuc) {

							$hCount++;

						}

					}

					while ($hBol = $hKon->fetch_assoc()) {

						if($hBol['kontrol'] == "0") continue;

						$sure = strtotime($hBol['kontrol_teslim']);

						$sonuc = $gunumuz - $sure;

						if($hafta > $sonuc) {

							$hCount++;

						}

					}
				}

				$cezaRow = $database->query("SELECT gonullu_tarih FROM ceza_sistemi WHERE user_id = '$value'")->fetch_assoc();
				$gonullu = strtotime($cezaRow['gonullu_tarih']);
				$sonuc = $gunumuz - $gonullu; //eğer sonuç 1 haftadan yüksek bir sayı çıkar ise eski bir üye olduğu anlamına gelir, daha düşükse yeni gelmiştir.

				//Ücretli ama Yeni Gönüllü
				if ($sonuc < $hafta AND $uye['ucretli'] == "0") {
					if ($hCount >= 4) {
						array_push($sorunsuz, $uyeID);
					}
					else {
						array_push($sorunlu, $uyeID);
					}
				}
				//Gönüllü
				else if ($uye['ucretli'] == "0") {
					if ($hCount >= 1) {
						array_push($sorunsuz, $uyeID);
					}

					else {
						array_push($sorunlu, $uyeID);
					}
				}
				//Ücretli
				else {
					if ($hCount >= 4) {
						array_push($sorunsuz, $uyeID);
					}
					else {
						array_push($sorunlu, $uyeID);

					}
				}
			}
		}








		/**************************/



		//Sorunlu Üyeleri Tara

		foreach ($sorunlu as $key => $value) {


			//Üyenin bilgilerini ve ceza bilgilerini çekelim
			$uyeBilgileri = $database->query("SELECT * FROM uyeler WHERE id = '$value'")->fetch_assoc();
			$cezaRow = $database->query("SELECT * FROM ceza_sistemi WHERE user_id = '$value'");

			if ($cezaRow->num_rows > 0) { //satır varsa bilgileri çek
				$cezaRow = $cezaRow->fetch_assoc(); 
			}

			else { //satır yoksa oluştur ve çek
				$database->query("INSERT INTO ceza_sistemi(user_id,ceza,son_ceza) VALUES('$value','0','$tarih')");
				$cezaRow = $database->query("SELECT * FROM ceza_sistemi WHERE user_id = '$value'")->fetch_assoc();
			}

			//Son ceza zamanını al

			$sonCeza = $cezaRow['son_ceza'];
			$son_ceza = strtotime($sonCeza); //Daha önce ceza girilmiş ise onu zamana çevirerek alalım
			$sonuc = $gunumuz - $son_ceza; //eğer sonuç 1 haftadan yüksek bir sayı çıkar ise son ceza üstünden 1 hafta geçmiştir ve ceza kesilebilir, eğer daha düşük ise ceza kesilmez.

			if ($sonuc > $hafta) {  //ceza kesilir

				//Ücretli ama Yeni Gönüllü
				if ($sonuc < $hafta AND $uyeBilgileri['ucretli'] == "0")
					$st_mem = "1";

				//Gönüllü
				else if ($uyeBilgileri['ucretli'] == "0")
					$st_mem = "0";

				//Ücretli
				else
					$st_mem = "1";

			
			
				//Ücretli ise
				if ($st_mem == "1") {
					$cezaCount = $cezaRow['ceza'];
					//Bakiye hesapla
					$bakiye = $uyeBilgileri['bakiye'];
					switch ($cezaCount) {
						case '0':
							$nBakiye = $bakiye - 10;
							break;
						case '1':
							$nBakiye = $bakiye - 35;
							break;
						case '2':
							$nBakiye = "ban";
							break;
						default:
							$nBakiye = $bakiye - 10;
							break;
					}

					//Bakiye güncelle
					$database->query("UPDATE uyeler SET bakiye = '$nBakiye' WHERE id = '$value'");

					//Bildirim Gönderelim
					?>
						var hedef = "member";
						var id = <?php echo $value ?>;
						var type = "3";
						var baslik = "Ceza Botu tarafından uyarıldınız!";
						var icerik = "Son bir haftada 4 bölümden az bölüm yaptığınız için uyarı aldınız ve bakiyenizden <?php if ($cezaCount == "0") echo "5"; else echo "20"; ?>TL kesildi. Bir sonraki uyarılarda farklı tarifeler uygulanacaktır. Cezalarınızı panelin sağ bölümünden takip edebilirsiniz. Eğer bir hata olduğunu düşünüyorsanız lütfen yetkililer ile iletişime geçin.";
						sendNotification(hedef,id,type,baslik,icerik);
					<?php

					//Bakiye Log
					if ($cezaCount == "0") $tl = "10"; else $tl = "35";
					bakiye_log($uyeBilgileri['user']. " ceza yedi ve bakiyesinden ".$tl."TL eksildi, yeni bakiye: ".$nBakiye);

					//Genel Log
					ekip_log($uyeBilgileri['user']." haftalık 4 bölüm yapmayarak ceza yedi ve ceza kesildi.");

				}
				//Gönüllü ise
				else {
					//Bildirim Gönderiyoruz
					?>
						var hedef = "member";
						var id = <?php echo $value ?>;
						var type = "3";
						var baslik = "Ceza Botu tarafından uyarıldınız!";
						var icerik = "Son bir haftadır bölüm yapmadığınız için uyarı aldınız. Uyarı sayınız 3'e ulaştığında ekipten otomatik olarak atılacaksınız. Cezalarınızı panelin sağ bölümünden takip edebilirsiniz.";
						sendNotification(hedef,id,type,baslik,icerik);
					<?php

					//Genel Log
					ekip_log($uyeBilgileri['user']." bu hafta hiç bölüm yapmayarak ceza aldı.");
				}

				//Ceza Arttır
				$newCeza = $cezaRow['ceza'] + 1;
				$database->query("UPDATE ceza_sistemi SET ceza = '$newCeza', son_ceza = '$tarih' WHERE user_id = '$value'");
				if ($newCeza >= 3) {
					$database->query("UPDATE uyeler SET status = 'banned' WHERE id = '$value'");
					$database->query("INSERT INTO ban_sebepleri (user_id,ban_sebebi,banlayan) VALUES('$value','Ceza Botu tarafından 2 uyarıya rağmen bölüm yapmamanız dolayısıyla banlandınız.','2')");
					ekip_log($uyeBilgileri['user']." ceza botundan aldığı 3. ceza sonucu ekipten atıldı.");
				}
			}

		}


		//Sorunsuz Üyeleri Tarayalım, Af Verelim
		foreach ($sorunsuz as $key => $value) {

			//Üyenin bilgilerini ve ceza bilgilerini çekelim
			$uyeBilgileri = $database->query("SELECT * FROM uyeler WHERE id = '$value'")->fetch_assoc();
			$cezaRow = $database->query("SELECT * FROM ceza_sistemi WHERE user_id = '$value'");
			if ($cezaRow->num_rows > 0) { //satır varsa bilgileri çek
				$cezaRow = $cezaRow->fetch_assoc(); 
			}
			else { //satır yoksa atla
				continue;				
			}

			if ($ceza == 0)	continue;

			//Son ceza zamanını al
			$sonCeza = $cezaRow['son_ceza'];
			$son_ceza = strtotime($sonCeza); //Daha önce ceza girilmiş ise onu zamana çevirerek alalım
			$sonuc = $gunumuz - $son_ceza; //eğer sonuç 1 haftadan yüksek bir sayı çıkar ise son ceza üstünden 1 hafta geçmiştir ve af verilebilir, eğer daha düşük ise af verilemez.



			if ($sonuc > $hafta) {  //af verilebilir

				//Son af zamanını al

				$sonAf = $cezaRow['son_af'];
				$son_af = strtotime($sonAf); //Daha önce ceza girilmiş ise onu zamana çevirerek alalım
				$sonuc = $gunumuz - $son_af; //eğer sonuç 1 haftadan yüksek bir sayı çıkar ise son af üstünden 1 hafta geçmiştir ve af verilebilir, eğer daha düşük ise af verilemez.

				

				if ($sonuc > $hafta) {
					//Ücretli ama Yeni Gönüllü
					if ($sonuc < $hafta AND $uyeBilgileri['ucretli'] == "0")
						$st_mem = "1";

					//Gönüllü
					else if ($uyeBilgileri['ucretli'] == "0")
						$st_mem = "0";

					//Ücretli
					else
						$st_mem = "1";


					/****Af Verme İşlemi****/
					//Ücretli ise
					if ($st_mem == "1") {
						//Bildirim Gönderelim
						?>
							var hedef = "member";
							var id = <?php echo $value ?>;
							var type = "2";
							var baslik = "Uyarınız Silindi!";
							var icerik = "Son bir haftada 4 bölüm veya daha fazla bölüm yaptığınız için daha önceki bir cezanız silindi. Cezalarınızı panelin sağ bölümünden takip edebilirsiniz. Eğer bir hata olduğunu düşünüyorsanız lütfen yetkililer ile iletişime geçin.";
							sendNotification(hedef,id,type,baslik,icerik);
						<?php

						//Genel Log
						ekip_log($uyeBilgileri['user']." son bir haftada 4 bölüm yaparak önceki cezası silindi.");	
					}
					//Gönüllü ise
					else {
						//Bildirim Gönderiyoruz
						?>
							var hedef = "member";
							var id = <?php echo $value ?>;
							var type = "3";
							var baslik = "Ceza Botu tarafından uyarıldınız!";
							var icerik = "Son bir haftadır bölüm yapmadığınız için uyarı aldınız. Uyarı sayınız 3'e ulaştığında ekipten otomatik olarak atılacaksınız. Cezalarınızı panelin sağ bölümünden takip edebilirsiniz.";
							sendNotification(hedef,id,type,baslik,icerik);
						<?php

						//Genel Log
						ekip_log($uyeBilgileri['user']." bu hafta hiç bölüm yapmayarak ceza aldı.");
					}

					//Ceza Düşür
					$newCeza = $cezaRow['ceza'] - 1;
					$database->query("UPDATE ceza_sistemi SET ceza = '$newCeza', son_af = '$tarih' WHERE user_id = '$value'");

				}

			}

		}


	?>
</script>