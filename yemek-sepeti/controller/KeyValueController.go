package controller

import (
	"encoding/json"
	"fmt"
	"github.com/mstgnz/yemek-sepeti-challange/dto"
	"github.com/mstgnz/yemek-sepeti-challange/service"
	"net/http"
)

type IKeyValueController interface {
	GetAll(w http.ResponseWriter, r *http.Request)
	GetKey(w http.ResponseWriter, r *http.Request)
	Insert(w http.ResponseWriter, r *http.Request)
	Update(w http.ResponseWriter, r *http.Request)
	Delete(w http.ResponseWriter, r *http.Request)
	Save(w http.ResponseWriter, r *http.Request)
}

type keyValueController struct {
	keyValueService service.IKeyValueService
}

func KeyValueController(keyValueServ service.IKeyValueService) IKeyValueController {
	return &keyValueController{
		keyValueService: keyValueServ,
	}
}

func (c *keyValueController) GetAll(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type","application/json")
	response := dto.Response{Status: true, Message: "Mevcut Liste", Stores: c.keyValueService.GetAll()}
	output, err := json.Marshal(response)
	if err != nil {
		fmt.Fprintf(w, err.Error())
	} else {
		fmt.Fprintf(w, string(output))
	}
}

func (c *keyValueController) GetKey(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type","application/json")
	err := r.ParseForm()
	if err != nil {
		return
	}
	key := r.FormValue("key")
	response := dto.Response{Status: false, Message: "Hata!, Veri Bulunamadı", Stores: []dto.KeyValueDto{dto.KeyValueDto{Key: key, Value: ""}}}
	value := c.keyValueService.GetKey(key)
	if len(value) > 0 {
		response.Status = true
		response.Message = "Veri Bulundu"
		response.Stores[0].Value = value
	}
	output, err := json.Marshal(response)
	if err != nil {
		fmt.Fprintf(w, err.Error())
	} else {
		fmt.Fprintf(w, string(output))
	}
}

func (c *keyValueController) Insert(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type","application/json")
	err := r.ParseForm()
	if err != nil {
		return
	}
	key := r.FormValue("key")
	value := r.FormValue("value")
	response := dto.Response{Status: false, Message: "Hata!, Veri Eklenemedi", Stores: []dto.KeyValueDto{dto.KeyValueDto{Key: key, Value: value}}}
	// key daha önce eklenmiş mi ?
	existsValue := c.keyValueService.GetKey(key)
	if len(existsValue) > 0 {
		response.Message = "Bu Key daha önce eklenmiş, tekrar eklenemez!"
	}else{
		isAdded := c.keyValueService.Insert(key, value)
		if isAdded {
			response.Status = true
			response.Message = "Veri Başarıyla Eklendi"
			response.Stores[0].Key = key
			response.Stores[0].Value = value
		}
	}
	output, err := json.Marshal(response)
	if err != nil {
		fmt.Fprintf(w, err.Error())
	} else {
		fmt.Fprintf(w, string(output))
	}
}

func (c *keyValueController) Update(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type","application/json")
	err := r.ParseForm()
	if err != nil {
		return
	}
	key := r.FormValue("key")
	value := r.FormValue("value")
	isUpdated := c.keyValueService.Update(key, value)
	response := dto.Response{Status: false, Message: "Hata!, Veri Güncellenemedi", Stores: []dto.KeyValueDto{dto.KeyValueDto{Key: key, Value: value}}}
	if isUpdated {
		response.Status = true
		response.Message = "Veri Başarıyla Güncellendi"
		response.Stores[0].Key = key
		response.Stores[0].Value = value
	}
	output, err := json.Marshal(response)
	if err != nil {
		fmt.Fprintf(w, err.Error())
	} else {
		fmt.Fprintf(w, string(output))
	}
}

func (c *keyValueController) Delete(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type","application/json")
	err := r.ParseForm()
	if err != nil {
		return
	}
	key := r.FormValue("key")
	value := c.keyValueService.GetKey(key)
	isDeleted := c.keyValueService.Delete(key)
	response := dto.Response{Status: false, Message: "Hata!, Veri Silinemedi", Stores: []dto.KeyValueDto{dto.KeyValueDto{Key: key, Value: value}}}
	if isDeleted {
		response.Status = true
		response.Message = "Veri Başarıyla Silindi"
		response.Stores[0].Key = key
		response.Stores[0].Value = value
	}
	output, err := json.Marshal(response)
	if err != nil {
		fmt.Fprintf(w, err.Error())
	} else {
		fmt.Fprintf(w, string(output))
	}
}

func (c *keyValueController) Save(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type","application/json")
	c.keyValueService.Save()
	response := dto.Response{Status: true, Message: "Veriler Kaydedildi", Stores: []dto.KeyValueDto{}}
	output, err := json.Marshal(response)
	if err != nil {
		fmt.Fprintf(w, err.Error())
	} else {
		fmt.Fprintf(w, string(output))
	}
}