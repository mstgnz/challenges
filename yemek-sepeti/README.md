# Yemek Sepeti Challange

## In Memory (key:value) Store Api With Go

### Setup and Running :

- Proje Kurulumu :
```
git clone https://github.com/mstgnz/yemek-sepeti-challange.git
cd yemek-sepeti-challange
cd cmd
go run main.go
```
- Özet : Bu api servisi için Linear Linked List kullanılmıştır. Veri yapıları ile ilgili daha detaylı bilgi için [Data Structures With Go](https://github.com/mstgnz/Lessons/tree/main/Go/DataStructures).
- Endpoint Listesi :
  - Tüm listeyi almak için : `GET` `http://localhost:8080/api/`
  - Veri eklemek için : `POST` `http://localhost:8080/api/insert`
  - Key ile Value çekmek için : `POST` `http://localhost:8080/api/key`
  - Veri güncellemek için : `PUT` `http://localhost:8080/api/update`
  - Veri silmek için : `POST` `http://localhost:8080/api/delete`
  - Veri kaydetmek için : `POST` `http://localhost:8080/api/save`

Not: Kaydedilen veriler server tekrar ayağa kaldırıldığında otomatik olarak listeye eklenir.

Not : net/http paketi parametre değeri olarak regex kabul etmediği için GET ve DELETE olması gereken iki istek POST olarak ayarlanmıştır.

İstek Listesi : [Postman Collection](https://github.com/mstgnz/yemek-sepeti-challange/blob/main/In%20Memory%20Store.postman_collection.json)