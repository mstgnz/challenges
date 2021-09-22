## Challenge

Projede API, WORKER ve CALLBACK olmak üzeri 3 ana başlık altında yapılmıştır.

Raporlama api enpoint altında yapılmıştır.

### Installation

```bash
    composer install
    php artisan key:generate
```

### Database

Apps,devices,failed_jobs,jobs,purchases adında 5 adet tablo bulunmaktadır.

Tablo ilişkileri ve kolonları migration olarak oluşturdum.

Laravel faker ve seeder kullanarak tabloları Dummy veriler ile doldurdum.

```bash
php artisan migrate --seed
```

Sql Export: https://raw.githubusercontent.com/uguryildiz81/challenge/main/teknasyon_2021-03-14.sql

### API

Api tarafı için 4 adet controller oluşturdum.
(AppController,DeviceController,PurchaseController,ReportController)

PurchaseController da kullanılmak için StartedEvent,CanceledEvent,RenewedEvent şeklinde 3 event oluşturdum.

Event lerı ProcessCallback worker a bağlayarak istek atılma işlemi yaptım.

Http istediği 200 veya 201 olmaz ise 1 saat arayla tekrar dener. 3. denemeden sonra işlemi kapatıyor.

Purchase endpointleri için CheckToken adında bir middleware oluşturdum.'client-token' zorunludur.

Postman: https://raw.githubusercontent.com/uguryildiz81/challenge/main/Teknasyon.postman_collection.json

Endpoints

-   (GET) /api/apps => Application Listesi
-   (POST) /api/apps => Application Listesi {"name":"test apps","endpoint":"https://www.google.com/"}
-   (GET) /api/devices => Device Listesi
-   (GET) /api/devices/{id} => Device Bilgisi {id} = device_id
-   (POST) /api/devices/register => Device kayıt isteği, Gerekli Datalar : {"uid":"3", "appId":"1", "language":"tr", "os":"ios"}
-   (POST) /api/purchase => Satın alma kayıt isteği, Gerekli Datalar :{"client-token":"f22174cc73f4b153f676bbd52036acb2762b847c8aedf99ef23268f3b5b4383c", "receipt":"testreceipt3"}
-   (POST) /api/purchase/check => Abonelik kontrolü, Gerekli Datalar :{"client-token":"f22174cc73f4b153f676bbd52036acb2762b847c8aedf99ef23268f3b5b4383c"}
-   **(GET) /api/reports => Raporlama (Uygulama,gün,ios-start,ios-update,ios-cancel,android-start,android-update,android-cancel,total) şeklin çıktı verir.**

### Worker

ProcessPurchases adında bir job ve Purchases adında bir command oluşturdum.

Purchase tablosundaki expire-date geçen ve iptal olmamış kayıtları bir Purchases Komut dosyası ekleyerek kuyruk aktarılma işlemi yaptım.(Crona bağlanarak çalıştırıla bilir.)

```bash
php artisan purchase:run
```

Kuyruktaki dataları işlemesi için supervisord kullandım.

Bu şekilde bir çok worker oluşturularak bekleyen kayıtları hızlı bir şekilde eritmesini sağladım.

Bir worker aynı anda kaç işlem yapcağını numprocs üzerinden belirleye biliyoruz.

İşlem sonuçlarına göre StartedEvent,CanceledEvent,RenewedEvent eventlarını çalıştırdım ve ProcessCallback ile enpointlere dönüş yaptım.

```bash
[program:purchase_worker_1]
process_name=%(program_name)s_%(process_num)02d
command=php /Users/uguryildiz/Sites/teknasyon/artisan queue:work --sleep=3 --tries=3 --max-time=3600 --queue=high,medium,default
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=uguryildiz
numprocs=8
redirect_stderr=true
stdout_logfile=/Users/uguryildiz/Sites/teknasyon/storage/logs/laravel_queue.log
stopwaitsecs=3600
```

.env dosyasında QUEUE_CONNECTION=database olarak ayarlanması gerekiyor.

### Callback

Api ve worker için event listener uygulamasıdır.

Abonelik durumu her hangi bir device değişiklik durumunda StartedEvent,CanceledEvent,RenewedEvent eventlarına istek atarak ilgili Uygulamanın enpoint adresine dönüş yapar.

Http istediği 200 veya 201 olmaz ise 1 saat arayla tekrar dener. 3. denemeden sonra işlemi kapatıyor.
