# Yemeksepeti Golang Ödevi

In memory key-value store olarak çalışan bir <b>REST-API</b> servisi geliştirilmesini istiyoruz.

## Gereken Özellikler
- key ’i set etmek için bir endpoint
- key ’i get etmek için bir endpoint
- Komple data’yı flush etmek için bir endpoint ? (zorunlu değil)
- Belirli bir interval’da (N dakikada bir) dosyaya kaydetmeli
- Uygulama durup tekrar ayağa kalktığında, eğer kaydedilmiş dosya varsa, tekrar varolan verileri hafızaya yükelemeli ( /tmp/TIMESTAMP-data.json ?)

## Tanımlar
- Uygulamanın standart kütüphane kullanılarak geliştirilmesini istiyoruz.
- Tüm value’lar string olarak işlenebilir, foo=1 ya da foo=bar şeklinde tip farkı yok
- Value karşılığı bir collection değil, yani key’i foo olanın value’su da tek bir değer olabilir, list/array olmasına gerek yok.

## Bonus Değerlendirme Kriterleri
- Herhangi bir design pattern takip edilmiş mi ? (ddd, repository vs ?)
- Uygulamayı tarif eden README var mı?
- go doc ’a dikkat edilmiş mi?
- api doc var mı?
- Testler yazılmış mı?
- http isteklerini gösteren bir server log var mı?
- Golang kod yazma standartlarını kapsıyor mu? Takip edilen bir style guide var mı?
- Uygulama containerized durumda mı?
- Herhangi bir yere deploy edilmiş mi? (heroku? gcloud? aws?)
- Sanki open-source projeymiş gibi, bir open-source projede olması gereken şeyler var mı? (Lisans, ya da Github’a deploy edilmişse build/lint actionları vs...)