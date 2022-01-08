package service

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"strings"

	"yemek-sepeti/dto"
	"yemek-sepeti/entity"
)

type IKeyValueService interface {
	GetAll() []dto.KeyValueDto
	GetKey(key string) string
	Insert(key, value string) bool
	Update(key, value string) bool
	Delete(key string) bool
	Init()
	Save()
}

type keyValueService struct {
	kvEntity entity.KeyValue
}

func KeyValueService(kvEntity entity.KeyValue) IKeyValueService {
	return &keyValueService{
		kvEntity: kvEntity,
	}
}

func (s *keyValueService) GetAll() []dto.KeyValueDto {
	return s.kvEntity.Flush()
}

func (s *keyValueService) GetKey(key string) string {
	search, err := s.kvEntity.Search(key)
	if err == nil {
		return search.Value
	}
	return ""
}

func (s *keyValueService) Insert(key, value string) bool {
	return s.kvEntity.AddToEnd(key, value)
}

func (s *keyValueService) Update(key, value string) bool {
	_, err := s.kvEntity.Update(key, value)
	if err != nil {
		return false
	}
	return true
}

func (s *keyValueService) Delete(key string) bool {
	return s.kvEntity.Delete(key)
}

func (s *keyValueService) Init() {
	byteValue, _ := ioutil.ReadFile("./tmp/data.json")
	var keyValues map[string]string
	json.Unmarshal(byteValue, &keyValues)
	//fmt.Println(keyValues)
	for key, value := range keyValues {
		//fmt.Println(key, value)
		s.Insert(key, value)
	}
}

func (s *keyValueService) Save() {
	//file, _ := json.MarshalIndent(s.GetAll(), "", " ")
	test := s.GetAll()
	generateJsonStr := "{"
	for _, value := range test {
		fmt.Println(value)
		generateJsonStr += "\"" +value.Key + "\":\"" + value.Value +"\","
	}
	generateJsonStr = strings.TrimSuffix(generateJsonStr, ",")
	generateJsonStr += "}"
	_ = ioutil.WriteFile("./tmp/data.json", []byte(generateJsonStr), 0644)
}